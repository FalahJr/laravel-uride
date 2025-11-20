@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Edit Commission Setting</h3>
    </div>

    <div class="page-content">
    @section('content')
        <div class="page-heading">
            <h3>Ubah Pengaturan Komisi</h3>
        </div>

        <div class="page-content">
            <section class="row">
                <div class="col-6">
                    <div class="card">
                        <div class="card-body">
                            @if ($errors->any())
                                <div class="alert alert-danger">
                                    <strong>Terdapat kesalahan:</strong>
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            <form action="{{ route('commission.settings.update', $item->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="mb-3">
                                    <label class="form-label">Tipe Layanan</label>
                                    <select name="service_type" class="form-control">
                                        <option value="ride"
                                            {{ old('service_type', $item->service_type) == 'ride' ? 'selected' : '' }}>Ride
                                        </option>
                                        <option value="food"
                                            {{ old('service_type', $item->service_type) == 'food' ? 'selected' : '' }}>Food
                                        </option>
                                        <option value="delivery"
                                            {{ old('service_type', $item->service_type) == 'delivery' ? 'selected' : '' }}>
                                            Delivery</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Biaya Tetap (Rp)</label>
                                    <input type="number" step="0.01" name="fixed_fee" class="form-control"
                                        value="{{ old('fixed_fee', $item->fixed_fee) }}">
                                </div>
                                <button class="btn btn-primary">Perbarui</button>
                                <a href="{{ route('commission.settings.index') }}" class="btn btn-secondary">Kembali</a>
                            </form>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endsection
