@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Buat Pengajuan Merchant</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-body">
                        <form method="POST" action="{{ route('merchant.store') }}">
                            @csrf

                            <div class="mb-3">
                                <label class="form-label">Pemilik (User)</label>
                                <select name="user_id" class="form-control" required>
                                    <option value="">-- pilih user --</option>
                                    @foreach ($users as $u)
                                        <option value="{{ $u->id }}">{{ $u->nama_lengkap }} ({{ $u->email }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Kategori</label>
                                <select name="merchant_kategori_id" class="form-control">
                                    <option value="">-- pilih kategori --</option>
                                    @foreach ($kategoris as $k)
                                        <option value="{{ $k->id }}">{{ $k->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Nama Merchant</label>
                                <input name="name" value="{{ old('name') }}" class="form-control" required
                                    maxlength="100" />
                            </div>

                            <button class="btn btn-primary">Buat Pengajuan</button>
                            <a href="{{ route('merchant.index') }}" class="btn btn-secondary">Batal</a>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
