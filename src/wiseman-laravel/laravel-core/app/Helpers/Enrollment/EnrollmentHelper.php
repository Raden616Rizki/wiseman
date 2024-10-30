<?php

namespace App\Helpers\Enrollment;

use App\Helpers\Venturo;
use App\Models\EnrollmentModel;
use Throwable;

class EnrollmentHelper extends Venturo
{
    
    private $enrollmentModel;

    public function __construct()
    {
        $this->enrollmentModel = new EnrollmentModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $enrollment = $this->enrollmentModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $enrollment
        ];
    }

    public function getById(string $id): array
    {
        $enrollment = $this->enrollmentModel->getById($id);

        if (empty($enrollment)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $enrollment
        ];
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();
            
            $enrollment = $this->enrollmentModel->store($payload);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $enrollment
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
            
            $this->enrollmentModel->edit($payload, $id);
            $enrollment = $this->getById($id);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $enrollment['data']
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
            $this->enrollmentModel->drop($id);

            $this->commitTransaction();
            return true;
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return false;
        }
    }
    
}
