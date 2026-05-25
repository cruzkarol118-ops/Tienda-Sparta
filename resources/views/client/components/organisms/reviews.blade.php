<div class="container py-5">
    <h1 class="text-center py-4">Lo que dicen nuestras <span class="text-gradient-gold">clientas</span></h1>

    <div class="row">
        <div class="col-12">
            <div class="splide" id="reviews-carousel">
                <div class="splide__track">
                    <ul class="splide__list">
                        @forelse($reviews as $review)
                            <li class="splide__slide px-2">
                                <a href="{{ route('clientProductDetail', $review->product->slug) }}" class="text-decoration-none text-reset">
                                    <div class="card border-0 shadow-hover h-100 overflow-hidden">
                                        @php $img = $review->product->productImage->first(); @endphp
                                        @if($img)
                                            <img src="{{ asset('shop/products/'.$img->path) }}"
                                                 class="card-img-top" alt="" style="height: 180px; object-fit: cover;">
                                        @else
                                            <div style="height: 180px; background: #f5f5f5;"></div>
                                        @endif
                                        <div class="card-body p-4">
                                            <div class="d-flex mb-3">
                                                <img src="https://ui-avatars.com/api/?name={{ urlencode($review->customer?->name ?? 'A') }}&background=D4AF37&color=fff"
                                                     class="rounded-circle me-3" width="60" alt="">
                                                <div>
                                                    <h5 class="mb-0">{{ $review->customer?->name ?? 'Anónima' }}</h5>
                                                    <div class="text-warning d-flex">
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
                                                <p class="mb-2">"{{ $review->comment }}"</p>
                                            @endif
                                            <small class="text-muted d-block mt-auto">Producto: <span class="fw-bold">{{ $review->product->title }}</span></small>
                                        </div>
                                    </div>
                                </a>
                            </li>
                        @empty
                            <li class="splide__slide px-2">
                                <div class="card border-0 shadow-hover h-100 d-flex align-items-center justify-content-center" style="min-height: 300px;">
                                    <p class="text-muted">Aún no hay reseñas. ¡ Sé la primera en escribir una !</p>
                                </div>
                            </li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .text-gradient-gold {
        background: linear-gradient(135deg, #FFD700 0%, #D4AF37 100%);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-weight: 700;
    }
    .shadow-hover {
        transition: transform 0.3s, box-shadow 0.3s;
    }
    .shadow-hover:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }
</style>

<link href="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/css/splide.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/@splidejs/splide@4.1.4/dist/js/splide.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var splide = new Splide('#reviews-carousel', {
            type    : 'slide',
            perPage : 3,
            perMove : 1,
            gap     : '1.5rem',
            autoplay: true,
            rewind  : true,
            breakpoints: {
                992: { perPage: 2 },
                768: { perPage: 1 }
            }
        });
        splide.mount();
    });
</script>
