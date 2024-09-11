<?php

namespace App\Helpers\Activity;

use App\Helpers\Venturo;
use App\Models\ActivityModel;
use Throwable;

class ActivityHelper extends Venturo
{
    
    private $activityModel;

    public function __construct()
    {
        $this->activityModel = new ActivityModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $activity = $this->activityModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $activity
        ];
    }

    public function getById(string $id): array
    {
        $activity = $this->activityModel->getById($id);

        if (empty($activity)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $activity
        ];
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();
            
            $activity = $this->activityModel->store($payload);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $activity
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
            
            $this->activityModel->edit($payload, $id);
            $activity = $this->getById($id);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $activity['data']
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
            $this->activityModel->drop($id);

            $this->commitTransaction();
            return true;
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return false;
        }
    }
    
}
