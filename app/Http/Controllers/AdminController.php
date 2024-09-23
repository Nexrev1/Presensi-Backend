<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Izin;
use App\Models\Presensi;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    // Dashboard
    public function index()
    {
        return view('admin.dashboard');
    }

    // Manage Users
    public function manageUsers(Request $request)
    {
        $search = $request->input('search');

        $query = User::query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
        }

        // Menggunakan paginasi, misalnya 10 item per halaman
        $users = $query->paginate(10);

        return view('admin.manage-users', compact('users'));
    }

    public function editUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.manage-users')->with('error', 'User not found');
        }
        return view('admin.edit-user', compact('user'));
    }

    public function dashboard()
    {
        // Fetch today's attendance
        $today = now()->toDateString();
        $attendances = Presensi::whereDate('tanggal', $today)->with('user')->get();
    
        return view('admin.dashboard', compact('attendances'));
    }

    // Tampilkan formulir tambah pengguna
    public function showAddUserForm()
    {
        return view('admin.add-user');
    }

    // Tambah pengguna
    public function addUser(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,user',
            'nik' => 'required|string|max:20|unique:users', // Validasi NIK
        ]);

        if ($validator->fails()) {
            return redirect()->route('admin.add-user')
                             ->withErrors($validator)
                             ->withInput();
        }

        // Buat pengguna baru
        User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => Hash::make($request->input('password')),
            'role' => $request->input('role'),
            'nik' => $request->input('nik'),
        ]);

        return redirect()->route('admin.add-user')->with('success', 'User added successfully');
    }

    // Memperbarui pengguna
    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:admin,user',
            'password' => 'nullable|min:8|confirmed',
            'nik' => 'required|string|max:20|unique:users,nik,' . $id, // Validasi NIK
        ]);

        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.manage-users')->with('error', 'User not found');
        }

        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->role = $request->input('role');
        $user->nik = $request->input('nik');

        if ($request->filled('password')) {
            $user->password = Hash::make($request->input('password'));
        }

        $user->save();

        return redirect()->route('admin.manage-users')->with('success', 'User updated successfully');
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return redirect()->route('admin.manage-users')->with('error', 'User not found');
        }
        $user->delete();
        return redirect()->route('admin.manage-users')->with('success', 'User deleted successfully');
    }

    // Manage Presensi
    public function managePresensi()
    {
        $presensis = Presensi::with('user')->paginate(10);
        return view('admin.manage-presensi', compact('presensis'));
    }

    public function editPresensi($id)
    {
        $presensi = Presensi::find($id);
        if (!$presensi) {
            return redirect()->route('admin.manage-presensi')->with('error', 'Presensi not found');
        }
        return view('admin.edit-presensi', compact('presensi'));
    }

    public function updatePresensi(Request $request, $id)
    {
        $request->validate([
            'tanggal' => 'required|date',
            'masuk' => 'required|date_format:H:i:s',
            'pulang' => 'nullable|date_format:H:i:s',
        ]);

        $presensi = Presensi::find($id);
        if (!$presensi) {
            return redirect()->route('admin.manage-presensi')->with('error', 'Presensi not found');
        }

        $presensi->update([
            'tanggal' => $request->input('tanggal'),
            'masuk' => $request->input('masuk'),
            'pulang' => $request->input('pulang'),
        ]);

        return redirect()->route('admin.manage-presensi')->with('success', 'Presensi updated successfully');
    }

    public function deletePresensi($id)
    {
        $presensi = Presensi::find($id);
        if (!$presensi) {
            return redirect()->route('admin.manage-presensi')->with('error', 'Presensi not found');
        }
        $presensi->delete();
        return redirect()->route('admin.manage-presensi')->with('success', 'Presensi deleted successfully');
    }
      // Manage Izin
      public function manageIzin(Request $request)
      {
          $search = $request->input('search');
  
          $query = Izin::query();
  
          if ($search) {
              $query->whereHas('user', function ($q) use ($search) {
                  $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
              })
              ->orWhere('jenis_izin', 'like', "%{$search}%");
          }
  
          $izin = $query->paginate(10);
  
          return view('admin.manage-izin', compact('izin'));
      }
  
      public function editIzin($id)
      {
          $izin = Izin::find($id);
          if (!$izin) {
              return redirect()->route('admin.manage-izin')->with('error', 'Izin not found');
          }
          return view('admin.edit-izin', compact('izin'));
      }
  
      public function updateIzin(Request $request, $id)
      {
          $validator = Validator::make($request->all(), [
              'status' => 'required|in:approved,rejected',
          ]);
  
          if ($validator->fails()) {
              return redirect()->route('admin.edit-izin', $id)
                               ->withErrors($validator)
                               ->withInput();
          }
  
          $izin = Izin::find($id);
          if (!$izin) {
              return redirect()->route('admin.manage-izin')->with('error', 'Izin not found');
          }
  
          $izin->status = $request->input('status');
          $izin->save();
  
          return redirect()->route('admin.manage-izin')->with('success', 'Izin updated successfully');
      }
  
      public function deleteIzin($id)
      {
          $izin = Izin::find($id);
          if (!$izin) {
              return redirect()->route('admin.manage-izin')->with('error', 'Izin not found');
          }
  
          if ($izin->dokumen) {
              Storage::disk('public')->delete($izin->dokumen);
          }
  
          $izin->delete();
          return redirect()->route('admin.manage-izin')->with('success', 'Izin deleted successfully');
      }
    
}
