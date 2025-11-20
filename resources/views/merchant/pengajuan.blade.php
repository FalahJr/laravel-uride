@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Pengajuan Merchant</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Pengajuan Merchant (Belum Aktif)</h4>
                        <form class="d-flex" method="GET" action="{{ route('merchant.pengajuan') }}">
                            <input name="q" value="{{ $q ?? '' }}" class="form-control me-2"
                                placeholder="Cari nama merchant" />
                            <button class="btn btn-primary">Cari</button>
                        </form>
                        <div class="d-flex gap-2">
                            <a href="{{ route('merchant.create') }}" class="btn btn-success">Buat Pengajuan Merchant</a>
                        </div>
                    </div>
                    <div class="card-body">
                        @if (session('status'))
                            <div class="alert alert-success">{{ session('status') }}</div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nama</th>
                                        <th>Kategori</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($merchants as $merchant)
                                        <tr>
                                            <td>{{ $loop->iteration + ($merchants->currentPage() - 1) * $merchants->perPage() }}
                                            </td>
                                            <td>{{ $merchant->name }}</td>
                                            <td>{{ $merchant->kategori->name ?? '-' }}</td>
                                            <td>{{ $merchant->status }}</td>
                                            <td>
                                                <a href="{{ route('merchant.show', $merchant->id) }}"
                                                    class="btn btn-sm btn-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">Tidak ada pengajuan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $merchants->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
