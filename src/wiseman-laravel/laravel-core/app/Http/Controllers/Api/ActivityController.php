<?php

namespace App\Http\Controllers\Api;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityRequest;
use App\Http\Resources\BaseCollection;
use App\Helpers\Activity\ActivityHelper;
use App\Http\Resources\ActivityResource;
use Spatie\GoogleCalendar\Event;
use Carbon\Carbon;
// use App\Helpers\Activity\GoogleCalendarHelper;

class ActivityController extends Controller
{
    private $activity;
    private $event;
    // private $googleCalendar;

    public function __construct()
    {
        $this->activity = new ActivityHelper();
        $this->event = new Event();
        // $this->googleCalendar = new GoogleCalendarHelper();
    }

    public function index(Request $request)
    {
        $filter = [
            'group_id' => $request->group_id ?? '',
            'user_id' => $request->user_id ?? '',
            'description' => $request->description ?? '',
            'start_time' => $request->start_time ?? '',
            'end_time' => $request->end_time ?? '',
            'is_priority' => $request->is_priority ?? '',
            'is_finished' => $request->is_finished ?? '',
        ];

        $activity = $this->activity->getAll($filter, $request->per_page ?? 25, $request->sort ?? '');
        return response()->success(new BaseCollection(ActivityResource::collection($activity['data']), $activity['data']));
    }

    public function show($id)
    {
        $activity = $this->activity->getById($id);

        if (!$activity['status']) {
            return response()->failed(['Mohon maaf data tidak ditemukan'], 404);
        }

        return response()->success(new ActivityResource($activity['data']));
    }

    public function store(ActivityRequest $request)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['group_id', 'user_id', 'description', 'start_time', 'end_time', 'is_priority', 'is_finished']);

        $user = UserModel::find($payload['user_id']);

        $this->event->name = $payload['description'];
        $this->event->description = ($payload['is_priority'] == 1 ? "Aktivitas Diprioritaskan" : "Bukan Aktivitas Prioritas") .
                            ($payload['is_finished'] == 1 ? " - Sudah Selesai" : " - Belum Selesai");
        $this->event->startDateTime = Carbon::parse($payload['start_time']);
        $this->event->endDateTime = Carbon::parse($payload['end_time']);
        $this->event->addAttendee([
            'email' => $user->email,
            'name' => $user->name,
            'comment' => 'Aktivitas Baru',
            'responseStatus' => 'needsAction',
        ]);

        $this->event->save();

        $activity = $this->activity->create($payload);

        if (!$activity['status']) {
            return response()->failed($activity['error']);
        }

        // $this->integrateWithGoogleCalendar('create', $activity['data']);

        return response()->success(new ActivityResource($activity['data']), "Data berhasil ditambahkan");
    }

    public function update(ActivityRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['group_id', 'user_id', 'description', 'start_time', 'end_time', 'is_priority', 'is_finished']);

        $activity = $this->activity->update($payload, $id ?? '');

        if (!$activity['status']) {
            return response()->failed($activity['error']);
        }

        // $this->integrateWithGoogleCalendar('update', $activity['data']);

        return response()->success(new ActivityResource($activity['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $activity = $this->activity->delete($id);

        if (!$activity) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        // $this->integrateWithGoogleCalendar('delete', ['id' => $id]);

        return response()->success($activity, 'Data berhasil dihapus');
    }

    // private function integrateWithGoogleCalendar($action, $data)
    // {
    //     $calendarId = 'primary';

    //     switch ($action) {
    //         case 'create':
    //             $eventDetails = [
    //                 'summary' => $data['description'],
    //                 'start' => ['dateTime' => $data['start_time']],
    //                 'end' => ['dateTime' => $data['end_time']],
    //                 'attendees' => $this->getAttendees($data['user_id']),
    //             ];
    //             $googleEvent = $this->googleCalendar->createEvent($calendarId, $eventDetails);
    //             $this->activity->update(['google_calendar_event_id' => $googleEvent->getId()], $data['id']);
    //             break;

    //         case 'update':
    //             $eventId = $data['google_calendar_event_id'];
    //             if ($eventId) {
    //                 $eventDetails = [
    //                     'summary' => $data['description'],
    //                     'start' => ['dateTime' => $data['start_time']],
    //                     'end' => ['dateTime' => $data['end_time']],
    //                     'attendees' => $this->getAttendees($data['user_id']),
    //                 ];
    //                 $this->googleCalendar->updateEvent($calendarId, $eventId, $eventDetails);
    //             }

    //             break;

    //         case 'delete':
    //             $eventId = $data['google_calendar_event_id'];
    //             if ($eventId) {
    //                 $this->googleCalendar->deleteEvent($calendarId, $eventId);
    //             }
    //             break;
    //     }
    // }

    // private function getAttendees($userId)
    // {
    //     $users = UserModel::whereIn('id', explode(',', $userId))->get();
    //     $attendees = [];
    //     foreach ($users as $user) {
    //         $attendees[] = ['email' => $user->email];
    //     }
    //     return $attendees;
    // }
}
