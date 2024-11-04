<?php

namespace App\Http\Controllers\Api;

use App\Helpers\UserVoting\UserVotingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserVotingRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\UserVotingResource;
use Illuminate\Http\Request;

class UserVotingController extends Controller
{
    private $uservoting;

    public function __construct()
    {
        $this->uservoting = new UserVotingHelper();
    }

    public function index(Request $request)
    {
        $filter = [
			'user_id' => $request->userId ?? '',
			'voting_id' => $request->votingId ?? '',
		];

        $uservoting = $this->uservoting->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new BaseCollection(UserVotingResource::collection($uservoting['data']), $uservoting['data']));
    }

    public function show($id)
    {
        $uservoting = $this->uservoting->getById($id);

        if (!$uservoting['status']) {
            return response()->failed(['Mohon maaf data tidak ditemukan'], 404);
        }

        return response()->success(new UserVotingResource($uservoting['data']));
    }

    public function store(UserVotingRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['user_id', 'voting_id']);
        $uservoting = $this->uservoting->create($payload);

        if (!$uservoting['status']) {
            return response()->failed($uservoting['error']);
        }

        return response()->success(new UserVotingResource($uservoting['data']), "Data berhasil ditambahkan");
    }

    public function update(UserVotingRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['user_id', 'voting_id']);
        $uservoting = $this->uservoting->update($payload, $id ?? '');

        if (!$uservoting['status']) {
            return response()->failed($uservoting['error']);
        }

        return response()->success(new UserVotingResource($uservoting['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $uservoting = $this->uservoting->delete($id);

        if (!$uservoting) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($uservoting, 'Data berhasil dihapus');
    }
}
