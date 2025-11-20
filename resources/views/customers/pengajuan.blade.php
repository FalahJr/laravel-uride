@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Pengajuan Customer</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Pengajuan Customer</h4>
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
                                        <th>Email</th>
                                        <th>Telepon</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($customers as $c)
                                        <tr>
                                            <td>{{ $loop->iteration + ($customers->currentPage() - 1) * $customers->perPage() }}
                                            </td>
                                            <td>{{ $c->nama_lengkap }}</td>
                                            <td>{{ $c->email }}</td>
                                            <td>{{ $c->nomor_telepon }}</td>
                                            <td>
                                                <a href="{{ route('customers.show', $c->id) }}"
                                                    class="btn btn-sm btn-primary">Detail</a>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5">Belum ada pengajuan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $customers->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
