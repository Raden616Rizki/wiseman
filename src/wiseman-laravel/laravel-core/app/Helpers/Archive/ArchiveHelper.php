<?php

namespace App\Helpers\Archive;

use App\Helpers\Venturo;
use Illuminate\Support\Facades\Storage;
use App\Models\ArchiveModel;
use Throwable;

class ArchiveHelper extends Venturo
{
    const FILES_DIRECTORY = 'files';
    private $archiveModel;

    public function __construct()
    {
        $this->archiveModel = new ArchiveModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $archive = $this->archiveModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $archive
        ];
    }

    public function getById(string $id): array
    {
        $archive = $this->archiveModel->getById($id);

        if (empty($archive)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $archive
        ];
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();

            $payload = $this->uploadGetPayload($payload);
            $archive = $this->archiveModel->store($payload);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $archive
            ];
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function update(array $payload, string $id): array
    {
        try {
            $this->beginTransaction();

            $payload = $this->uploadGetPayload($payload);
            $this->archiveModel->edit($payload, $id);
            $archive = $this->getById($id);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $archive['data']
            ];
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return [
                'status' => false,
                'error' => $th->getMessage()
            ];
        }
    }

    public function delete(string $id): bool
    {
        try {
            $this->beginTransaction();
            $this->archiveModel->drop($id);

            $this->commitTransaction();
            return true;
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return false;
        }
    }

    public function copy(string $id): bool
    {
        try {
            $this->beginTransaction();
            $archive = $this->archiveModel->getById($id);

            if (!$archive) {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }

            $newArchive = $archive->replicate();
            $newArchive->name = $archive->name . '-copy';
            $newFileName = 'files/FILE' . '_' . date('Ymdhis') . '.' . pathinfo($archive->file, PATHINFO_EXTENSION);
            $newFilePath = '/public/' . $newFileName;

            Storage::copy('/public/'.$archive->file, $newFilePath);
            $newArchive->file = $newFileName;

            $newArchive->save();

            $this->commitTransaction();
            return true;
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return false;
        }
    }

    private function uploadGetPayload(array $payload): array
    {
        if (!empty($payload['file'])) {
            $fileName = $this->generateFileName($payload['file'], 'FILE' . '_' . date('Ymdhis'));
            $files = $payload['file']->storeAs(self::FILES_DIRECTORY, $fileName, 'public');
            $payload['file'] = $files;
        } else {
            unset($payload['file']);
        }

        return $payload;
    }
}
