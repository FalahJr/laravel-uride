@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Commission Settings</h3>
    @section('content')
        <div class="page-heading">
            <h3>Pengaturan Komisi</h3>
        </div>

        <div class="page-content">
            <section class="row">
                <div class="col-12">
                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-body">
                            <a href="{{ route('commission.settings.create') }}" class="btn btn-primary mb-3">Buat Baru</a>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Tipe Layanan</th>
                                            <th>Biaya Tetap</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($items as $item)
                                            <tr>
                                                <td>{{ $item->id }}</td>
                                                <td>{{ ucfirst($item->service_type) }}</td>
                                                <td>Rp. {{ number_format($item->fixed_fee, 2, ',', '.') }}</td>
                                                <td>
                                                    <a href="{{ route('commission.settings.edit', $item->id) }}"
                                                        class="btn btn-sm btn-warning">Ubah</a>
                                                    <form action="{{ route('commission.settings.destroy', $item->id) }}"
                                                        method="POST" style="display:inline-block"
                                                        onsubmit="return confirm('Hapus pengaturan ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button class="btn btn-sm btn-danger">Hapus</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4">Belum ada pengaturan komisi.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    @endsection
