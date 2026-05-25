@extends('admin.layout')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Información de la Solicitud</h5>
                <table class="table table-borderless">
                    <tr>
                        <th>ID:</th>
                        <td>#{{ $return->id }}</td>
                    </tr>
                    <tr>
                        <th>Código de Orden:</th>
                        <td>{{ $return->order_code }}</td>
                    </tr>
                    <tr>
                        <th>Motivo:</th>
                        <td>{{ ucfirst(str_replace('_', ' ', $return->reason)) }}</td>
                    </tr>
                    <tr>
                        <th>Descripción:</th>
                        <td>{{ $return->description ?? 'Sin descripción' }}</td>
                    </tr>
                    @if($return->product_image)
                    <tr>
                        <th>Imagen del producto:</th>
                        <td>
                            <a href="{{ asset('storage/'.$return->product_image) }}" target="_blank">
                                <img src="{{ asset('storage/'.$return->product_image) }}" alt="Producto" style="max-width:200px;max-height:200px;border-radius:8px;">
                            </a>
                        </td>
                    </tr>
                    @endif
                    <tr>
                        <th>Estado actual:</th>
                        <td>
                            @switch($return->status)
                                @case('pending') <span class="badge bg-warning text-dark">Pendiente</span> @break
                                @case('approved') <span class="badge bg-info">Aprobada</span> @break
                                @case('rejected') <span class="badge bg-danger">Rechazada</span> @break
                                @case('completed') <span class="badge bg-success">Completada</span> @break
                                @case('cancelled') <span class="badge bg-secondary">Cancelada</span> @break
                            @endswitch
                        </td>
                    </tr>
                    <tr>
                        <th>Fecha:</th>
                        <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Información del Cliente</h5>
                <table class="table table-borderless">
                    <tr>
                        <th>Nombre:</th>
                        <td>{{ $return->customer_name }}</td>
                    </tr>
                    <tr>
                        <th>Email:</th>
                        <td>{{ $return->customer_email }}</td>
                    </tr>
                    <tr>
                        <th>Teléfono:</th>
                        <td>{{ $return->customer_phone ?? 'No registrado' }}</td>
                    </tr>
                    @if($return->customer)
                    <tr>
                        <th>Cliente registrado:</th>
                        <td>Sí (ID: {{ $return->customer->id }})</td>
                    </tr>
                    @endif
                </table>
            </div>
        </div>
    </div>
</div>

@if($return->admin_note)
<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title">Nota del Administrador</h5>
        <p class="mb-0">{{ $return->admin_note }}</p>
    </div>
</div>
@endif

<div class="card mt-3">
    <div class="card-body">
        <h5 class="card-title">Actualizar Estado</h5>
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif
        <form method="POST" action="{{ route('admin.returns.update-status', $return->id) }}">
            @csrf
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Nuevo estado</label>
                    <select name="status" class="form-select" required>
                        <option value="pending" {{ $return->status == 'pending' ? 'selected' : '' }}>Pendiente</option>
                        <option value="approved" {{ $return->status == 'approved' ? 'selected' : '' }}>Aprobar</option>
                        <option value="rejected" {{ $return->status == 'rejected' ? 'selected' : '' }}>Rechazar</option>
                        <option value="completed" {{ $return->status == 'completed' ? 'selected' : '' }}>Completada</option>
                        <option value="cancelled" {{ $return->status == 'cancelled' ? 'selected' : '' }}>Cancelar</option>
                    </select>
                </div>
                <div class="col-md-8 mb-3">
                    <label class="form-label">Nota (obligatorio si rechazas)</label>
                    <textarea name="admin_note" rows="2" class="form-control" placeholder="Agrega una nota...">{{ old('admin_note', $return->admin_note) }}</textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Estado</button>
        </form>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('admin.returns') }}" class="btn btn-secondary">← Volver al listado</a>
</div>
@endsection