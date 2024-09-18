<?php

namespace App\Helpers\Group;

use App\Helpers\Venturo;
use App\Models\GroupModel;
use Throwable;

class GroupHelper extends Venturo
{

    private $groupModel;

    public function __construct()
    {
        $this->groupModel = new GroupModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $group = $this->groupModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $group
        ];
    }

    public function getById(string $id): array
    {
        $group = $this->groupModel->getById($id);

        if (empty($group)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $group
        ];
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();

            $group = $this->groupModel->store($payload);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $group
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

            $this->groupModel->edit($payload, $id);
            $group = $this->getById($id);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $group['data']
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
            $this->groupModel->drop($id);

            $this->commitTransaction();
            return true;
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return false;
        }
    }

}
