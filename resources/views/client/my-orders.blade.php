<x-template.layout title="{{$title}}">
    <x-organisms.navbar :path="$shop->path"/>
    <div class="container py-5">
        <h2 class="fw-bold mb-4">Mis Órdenes</h2>

        @if($orders->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size:3rem;color:#dee2e6;"></i>
                <p class="text-muted mt-3">No has realizado ninguna orden aún.</p>
                <a href="{{ route('clientProducts') }}" class="btn btn-custom">Ver productos</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Código</th>
                            <th>Productos</th>
                            <th>Total</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $order->order_code }}</td>
                            <td>{{ $order->details->count() }} producto(s)</td>
                            <td>${{ number_format($order->total, 2) }}</td>
                            <td>
                                @switch($order->status)
                                    @case(0) <span class="badge bg-warning text-dark">Sin procesar</span> @break
                                    @case(1) <span class="badge bg-info">Confirmada</span> @break
                                    @case(2) <span class="badge bg-primary">Procesada</span> @break
                                    @case(3) <span class="badge bg-danger">Pendiente</span> @break
                                    @case(4) <span class="badge bg-secondary">Enviando</span> @break
                                    @case(5) <span class="badge bg-success">Completada</span> @break
                                @endswitch
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <x-organisms.footer :shop="$shop"/>
</x-template.layout>