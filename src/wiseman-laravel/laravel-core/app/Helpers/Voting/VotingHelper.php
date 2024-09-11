<?php

namespace App\Helpers\Voting;

use App\Helpers\Venturo;
use App\Models\VotingModel;
use App\Models\VotingOptionModel;
use Throwable;

class VotingHelper extends Venturo
{

    private $votingModel;
	private $votingOptionModel;

    public function __construct()
    {
        $this->votingModel = new VotingModel();
		$this->votingOptionModel = new VotingOptionModel();
    }

    public function getAll(array $filter, int $itemPerPage = 0, string $sort = ''): array
    {
        $voting = $this->votingModel->getAll($filter, $itemPerPage, $sort);

        return [
            'status' => true,
            'data' => $voting
        ];
    }

    public function getById(string $id): array
    {
        $voting = $this->votingModel->getById($id);

        if (empty($voting)) {
            return [
                'status' => false,
                'data' => null
            ];
        }

        return [
            'status' => true,
            'data' => $voting
        ];
    }

    public function create(array $payload): array
    {
        try {
            $this->beginTransaction();

            $voting = $this->votingModel->store($payload);
			$this->insertUpdateVotingOption($payload['voting_options'] ?? [], $voting->id);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $voting
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

            $this->votingModel->edit($payload, $id);
			$this->insertUpdateVotingOption($payload['voting_options'] ?? [], $id);
			$this->deleteVotingOption($payload['voting_options_deleted'] ?? []);
            $voting = $this->getById($id);

            $this->commitTransaction();
            return [
                'status' => true,
                'data' => $voting['data']
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
            $this->votingModel->drop($id);

            $this->commitTransaction();
            return true;
        } catch (Throwable $th) {
            $this->rollbackTransaction();
            return false;
        }
    }


	private function deleteVotingOption(array $voting_options): void
	{
		if (empty($voting_options)) {
			return;
		}

		foreach ($voting_options as $val) {
			$this->votingOptionModel->drop($val['id']);
		}
	}

	private function insertUpdateVotingOption(array $voting_options, string $votingId): void
	{
		if (empty($voting_options)) {
			return;
		}

		foreach ($voting_options as $val) {
			if (isset($val['is_added']) && $val['is_added']) {
				$val['voting_id'] = $votingId;
				$this->votingOptionModel->store($val);
			}

			if (isset($val['is_updated']) && $val['is_updated']) {
				$this->votingOptionModel->edit($val, $val['id']);
			}
		}
	}
}
