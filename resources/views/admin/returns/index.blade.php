@extends('admin.layout')
@section('content')
<div class="card">
    <div class="card-body">
        @if($returns->isEmpty())
            <div class="text-center py-5">
                <p class="text-muted">No hay solicitudes de devolución/garantía.</p>
            </div>
        @else
            <table class="table table-striped" id="table1">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Orden</th>
                        <th>Cliente</th>
                        <th>Motivo</th>
                        <th>Imagen</th>
                        <th>Fecha</th>
                        <th>Estado</th>
                        <th>Acción</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($returns as $return)
                    <tr>
                        <td>{{ $return->id }}</td>
                        <td>{{ $return->order_code }}</td>
                        <td>{{ $return->customer_name }}<br><small class="text-muted">{{ $return->customer_email }}</small></td>
                        <td>{{ ucfirst(str_replace('_', ' ', $return->reason)) }}</td>
                        <td>
                            @if($return->product_image)
                                <a href="{{ asset('storage/'.$return->product_image) }}" target="_blank">
                                    <img src="{{ asset('storage/'.$return->product_image) }}" alt="Producto" style="width:50px;height:50px;object-fit:cover;border-radius:4px;">
                                </a>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                        <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
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
                        <td>
                            <a href="{{ route('admin.returns.detail', $return->id) }}" class="btn btn-sm btn-outline-primary">Detalle</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</div>
@endsection