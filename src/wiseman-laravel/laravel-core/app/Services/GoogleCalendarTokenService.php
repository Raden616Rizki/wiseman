<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use Illuminate\Support\Facades\File;

class GoogleCalendarTokenService
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setAuthConfig(storage_path('app/google-calendar/oauth-credentials.json'));
        $this->client->addScope(Calendar::CALENDAR);
        $this->client->setAccessType('offline');
        $this->client->setPrompt('select_account consent');

        if ($this->hasStoredToken()) {
            $this->client->setAccessToken($this->getStoredToken());

            // Refresh token if expired
            if ($this->client->isAccessTokenExpired()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
                $this->storeToken($this->client->getAccessToken());
            }
        }
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setAuthToken($authCode)
    {
        $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
        $this->storeToken($accessToken);
    }

    protected function storeToken($token)
    {
        File::put(storage_path('app/google-calendar/oauth-token.json'), json_encode($token));
    }

    protected function getStoredToken()
    {
        return json_decode(File::get(storage_path('app/google-calendar/oauth-token.json')), true);
    }

    protected function hasStoredToken()
    {
        return File::exists(storage_path('app/google-calendar/oauth-token.json'));
    }
}
