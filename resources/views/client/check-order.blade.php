<x-template.layout title="{{ $title }}" >  
  <x-organisms.navbar :path="$shop->path"/>
  @auth('customer')
    <div class="container py-3">
      <h4 class="fw-bold mb-3">Mis Órdenes</h4>
      @if($myOrders->isEmpty())
        <p class="text-muted">No tienes órdenes registradas.</p>
      @else
        <div class="table-responsive">
          <table class="table table-striped">
            <thead>
              <tr>
                <th>Código</th>
                <th>Productos</th>
                <th>Total</th>
                <th>Estado</th>
                <th>Fecha</th>
              </tr>
            </thead>
            <tbody>
              @foreach($myOrders as $myOrder)
              <tr>
                <td>{{ $myOrder->order_code }}</td>
                <td>{{ $myOrder->details->count() }} producto(s)</td>
                <td>${{ number_format($myOrder->total, 2) }}</td>
                <td>
                  @switch($myOrder->status)
                    @case(0) <span class="badge bg-warning text-dark">Sin procesar</span> @break
                    @case(1) <span class="badge bg-info">Confirmada</span> @break
                    @case(2) <span class="badge bg-primary">Procesada</span> @break
                    @case(3) <span class="badge bg-danger">Pendiente</span> @break
                    @case(4) <span class="badge bg-secondary">Enviando</span> @break
                    @case(5) <span class="badge bg-success">Completada</span> @break
                  @endswitch
                </td>
                <td>{{ $myOrder->created_at->format('d/m/Y') }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      @endif
    </div>
  @endauth
  @if(!auth()->guard('customer')->check())
    <x-molecules.check-order.form />
  @endif
  @isset($orderDetail)
    <x-molecules.check-order.data :order="$order" :orderDetail="$orderDetail" :orderTotal="$orderTotal ?? 0"/>
  @endisset
  <x-organisms.footer :shop="$shop"/>
</x-template.layout>