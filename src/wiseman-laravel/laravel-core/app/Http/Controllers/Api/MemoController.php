<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Memo\MemoHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\MemoRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\MemoResource;
use Illuminate\Http\Request;

class MemoController extends Controller
{
    private $memo;

    public function __construct()
    {
        $this->memo = new MemoHelper();
    }

    public function index(Request $request)
    {
        $filter = [
			'group_id' => $request->group_id ?? '',
			'message' => $request->message ?? '',
		];

        $memo = $this->memo->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new BaseCollection(MemoResource::collection($memo['data']), $memo['data']));
    }

    public function show($id)
    {
        $memo = $this->memo->getById($id);

        if (!$memo['status']) {
            return response()->failed(['Mohon maaf data tidak ditemukan'], 404);
        }

        return response()->success(new MemoResource($memo['data']));
    }

    public function store(MemoRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['group_id', 'message']);
        $memo = $this->memo->create($payload);

        if (!$memo['status']) {
            return response()->failed($memo['error']);
        }

        return response()->success(new MemoResource($memo['data']), "Data berhasil ditambahkan");
    }

    public function update(MemoRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['group_id', 'message']);
        $memo = $this->memo->update($payload, $id ?? '');

        if (!$memo['status']) {
            return response()->failed($memo['error']);
        }

        return response()->success(new MemoResource($memo['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $memo = $this->memo->delete($id);

        if (!$memo) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($memo, 'Data berhasil dihapus');
    }
}
