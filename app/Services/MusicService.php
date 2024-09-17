<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use App\Models\Track;
use App\Models\Album;
use App\Models\Artist;
use Illuminate\Support\Facades\Storage;
use Google\Client as Google_Client; // Sử dụng alias để tránh xung đột
use Google\Service\YouTube as Google_Service_YouTube; // Đảm bảo import đúng
use YoutubeDl\YoutubeDl;
use YoutubeDl\Exception\CopyrightException;
use YoutubeDl\Exception\NotFoundException;
use YoutubeDl\Exception\PrivateVideoException;

class MusicService
{
    protected $youtubeClient;
    protected $youtubeDl;

    public function __construct()
    {
        $this->youtubeClient = $this->getYoutubeClient();
        $this->youtubeDl = new YoutubeDl();
    }

    public function searchAndSaveTrack($title, $artistName)
    {
        // Tìm kiếm trên YouTube
        $youtubeTrack = $this->searchYoutube($title, $artistName);
        if ($youtubeTrack) {
            return $this->saveYoutubeTrack($youtubeTrack);
        }

        return null;
    }

    protected function searchYoutube($title, $artistName)
    {
        $youtube = new Google_Service_YouTube($this->youtubeClient);
        $query = $title . ' ' . $artistName;

        $searchResponse = $youtube->search->listSearch('id,snippet', [
            'q' => $query,
            'type' => 'video',
            'videoCategoryId' => '10', // Music category
            'maxResults' => 1,
        ]);

        if (!empty($searchResponse['items'])) {
            return $searchResponse['items'][0];
        }

        return null;
    }

    protected function saveYoutubeTrack($videoData)
    {
        $videoId = $videoData['id']['videoId'];
        $videoTitle = $videoData['snippet']['title'];

        // Tạo nghệ sĩ mặc định cho YouTube tracks
        $artist = Artist::firstOrCreate(['name' => 'YouTube Artist']);

        // Tạo album mặc định cho YouTube tracks
        $album = Album::firstOrCreate(
            ['name' => 'YouTube Tracks'],
            ['artist_id' => $artist->id]
        );

        // Tải audio từ YouTube
        $audioUrl = $this->downloadYoutubeAudio($videoId);

        if ($audioUrl) {
            $track = Track::create([
                'name' => $videoTitle,
                'artist_id' => $artist->id,
                'album_id' => $album->id,
                'duration_ms' => 0, // Cần tính toán thời lượng thực tế
                'audio_url' => $audioUrl,
                'youtube_id' => $videoId
            ]);

            return $track;
        }

        return null;
    }

    protected function downloadYoutubeAudio($videoId)
    {
        $url = "https://www.youtube.com/watch?v={$videoId}";
        $filename = 'tracks/youtube_' . $videoId . '.mp3';

        try {
            // Tạo một đối tượng Options
            $options = new \YoutubeDl\Options();
            $options->setOption('extract-audio', true);
            $options->setOption('audio-format', 'mp3');
            $options->setOption('output', storage_path('app/public/' . $filename));

            // Tải audio
            $this->youtubeDl->download($url, $options);

            return $filename;
        } catch (CopyrightException $e) {
            \Log::error('Copyright issue: ' . $e->getMessage());
        } catch (NotFoundException $e) {
            \Log::error('Video not found: ' . $e->getMessage());
        } catch (PrivateVideoException $e) {
            \Log::error('Private video: ' . $e->getMessage());
        } catch (\Exception $e) {
            \Log::error('Error downloading YouTube audio: ' . $e->getMessage());
        }

        return null;
    }

    protected function getYoutubeClient()
    {
        $client = new Google_Client();
        $client->setApplicationName("music-streaming");
        $client->setDeveloperKey(env('AIzaSyCKfu8nSd0_lC3NEpqTWr8oQaD3ubugIuQ'));
        return $client;
    }
}
