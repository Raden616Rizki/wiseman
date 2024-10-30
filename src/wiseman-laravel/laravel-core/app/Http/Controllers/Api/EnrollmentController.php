<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Enrollment\EnrollmentHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\EnrollmentRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\EnrollmentResource;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    private $enrollment;

    public function __construct()
    {
        $this->enrollment = new EnrollmentHelper();
    }

    public function index(Request $request)
    {
        $filter = [
			'user_id' => $request->user_id ?? '',
			'group_id' => $request->group_id ?? '',
		];

        $enrollment = $this->enrollment->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new BaseCollection(EnrollmentResource::collection($enrollment['data']), $enrollment['data']));
    }

    public function show($id)
    {
        $enrollment = $this->enrollment->getById($id);

        if (!$enrollment['status']) {
            return response()->failed(['Mohon maaf data tidak ditemukan'], 404);
        }

        return response()->success(new EnrollmentResource($enrollment['data']));
    }

    public function store(EnrollmentRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['user_id', 'group_id']);
        $enrollment = $this->enrollment->create($payload);

        if (!$enrollment['status']) {
            return response()->failed($enrollment['error']);
        }

        return response()->success(new EnrollmentResource($enrollment['data']), "Data berhasil ditambahkan");
    }

    public function update(EnrollmentRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['user_id', 'group_id']);
        $enrollment = $this->enrollment->update($payload, $id ?? '');

        if (!$enrollment['status']) {
            return response()->failed($enrollment['error']);
        }

        return response()->success(new EnrollmentResource($enrollment['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $enrollment = $this->enrollment->delete($id);

        if (!$enrollment) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($enrollment, 'Data berhasil dihapus');
    }
}
