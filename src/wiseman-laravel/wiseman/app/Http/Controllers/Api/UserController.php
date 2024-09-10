<?php

namespace App\Http\Controllers\Api;

use App\Helpers\User\UserHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\CreateRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Http\Resources\User\UserCollections;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;

class UserController extends Controller
{
    private $user;

    public function __construct()
    {
        $this->user = new UserHelper();
    }

    /**
     * Mengambil list user
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     */
    public function index(Request $request)
    {
        $filter = [
            'username' => $request->username ?? '',
            'email' => $request->email ?? '',
        ];

        $perPage = $request->per_page ?? 5;

        $users = $this->user->getAll($filter, $perPage, $request->sort ?? '');
        return response()->success(new UserCollections($users['data']));
    }

    /**
     * Membuat data user baru & disimpan ke tabel m_user
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     */
    public function store(CreateRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/User/CreateRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }
        $payload = $request->only(['email', 'username', 'password', 'photo_profile', 'phone']);
        $user = $this->user->create($payload);
        if (!$user['status']) {
            return response()->failed($user['error']);
        }
        return response()->success(new UserResource($user['data']));
    }

    /**
     * Menampilkan user secara spesifik dari tabel m_user
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     */
    public function show($id)
    {
        $user = $this->user->getById($id);

        if (!($user['status'])) {
            return response()->failed(['Data user tidak ditemukan'], 404);
        }

        return response()->success(new UserResource($user['data']));
    }

    /**
     * Mengubah data user di tabel m_user
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     */
    public function update(UpdateRequest $request)
    {
        /**
         * Menampilkan pesan error ketika validasi gagal
         * pengaturan validasi bisa dilihat pada class app/Http/request/User/UpdateRequest
         */
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['email', 'username', 'password', 'id', 'photo_profile', 'phone']);
        $user = $this->user->update($payload, $payload['id']);

        if (!$user['status']) {
            return response()->failed($user['error']);
        }
        return response()->success(new UserResource($user['data']));
    }


    /**
     * Soft delete data user
     *
     * @author Wahyu Agung <wahyuagung26@email.com>
     * @param mixed $id
     */
    public function destroy($id)
    {
        $user = $this->user->delete($id);
        if (!$user) {
            return response()->failed(['Mohon maaf data pengguna tidak ditemukan']);
        }
        return response()->success($user);
    }
}
