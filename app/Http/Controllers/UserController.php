<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Presensi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Method to update user
    public function updateUser(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'role' => 'required|in:admin,user',
            'password' => 'nullable|string|min:8|confirmed', // Validasi password jika ada
        ]);

        // Find the user by ID
        $user = User::findOrFail($id);

        // Hash password if present
        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->input('password'));
        } else {
            // Remove password field if not provided
            unset($validated['password']);
        }

        // Update the user record
        $user->update($validated);

        // Redirect with success message
        return redirect()->route('admin.manage-users')->with('success', 'User updated successfully');
    }

    // Method to display rekap absen for authenticated user
    public function rekapAbsen()
    {
        $userId = auth()->id(); // Get the ID of the authenticated user
        
        // Get the presensi records for the authenticated user
        $presensiRecords = Presensi::where('user_id', $userId)
            ->orderBy('tanggal', 'desc')
            ->paginate(10);

        // Return the view with the records
        return view('users.rekap-absen', compact('presensiRecords'));
    }
}
