<?php

namespace App\Http\Controllers;

use App\Models\DoaSakit;
use Illuminate\Http\Request;

class DoaSakitController extends Controller
{
    public function index()
    {
        $doaList = DoaSakit::orderBy('id', 'desc')->get();
        return view('doa_sakit.index', compact('doaList'));
    }

    public function create()
    {
        return view('doa_sakit.form');
    }

    public function store(Request $request)
    {
        // Ubah 'waktu_doa' menjadi 'tanggal_doa' dan 'jam_doa'
        $validated = $request->validate([
            'nama'           => 'required|string|max:50',
            'gender'         => 'required|in:Laki-laki,Perempuan',
            'jenis_penyakit' => 'required|string|max:50',
            'tanggal_doa'    => 'required|date',
            'jam_doa'        => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'alamat'         => 'required|string|max:50',
            'no_hp'          => 'nullable|string|max:50',
            'catatan'        => 'nullable|string',
        ], [
            'jam_doa.regex'  => 'Format jam harus 24 jam (contoh: 08:30 atau 14:30).',
        ]);

        // Default status saat buat baru
        $validated['status'] = 'Di Terima';

        DoaSakit::create($validated);

        return redirect()->route('doa-sakit.index')->with('success', 'Permohonan doa berhasil dikirim!');
    }

    public function edit($id)
    {
        $doaSakit = DoaSakit::findOrFail($id);
        return view('doa_sakit.form', compact('doaSakit'));
    }

    public function update(Request $request, $id)
    {
        $doaSakit = DoaSakit::findOrFail($id);

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
        ], [
            'jam_doa.regex'  => 'Format jam harus 24 jam (contoh: 08:30 atau 14:30).',
        ]);

        $doaSakit->update($validated);

        return redirect()->route('doa-sakit.index')->with('success', 'Permohonan doa berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $doaSakit = DoaSakit::findOrFail($id);
        $doaSakit->delete();

        return redirect()->route('doa-sakit.index')->with('success', 'Data berhasil dihapus!');
    }
}
