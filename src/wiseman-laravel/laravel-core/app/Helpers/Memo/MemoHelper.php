<?php

namespace App\Helpers\Memo;

use App\Helpers\Venturo;
use App\Models\MemoModel;
use Throwable;

class MemoHelper extends Venturo
{
    
    private $memoModel;

    public function __construct()
    {
        $this->memoModel = new MemoModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $memo = $this->memoModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $memo
        ];
    }

    public function getById(string $id): array
    {
        $memo = $this->memoModel->getById($id);

        if (empty($memo)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $memo
        ];
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();
            
            $memo = $this->memoModel->store($payload);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $memo
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
            
            $this->memoModel->edit($payload, $id);
            $memo = $this->getById($id);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $memo['data']
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
            $this->memoModel->drop($id);

            $this->commitTransaction();
            return true;
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return false;
        }
    }
    
}
