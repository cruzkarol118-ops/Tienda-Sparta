<x-template.layout title="{{$title}}">
    <x-organisms.navbar :path="$shop->path"/>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">
                <div class="card shadow-sm p-4" style="border-radius:16px;">
                    <h2 class="fw-bold mb-1">Solicitar Devolución / Garantía</h2>
                    <p class="text-muted mb-4">Completa el formulario con los datos de tu pedido y el motivo de la solicitud.</p>

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.return.submit') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Código de la orden *</label>
                            <input type="text" class="form-control @error('order_code') is-invalid @enderror" name="order_code" value="{{ old('order_code') }}" required placeholder="Ej: ABC-20250524">
                            @error('order_code') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre completo *</label>
                            <input type="text" class="form-control @error('customer_name') is-invalid @enderror" name="customer_name" value="{{ old('customer_name', Auth::guard('customer')->user()->name ?? '') }}" required>
                            @error('customer_name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Correo electrónico *</label>
                                <input type="email" class="form-control @error('customer_email') is-invalid @enderror" name="customer_email" value="{{ old('customer_email', Auth::guard('customer')->user()->email ?? '') }}" required>
                                @error('customer_email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Teléfono</label>
                                <input type="text" class="form-control @error('customer_phone') is-invalid @enderror" name="customer_phone" value="{{ old('customer_phone', Auth::guard('customer')->user()->phone ?? '') }}">
                                @error('customer_phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Imagen del producto <small class="text-muted">(opcional)</small></label>
                            <input type="file" class="form-control @error('product_image') is-invalid @enderror" name="product_image" accept="image/*">
                            @error('product_image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Motivo de la solicitud *</label>
                            <select class="form-select @error('reason') is-invalid @enderror" name="reason" required>
                                <option value="">Selecciona un motivo</option>
                                <option value="defecto_fabricacion" {{ old('reason') == 'defecto_fabricacion' ? 'selected' : '' }}>Defecto de fábrica</option>
                                <option value="producto_incorrecto" {{ old('reason') == 'producto_incorrecto' ? 'selected' : '' }}>Producto incorrecto</option>
                                <option value="producto_danado" {{ old('reason') == 'producto_danado' ? 'selected' : '' }}>Producto dañado al llegar</option>
                                <option value="garantia" {{ old('reason') == 'garantia' ? 'selected' : '' }}>Garantía</option>
                                <option value="cambio_talla" {{ old('reason') == 'cambio_talla' ? 'selected' : '' }}>Cambio de talla</option>
                                <option value="otro" {{ old('reason') == 'otro' ? 'selected' : '' }}>Otro</option>
                            </select>
                            @error('reason') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-semibold">Descripción detallada</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" name="description" rows="4" placeholder="Describe el problema con el mayor detalle posible...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <button type="submit" class="btn btn-custom w-100 py-2 fw-bold">Enviar Solicitud</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-organisms.footer :shop="$shop"/>
</x-template.layout>