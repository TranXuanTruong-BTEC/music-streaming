<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\Artist;
use App\Services\SpotifyService;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }

    public function userList()
    {
        $users = User::all(); // Lấy tất cả người dùng
        return view('admin.user-list', compact('users'));
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:user,admin',
        ]);

        try {
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => $request->role,
            ]);
            return redirect()->route('admin.users')->with('success', 'Tài khoản đã được tạo thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Có lỗi xảy ra khi tạo tài khoản: ' . $e->getMessage());
        }
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$id,
            'role' => 'required|in:user,admin',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
        }

        $request->validate($rules);

        try {
            $userData = [
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
            ];

            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);
            return redirect()->route('admin.users')->with('success', 'Tài khoản đã được cập nhật thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Có lỗi xảy ra khi cập nhật tài khoản: ' . $e->getMessage());
        }
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        try {
            $user->delete();
            return redirect()->route('admin.users')->with('success', 'Tài khoản đã được xóa thành công.');
        } catch (\Exception $e) {
            return redirect()->route('admin.users')->with('error', 'Có lỗi xảy ra khi xóa tài khoản: ' . $e->getMessage());
        }
    }

    public function artists(SpotifyService $spotifyService)
    {
        $artists = Artist::paginate(15);
        
        foreach ($artists as $artist) {
            if ($artist->spotify_id && !$artist->image_url) {
                $spotifyArtist = $spotifyService->getArtist($artist->spotify_id);
                if (isset($spotifyArtist->images[0]->url)) {
                    $artist->image_url = $spotifyArtist->images[0]->url;
                    $artist->save();
                }
            }
        }
        
        return view('admin.artists.artists', compact('artists'));
    }
}