<?php

namespace App\Helpers\User;

use App\Helpers\Venturo;
use App\Models\UserModel;
use Throwable;

class UserHelper extends Venturo
{

    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $user = $this->userModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $user
        ];
    }

    public function getById(string $id): array
    {
        $user = $this->userModel->getById($id);

        if (empty($user)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $user
        ];
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();

            $user = $this->userModel->store($payload);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $user
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

            $this->userModel->edit($payload, $id);
            $user = $this->getById($id);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $user['data']
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
            $this->userModel->drop($id);

            $this->commitTransaction();
            return true;
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return false;
        }
    }
}
