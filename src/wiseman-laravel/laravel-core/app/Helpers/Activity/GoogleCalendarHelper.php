<?php

namespace App\Helpers\Activity;

use Google\Client as GoogleClient;
use Google\Service\Calendar as GoogleCalendar;
use Google\Service\Calendar\Event as GoogleCalendarEvent;

class GoogleCalendarHelper
{
    private $client;
    private $calendarService;

    public function __construct()
    {
        $this->client = new GoogleClient();
        // $this->client->setAuthConfig([
        //     'client_id' => env('GOOGLE_CLIENT_ID'),
        //     'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        // ]);
        $this->client->setClientId(env('GOOGLE_CLIENT_ID'));
        $this->client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $this->client->setScopes(GoogleCalendar::CALENDAR);
        $this->client->setAccessType('offline');
        $this->calendarService = new GoogleCalendar($this->client);
    }

    public function createEvent($calendarId, $eventDetails)
    {
        $event = new GoogleCalendarEvent($eventDetails);
        return $this->calendarService->events->insert($calendarId, $event);
    }

    public function updateEvent($calendarId, $eventId, $eventDetails)
    {
        $event = new GoogleCalendarEvent($eventDetails);
        return $this->calendarService->events->update($calendarId, $eventId, $event);
    }

    public function deleteEvent($calendarId, $eventId)
    {
        return $this->calendarService->events->delete($calendarId, $eventId);
    }

    public function addAttendees($calendarId, $eventId, $attendees)
    {
        $event = $this->calendarService->events->get($calendarId, $eventId);
        $event->attendees = $attendees;
        return $this->calendarService->events->update($calendarId, $eventId, $event);
    }
}
