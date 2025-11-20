@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>
            @if (!empty($address))
                Edit
            @else
                Tambah
            @endif Alamat - {{ $merchant->name }}
        </h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ !empty($address) ? route('merchant.address.update', [$merchant->id, $address->id]) : route('merchant.address.store', $merchant->id) }}">
                            @csrf
                            @if (!empty($address))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Alamat</label>
                                <textarea name="alamat" class="form-control" required>{{ old('alamat', $address->alamat ?? '') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Latitude</label>
                                <input name="latitude" value="{{ old('latitude', $address->latitude ?? '') }}"
                                    class="form-control" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Longitude</label>
                                <input name="longitude" value="{{ old('longitude', $address->longitude ?? '') }}"
                                    class="form-control" />
                            </div>

                            <button class="btn btn-primary">Simpan</button>
                            <a href="{{ route('merchant.show', $merchant->id) }}" class="btn btn-outline-dark">Kembali ke
                                Merchant</a>
                            <a href="{{ route('merchant.address.index', $merchant->id) }}"
                                class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
