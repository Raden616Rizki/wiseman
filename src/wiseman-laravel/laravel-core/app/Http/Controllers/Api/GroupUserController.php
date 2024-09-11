<?php

namespace App\Http\Controllers\Api;

use App\Helpers\GroupUser\GroupUserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GroupUserRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\GroupUserResource;
use Illuminate\Http\Request;

class GroupUserController extends Controller
{
    private $groupuser;

    public function __construct()
    {
        $this->groupuser = new GroupUserHelper();
    }

    public function index(Request $request)
    {
        $filter = [
			'group_id' => $request->group_id ?? '',
			'user_id' => $request->user_id ?? '',
			'is_admin' => $request->is_admin ?? '',
		];

        $groupuser = $this->groupuser->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new BaseCollection(GroupUserResource::collection($groupuser['data']), $groupuser['data']));
    }

    public function show($id)
    {
        $groupuser = $this->groupuser->getById($id);

        if (!$groupuser['status']) {
            return response()->failed(['Mohon maaf data tidak ditemukan'], 404);
        }

        return response()->success(new GroupUserResource($groupuser['data']));
    }

    public function store(GroupUserRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['group_id', 'user_id', 'is_admin']);
        $groupuser = $this->groupuser->create($payload);

        if (!$groupuser['status']) {
            return response()->failed($groupuser['error']);
        }

        return response()->success(new GroupUserResource($groupuser['data']), "Data berhasil ditambahkan");
    }

    public function update(GroupUserRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['group_id', 'user_id', 'is_admin']);
        $groupuser = $this->groupuser->update($payload, $id ?? '');

        if (!$groupuser['status']) {
            return response()->failed($groupuser['error']);
        }

        return response()->success(new GroupUserResource($groupuser['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $groupuser = $this->groupuser->delete($id);

        if (!$groupuser) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($groupuser, 'Data berhasil dihapus');
    }
}
