@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Detail Merchant</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row gy-4">
                            <div class="col-md-4">
                                <div class="text-center">
                                    @php
                                        $name = $merchant->name ?? 'Merchant';
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

                                    <div class="profile-avatar mb-3">
                                        <div
                                            class="rounded-circle bg-primary text-white d-inline-flex align-items-center justify-content-center avatar-lg">
                                            {{ $initials }}
                                        </div>
                                    </div>

                                    <h4 class="fw-bold mb-0">{{ $merchant->name }}</h4>
                                    <div class="small text-muted mb-2">{{ $merchant->user->email ?? '' }}</div>

                                    <div class="mb-2">
                                        <span class="badge bg-success me-1">{{ $merchant->kategori->name ?? '—' }}</span>
                                        <span
                                            class="badge bg-{{ $merchant->status === 'active' ? 'success' : ($merchant->status === 'pending' ? 'info' : 'secondary') }}">{{ ucfirst($merchant->status) }}</span>
                                    </div>

                                    <div class="mb-2">
                                        <p class="text-muted">Saldo E-Wallet: <strong>Rp
                                                {{ number_format(optional($ewallet)->saldo ?? 0, 0, ',', '.') }}</strong>
                                        </p>
                                    </div>

                                    <div class="d-grid gap-2">
                                        <a href="{{ route('merchant.items.index', $merchant->id) }}"
                                            class="btn btn-outline-primary btn-sm">Kelola Item</a>
                                        <a href="{{ route('merchant.address.index', $merchant->id) }}"
                                            class="btn btn-outline-secondary btn-sm">Kelola Alamat</a>

                                    </div>

                                    <div class="mt-3 d-flex justify-content-center gap-2">
                                        @if ($merchant->status != 'active')
                                            <button type="button" id="btnTerima"
                                                class="btn btn-success btn-sm">Terima</button>
                                            <button type="button" id="btnTolak" class="btn btn-danger btn-sm"
                                                data-bs-toggle="modal" data-bs-target="#rejectModal">Tolak</button>
                                        @endif

                                        <a href="{{ route('merchant.pengajuan') }}"
                                            class="btn btn-outline-dark btn-sm">Kembali</a>
                                    </div>

                                    <form id="verifyForm" method="POST"
                                        action="{{ route('merchant.verify', $merchant->id) }}" style="display:none;">@csrf
                                        <input type="hidden" name="action" value="">
                                        <input type="hidden" name="alasan_penolakan" value="">
                                    </form>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="mb-3 d-flex justify-content-between align-items-center">
                                    <h4 class="m-0">Ringkasan</h4>
                                    <small class="text-muted">Dibuat:
                                        {{ $merchant->created_at ? $merchant->created_at->format('d M Y') : '-' }}</small>
                                </div>

                                <div class="mb-4">
                                    <p class="text-muted">{{ $merchant->description ?? 'Belum ada deskripsi merchant.' }}
                                    </p>
                                </div>

                                <div class="row mb-4">
                                    <div class="col-sm-4">
                                        <div class="card small-card">
                                            <div class="card-body text-center">
                                                <h5 class="mb-0">{{ $merchant->items->count() }}</h5>
                                                <small class="text-muted">Items</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card small-card">
                                            <div class="card-body text-center">
                                                <h5 class="mb-0">{{ $merchant->addresses->count() }}</h5>
                                                <small class="text-muted">Alamat</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-4">
                                        <div class="card small-card">
                                            <div class="card-body text-center">
                                                <h5 class="mb-0">
                                                    {{ $merchant->user ? $merchant->user->nama_lengkap : '-' }}</h5>
                                                <small class="text-muted">Pemilik</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Items</h5>
                                    @php $totalItems = $merchant->items->count(); @endphp
                                    @if ($totalItems > 4)
                                        <a href="{{ route('merchant.items.index', $merchant->id) }}"
                                            class="btn btn-sm btn-outline-primary">Lihat semua ({{ $totalItems }})</a>
                                    @else
                                        <a href="{{ route('merchant.items.index', $merchant->id) }}"
                                            class="btn btn-sm btn-outline-secondary">Kelola Item</a>
                                    @endif
                                </div>

                                <div class="row gx-3 gy-4 mb-4">
                                    @php $showItems = $merchant->items->take(4); @endphp
                                    @forelse($showItems as $item)
                                        <div class="col-6 col-md-4 col-lg-3">
                                            <div class="card item-card h-100 shadow-sm">
                                                <div class="card-img-top d-flex align-items-center justify-content-center p-3"
                                                    style="height:120px;overflow:hidden;">
                                                    <img src="{{ $item->image_url ?? asset('mazer/static/images/placeholder.png') }}"
                                                        class="img-fluid" alt="item" style="max-height:100%;">
                                                </div>
                                                <div class="card-body p-3">
                                                    <h6 class="card-title mb-1 text-truncate">{{ $item->nama_barang }}</h6>
                                                    <div class="mb-2"><span class="badge bg-info">Rp
                                                            {{ number_format($item->harga, 0, ',', '.') }}</span></div>
                                                    <p class="small text-muted mb-2">Stok: {{ $item->stock }}</p>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('merchant.items.edit', [$merchant->id, $item->id]) }}"
                                                            class="btn btn-sm btn-outline-primary">Edit</a>
                                                        <form
                                                            action="{{ route('merchant.items.destroy', [$merchant->id, $item->id]) }}"
                                                            method="POST" onsubmit="return confirm('Hapus item ini?');">
                                                            @csrf @method('DELETE')
                                                            <button class="btn btn-sm btn-outline-danger">Hapus</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="col-12">
                                            <div class="text-muted">Tidak ada item.</div>
                                        </div>
                                    @endforelse
                                </div>

                                <h5>Alamat</h5>
                                <div class="mb-3">
                                    @forelse($merchant->addresses as $addr)
                                        <div class="card mb-2">
                                            <div class="card-body d-flex justify-content-between align-items-start">
                                                <div>
                                                    <div class="fw-bold">{{ Str::limit($addr->alamat, 70) }}</div>
                                                    @if ($addr->latitude && $addr->longitude)
                                                        <small class="text-muted">{{ $addr->latitude }},
                                                            {{ $addr->longitude }}</small>
                                                    @endif
                                                </div>
                                                <div class="ms-3">
                                                    <a href="{{ route('merchant.address.index', $merchant->id) }}"
                                                        class="btn btn-sm btn-outline-primary">Kelola</a>
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-muted">Tidak ada alamat.</div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Reject modal (reason form) -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Alasan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="rejectFormVisible">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="alasanInput" class="form-label">Masukkan alasan penolakan</label>
                            <textarea id="alasanInput" class="form-control" rows="4" required></textarea>
                            <div class="invalid-feedback">Alasan penolakan wajib diisi.</div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-danger">Lanjutkan &amp; Konfirmasi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const verifyForm = document.getElementById('verifyForm');
            const alasanInput = document.getElementById('alasanInput');
            const rejectModalEl = document.getElementById('rejectModal');
            const rejectModal = new bootstrap.Modal(rejectModalEl);

            document.getElementById('btnTerima').addEventListener('click', function() {
                if (!confirm('Apakah Anda yakin ingin menerima pengajuan merchant ini?')) return;
                verifyForm.querySelector('input[name="action"]').value = 'approve';
                verifyForm.submit();
            });

            document.getElementById('btnTolak').addEventListener('click', function() {
                rejectModal.show();
            });

            document.getElementById('rejectFormVisible').addEventListener('submit', function(e) {
                e.preventDefault();
                const reason = alasanInput.value.trim();
                if (!reason) {
                    alasanInput.classList.add('is-invalid');
                    return;
                }
                verifyForm.querySelector('input[name="action"]').value = 'reject';
                verifyForm.querySelector('input[name="alasan_penolakan"]').value = reason;
                rejectModal.hide();
                verifyForm.submit();
            });
        });
    </script>

    <style>
        .avatar-lg {
            width: 140px;
            height: 140px;
            font-size: 40px;
        }

        .small-card {
            border-radius: 8px;
        }

        .profile-avatar {
            display: flex;
            justify-content: center;
        }

        .card .card-body .card-title {
            font-size: 0.95rem;
        }

        /* Item card tweaks */
        .item-card {
            border-radius: 8px;
        }

        .item-card .card-img-top img {
            object-fit: cover;
        }

        .text-truncate {
            display: block;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endsection
