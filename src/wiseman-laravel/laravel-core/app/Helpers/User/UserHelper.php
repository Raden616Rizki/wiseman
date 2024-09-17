<?php

namespace App\Helpers\User;

use Throwable;
use App\Helpers\Venturo;
use App\Models\UserModel;
use Illuminate\Support\Facades\Hash;

class UserHelper extends Venturo
{
    const USER_FILES_DIRECTORY = 'users';
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
            $payload['password'] = Hash::make($payload['password']);

			$payload = $this->uploadGetPayload($payload);
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
            if (isset($payload['password']) && !empty($payload['password'])) {
                $payload['password'] = Hash::make($payload['password']) ?: '';
            } else {
                unset($payload['password']);
            }

			$payload = $this->uploadGetPayload($payload);
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

    private function uploadGetPayload(array $payload): array
	{
		if (!empty($payload['photo'])) {
			$fileName = $this->generateFileName($payload['photo'], 'USER' . '_' . date('Ymdhis'));
			$user = $payload['photo']->storeAs(self::USER_FILES_DIRECTORY, $fileName, 'public');
			$payload['photo'] = $user;
		} else {
			unset($payload['photo']);
		}

		return $payload;
	}
}
