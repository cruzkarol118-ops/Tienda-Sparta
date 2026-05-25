@push('css')
<style>
  .contact-section {
    padding-top: 2rem;
    padding-bottom: 3rem;
  }

  .form-container {
    background: #ffffff;
    padding: 2rem;
    border-radius: 0.5rem;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
    color: #000000;
    max-width: 760px;
    margin-left: auto;
    margin-right: auto;
  }

  .form-container h2 {
    font-weight: 600;
    margin-bottom: 1rem;
    color: #000000;
  }

  .form-container label {
    font-weight: 500;
    color: #000000;
  }

  .form-control,
  .form-select {
    border-radius: 0.5rem;
    border: 1px solid #ddd;
    color: #000;
    background-color: #fff;
  }

  .form-control:focus,
  .form-select:focus {
    border-color: #d4af37;
    box-shadow: 0 0 0 0.2rem rgba(212, 175, 55, 0.25);
  }
</style>
@endpush

<div class="contact-section">
  <div class="container">
    <h1 class="font-primary text-center mt-5">Queremos escucharte</h1>
    <p class="text-center fs-5 mb-0">Este formulario llega directamente al modulo de Contactos del administrador.</p>

    <div class="form-container mt-5">
      <h2 class="text-center">&iquest;Tienes algo que contarnos?</h2>
      <form action="{{ route('clientContactForm') }}" method="post">
        @csrf
        <div class="mb-3">
          <label for="firstname" class="form-label">Nombre</label>
          <input type="text" id="firstname" name="firstname" class="form-control" placeholder="Tu nombre..." required>
        </div>

        <div class="mb-3">
          <label for="lastname" class="form-label">Apellido</label>
          <input type="text" id="lastname" name="lastname" class="form-control" placeholder="Tu apellido..." required>
        </div>

        <div class="mb-3">
          <label for="email" class="form-label">Correo</label>
          <input type="email" id="email" name="email" class="form-control" placeholder="Tu correo..." required>
        </div>

        <div class="mb-3">
          <label for="country" class="form-label">Pais</label>
          <select id="country" name="country" class="form-select" required>
            <option value="colombia">Colombia</option>
            <option value="mexico">Mexico</option>
            <option value="argentina">Argentina</option>
            <option value="otros">Otro</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="subject" class="form-label">Mensaje</label>
          <textarea id="subject" name="subject" class="form-control" placeholder="Dejanos tu mensaje o sugerencia..." rows="6"></textarea>
        </div>

        <div class="text-center">
          <button type="submit" class="btn btn-custom">Enviar mensaje</button>
        </div>
      </form>
    </div>
  </div>
</div>
