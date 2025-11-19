@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Detail Pengajuan Driver</h3>
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
                                        <button type="button" id="btnTerima" class="btn btn-success">Terima</button>
                                        <button type="button" id="btnTolak" class="btn btn-danger" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal">Tolak</button>
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

                                // Terima -> show confirmation directly
                                document.getElementById('btnTerima').addEventListener('click', function() {
                                    confirmMessage.textContent = 'Apakah Anda yakin ingin menerima pengajuan ini?';
                                    confirmBtn.dataset.action = 'approve';
                                    confirmModal.show();
                                });

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
                            });
                        </script>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
{{-- </tr> --}}
