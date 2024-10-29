<?php

namespace App\Http\Controllers\Api;

use App\Helpers\Archive\ArchiveHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ArchiveRequest;
use App\Http\Resources\BaseCollection;
use App\Http\Resources\ArchiveResource;
use Illuminate\Http\Request;

class ArchiveController extends Controller
{
    private $archive;

    public function __construct()
    {
        $this->archive = new ArchiveHelper();
    }

    public function index(Request $request)
    {
        $filter = [
			'name' => $request->name ?? '',
			'file' => $request->file ?? '',
			'parent_id' => $request->parentId ?? '',
			'group_id' => $request->groupId ?? '',
		];

        $archive = $this->archive->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new BaseCollection(ArchiveResource::collection($archive['data']), $archive['data']));
    }

    public function show($id)
    {
        $archive = $this->archive->getById($id);

        if (!$archive['status']) {
            return response()->failed(['Mohon maaf data tidak ditemukan'], 404);
        }

        return response()->success(new ArchiveResource($archive['data']));
    }

    public function store(ArchiveRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            error_log($request->validator->errors());
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'file', 'parent_id', 'group_id']);
        $archive = $this->archive->create($payload);

        if (!$archive['status']) {
            return response()->failed($archive['error']);
        }

        return response()->success(new ArchiveResource($archive['data']), "Data berhasil ditambahkan");
    }

    public function update(ArchiveRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['name', 'file', 'parent_id', 'group_id']);
        $archive = $this->archive->update($payload, $id ?? '');

        if (!$archive['status']) {
            return response()->failed($archive['error']);
        }

        return response()->success(new ArchiveResource($archive['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $archive = $this->archive->delete($id);

        if (!$archive) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($archive, 'Data berhasil dihapus');
    }

    public function copy($id)
    {
        $archive = $this->archive->copy($id);

        if (!$archive) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($archive, 'Data berhasil disalin');
    }
}
