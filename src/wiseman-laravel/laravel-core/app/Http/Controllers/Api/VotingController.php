<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Voting\VotingHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\VotingRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\VotingResource;
use Illuminate\Http\Request;

class VotingController extends Controller
{
    private $voting;

    public function __construct()
    {
        $this->voting = new VotingHelper();
    }

    public function index(Request $request)
    {
        $filter = [
			'description' => $request->description ?? '',
			'limit_time' => $request->limitTime ?? '',
			'group_id' => $request->groupId ?? '',
		];

        $voting = $this->voting->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new BaseCollection(VotingResource::collection($voting['data']), $voting['data']));
    }

    public function show($id)
    {
        $voting = $this->voting->getById($id);

        if (!$voting['status']) {
            return response()->failed(['Mohon maaf data tidak ditemukan'], 404);
        }

        return response()->success(new VotingResource($voting['data']));
    }

    public function store(VotingRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['description', 'limit_time', 'group_id', 'voting_options']);
        $voting = $this->voting->create($payload);

        if (!$voting['status']) {
            return response()->failed($voting['error']);
        }

        return response()->success(new VotingResource($voting['data']), "Data berhasil ditambahkan");
    }

    public function update(VotingRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['description', 'limit_time', 'group_id', 'voting_options', 'voting_options_deleted']);
        $voting = $this->voting->update($payload, $id ?? '');

        if (!$voting['status']) {
            return response()->failed($voting['error']);
        }

        return response()->success(new VotingResource($voting['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $voting = $this->voting->delete($id);

        if (!$voting) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($voting, 'Data berhasil dihapus');
    }
}
