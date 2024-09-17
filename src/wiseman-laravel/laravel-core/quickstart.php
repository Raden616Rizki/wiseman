<?php
/**
 * Copyright 2018 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
// [START calendar_quickstart]
require __DIR__ . '/vendor/autoload.php';

if (php_sapi_name() != 'cli') {
    throw new Exception('This application must be run on the command line.');
}

use Google\Client;
use Google\Service\Calendar;
use Google\Service\Calendar\Event;

/**
 * Returns an authorized API client.
 * @return Client the authorized client object
 */
function getClient()
{
    $client = new Client();
    $client->setApplicationName('Google Calendar API PHP Quickstart');
    // $client->setScopes('https://www.googleapis.com/auth/calendar.events.readonly');
    $client->setScopes(Calendar::CALENDAR);
    $client->setAuthConfig('storage/app/google-calendar/oauth-credentials.json');
    $client->setAccessType('offline');
    $client->setPrompt('select_account consent');

    // Load previously authorized token from a file, if it exists.
    // The file token.json stores the user's access and refresh tokens, and is
    // created automatically when the authorization flow completes for the first
    // time.
    $tokenPath = 'storage/app/google-calendar/oauth-token.json';
    if (file_exists($tokenPath)) {
        $accessToken = json_decode(file_get_contents($tokenPath), true);
        $client->setAccessToken($accessToken);
    }

    // If there is no previous token or it's expired.
    if ($client->isAccessTokenExpired()) {
        // Refresh the token if possible, else fetch a new one.
        if ($client->getRefreshToken()) {
            $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
        } else {
            // Request authorization from the user.
            $authUrl = $client->createAuthUrl();
            printf("Open the following link in your browser:\n%s\n", $authUrl);
            print 'Enter verification code: ';
            $authCode = trim(fgets(STDIN));

            // Exchange authorization code for an access token.
            $accessToken = $client->fetchAccessTokenWithAuthCode($authCode);
            $client->setAccessToken($accessToken);

            // Check to see if there was an error.
            if (array_key_exists('error', $accessToken)) {
                throw new Exception(join(', ', $accessToken));
            }
        }
        // Save the token to a file.
        if (!file_exists(dirname($tokenPath))) {
            mkdir(dirname($tokenPath), 0700, true);
        }
        file_put_contents($tokenPath, json_encode($client->getAccessToken()));
    }
    return $client;
}


// Get the API client and construct the service object.
$client = getClient();
$service = new Calendar($client);

$event = new Event(array(
    "summary" => "Google I/0 2024 {2}",
    "location" => "Google Gmeet",
    "description" => "A chance to hear more about Google\'s developer products.",
    "start" => array(
        "dateTime" => "2024-09-17T09:00:00-07:00",
        "timeZone" => "Asia/Jakarta",
    ),
    "end" => array(
        "dateTime" => "2024-09-17T17:00:00-07:00",
        "timeZone" => "Asia/Jakarta",
    ),
    "conferenceData" => [
        "createRequest" => [
            "requestId" => uniqid()
        ]
    ]
));

$calendarId = '7309f7c600c606c2e58122946e604579f3e452e8dee47876429a175243a0a9f5@group.calendar.google.com';
$event = $service->events->insert($calendarId, $event, ['conferenceDataVersion' => 1]);
var_dump($event->hangoutLink);

// Print the next 10 events on the user's calendar.
try{

    $calendarId = '7309f7c600c606c2e58122946e604579f3e452e8dee47876429a175243a0a9f5@group.calendar.google.com';
    $optParams = array(
        'maxResults' => 10,
        'orderBy' => 'startTime',
        'singleEvents' => true,
        'timeMin' => date('c'),
    );
    $results = $service->events->listEvents($calendarId, $optParams);
    $events = $results->getItems();

    if (empty($events)) {
        print "No upcoming events found.\n";
    } else {
        print "Upcoming events:\n";
        foreach ($events as $event) {
            $start = $event->start->dateTime;
            if (empty($start)) {
                $start = $event->start->date;
            }
            printf("%s (%s)\n", $event->getSummary(), $start);
        }
    }
}
catch(Exception $e) {
    // TODO(developer) - handle error appropriately
    echo 'Message: ' .$e->getMessage();
}
// [END calendar_quickstart]
