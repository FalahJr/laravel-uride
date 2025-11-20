@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Detail Customer</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4 text-center">
                                @if (!empty($customer->foto_profil))
                                    <img src="{{ asset($customer->foto_profil) }}" alt="Foto Profil"
                                        class="img-thumbnail mb-2 d-block mx-auto"
                                        style="width:120px;height:120px;object-fit:cover;border-radius:8px;">
                                @else
                                    <div class="avatar bg-secondary text-white mb-2 mx-auto"
                                        style="width:120px;height:120px;display:flex;align-items:center;justify-content:center;border-radius:8px;font-size:28px;">
                                        {{ strtoupper(substr($customer->nama_lengkap, 0, 1)) }}</div>
                                @endif

                                <h4>{{ $customer->nama_lengkap }}</h4>
                                <p class="text-muted">{{ $customer->email }}</p>
                                <p class="text-muted">Status:
                                    <strong>{{ $customer->customer_status ?? ($customer->is_verified ? 'Active' : 'Pending') }}</strong>
                                </p>

                                <p class="text-muted">Reputasi: <strong>{{ $customer->reputasi ?? 0 }}</strong></p>

                                <p class="text-muted">Saldo E-Wallet: <strong>Rp
                                        {{ number_format(optional($ewallet)->saldo ?? 0, 0, ',', '.') }}</strong></p>

                                <div class="mt-3 d-grid gap-2">
                                    <a href="{{ route('customers.index') }}" class="btn btn-outline-dark btn-sm">Kembali</a>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h5>Alamat</h5>
                                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                        data-bs-target="#addAddressModal">Tambah Alamat</button>
                                </div>
                                @forelse($addresses as $addr)
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <div class="fw-bold">{{ $addr->label ?? 'Alamat' }}</div>
                                            <div class="text-muted">{{ $addr->alamat }}</div>
                                            @if ($addr->latitude && $addr->longitude)
                                                <div class="small text-muted">{{ $addr->latitude }},
                                                    {{ $addr->longitude }}
                                                </div>
                                            @endif
                                            <div class="mt-2">
                                                @if ($addr->is_primary)
                                                    <span class="badge bg-success">Alamat Utama</span>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary ms-2 edit-address-btn"
                                                        data-id="{{ $addr->id }}"
                                                        data-label="{{ $addr->label ?? '' }}"
                                                        data-alamat="{{ e($addr->alamat) }}"
                                                        data-latitude="{{ $addr->latitude ?? '' }}"
                                                        data-longitude="{{ $addr->longitude ?? '' }}"
                                                        data-is_primary="{{ $addr->is_primary ? 1 : 0 }}">Edit</button>
                                                @else
                                                    <form method="POST"
                                                        action="{{ route('customers.address.setPrimary', ['id' => $customer->id, 'address' => $addr->id]) }}"
                                                        style="display:inline">
                                                        @csrf
                                                        <button type="submit"
                                                            class="btn btn-sm btn-outline-primary">Jadikan Alamat
                                                            Utama</button>
                                                    </form>
                                                    <button type="button"
                                                        class="btn btn-sm btn-outline-secondary ms-2 edit-address-btn"
                                                        data-id="{{ $addr->id }}"
                                                        data-label="{{ $addr->label ?? '' }}"
                                                        data-alamat="{{ e($addr->alamat) }}"
                                                        data-latitude="{{ $addr->latitude ?? '' }}"
                                                        data-longitude="{{ $addr->longitude ?? '' }}"
                                                        data-is_primary="{{ $addr->is_primary ? 1 : 0 }}">Edit</button>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-muted">Belum ada alamat.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Add Address Modal -->
    <div class="modal fade" id="addAddressModal" tabindex="-1" aria-labelledby="addAddressModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('customers.address.store', $customer->id) }}">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="addAddressModalLabel">Tambah Alamat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" name="label" class="form-control" placeholder="Contoh: Rumah, Kantor">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea name="alamat" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" name="latitude" class="form-control" placeholder="-6.1970000">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" name="longitude" class="form-control" placeholder="106.8030000">
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="is_primary"
                                name="is_primary">
                            <label class="form-check-label" for="is_primary">Set sebagai alamat primary</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Address Modal -->
    <div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="editAddressForm" method="POST" action="#">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title" id="editAddressModalLabel">Edit Alamat</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Label</label>
                            <input type="text" id="edit_label" name="label" class="form-control"
                                placeholder="Contoh: Rumah, Kantor">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <textarea id="edit_alamat" name="alamat" class="form-control" rows="3" required></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" id="edit_latitude" name="latitude" class="form-control"
                                    placeholder="-6.1970000">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" id="edit_longitude" name="longitude" class="form-control"
                                    placeholder="106.8030000">
                            </div>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" id="edit_is_primary"
                                name="is_primary">
                            <label class="form-check-label" for="edit_is_primary">Set sebagai alamat primary</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const customerId = {{ $customer->id }};
            const editButtons = document.querySelectorAll('.edit-address-btn');
            const editModal = document.getElementById('editAddressModal');
            const editForm = document.getElementById('editAddressForm');

            editButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const label = this.dataset.label || '';
                    const alamat = this.dataset.alamat || '';
                    const latitude = this.dataset.latitude || '';
                    const longitude = this.dataset.longitude || '';
                    const isPrimary = this.dataset.is_primary == '1';

                    document.getElementById('edit_label').value = label;
                    document.getElementById('edit_alamat').value = alamat;
                    document.getElementById('edit_latitude').value = latitude;
                    document.getElementById('edit_longitude').value = longitude;
                    document.getElementById('edit_is_primary').checked = isPrimary;

                    // set form action
                    editForm.action = '/customers/' + customerId + '/address/' + id;

                    // show modal
                    const modal = new bootstrap.Modal(editModal);
                    modal.show();
                });
            });
        });
    </script>
@endsection
