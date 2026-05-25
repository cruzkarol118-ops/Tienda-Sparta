<x-template.layout title="{{$title}}">
    <x-organisms.navbar :path="$shop->path"/>
    <div class="container py-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">Mis Solicitudes</h2>
            <a href="{{ route('customer.return.form') }}" class="btn btn-custom">Nueva Solicitud</a>
        </div>

        @if($returns->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-inbox" style="font-size:3rem;color:#dee2e6;"></i>
                <p class="text-muted mt-3">No has realizado ninguna solicitud de devolución o garantía.</p>
                <a href="{{ route('customer.return.form') }}" class="btn btn-custom">Solicitar ahora</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Orden</th>
                            <th>Motivo</th>
                            <th>Fecha</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($returns as $return)
                        <tr>
                            <td>{{ $return->id }}</td>
                            <td>{{ $return->order_code }}</td>
                            <td>{{ ucfirst(str_replace('_', ' ', $return->reason)) }}</td>
                            <td>{{ $return->created_at->format('d/m/Y') }}</td>
                            <td>
                                @switch($return->status)
                                    @case('pending')
                                        <span class="badge bg-warning text-dark">Pendiente</span>
                                        @break
                                    @case('approved')
                                        <span class="badge bg-info">Aprobada</span>
                                        @break
                                    @case('rejected')
                                        <span class="badge bg-danger">Rechazada</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Completada</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-secondary">Cancelada</span>
                                        @break
                                @endswitch
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    <x-organisms.footer :shop="$shop"/>
</x-template.layout>