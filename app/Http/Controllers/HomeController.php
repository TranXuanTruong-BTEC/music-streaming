<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $data = [
            'recentActivity' => "Check out the latest trending songs!",
            'recommendedPlaylists' => "Discover our curated playlists",
            'newReleases' => "Explore new releases this week"
        ];

        return view('home', $data);
    }
}
