<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Permohonan Doa - {{ $doaSakit->nama }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light min-vh-100 d-flex align-items-center py-4">

<div class="container" style="max-width: 650px;">

    <!-- Alert Notifikasi Sukses/Gagal -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show rounded-3 mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden mb-4">
        <!-- Card Header -->
        <div class="card-header bg-dark text-white p-3 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">📋 Detail Permohonan Doa</h5>
            @php
                $statusColor = [
                    'Di Terima' => 'bg-warning text-dark',
                    'Selesai' => 'bg-success',
                    'Batal/Ditolak' => 'bg-danger'
                ][$doaSakit->status] ?? 'bg-secondary';
            @endphp
            <span class="badge {{ $statusColor }} px-3 py-2 rounded-pill">{{ $doaSakit->status }}</span>
        </div>

        <div class="card-body p-4">

            <!-- Nama & Gender -->
            <div class="mb-3 border-bottom pb-3">
                <div class="text-secondary small fw-bold">Nama Pemohon</div>
                <div class="fs-4 fw-bold text-dark">{{ $doaSakit->nama }}</div>
                <div class="badge bg-light text-dark border mt-1">
                    {{ $doaSakit->gender == 'Laki-laki' ? '👨 Laki-laki' : '👩 Perempuan' }}
                </div>
            </div>

            <!-- Detail Pergumulan Doa -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="text-secondary small fw-bold">Jenis Penyakit / Pergumulan</div>
                    <div class="fw-semibold text-dark">{{ $doaSakit->jenis_penyakit }}</div>
                </div>
                <div class="col-md-6">
                    <div class="text-secondary small fw-bold">Jadwal Doa</div>
                    <div class="fw-semibold text-dark">
                        📅 {{ \Carbon\Carbon::parse($doaSakit->tanggal_doa)->translatedFormat('d F Y') }} <br>
                        ⏰ Pukul {{ \Carbon\Carbon::parse($doaSakit->jam_doa)->format('H:i') }} WIB
                    </div>
                </div>
            </div>

            <!-- Kontak & Alamat -->
            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <div class="text-secondary small fw-bold">Nomor HP / WhatsApp</div>
                    <div>
                        @if($doaSakit->no_hp)
                            @php
                                // Formatting nomor HP untuk wa.me link
                                $hpFormatted = preg_replace('/[^0-9]/', '', $doaSakit->no_hp);
                                if (str_starts_with($hpFormatted, '0')) {
                                    $hpFormatted = '62' . substr($hpFormatted, 1);
                                }
                            @endphp
                            <a href="https://wa.me/{{ $hpFormatted }}" target="_blank" class="btn btn-outline-success btn-sm rounded-3 mt-1 fw-bold">
                                💬 {{ $doaSakit->no_hp }}
                            </a>
                        @else
                            <span class="text-muted small">- Tidak ada kontak -</span>
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="text-secondary small fw-bold">Alamat Domisili</div>
                    <div class="fw-normal text-dark">{{ $doaSakit->alamat }}</div>
                </div>
            </div>
            @if(!empty($linkLokasi))
                <div class="mb-3">
                    <div class="text-secondary small fw-bold mb-1">Link Google Maps</div>
                    <a href="{{ $linkLokasi }}" target="_blank" rel="noopener noreferrer" class="btn btn-primary btn-sm rounded-3 fw-bold">
                        📍 Buka Lokasi di Google Maps
                    </a>
                </div>
            @endif

            <!-- Preview Embed Google Maps -->
            @php
                $mapQuery = !empty($linkLokasi) && !str_contains($linkLokasi, 'maps.app.goo.gl') ? $linkLokasi : $doaSakit->alamat;
            @endphp
            @if(!empty($mapQuery))
                <div class="card border-0 shadow-sm mb-3 rounded-3 overflow-hidden">
                    <div class="card-header bg-dark text-white py-2 px-3 small fw-bold">
                        📍 Preview Lokasi Pemohon
                    </div>
                    <div class="card-body p-0">
                        <div class="ratio ratio-21x9" style="max-height: 200px;">
                            <iframe src="https://maps.google.com/maps?q={{ urlencode($mapQuery) }}&t=&z=16&ie=UTF8&iwloc=&output=embed" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Catatan Tambahan -->
            <div class="mb-4">
                <div class="text-secondary small fw-bold">Catatan Tambahan</div>
                <div class="p-3 bg-light rounded-3 border text-secondary mt-1">
                    {{ $doaSakit->catatan ?: 'Tidak ada catatan tambahan.' }}
                </div>
            </div>

            <!-- Tombol Navigasi / Aksi -->
            <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                <a href="{{ route('doa-sakit.index') }}" class="btn btn-light rounded-3 px-4">
                    ⬅️ Kembali
                </a>
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-danger rounded-3" data-bs-toggle="modal" data-bs-target="#deleteModal">
                        🗑️ Hapus
                    </button>
                    <a href="{{ route('doa-sakit.edit', $doaSakit->id) }}" class="btn btn-primary rounded-3 px-4">
                        ✏️ Edit Data
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4 overflow-hidden">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title fw-bold">Konfirmasi Hapus Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="fs-6 mb-1">Apakah Anda yakin ingin menghapus data permohonan doa untuk:</p>
                <h4 class="fw-bold text-danger">{{ $doaSakit->nama }}</h4>
                <p class="text-muted small mt-2 mb-0">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer bg-light border-0">
                <button type="button" class="btn btn-secondary rounded-3" data-bs-dismiss="modal">Batal</button>
                <form action="{{ route('doa-sakit.destroy', $doaSakit->id) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger rounded-3">Ya, Hapus Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
