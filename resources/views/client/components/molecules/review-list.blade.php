<div class="row">
    <div class="col-12">
        <h2 class="mb-4">Reseñas de clientas</h2>

        @if($reviews->isEmpty())
            <p class="text-muted">Aún no hay reseñas para este producto. Sé la primera en escribir una.</p>
        @else
            <div class="row">
                @foreach($reviews as $review)
                    <div class="col-md-6 mb-3">
                        <div class="card border shadow-sm h-100">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($review->customer?->name ?? 'Anónima') }}&background=D4AF37&color=fff"
                                         class="rounded-circle me-3" width="48" alt="">
                                    <div>
                                        <h6 class="mb-0">{{ $review->customer?->name ?? 'Anónima' }}</h6>
                                        <div class="text-warning d-flex" style="font-size:0.9rem;">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= $review->rating)
                                                    <i class="bi bi-star-fill"></i>
                                                @else
                                                    <i class="bi bi-star"></i>
                                                @endif
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                                @if($review->comment)
                                    <p class="mb-0">"{{ $review->comment }}"</p>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @auth('customer')
            <hr class="my-4">
            @php
                $existingReview = $reviews->firstWhere('customer_id', Auth::guard('customer')->id());
            @endphp
            @if($existingReview)
                <h4>Tu reseña</h4>
                <div class="card border mt-3">
                    <div class="card-body">
                        <div class="text-warning d-flex mb-2" style="font-size:1.1rem;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $existingReview->rating)
                                    <i class="bi bi-star-fill"></i>
                                @else
                                    <i class="bi bi-star"></i>
                                @endif
                            @endfor
                        </div>
                        @if($existingReview->comment)
                            <p class="mb-2">"{{ $existingReview->comment }}"</p>
                        @endif
                        @if(!$existingReview->is_approved)
                            <span class="badge bg-warning text-dark mb-2">Pendiente de aprobación</span>
                        @endif
                        <button type="button" class="btn btn-sm btn-outline-dark" id="edit-review-toggle">Editar reseña</button>

                        <form id="review-edit-form" class="mt-3 d-none">
                            @csrf
                            <input type="hidden" name="rating" id="edit-rating-value" value="{{ $existingReview->rating }}">
                            <div class="mb-3">
                                <label class="form-label">Calificación</label>
                                <div class="edit-star-rating d-flex gap-1" style="font-size:1.5rem; cursor:pointer;">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi {{ $i <= $existingReview->rating ? 'bi-star-fill' : 'bi-star' }} text-warning" data-star="{{ $i }}"></i>
                                    @endfor
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="edit-comment" class="form-label">Comentario (opcional)</label>
                                <textarea name="comment" id="edit-comment" class="form-control" rows="3" maxlength="1000">{{ $existingReview->comment }}</textarea>
                            </div>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-dark" id="review-update-btn">Actualizar reseña</button>
                                <button type="button" class="btn btn-outline-danger" id="review-delete-btn">Eliminar reseña</button>
                            </div>
                        </form>
                    </div>
                </div>
            @else
                <h4>Escribe tu reseña</h4>
                <form id="review-form" class="mt-3">
                    @csrf
                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                    <div class="mb-3">
                        <label class="form-label">Calificación</label>
                        <div class="star-rating d-flex gap-1" style="font-size:1.5rem; cursor:pointer;">
                            @for($i = 1; $i <= 5; $i++)
                                <i class="bi bi-star text-warning" data-star="{{ $i }}"></i>
                            @endfor
                        </div>
                        <input type="hidden" name="rating" id="rating-value" value="0">
                    </div>
                    <div class="mb-3">
                        <label for="comment" class="form-label">Comentario (opcional)</label>
                        <textarea name="comment" id="comment" class="form-control" rows="3" maxlength="1000"></textarea>
                    </div>
                    <button type="submit" class="btn btn-dark" id="review-submit-btn">Enviar reseña</button>
                </form>
            @endif
        @else
            <hr class="my-4">
            <p><a href="{{ route('customer.login') }}">Inicia sesión</a> para escribir una reseña.</p>
        @endauth
    </div>
</div>

