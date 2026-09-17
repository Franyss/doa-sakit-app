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
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Daftar Permohonan Doa Sakit</h2>
        <a href="{{ route('doa-sakit.create') }}" class="btn btn-primary">+ Tambah Permohonan</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Gender</th>
                            <th>Penyakit</th>
                            <th>Waktu Doa</th>
                            <th>Alamat</th>
                            <th>No. HP</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($doaList as $index => $doa)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td><strong>{{ $doa->nama }}</strong></td>
                                <td>{{ $doa->gender }}</td>
                                <td>{{ $doa->jenis_penyakit }}</td>
                                <td>
                                    {{ \Carbon\Carbon::parse($doa->tanggal_doa)->format('d-m-Y') }}
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
                                <td>
                                    <a href="{{ route('doa-sakit.edit', $doa->id) }}" class="btn btn-sm btn-outline-primary me-1">Edit</a>
                                    <button type="button"
                                            class="btn btn-sm btn-outline-danger"
                                            onclick="confirmDelete('{{ route('doa-sakit.destroy', $doa->id) }}', '{{ $doa->nama }}')">
                                        Hapus
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4 text-muted">Belum ada data permohonan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <p class="fs-5 mb-1">Apakah Anda yakin ingin menghapus data permohonan doa untuk:</p>
                <h4 class="fw-bold text-danger" id="deleteNamaUser">-</h4>
                <p class="text-muted small mt-2 mb-0">Tindakan ini tidak dapat dibatalkan!</p>
            </div>
            <div class="modal-footer bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Ya, Hapus Data</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function confirmDelete(actionUrl, nama) {
        document.getElementById('deleteForm').action = actionUrl;
        document.getElementById('deleteNamaUser').textContent = nama;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }
</script>
</body>
</html>
