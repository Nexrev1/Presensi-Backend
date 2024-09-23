<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Izin;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class IzinController extends Controller
{
    // Menampilkan daftar izin (untuk API)
    public function index(Request $request)
    {
        $izin = Izin::with('user')->get();

        if ($request->wantsJson()) {
            return response()->json($izin);
        }

        return view('admin.manage-izin', ['izin' => $izin]);
    }

   // Menyimpan izin baru
public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'jenis_izin' => 'required|string',
        'tanggal_mulai' => 'required|date',
        'tanggal_selesai' => 'required|date',
        'dokumen' => 'nullable|file|mimes:pdf,jpg,png,jpeg|max:2048',
    ]);

    if ($validator->fails()) {
        return response()->json(['errors' => $validator->errors()], 422);
    }

    $izin = new Izin();
    $izin->user_id = auth()->user()->id;
    $izin->jenis_izin = $request->input('jenis_izin');
    $izin->tanggal_mulai = $request->input('tanggal_mulai');
    $izin->tanggal_selesai = $request->input('tanggal_selesai');
    
    if ($request->hasFile('dokumen')) {
        $file = $request->file('dokumen');
        $path = $file->store('dokumen_izin', 'public');
        $izin->dokumen = $path;
    }

    $izin->save();
    return response()->json(['message' => 'Izin berhasil diajukan'], 201);
}

    // Memperbarui izin
    public function update(Request $request, $id)
    {
        $izin = Izin::find($id);

        if (!$izin) {
            return response()->json(['message' => 'Izin tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:approved,rejected',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $izin->status = $request->input('status');
        $izin->save();

        return response()->json(['message' => 'Izin berhasil diperbarui']);
    }

    // Menghapus izin
    public function destroy($id)
    {
        $izin = Izin::find($id);

        if (!$izin) {
            return response()->json(['message' => 'Izin tidak ditemukan'], 404);
        }

        // Hapus dokumen jika ada
        if ($izin->dokumen) {
            Storage::disk('public')->delete($izin->dokumen);
        }

        $izin->delete();
        return response()->json(['message' => 'Izin berhasil dihapus']);
    }

    // Menyetujui izin
public function approve(Request $request, $id)
{
    $izin = Izin::find($id);

    if (!$izin) {
        return response()->json(['message' => 'Izin tidak ditemukan'], 404);
    }

    $izin->status = 'approved';
    $izin->save();

    return redirect()->route('admin.manage-izin')->with('success', 'Izin telah disetujui.');
}

// Menolak izin
public function reject(Request $request, $id)
{
    $izin = Izin::find($id);

    if (!$izin) {
        return response()->json(['message' => 'Izin tidak ditemukan'], 404);
    }

    $izin->status = 'rejected';
    $izin->save();

    return redirect()->route('admin.manage-izin')->with('success', 'Izin telah ditolak.');
}
}
