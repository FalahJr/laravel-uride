@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Manajemen Alamat - {{ $merchant->name }}</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Alamat Merchant</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('merchant.show', $merchant->id) }}" class="btn btn-outline-secondary">Kembali ke
                                Merchant</a>
                            <a href="{{ route('merchant.address.create', $merchant->id) }}" class="btn btn-primary">Tambah
                                Alamat</a>
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
                                        <th>Alamat</th>
                                        <th>Koordinat</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($addresses as $a)
                                        <tr>
                                            <td>{{ $loop->iteration + ($addresses->currentPage() - 1) * $addresses->perPage() }}
                                            </td>
                                            <td>{{ $a->alamat }}</td>
                                            <td>{{ $a->latitude ?? '-' }}, {{ $a->longitude ?? '-' }}</td>
                                            <td>
                                                <a href="{{ route('merchant.address.edit', [$merchant->id, $a->id]) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form
                                                    action="{{ route('merchant.address.destroy', [$merchant->id, $a->id]) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Hapus alamat?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">Belum ada alamat.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $addresses->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
