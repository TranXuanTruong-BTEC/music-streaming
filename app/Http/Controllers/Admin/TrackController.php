<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Track;
use App\Services\MusicService;
use Illuminate\Http\Request;

class TrackController extends Controller
{
    protected $musicService;

    public function __construct(MusicService $musicService)
    {
        $this->musicService = $musicService;
    }

    public function index()
    {
        // Lấy danh sách các bài hát từ database
        $tracks = Track::with(['album', 'artist'])->paginate(10);
        return view('admin.tracks.index', compact('tracks'));
    }

    public function fetchTrack(Request $request)
    {
        $title = $request->input('title');
        $artistName = $request->input('artist');

        $track = $this->musicService->searchAndSaveTrack($title, $artistName);

        if ($track) {
            return redirect()->route('admin.tracks.index')->with('success', 'Bài hát đã được thêm thành công.');
        } else {
            return redirect()->route('admin.tracks.index')->with('error', 'Không thể tìm thấy hoặc thêm bài hát.');
        }
    }

    // Các phương thức khác...
}
