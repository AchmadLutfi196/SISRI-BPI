<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile photo.
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'foto' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        $user = $request->user();
        
        // Determine which model to update
        if ($user->isMahasiswa() && $user->mahasiswa) {
            $model = $user->mahasiswa;
            $folder = 'foto/mahasiswa';
        } elseif (($user->isDosen() || $user->isKoordinator()) && $user->dosen) {
            $model = $user->dosen;
            $folder = 'foto/dosen';
        } else {
            return back()->with('error', 'Profil tidak ditemukan.');
        }

        // Delete old photo if exists
        if ($model->foto) {
            Storage::disk('public')->delete($model->foto);
        }

        // Store new photo
        $path = $request->file('foto')->store($folder, 'public');
        $model->update(['foto' => $path]);

        return back()->with('status', 'photo-updated');
    }

    /**
     * Delete the user's profile photo.
     */
    public function deletePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();
        
        // Determine which model to update
        if ($user->isMahasiswa() && $user->mahasiswa) {
            $model = $user->mahasiswa;
        } elseif (($user->isDosen() || $user->isKoordinator()) && $user->dosen) {
            $model = $user->dosen;
        } else {
            return back()->with('error', 'Profil tidak ditemukan.');
        }

        // Delete photo if exists
        if ($model->foto) {
            Storage::disk('public')->delete($model->foto);
            $model->update(['foto' => null]);
        }

        return back()->with('status', 'photo-deleted');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
