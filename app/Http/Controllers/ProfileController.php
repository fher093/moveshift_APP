<?php

namespace App\Http\Controllers;

use App\Services\ProfileService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    protected ProfileService $profileService;

    public function __construct(ProfileService $profileService)
    {
        $this->profileService = $profileService;
    }

    public function edit(): View
    {
        $user = auth()->user();
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'zone' => ['nullable', 'string', 'max:255'],
            'career' => ['nullable', 'string', 'max:255'],
            'avatar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif', 'max:2048'],
        ]);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $path = $file->store('avatars', 'public');
            $validated['avatar'] = $path;

            if (auth()->user()->avatar) {
                Storage::disk('public')->delete(auth()->user()->avatar);
            }
        }

        $request->user()->update($validated);

        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => 'required|string',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = $this->profileService->changePassword(
            auth()->id(),
            $validated['current_password'],
            $validated['password']
        );

        if ($result['success']) {
            return back()->with('status', 'password-updated');
        }

        return back()->withErrors(['error' => $result['message']]);
    }
}