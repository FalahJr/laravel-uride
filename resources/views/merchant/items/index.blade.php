@extends('layouts.app')

@section('content')
    <div class="page-heading">
        <h3>Manajemen Item - {{ $merchant->name }}</h3>
    </div>

    <div class="page-content">
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Item Merchant</h4>
                        <div class="d-flex gap-2">
                            <a href="{{ route('merchant.show', $merchant->id) }}" class="btn btn-outline-secondary">Kembali ke
                                Merchant</a>
                            <a href="{{ route('merchant.items.create', $merchant->id) }}" class="btn btn-primary">Tambah
                                Item</a>
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
                                        <th>Harga</th>
                                        <th>Stock</th>
                                        <th>Available</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td>{{ $loop->iteration + ($items->currentPage() - 1) * $items->perPage() }}
                                            </td>
                                            <td>{{ $item->nama_barang }}</td>
                                            <td>{{ $item->harga }}</td>
                                            <td>{{ $item->stock }}</td>
                                            <td>{{ $item->is_available ? 'Ya' : 'Tidak' }}</td>
                                            <td>
                                                <a href="{{ route('merchant.items.edit', [$merchant->id, $item->id]) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                                <form
                                                    action="{{ route('merchant.items.destroy', [$merchant->id, $item->id]) }}"
                                                    method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-danger"
                                                        onclick="return confirm('Hapus item?')">Hapus</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6">Belum ada item.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div
                            class="d-flex flex-column flex-sm-row justify-content-between align-items-center merchant-pagination">
                            <div class="mb-2 mb-sm-0 text-muted small">Showing {{ $items->firstItem() ?? 0 }} to
                                {{ $items->lastItem() ?? 0 }} of {{ $items->total() }} results</div>
                            <nav aria-label="Items pagination">
                                {{ $items->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>

                        <style>
                            /* Local overrides to prevent theme CSS from blowing up pagination */
                            .merchant-pagination {
                                gap: 12px;
                            }

                            .merchant-pagination .pagination {
                                margin: 0;
                            }

                            .merchant-pagination .page-link {
                                font-size: .9rem;
                                padding: .35rem .6rem;
                            }

                            .merchant-pagination .page-item:first-child .page-link,
                            .merchant-pagination .page-item:last-child .page-link {
                                border-radius: .35rem;
                            }

                            .merchant-pagination .page-item .page-link svg {
                                width: 1em;
                                height: 1em;
                            }
                        </style>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection
