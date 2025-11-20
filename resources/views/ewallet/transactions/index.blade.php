@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Transaksi E-Wallet</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger">{{ session('error') }}</div>
                        @endif

                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Pengguna</th>
                                        <th>Tipe</th>
                                        <th>Jumlah</th>
                                        <th>Status</th>
                                        <th>Bukti</th>
                                        <th>Dibuat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($transactions as $t)
                                        <tr>
                                            <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                                            </td>
                                            <td>{{ $t->nama_lengkap }}<br><small
                                                    class="text-muted">{{ $t->email }}</small></td>
                                            <td>{{ ucfirst($t->type) }}</td>
                                            <td>Rp {{ number_format($t->amount, 0, ',', '.') }}</td>
                                            <td>
                                                @if ($t->status == 'pending')
                                                    <span class="badge bg-warning text-dark">Menunggu</span>
                                                @elseif($t->status == 'approved')
                                                    <span class="badge bg-success">Disetujui</span>
                                                @else
                                                    <span class="badge bg-danger">Ditolak</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if (!empty($t->proof_file))
                                                    <button class="btn btn-sm btn-outline-primary proof-preview-btn"
                                                        data-proof="{{ $t->proof_file }}">Pratinjau</button>
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>{{ \Illuminate\Support\Carbon::parse($t->created_at)->format('Y-m-d H:i') }}
                                            </td>
                                            <td>
                                                @if ($t->status == 'pending')
                                                    <button class="btn btn-sm btn-success me-1 action-btn"
                                                        data-action="approve" data-id="{{ $t->id }}">Setujui</button>
                                                    <button class="btn btn-sm btn-danger action-btn" data-action="reject"
                                                        data-id="{{ $t->id }}">Tolak</button>
                                                @else
                                                    <span class="text-muted">Tidak ada tindakan</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">Tidak ada transaksi.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $transactions->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Confirm Modal -->
    <div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="confirmModalBody">
                </div>
                <div class="modal-footer">
                    <form id="confirmForm" method="POST" action="#">
                        @csrf
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="confirmSubmit">Ya</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Proof Preview Modal -->
    <div class="modal fade" id="proofModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Pratinjau Bukti</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center" id="proofModalBody">
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // action buttons
            document.querySelectorAll('.action-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const action = this.dataset.action; // approve/reject
                    const id = this.dataset.id;
                    const form = document.getElementById('confirmForm');
                    form.action = '/ewallet/transactions/' + id + '/' + action;
                    document.getElementById('confirmModalBody').textContent = (action ===
                        'approve') ?
                        'Apakah Anda yakin ingin menyetujui transaksi ini?' :
                        'Apakah Anda yakin ingin menolak transaksi ini?';
                    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
                    modal.show();
                });
            });

            // proof preview
            document.querySelectorAll('.proof-preview-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    const path = this.dataset.proof;
                    const body = document.getElementById('proofModalBody');
                    body.innerHTML = '<img src="' + path + '" alt="Bukti" class="img-fluid" />';
                    const modal = new bootstrap.Modal(document.getElementById('proofModal'));
                    modal.show();
                });
            });
        });
    </script>
@endsection
