<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Group\GroupHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\GroupResource;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    private $group;

    public function __construct()
    {
        $this->group = new GroupHelper();
    }

    public function index(Request $request)
    {
        $filter = [
			'name' => $request->name ?? '',
			'description' => $request->description ?? '',
		];

        $group = $this->group->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new BaseCollection(GroupResource::collection($group['data']), $group['data']));
    }

    public function show($id)
    {
        $group = $this->group->getById($id);

        if (!$group['status']) {
            return response()->failed(['Mohon maaf data tidak ditemukan'], 404);
        }

        return response()->success(new GroupResource($group['data']));
    }

    public function store(GroupRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'description']);
        $group = $this->group->create($payload);

        if (!$group['status']) {
            return response()->failed($group['error']);
        }

        return response()->success(new GroupResource($group['data']), "Data berhasil ditambahkan");
    }

    public function update(GroupRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'description']);
        $group = $this->group->update($payload, $id ?? '');

        if (!$group['status']) {
            return response()->failed($group['error']);
        }

        return response()->success(new GroupResource($group['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $group = $this->group->delete($id);

        if (!$group) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($group, 'Data berhasil dihapus');
    }
}
