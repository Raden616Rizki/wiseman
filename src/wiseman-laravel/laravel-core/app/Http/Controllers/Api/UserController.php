<?php

namespace App\Http\Controllers\Api;

use App\Helpers\User\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new UserHelper();
    }

    public function index(Request $request)
    {
        $filter = [
			// 'm_user_roles_id' => $request->m_user_roles_id ?? '',
			'name' => $request->name ?? '',
			'email' => $request->email ?? '',
			'password' => $request->password ?? '',
			'phone_number' => $request->phone_number ?? '',
			'photo' => $request->photo ?? '',
		];

        $user = $this->user->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new BaseCollection(UserResource::collection($user['data']), $user['data']));
    }

    public function show($id)
    {
        $user = $this->user->getById($id);

        if (!$user['status']) {
            return response()->failed(['Mohon maaf data tidak ditemukan'], 404);
        }

        return response()->success(new UserResource($user['data']));
    }

    public function store(UserRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        // $payload = $request->only(['m_user_roles_id', 'name', 'email', 'password', 'phone_number', 'photo']);
        $payload = $request->only(['name', 'email', 'password', 'phone_number', 'photo']);
        $user = $this->user->create($payload);

        if (!$user['status']) {
            return response()->failed($user['error']);
        }

        return response()->success(new UserResource($user['data']), "Data berhasil ditambahkan");
    }

    public function update(UserRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        // $payload = $request->only(['m_user_roles_id', 'name', 'email', 'password', 'phone_number', 'photo']);
        $payload = $request->only(['name', 'email', 'password', 'phone_number', 'photo']);
        $user = $this->user->update($payload, $id ?? '');

        if (!$user['status']) {
            return response()->failed($user['error']);
        }

        return response()->success(new UserResource($user['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $user = $this->user->delete($id);

        if (!$user) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($user, 'Data berhasil dihapus');
    }
}
