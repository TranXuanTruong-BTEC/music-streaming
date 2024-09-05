<?php

namespace App\Services;

use SpotifyWebAPI\Session;
use SpotifyWebAPI\SpotifyWebAPI;
use Exception;

class SpotifyService
{
    protected $api;

    public function __construct()
    {
        $clientId = config('services.spotify.client_id');
        $clientSecret = config('services.spotify.client_secret');

        if (!$clientId || !$clientSecret) {
            throw new Exception('Spotify client ID or client secret is missing');
        }

        $session = new Session($clientId, $clientSecret);

        try {
            $session->requestCredentialsToken();
            $accessToken = $session->getAccessToken();

            $this->api = new SpotifyWebAPI();
            $this->api->setAccessToken($accessToken);
        } catch (Exception $e) {
            throw new Exception('Failed to authenticate with Spotify: ' . $e->getMessage());
        }
    }

    public function getNewReleases($limit = 20)
    {
        return $this->api->getNewReleases(['limit' => $limit])->albums->items;
    }

    public function getAlbumTracks($albumId)
    {
        return $this->api->getAlbumTracks($albumId)->items;
    }

    public function getTrackDetails($trackId)
    {
        return $this->api->getTrack($trackId);
    }
}