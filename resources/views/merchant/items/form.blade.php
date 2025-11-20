@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>
            @if (!empty($item))
                Edit
            @else
                Tambah
            @endif Item - {{ $merchant->name }}
        </h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form method="POST"
                            action="{{ !empty($item) ? route('merchant.items.update', [$merchant->id, $item->id]) : route('merchant.items.store', $merchant->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @if (!empty($item))
                                @method('PUT')
                            @endif

                            <div class="mb-3">
                                <label class="form-label">Nama Barang</label>
                                <input name="nama_barang" value="{{ old('nama_barang', $item->nama_barang ?? '') }}"
                                    class="form-control" required maxlength="100" />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Harga</label>
                                <input name="harga" value="{{ old('harga', $item->harga ?? '') }}" class="form-control"
                                    required />
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Foto (opsional)</label>
                                @if (!empty($item) && !empty($item->image_url))
                                    <div class="mb-2">
                                        <img src="{{ $item->image_url }}" alt="preview"
                                            style="max-width:200px;max-height:160px;border-radius:6px;">
                                    </div>
                                @endif
                                <input type="file" name="image" accept="image/*" class="form-control" />
                                <div class="form-text">File gambar maksimal 2MB. Format: jpg, png, gif.</div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Stock</label>
                                <input name="stock" value="{{ old('stock', $item->stock ?? 0) }}" class="form-control" />
                            </div>

                            <div class="mb-3 form-check">
                                <input type="checkbox" name="is_available" value="1" class="form-check-input"
                                    id="isAvailable"
                                    {{ old('is_available', $item->is_available ?? true) ? 'checked' : '' }}>
                                <label class="form-check-label" for="isAvailable">Tersedia</label>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Deskripsi</label>
                                <textarea name="description" class="form-control">{{ old('description', $item->description ?? '') }}</textarea>
                            </div>

                            <button class="btn btn-primary">Simpan</button>
                            <a href="{{ route('merchant.show', $merchant->id) }}" class="btn btn-outline-dark">Kembali ke
                                Merchant</a>
                            <a href="{{ route('merchant.items.index', $merchant->id) }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
