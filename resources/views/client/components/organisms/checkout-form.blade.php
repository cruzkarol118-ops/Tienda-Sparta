@php
    $customer = Auth::guard('customer')->user();
@endphp
<div class="container py-4">
@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
    <form action="{{ route('clientCheckoutSave') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="name">Nombre</label>
            <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror bg-transparent" placeholder="Mike" value="{{ old('name', $customer->name ?? '') }}" required>
            @error('name') 
              <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="phone">Celular</label>
            <input type="text" name="phone" id="phone" class="form-control @error('phone') is-invalid @enderror bg-transparent" placeholder="312222xxx" value="{{ old('phone', $customer->phone ?? '') }}" required>
            @error('phone') 
              <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="email">Correo</label>
            <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror bg-transparent" placeholder="hola@gmail.com" value="{{ old('email', $customer->email ?? '') }}" required>
            @error('email') 
              <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        @guest('customer')
        <div class="form-group">
            <label for="checkout_password">Contraseña <small class="text-muted">(Si ya tienes cuenta, ingresa tu contraseña para iniciar sesión)</small></label>
            <input type="password" name="checkout_password" id="checkout_password" class="form-control @error('checkout_password') is-invalid @enderror bg-transparent" placeholder="Tu contraseña">
            @error('checkout_password') 
              <small class="text-danger">{{ $message }}</small>
            @enderror
            <small class="text-muted">¿No tienes cuenta? <a href="{{ route('customer.register') }}">Regístrate aquí</a></small>
        </div>
        @endguest
        <div class="form-group">
            <label for="address">Direccion exacta</label>
            <input type="text" name="address" id="address" class="form-control @error('address') is-invalid @enderror bg-transparent" placeholder="av 12 cucuta xx" value="{{ old('address', $customer->address ?? '') }}" required>
            @error('address') 
              <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group">
            <label for="note">Nota/Sugerencia</label>
            <textarea name="note" id="note" cols="30" class="form-control @error('note') is-invalid @enderror bg-transparent" placeholder="Casa, apartamento, etc  . . .">{{ old('note') }}</textarea>
            @error('note')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>
        <button type="submit" class="btn btn-primary float-end">Order</button>
    </form>
</div>
@push('js')
    <script>
        autosize();
        function autosize(){
            var text = $('#note');

            text.each(function(){
                $(this).attr('rows',1);
                resize($(this));
                this.style.overflow = 'hidden';
                this.style.backgroundColor = 'transparent';
            });

            text.on('input', function(){
                resize($(this));
            });
            
            function resize ($text) {
                $text.css('height', 'auto');
                $text.css('height', $text[0].scrollHeight+'px');
            }
        }
    </script>
@endpush