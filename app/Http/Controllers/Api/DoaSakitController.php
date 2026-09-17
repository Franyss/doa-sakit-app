<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoaSakit;
use Illuminate\Http\Request;

class DoaSakitController extends Controller
{
    public function index()
    {
        $data = DoaSakit::orderBy('id', 'desc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $data
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama'           => 'required|string|max:50',
            'gender'         => 'required|in:Laki-laki,Perempuan',
            'jenis_penyakit' => 'required|string|max:50',
            'tanggal_doa'    => 'required|date',
            'jam_doa'        => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'alamat'         => 'required|string|max:50',
            'no_hp'          => 'nullable|string|max:50',
            'catatan'        => 'nullable|string',
        ]);

        $validated['status'] = 'Di Terima';
        $doa = DoaSakit::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Permohonan doa berhasil dibuat',
            'data' => $doa
        ], 201);
    }

    public function show($id)
    {
        $doa = DoaSakit::find($id);

        if (!$doa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $doa
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $doa = DoaSakit::find($id);

        if (!$doa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $validated = $request->validate([
            'nama'           => 'required|string|max:50',
            'gender'         => 'required|in:Laki-laki,Perempuan',
            'jenis_penyakit' => 'required|string|max:50',
            'tanggal_doa'    => 'required|date',
            'jam_doa'        => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'alamat'         => 'required|string|max:50',
            'no_hp'          => 'nullable|string|max:50',
            'catatan'        => 'nullable|string',
            'status'         => 'required|in:Di Terima,Selesai,Batal/Ditolak',
        ]);

        $doa->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil diperbarui',
            'data' => $doa
        ], 200);
    }

    public function destroy($id)
    {
        $doa = DoaSakit::find($id);

        if (!$doa) {
            return response()->json([
                'status' => 'error',
                'message' => 'Data tidak ditemukan'
            ], 404);
        }

        $doa->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Data berhasil dihapus'
        ], 200);
    }
}
