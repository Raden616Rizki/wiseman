<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Activity\ActivityHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\ActivityResource;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    private $activity;

    public function __construct()
    {
        $this->activity = new ActivityHelper();
    }

    public function index(Request $request)
    {
        $filter = [
			'group_id' => $request->group_id ?? '',
			'user_id' => $request->user_id ?? '',
			'description' => $request->description ?? '',
			'start_time' => $request->start_time ?? '',
			'end_time' => $request->end_time ?? '',
			'is_priority' => $request->is_priority ?? '',
			'is_finished' => $request->is_finished ?? '',
		];

        $activity = $this->activity->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new BaseCollection(ActivityResource::collection($activity['data']), $activity['data']));
    }

    public function show($id)
    {
        $activity = $this->activity->getById($id);

        if (!$activity['status']) {
            return response()->failed(['Mohon maaf data tidak ditemukan'], 404);
        }

        return response()->success(new ActivityResource($activity['data']));
    }

    public function store(ActivityRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['group_id', 'user_id', 'description', 'start_time', 'end_time', 'is_priority', 'is_finished']);
        $activity = $this->activity->create($payload);

        if (!$activity['status']) {
            return response()->failed($activity['error']);
        }

        return response()->success(new ActivityResource($activity['data']), "Data berhasil ditambahkan");
    }

    public function update(ActivityRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['group_id', 'user_id', 'description', 'start_time', 'end_time', 'is_priority', 'is_finished']);
        $activity = $this->activity->update($payload, $id ?? '');

        if (!$activity['status']) {
            return response()->failed($activity['error']);
        }

        return response()->success(new ActivityResource($activity['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $activity = $this->activity->delete($id);

        if (!$activity) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($activity, 'Data berhasil dihapus');
    }
}
