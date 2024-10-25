<?php

namespace App\Http\Controllers\Api;

use App\Models\UserModel;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\ActivityRequest;
use App\Http\Resources\BaseCollection;
use App\Helpers\Activity\ActivityHelper;
use App\Http\Resources\ActivityResource;
use App\Models\ActivityModel;
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
            'group_id' => $request->groupId ?? '',
            'user_id' => $request->userId ?? '',
            'description' => $request->description ?? '',
            'start_time' => $request->startTime ?? '',
            'end_time' => $request->endTime ?? '',
            'is_priority' => $request->isPriority ?? '',
            'is_finished' => $request->isFinished ?? '',
        ];

        $activity = $this->activity->getAll($filter, $request->perPage ?? 25, $request->sort ?? '');
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
        $color = $payload['is_priority'] == 1 ? 11 : 6;
        $this->event->setColorId($color);
        $this->event->startDateTime = Carbon::parse($payload['start_time']);
        $this->event->endDateTime = Carbon::parse($payload['end_time']);
        $this->event->addAttendee([
            'email' => $user->email,
            'name' => $user->name,
            'comment' => 'Aktivitas Baru',
            'responseStatus' => 'needsAction',
        ]);

        $eventData = $this->event->save();
        $eventId = $eventData->id;
        $payload['google_calendar_event_id'] = $eventId;

        $activity = $this->activity->create($payload);

        if (!$activity['status']) {
            return response()->failed($activity['error']);
        }

        return response()->success(new ActivityResource($activity['data']), "Data berhasil ditambahkan");
    }

    public function update(ActivityRequest $request, $id)
    {
        if (isset($request->validator) && $request->validator->fails()) {
            return response()->failed($request->validator->errors());
        }

        $payload = $request->only(['group_id', 'user_id', 'description', 'start_time', 'end_time', 'is_priority', 'is_finished', 'google_calendar_event_id']);

        $user = UserModel::find($payload['user_id']);
        $eventId = $payload['google_calendar_event_id'];
        $event = Event::find($eventId);
        $event->name = $payload['description'];
        $event->description = ($payload['is_priority'] == 1 ? "Aktivitas Diprioritaskan" : "Bukan Aktivitas Prioritas") .
            ($payload['is_finished'] == 1 ? " - Sudah Selesai" : " - Belum Selesai");
        $color = $payload['is_priority'] == 1 ? 11 : 6;
        $event->setColorId($color);
        $event->startDateTime = Carbon::parse($payload['start_time']);
        $event->endDateTime = Carbon::parse($payload['end_time']);
        $event->addAttendee([
            'email' => $user->email,
            'name' => $user->name,
            'comment' => 'Aktivitas Baru',
            'responseStatus' => 'needsAction',
        ]);

        $event->save();

        $activity = $this->activity->update($payload, $id ?? '');

        if (!$activity['status']) {
            return response()->failed($activity['error']);
        }

        return response()->success(new ActivityResource($activity['data']), "Data berhasil diganti");
    }

    public function destroy($id)
    {
        $activity = ActivityModel::find($id);
        $activityEventId = $activity->google_calendar_event_id;
        $event = Event::find($activityEventId);

        $event->delete();

        $activity = $this->activity->delete($id);

        if (!$activity) {
            return response()->failed(['Mohon maaf data tidak ditemukan']);
        }

        return response()->success($activity, 'Data berhasil dihapus');
    }
}
