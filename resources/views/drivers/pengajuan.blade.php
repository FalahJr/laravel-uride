@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Pengajuan Driver</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Pengajuan Driver (Belum Terverifikasi)</h4>
                        <form class="d-flex" method="GET" action="{{ route('drivers.pengajuan') }}">
                            <input name="q" value="{{ $q ?? '' }}" class="form-control me-2"
                                placeholder="Cari nama, username atau plat" />
                            <button class="btn btn-primary">Cari</button>
                        </form>
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
                                        <th>Username</th>
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Nomor Plat</th>
                                        <th>Nomor SIM</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($drivers as $driver)
                                        <tr>
                                            <td>{{ $loop->iteration + ($drivers->currentPage() - 1) * $drivers->perPage() }}
                                            </td>
                                            <td>{{ $driver->nama_lengkap }}</td>
                                            <td>{{ $driver->username }}</td>
                                            <td>{{ $driver->email }}</td>
                                            <td>{{ $driver->nomor_telepon }}</td>
                                            <td>{{ $driver->nomor_plat }}</td>
                                            <td>{{ $driver->nomor_sim }}</td>
                                            <td>
                                                <a href="{{ route('drivers.show', $driver->user_id) }}"
                                                    class="btn btn-sm btn-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8">Tidak ada data pengajuan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $drivers->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
