<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($doaSakit) ? 'Edit Permohonan' : 'Tambah Permohonan' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex align-items-center py-4">

<div class="container" style="max-width: 650px;">

    <!-- Tampilkan Notifikasi Error Validasi Server (Menggunakan PHP Native) -->
    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show rounded-3 mb-3" role="alert">
            <strong class="d-block mb-1">Gagal menyimpan data:</strong>
            <ul class="mb-0 ps-3">
                @php
                    foreach ($errors->all() as$error) {
                        echo '<li>' . e($error) . '</li>';
                    }
                @endphp
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header {{ isset($doaSakit) ? 'bg-primary' : 'bg-dark' }} text-white p-3 text-center">
            <h5 class="mb-0 fw-bold">
                {{ isset($doaSakit) ? 'Edit Permohonan Doa' : 'Formulir Permohonan Doa Baru' }}
            </h5>
        </div>

        <div class="card-body p-4">
            <form action="{{ isset($doaSakit) ? route('doa-sakit.update', $doaSakit->id) : route('doa-sakit.store') }}" method="POST">
                @csrf
                @if(isset($doaSakit))
                    @method('PUT')
                @endif

                <!-- Nama Lengkap -->
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Nama Lengkap</label>
                    <input type="text" name="nama" class="form-control rounded-3" value="{{ old('nama', $doaSakit->nama ?? '') }}" placeholder="Masukkan nama pemohon" required maxlength="50">
                </div>

                <!-- Gender & Jenis Penyakit -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Jenis Kelamin</label>
                        <select name="gender" class="form-select rounded-3" required>
                            <option value="Laki-laki" {{ old('gender', $doaSakit->gender ?? '') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                            <option value="Perempuan" {{ old('gender', $doaSakit->gender ?? '') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Jenis Penyakit</label>
                        <input type="text" name="jenis_penyakit" class="form-control rounded-3" value="{{ old('jenis_penyakit', $doaSakit->jenis_penyakit ?? '') }}" placeholder="Contoh: Demam, Flu" required maxlength="50">
                    </div>
                </div>

                <!-- Tanggal Doa & Jam Doa -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Tanggal Doa</label>
                        <input type="date" name="tanggal_doa" class="form-control rounded-3" value="{{ old('tanggal_doa', isset($doaSakit) ? \Carbon\Carbon::parse($doaSakit->tanggal_doa)->format('Y-m-d') : '') }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Jam Doa (Format 24 Jam)</label>
                        <input type="text"
                               name="jam_doa"
                               class="form-control rounded-3"
                               placeholder="Contoh: 00:00"
                               pattern="^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$"
                               value="{{ old('jam_doa', isset($doaSakit) ? \Carbon\Carbon::parse($doaSakit->jam_doa)->format('H:i') : '') }}"
                               maxlength="5"
                               required>
                    </div>
                </div>

                <!-- Alamat Domisili & Nomor HP -->
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Alamat Domisili</label>
                        <input type="text"
                               name="alamat"
                               id="alamat"
                               class="form-control rounded-3"
                               value="{{ old('alamat', $doaSakit->alamat ?? '') }}"
                               placeholder="Contoh: Jl. Sudirman No. 12, Palembang"
                               required
                               maxlength="225"
                               oninput="updateMapPreview()">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-secondary small fw-bold">Nomor HP / WhatsApp</label>
                        <input type="text" name="no_hp" class="form-control rounded-3" value="{{ old('no_hp', $doaSakit->no_hp ?? '') }}" placeholder="08xxxxxxxxxx" maxlength="50">
                    </div>
                </div>

                <!-- Live Preview Minimap berdasarkan Alamat -->
                <div class="card border-0 shadow-sm mb-3 rounded-3 overflow-hidden d-none" id="mapPreviewCard">
                    <div class="card-header bg-dark text-white py-2 px-3 small fw-bold">
                        📍 Live Preview Lokasi Pemohon
                    </div>
                    <div class="card-body p-0">
                        <div class="ratio ratio-21x9" style="max-height: 200px;">
                            <iframe id="mapFrame" src="" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>

                <!-- Catatan -->
                <div class="mb-3">
                    <label class="form-label text-secondary small fw-bold">Catatan Tambahan</label>
                    <textarea name="catatan" class="form-control rounded-3" rows="2" placeholder="Detail pergumulan doa (opsional)">{{ old('catatan', $doaSakit->catatan ?? '') }}</textarea>
                </div>

                <!-- Status (Khusus mode Edit) -->
                @if(isset($doaSakit))
                    <div class="mb-4">
                        <label class="form-label text-dark small fw-bold">Status Pelayanan</label>
                        <select name="status" class="form-select rounded-3 border-primary" required>
                            <option value="Di Terima" {{ old('status', $doaSakit->status) == 'Di Terima' ? 'selected' : '' }}>Di Terima</option>
                            <option value="Selesai" {{ old('status', $doaSakit->status) == 'Selesai' ? 'selected' : '' }}>Selesai</option>
                            <option value="Batal/Ditolak" {{ old('status', $doaSakit->status) == 'Batal/Ditolak' ? 'selected' : '' }}>Batal/Ditolak</option>
                        </select>
                    </div>
                @endif

                <!-- Tombol Aksi -->
                <div class="d-flex justify-content-end gap-2 mt-4 pt-2 border-top">
                    <a href="{{ route('doa-sakit.index') }}" class="btn btn-light rounded-3 px-4">Batal</a>
                    <button type="submit" class="btn {{ isset($doaSakit) ? 'btn-primary' : 'btn-dark' }} rounded-3 px-4">
                        {{ isset($doaSakit) ? 'Simpan Perubahan' : 'Kirim Permohonan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function updateMapPreview() {
        const alamatInput = document.getElementById('alamat');
        const mapCard = document.getElementById('mapPreviewCard');
        const mapFrame = document.getElementById('mapFrame');

        const alamatVal = alamatInput ? alamatInput.value.trim() : '';

        if (alamatVal !== "") {
            mapFrame.src = `https://maps.google.com/maps?q=${encodeURIComponent(alamatVal)}&t=&z=16&ie=UTF8&iwloc=&output=embed`;
            mapCard.classList.remove('d-none');
        } else {
            mapCard.classList.add('d-none');
            mapFrame.src = "";
        }
    }

    document.addEventListener("DOMContentLoaded", function() {
        updateMapPreview();
    });
</script>
</body>
</html>
