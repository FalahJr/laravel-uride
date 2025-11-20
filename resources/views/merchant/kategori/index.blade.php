@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Manajemen Kategori Merchant</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Kategori</h4>
                        <a href="{{ route('merchant.kategori.create') }}" class="btn btn-primary">Buat Kategori</a>
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
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($kategoris as $k)
                                        <tr>
                                            <td>{{ $loop->iteration + ($kategoris->currentPage() - 1) * $kategoris->perPage() }}
                                            </td>
                                            <td>{{ $k->name }}</td>
                                            <td>
                                                <a href="{{ route('merchant.kategori.edit', $k->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form action="{{ route('merchant.kategori.destroy', $k->id) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Hapus kategori?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">Belum ada kategori.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex justify-content-center">
                            {{ $kategoris->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
