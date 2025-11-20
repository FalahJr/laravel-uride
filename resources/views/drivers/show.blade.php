@extends('layouts.app')

@section('content')
    <div class="page-heading">
        @if ($driver->status == 'pending')
            <h3>Detail Pengajuan Driver</h3>
        @else
            <h3>Detail Driver</h3>
        @endif
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                @php
                                    // Safely build initials (handles multibyte & missing parts)
                                    $name = $driver->nama_lengkap ?? ($driver->username ?? 'User');
                                    $parts = preg_split('/\s+/', trim($name));
                                    $initials = '';
                                    foreach ($parts as $p) {
                                        if ($p === '') {
                                            continue;
                                        }
                                        $initials .= mb_substr($p, 0, 1);
                                        if (mb_strlen($initials) >= 2) {
                                            break;
                                        }
                                    }
                                    $initials = strtoupper($initials ?: mb_substr($name, 0, 1));
                                @endphp

                                <div class="mb-3">
                                    <div class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center"
                                        style="width:140px;height:140px;font-size:40px;">
                                        {{ $initials }}
                                    </div>
                                </div>

                                <h4 class="fw-bold">{{ $driver->nama_lengkap ?? '-' }}</h4>
                                <p class="text-muted">{{ '@' . ($driver->username ?? '-') }}</p>

                                <div class="mt-4">
                                    <h6>Foto Profil</h6>
                                    <div class="mb-2">
                                        @if (!empty($driver->driver_photo))
                                            <img src="{{ $driver->driver_photo }}" alt="Foto Profil"
                                                class="img-fluid rounded">
                                        @else
                                            <img src="/mazer/assets/static/images/faces/1.jpg" alt="Avatar"
                                                class="img-fluid rounded" style="max-width:200px;">
                                        @endif
                                    </div>
                                </div>

                                <div class="mt-3">
                                    <p class="text-muted">Saldo E-Wallet: <strong>Rp
                                            {{ number_format(optional($ewallet)->saldo ?? 0, 0, ',', '.') }}</strong></p>
                                </div>

                                {{-- Action buttons (use modals for confirm/reject) --}}
                                {{-- Hidden forms for submission --}}
                                <form id="approveForm" method="POST"
                                    action="{{ route('drivers.verify', $driver->user_id ?? $driver->id) }}"
                                    style="display:none;">
                                    @csrf
                                    <input type="hidden" name="action" value="approve">
                                </form>

                                <form id="rejectForm" method="POST"
                                    action="{{ route('drivers.verify', $driver->user_id ?? $driver->id) }}"
                                    style="display:none;">
                                    @csrf
                                    <input type="hidden" name="action" value="reject">
                                    <input type="hidden" name="alasan_penolakan" value="">
                                </form>

                                <div class="mt-3">
                                    <div class="btn-group" role="group">
                                        @if ($driver->status == 'pending')
                                            <button type="button" id="btnTerima" class="btn btn-success">Terima</button>
                                            <button type="button" id="btnTolak" class="btn btn-danger"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal">Tolak</button>
                                        @else
                                            <button type="button" id="btnLihatTransaksi" class="btn btn-info text-white"
                                                data-user-id="{{ $driver->user_id ?? $driver->id }}">Lihat
                                                Transaksi</button>
                                        @endif



                                        <a href="{{ route('drivers.pengajuan') }}" class="btn btn-secondary">Kembali</a>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <h5>Informasi Akun</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width:160px">Nama Lengkap</th>
                                        <td>{{ $driver->nama_lengkap ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Username</th>
                                        <td>{{ $driver->username ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Email</th>
                                        <td>{{ $driver->email ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Telepon</th>
                                        <td>{{ $driver->nomor_telepon ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Alamat</th>
                                        <td>{{ $driver->alamat ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Peran</th>
                                        <td>{{ $driver->role ?? 'driver' }}</td>
                                    </tr>
                                </table>

                                <h5 class="mt-4">Informasi Kendaraan & Dokumen</h5>
                                <table class="table table-borderless">
                                    <tr>
                                        <th style="width:160px">Nomor Plat</th>
                                        <td>{{ $driver->nomor_plat ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Nomor SIM</th>
                                        <td>{{ $driver->nomor_sim ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Status Pengajuan</th>
                                        <td>{{ $driver->status ?? '-' }}</td>
                                    </tr>
                                    <tr>
                                        <th>Dibuat pada</th>
                                        <td>{{ $driver->created_at ?? '-' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <!-- Reject modal (reason form) -->
                        <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Alasan Penolakan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <form id="rejectFormVisible">
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label for="alasanInput" class="form-label">Masukkan alasan
                                                    penolakan</label>
                                                <textarea id="alasanInput" class="form-control" rows="4" required></textarea>
                                                <div class="invalid-feedback">Alasan penolakan wajib diisi.</div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger">Lanjutkan &amp;
                                                Konfirmasi</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation modal -->
                        <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Konfirmasi</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <p id="confirmMessage">Apakah Anda yakin?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="button" id="confirmBtn" class="btn btn-primary">Ya,
                                            Lanjutkan</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- User Transactions Modal -->
                        <div class="modal fade" id="transactionsModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Daftar Transaksi E-Wallet</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>#</th>
                                                        <th>Tipe</th>
                                                        <th>Jumlah</th>
                                                        <th>Status</th>
                                                        <th>Bukti</th>
                                                        <th>Dibuat</th>
                                                    </tr>
                                                </thead>
                                                <tbody id="transactionsTableBody">
                                                    <tr>
                                                        <td colspan="6" class="text-center text-muted">Memuat...</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Tutup</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                // Ensure bootstrap is available
                                var rejectModalEl = document.getElementById('rejectModal');
                                var confirmModalEl = document.getElementById('confirmModal');
                                var rejectModal = new bootstrap.Modal(rejectModalEl);
                                var confirmModal = new bootstrap.Modal(confirmModalEl);

                                var approveForm = document.getElementById('approveForm');
                                var rejectForm = document.getElementById('rejectForm');
                                var rejectFormVisible = document.getElementById('rejectFormVisible');
                                var alasanInput = document.getElementById('alasanInput');
                                var confirmMessage = document.getElementById('confirmMessage');
                                var confirmBtn = document.getElementById('confirmBtn');

                                // Terima -> show confirmation directly (guard if button exists)
                                var btnTerima = document.getElementById('btnTerima');
                                if (btnTerima) {
                                    btnTerima.addEventListener('click', function() {
                                        confirmMessage.textContent = 'Apakah Anda yakin ingin menerima pengajuan ini?';
                                        confirmBtn.dataset.action = 'approve';
                                        confirmModal.show();
                                    });
                                }

                                // When reject form (visible modal) submitted, show confirmation modal with reason
                                rejectFormVisible.addEventListener('submit', function(e) {
                                    e.preventDefault();
                                    var reason = alasanInput.value.trim();
                                    if (!reason) {
                                        alasanInput.classList.add('is-invalid');
                                        return;
                                    }
                                    // set hidden input for actual reject form
                                    rejectForm.querySelector('input[name="alasan_penolakan"]').value = reason;

                                    // prepare confirm modal
                                    confirmMessage.textContent = 'Apakah Anda yakin ingin menolak pengajuan ini? Alasan: ' +
                                        reason;
                                    confirmBtn.dataset.action = 'reject';

                                    // hide reason modal and show confirmation
                                    rejectModal.hide();
                                    confirmModal.show();
                                });

                                // When confirmation confirmed, submit appropriate hidden form
                                confirmBtn.addEventListener('click', function() {
                                    var action = this.dataset.action;
                                    if (action === 'approve') {
                                        approveForm.submit();
                                    } else if (action === 'reject') {
                                        rejectForm.submit();
                                    }
                                });

                                // Lihat Transaksi -> fetch via AJAX and show modal
                                var btnLihat = document.getElementById('btnLihatTransaksi');
                                if (btnLihat) {
                                    btnLihat.addEventListener('click', function() {
                                        var userId = this.dataset.userId;
                                        var modalEl = document.getElementById('transactionsModal');
                                        var modal = new bootstrap.Modal(modalEl);
                                        var tbody = document.getElementById('transactionsTableBody');
                                        tbody.innerHTML =
                                            '<tr><td colspan="6" class="text-center text-muted">Memuat...</td></tr>';

                                        fetch('/ewallet/transactions/user/' + userId)
                                            .then(function(res) {
                                                if (!res.ok) throw new Error('Tidak dapat memuat transaksi.');
                                                return res.json();
                                            })
                                            .then(function(json) {
                                                var data = json.data || [];
                                                if (data.length === 0) {
                                                    tbody.innerHTML =
                                                        '<tr><td colspan="6" class="text-center text-muted">Tidak ada transaksi.</td></tr>';
                                                    modal.show();
                                                    return;
                                                }

                                                var rows = '';
                                                data.forEach(function(tx, idx) {
                                                    var tipe = (tx.type || '').charAt(0).toUpperCase() + (tx.type ||
                                                        '').slice(1);
                                                    var jumlah = 'Rp ' + new Intl.NumberFormat('id-ID').format(tx
                                                        .amount || 0);
                                                    var status = tx.status === 'pending' ? 'Menunggu' : (tx
                                                        .status === 'approved' ? 'Disetujui' : 'Ditolak');
                                                    var bukti = '-';
                                                    if (tx.proof_file) {
                                                        // open proof in new tab
                                                        bukti =
                                                            '<button type="button" class="btn btn-sm btn-outline-primary" onclick="window.open(\'' +
                                                            tx.proof_file + '\', \"_blank\")">Pratinjau</button>';
                                                    }
                                                    var created = tx.created_at ? new Date(tx.created_at)
                                                        .toLocaleString('id-ID', {
                                                            year: 'numeric',
                                                            month: '2-digit',
                                                            day: '2-digit',
                                                            hour: '2-digit',
                                                            minute: '2-digit'
                                                        }) : '-';

                                                    rows += '<tr>' +
                                                        '<td>' + (idx + 1) + '</td>' +
                                                        '<td>' + tipe + '</td>' +
                                                        '<td>' + jumlah + '</td>' +
                                                        '<td>' + status + '</td>' +
                                                        '<td>' + bukti + '</td>' +
                                                        '<td>' + created + '</td>' +
                                                        '</tr>';
                                                });

                                                tbody.innerHTML = rows;
                                                modal.show();
                                            })
                                            .catch(function(err) {
                                                tbody.innerHTML = '<tr><td colspan="6" class="text-center text-danger">' + (
                                                    err
                                                    .message || 'Terjadi kesalahan') + '</td></tr>';
                                                modal.show();
                                            });
                                    });
                                }
                            });
                        </script>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
{{-- </tr> --}}
