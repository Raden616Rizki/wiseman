<?php

namespace App\Helpers\UserVoting;

use App\Helpers\Venturo;
use App\Models\UserVotingModel;
use Throwable;

class UserVotingHelper extends Venturo
{
    
    private $userVotingModel;

    public function __construct()
    {
        $this->userVotingModel = new UserVotingModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $userVoting = $this->userVotingModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $userVoting
        ];
    }

    public function getById(string $id): array
    {
        $userVoting = $this->userVotingModel->getById($id);

        if (empty($userVoting)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $userVoting
        ];
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();
            
            $userVoting = $this->userVotingModel->store($payload);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $userVoting
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
            
            $this->userVotingModel->edit($payload, $id);
            $userVoting = $this->getById($id);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $userVoting['data']
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
            $this->userVotingModel->drop($id);

            $this->commitTransaction();
            return true;
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return false;
        }
    }
    
}
