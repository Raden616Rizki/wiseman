<?php

namespace App\Helpers\GroupUser;

use App\Helpers\Venturo;
use App\Models\GroupUserModel;
use Throwable;

class GroupUserHelper extends Venturo
{
    
    private $groupUserModel;

    public function __construct()
    {
        $this->groupUserModel = new GroupUserModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $groupUser = $this->groupUserModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $groupUser
        ];
    }

    public function getById(string $id): array
    {
        $groupUser = $this->groupUserModel->getById($id);

        if (empty($groupUser)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $groupUser
        ];
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();
            
            $groupUser = $this->groupUserModel->store($payload);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $groupUser
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
            
            $this->groupUserModel->edit($payload, $id);
            $groupUser = $this->getById($id);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $groupUser['data']
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
            $this->groupUserModel->drop($id);

            $this->commitTransaction();
            return true;
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return false;
        }
    }
    
}
