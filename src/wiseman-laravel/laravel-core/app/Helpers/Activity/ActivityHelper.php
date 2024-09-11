<?php

namespace App\Helpers\Activity;

use App\Helpers\Venturo;
use App\Models\ActivityModel;
use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use App\Models\User;
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

            $user = User::find($payload["user_id"]);

            if (!$user) {
                return response()->json(['error' => 'User not found'], 404);
            }

            $this->createGoogleCalendarEvent($user->email, $payload);

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

    private function createGoogleCalendarEvent($userEmail, $payload)
    {
        $client = new GoogleClient();
        $client->setApplicationName('Wiseman');
        $client->setScopes(GoogleCalendar::CALENDAR);
        $client->setAuthConfig(storage_path('app/credentials.json'));
        $client->setAccessType('offline');

        $service = new GoogleCalendar($client);

        $event = new GoogleCalendar\Event([
            'summary' => $payload['description'],
            'start' => ['dateTime' => $payload['start_time'], 'Indonesia/Jakarta' => 'UTC'],
            'end' => ['dateTime' => $payload['end_time'], 'Indonesia/Jakarta' => 'UTC'],
            'attendees' => [['email' => $userEmail]],
        ]);

        $calendarId = 'primary';
        $service->events->insert($calendarId, $event);
    }
}
