@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>
            @if (!empty($kategori))
                Edit
            @else
                Buat
            @endif Kategori
        </h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ !empty($kategori) ? route('merchant.kategori.update', $kategori->id) : route('merchant.kategori.store') }}">
                            @csrf
                            @if (!empty($kategori))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Nama Kategori</label>
                                <input name="name" value="{{ old('name', $kategori->name ?? '') }}" class="form-control"
                                    required maxlength="50" />
                            </div>

                            <button class="btn btn-primary">Simpan</button>
                            <a href="{{ route('merchant.kategori.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
