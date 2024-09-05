<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Album;

class AlbumController extends Controller
{
    public function index()
    {
        $albums = Album::with('artist')->paginate(15);
        return view('admin.albums.index', compact('albums'));
    }
}
