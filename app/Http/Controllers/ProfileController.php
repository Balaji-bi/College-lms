<?php

// app/Http/Controllers/ProfileController.php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        return view('profile.show', compact('user'));
    }
    
    public function update(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'year' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'role' => 'required|in:student,staff',
            'profile_photo' => 'nullable|image|max:1024',
        ]);
        
        if ($request->hasFile('profile_photo')) {
            // Delete old image if exists
            if ($user->profile_photo) {
                Storage::delete('public/profile_photos/' . $user->profile_photo);
            }
            
            // Store new image
            $filename = time() . '.' . $request->profile_photo->extension();
            $request->profile_photo->storeAs('public/profile_photos', $filename);
            $validated['profile_photo'] = $filename;
        } else {
            // Remove profile_photo from validated data if no new image
            unset($validated['profile_photo']);
        }
        
        $user->update($validated);
        
        return redirect()->route('profile.show')->with('success', 'Profile updated successfully');
    }
    
    public function checkProfileCompletion()
    {
        $user = Auth::user();
        return response()->json([
            'isComplete' => ($user->name && $user->year && $user->role) ? true : false
        ]);
    }
}