@push('js')
<script>
    function initStarRating(containerSelector, inputId) {
        const container = document.querySelector(containerSelector);
        if (!container) return;
        const stars = container.querySelectorAll('i');
        const input = document.getElementById(inputId);
        if (!input) return;

        function updateStars(value) {
            stars.forEach(function(s) {
                if (s.dataset.star <= value) {
                    s.classList.remove('bi-star');
                    s.classList.add('bi-star-fill');
                } else {
                    s.classList.remove('bi-star-fill');
                    s.classList.add('bi-star');
                }
            });
        }

        stars.forEach(function(star) {
            star.addEventListener('click', function() {
                input.value = this.dataset.star;
                updateStars(this.dataset.star);
            });
            star.addEventListener('mouseenter', function() {
                updateStars(this.dataset.star);
            });
            star.addEventListener('mouseleave', function() {
                updateStars(input.value);
            });
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        initStarRating('.star-rating', 'rating-value');
        initStarRating('.edit-star-rating', 'edit-rating-value');

        const toggleBtn = document.getElementById('edit-review-toggle');
        const editForm = document.getElementById('review-edit-form');
        if (toggleBtn && editForm) {
            toggleBtn.addEventListener('click', function() {
                editForm.classList.toggle('d-none');
                toggleBtn.textContent = editForm.classList.contains('d-none') ? 'Editar reseña' : 'Cancelar';
            });
        }

        const form = document.getElementById('review-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = document.getElementById('review-submit-btn');
                btn.disabled = true;
                btn.textContent = 'Enviando...';

                const formData = new FormData(form);

                fetch('{{ route("review.store") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                .then(function(res) {
                    if (!res.ok) {
                        return res.json().then(function(err) { throw err; });
                    }
                    return res.json();
                })
                .then(function(data) {
                    showToast('success', data.message || 'Reseña enviada correctamente');
                    form.innerHTML = '<p class="text-muted">Gracias por tu reseña.</p>';
                })
                .catch(function(err) {
                    showToast('error', err.message || 'Error al enviar la reseña');
                    btn.disabled = false;
                    btn.textContent = 'Enviar reseña';
                });
            });
        }

        const editFormEl = document.getElementById('review-edit-form');
        if (editFormEl) {
            editFormEl.addEventListener('submit', function(e) {
                e.preventDefault();
                const btn = document.getElementById('review-update-btn');
                btn.disabled = true;
                btn.textContent = 'Actualizando...';

                const formData = new FormData(editFormEl);

                fetch('{{ route("review.update", $existingReview?->id ?? 0) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                    body: formData,
                })
                .then(function(res) {
                    if (!res.ok) {
                        return res.json().then(function(err) { throw err; });
                    }
                    return res.json();
                })
                .then(function(data) {
                    showToast('success', data.message || 'Reseña actualizada');
                    editFormEl.classList.add('d-none');
                    if (toggleBtn) toggleBtn.textContent = 'Editar reseña';
                    location.reload();
                })
                .catch(function(err) {
                    showToast('error', err.message || 'Error al actualizar');
                    btn.disabled = false;
                    btn.textContent = 'Actualizar reseña';
                });
            });
        }

        const deleteBtn = document.getElementById('review-delete-btn');
        if (deleteBtn) {
            deleteBtn.addEventListener('click', function() {
                if (!confirm('¿Eliminar tu reseña permanentemente?')) return;
                deleteBtn.disabled = true;
                deleteBtn.textContent = 'Eliminando...';

                fetch('{{ route("review.destroy", $existingReview?->id ?? 0) }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                    },
                })
                .then(function(res) {
                    if (!res.ok) {
                        return res.json().then(function(err) { throw err; });
                    }
                    return res.json();
                })
                .then(function(data) {
                    showToast('success', data.message || 'Reseña eliminada');
                    location.reload();
                })
                .catch(function(err) {
                    showToast('error', err.message || 'Error al eliminar');
                    deleteBtn.disabled = false;
                    deleteBtn.textContent = 'Eliminar reseña';
                });
            });
        }
    });

    function showToast(type, message) {
        if (typeof Toastify !== 'undefined') {
            Toastify({
                text: message,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: type === 'success' ? "#4fbe87" : "#f3616d",
            }).showToast();
        } else {
            alert(message);
        }
    }
</script>
@endpush
