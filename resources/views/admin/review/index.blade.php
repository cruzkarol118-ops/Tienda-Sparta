@extends('admin.layout')

@section('css')
<style>
    .table-review-img {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 0.4rem;
    }
</style>
@endsection

@section('content')
<div class="col-12">
    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cliente</th>
                            <th>Calificación</th>
                            <th>Comentario</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reviews as $review)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @php $img = $review->product->productImage->first(); @endphp
                                        @if($img)
                                            <img src="{{ asset('shop/products/'.$img->path) }}"
                                                 class="table-review-img me-2" alt="">
                                        @endif
                                        <a href="{{ route('clientProductDetail', $review->product->slug) }}" target="_blank" class="text-truncate" style="max-width:150px; display:inline-block;">
                                            {{ $review->product->title }}
                                        </a>
                                    </div>
                                </td>
                                <td>{{ $review->customer?->name ?? 'Anónimo' }}</td>
                                <td>
                                    <div class="text-warning d-flex" style="font-size:0.85rem;">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <i class="bi bi-star-fill"></i>
                                            @else
                                                <i class="bi bi-star"></i>
                                            @endif
                                        @endfor
                                    </div>
                                </td>
                                <td class="text-truncate" style="max-width:200px;">{{ $review->comment ?? '—' }}</td>
                                <td>
                                    @if($review->is_approved)
                                        <span class="badge bg-success">Aprobada</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                    @endif
                                </td>
                                <td>{{ $review->created_at->format('d/m/Y') }}</td>
                                <td>
                                    <div class="d-flex gap-1">
                                        @if(!$review->is_approved)
                                            <a href="{{ route('admin.reviews.approve', $review->id) }}"
                                               class="btn btn-sm btn-success"
                                               onclick="return confirm('¿Aprobar esta reseña?')">
                                                <i class="bi bi-check-lg"></i> Aprobar
                                            </a>
                                        @else
                                            <a href="{{ route('admin.reviews.reject', $review->id) }}"
                                               class="btn btn-sm btn-outline-warning"
                                               onclick="return confirm('¿Rechazar esta reseña?')">
                                                <i class="bi bi-x-lg"></i> Rechazar
                                            </a>
                                        @endif
                                        <a href="{{ route('admin.reviews.destroy', $review->id) }}"
                                           class="btn btn-sm btn-outline-danger"
                                           onclick="return confirm('¿Eliminar esta reseña permanentemente?')">
                                            <i class="bi bi-trash"></i> Eliminar
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">No hay reseñas aún</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3">
                {{ $reviews->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
