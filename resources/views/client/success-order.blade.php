<x-template.layout title="{{ $title }}">
  <x-organisms.navbar :path="$shop->path" />
    <div class="container py-5 d-flex flex-column align-items-center gap-3">
      <img src="{{ asset('client/img/success-order.png') }}" class="border rounded rounded-3" style="width:40%;height:auto;">
      <div class="text-center">
        <h4>&iexcl;Muchas gracias por tu pedido!</h4>
        <p>C&oacute;digo de Orden: <u><b class="text-danger">{{ $order_code }}</b></u></p>
        <p>Puedes hacer seguimiento de tu pedido en <a href="{{ route('clientCheckOrder') }}"><u>Consultar Orden</u></a>. Por favor guarda este c&oacute;digo y no lo olvides para verificar el estado de tu pedido.</p>
      </div>
      <a href="{{ route('clientCheckOrder') }}" class="btn btn-primary">Consultar Orden</a>
    </div>
  <x-organisms.footer :shop="$shop"/>
  <script>
    const order = @json($order);
    const orderDetails = @json($order_details);
    let message = `Hola *${order.name}*.\n\n` +
              `Gracias por tu compra. Detalles de tu pedido #${order.order_code}:\n\n` +
              `Productos:\n${orderDetails.map(item =>
                `- ${item.title} (x${item.quantity}): $${item.price * item.quantity}`
              ).join('\n')}\n\n` +
              `Total: $${order.total}\n\n` +
              `Que sigue:\n` +
              `1. Contacto para pago.\n` +
              `2. Preparacion de tu pedido.\n` +
              `3. Entrega lista.\n\n` +
              `Dudas? Respondenos aqui.`;

window.open(`https://wa.me/573213333915?text=${encodeURIComponent(message)}`, '_blank');
</script>
</x-template.layout>
