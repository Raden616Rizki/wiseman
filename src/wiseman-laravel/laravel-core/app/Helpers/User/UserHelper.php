<?php

namespace App\Helpers\User;

use App\Helpers\Venturo;
use App\Models\UserModel;
use App\Models\GroupUserModel;
use Throwable;

class UserHelper extends Venturo
{
    
    private $userModel;
	private $groupUserModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
		$this->groupUserModel = new GroupUserModel();
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
			$this->insertUpdateGroupUser($payload['group_users'] ?? [], $user->id);

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
			$this->insertUpdateGroupUser($payload['group_users'] ?? [], $id);
			$this->deleteGroupUser($payload['group_users_deleted'] ?? []);
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
    

	private function deleteGroupUser(array $group_users): void
	{
		if (empty($group_users)) {
			return;
		}

		foreach ($group_users as $val) {
			$this->groupUserModel->drop($val['id']);
		}
	}

	private function insertUpdateGroupUser(array $group_users, string $userId): void
	{
		if (empty($group_users)) {
			return;
		}

		foreach ($group_users as $val) {
			if (isset($val['is_added']) && $val['is_added']) {
				$val['user_id'] = $userId;
				$this->groupUserModel->store($val);
			}

			if (isset($val['is_updated']) && $val['is_updated']) {
				$this->groupUserModel->edit($val, $val['id']);
			}
		}
	}
}
