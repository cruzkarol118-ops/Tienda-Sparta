<x-template.layout title="{{$title}}">
    <x-organisms.navbar :path="$shop->path"/>
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-6 col-md-8 col-12">
                <div class="card shadow-sm p-4" style="border-radius:16px;">
                    <h2 class="fw-bold mb-1">Mi Perfil</h2>
                    <p class="text-muted mb-4">Actualiza tus datos personales.</p>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer.profile.update') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nombre *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $customer->name) }}" required>
                            @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Correo electrónico</label>
                            <input type="email" class="form-control" value="{{ $customer->email }}" disabled>
                            <small class="text-muted">El correo no se puede cambiar.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Teléfono</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone" value="{{ old('phone', $customer->phone) }}">
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Dirección</label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" name="address" value="{{ old('address', $customer->address) }}">
                            @error('address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <hr>
                        <h6 class="fw-bold">Cambiar contraseña (opcional)</h6>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Contraseña actual</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" name="current_password" placeholder="Deja en blanco si no deseas cambiarla">
                            @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Nueva contraseña</label>
                            <input type="password" class="form-control @error('new_password') is-invalid @enderror" name="new_password" placeholder="Mínimo 6 caracteres">
                            @error('new_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Confirmar nueva contraseña</label>
                            <input type="password" class="form-control" name="new_password_confirmation" placeholder="Repite la nueva contraseña">
                        </div>

                        <button type="submit" class="btn btn-custom w-100 py-2 fw-bold">Guardar Cambios</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <x-organisms.footer :shop="$shop"/>
</x-template.layout>