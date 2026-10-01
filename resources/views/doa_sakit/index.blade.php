<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Doa Sakit</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container my-5">
    <!-- Header Page -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Daftar Permohonan Doa Sakit</h2>
        <a href="{{ route('doa-sakit.create') }}" class="btn btn-primary">+ Tambah Permohonan</a>
    </div>

    <!-- Alert Sukses -->
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show shadow-sm" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Card Tabel Data -->
    <div class="card shadow-sm border-0 rounded-3 mb-4 overflow-hidden">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0 align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th class="py-3 ps-3">No</th>
                            <th class="py-3">Nama</th>
                            <th class="py-3">Gender</th>
                            <th class="py-3">Penyakit</th>
                            <th class="py-3">Waktu Doa</th>
                            <th class="py-3">Alamat</th>
                            <th class="py-3">No. HP</th>
                            <th class="py-3">Status</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doaList as $index => $doa)
                            <tr>
                                <td class="ps-3 fw-bold text-secondary">{{ $index + 1 }}</td>
                                <td><strong>{{ $doa->nama }}</strong></td>
                                <td>{{ $doa->gender }}</td>
                                <td>{{ $doa->jenis_penyakit }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($doa->tanggal_doa)->format('d-m-Y') }}
                                    <br>
                                    <small class="text-muted">({{ \Carbon\Carbon::parse($doa->jam_doa)->format('H:i') }})</small>
                                </td>
                                <td>{{ $doa->alamat }}</td>
                                <td>{{ $doa->no_hp ?? '-' }}</td>
                                <td>
                                    <span class="badge
                                        {{ $doa->status == 'Di Terima' ? 'bg-warning text-dark' : '' }}
                                        {{ $doa->status == 'Selesai' ? 'bg-success' : '' }}
                                        {{ $doa->status == 'Batal/Ditolak' ? 'bg-danger' : '' }}">
                                        {{ $doa->status }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('doa-sakit.show', $doa->id) }}" class="btn btn-sm btn-info text-white rounded-2 px-3 fw-medium">
                                        🔍 Detail
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-5 text-muted">
                                    <p class="mb-0 fs-6">Belum ada data permohonan doa.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Card Minimap Lokasi Yayasan / Vittindo CCTV Palembang -->
    <div class="card shadow-sm border-0 rounded-3 overflow-hidden">
        <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center py-2 px-3">
            <span class="fw-bold">📍 Lokasi Yayasan (Vittindo CCTV Palembang)</span>
            <a href="https://maps.app.goo.gl/durkKX6pCJ4xGrGX7" target="_blank" class="btn btn-sm btn-outline-light rounded-2">
                Buka di Google Maps ↗
            </a>
        </div>
        <div class="card-body p-0">
            <div class="ratio ratio-21x9" style="max-height: 250px;">
                <!-- Peta dengan Titik Koordinat Presisi & Zoom Level 17 -->
                <iframe
                    src="https://maps.google.com/maps?q=-2.9467332,104.7524668+(Vittindo+CCTV+Palembang)&t=&z=17&ie=UTF8&iwloc=B&output=embed"
                    style="border:0;"
                    allowfullscreen=""
                    loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
            </div>
        </div>
        <div class="card-footer bg-white text-muted small py-2 px-3 border-top">
            <strong>Link Lokasi:</strong>
            <a href="https://maps.app.goo.gl/durkKX6pCJ4xGrGX7" target="_blank" class="text-primary text-decoration-none ms-1">
                https://maps.app.goo.gl/durkKX6pCJ4xGrGX7
            </a>
        </div>
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
