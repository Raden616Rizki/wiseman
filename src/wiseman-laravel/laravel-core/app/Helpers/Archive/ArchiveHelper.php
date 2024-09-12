<?php

namespace App\Helpers\Archive;

use App\Helpers\Venturo;
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

	private function uploadGetPayload(array $payload): array
	{
		if (!empty($payload['file'])) {
			$fileName = $this->generateFileName($payload['file'], 'FILE' . '_' . date('Ymdhis'));
			$_FILES = $payload['file']->storeAs(self::FILES_DIRECTORY, $fileName, 'public');
			$payload['file'] = $_FILES;
		} else {
			unset($payload['file']);
		}

		return $payload;
	}
}
