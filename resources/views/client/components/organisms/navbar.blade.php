@prepend('css')
<link rel="stylesheet" href="{{ asset('client/components/organisms/navbar/style.css') }}">
@endprepend
<header class="header" id="header">
  <nav class="nav container">
    <div class="nav-button">
      <button class="nav-toggle" id="nav-toggle" type="button" aria-label="Abrir menu" aria-controls="nav-menu" aria-expanded="false">
        <i class="bi bi-list"></i>
      </button>
    </div>
    <a href="/" class="nav-logo" id="logo" aria-label="Inicio">
      <img src="{{ asset('shop/'.$path) }}" alt="">
    </a>
    <div class="nav-menu" id="nav-menu">
      <div class="nav-scroll-wrapper">
        <button class="nav-scroll-arrow nav-scroll-left" type="button" aria-label="Desplazar a la izquierda"><i class="bi bi-chevron-left"></i></button>
        <x-molecules.navbar.menu />
        <button class="nav-scroll-arrow nav-scroll-right" type="button" aria-label="Desplazar a la derecha"><i class="bi bi-chevron-right"></i></button>
      </div>
      <div class="nav-actions">
        <x-molecules.navbar.search-bar/>
        <a href="{{ route('clientCarts') }}" class="nav-cart-link" title="Carrito" aria-label="Carrito">
          <i class="bi bi-cart3"></i>
          <span class="nav-cart-badge" id="cartCount">{{ count((array) session('cart')) }}</span>
        </a>
        @auth('customer')
          <div class="nav-dropdown">
            <button class="nav-dropdown-toggle" id="accountDropdown" type="button" data-bs-toggle="dropdown" aria-expanded="false">
              <span class="nav-dropdown-name">{{ Auth::guard('customer')->user()->name }}</span>
              <i class="bi bi-chevron-down"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
              <li><a class="dropdown-item" href="{{ route('customer.profile') }}">Mi Perfil</a></li>
              <li><a class="dropdown-item" href="{{ route('customer.my-returns') }}">Mis Solicitudes</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <a class="dropdown-item" href="{{ route('customer.logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Cerrar Sesi&oacute;n</a>
                <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" style="display:none;">
                  @csrf
                </form>
              </li>
            </ul>
          </div>
        @else
          <a href="{{ route('customer.login') }}" class="nav-action-link">Iniciar Sesi&oacute;n</a>
        @endauth
      </div>
      <button class="nav-close" id="nav-close" type="button" aria-label="Cerrar menu">
        <i class="bi bi-x"></i>
      </button>
    </div>
  </nav>
</header>
@prepend('js')
  <script>
    const navMenu = document.getElementById("nav-menu"),
    navToggle = document.getElementById("nav-toggle"),
    navClose = document.getElementById("nav-close");

    if (navToggle) {
        navToggle.addEventListener("click", () => {
            navMenu.classList.add("show-menu");
            navToggle.setAttribute("aria-expanded", "true");
        });
    }

    if (navClose) {
        navClose.addEventListener("click", () => {
            navMenu.classList.remove("show-menu");
            navToggle.setAttribute("aria-expanded", "false");
        });
    }

    const accountDropdown = document.getElementById("accountDropdown");
    if (accountDropdown) {
        accountDropdown.addEventListener("click", (event) => {
            const menu = accountDropdown.nextElementSibling;
            if (!menu) {
                return;
            }

            event.stopPropagation();
            menu.classList.toggle("show");
            accountDropdown.setAttribute("aria-expanded", menu.classList.contains("show") ? "true" : "false");
        });

        document.addEventListener("click", (event) => {
            const menu = accountDropdown.nextElementSibling;
            if (!menu || accountDropdown.contains(event.target) || menu.contains(event.target)) {
                return;
            }

            menu.classList.remove("show");
            accountDropdown.setAttribute("aria-expanded", "false");
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const pageLoadTime = performance.now();

        window.addEventListener('beforeunload', function() {
            const totalTime = Math.round((performance.now() - pageLoadTime) / 1000);

            const analyticsData = new Blob([JSON.stringify({
                time_spent: totalTime,
                page_url: window.location.pathname,
                _token: document.querySelector('meta[name="csrf-token"]').content
            })], {type: 'application/json'});

            navigator.sendBeacon('/track-time', analyticsData);
        });

        const navList = document.querySelector('.nav-list');
        if (!navList) return;

        const scrollLeftBtn = document.querySelector('.nav-scroll-left');
        const scrollRightBtn = document.querySelector('.nav-scroll-right');

        if (scrollLeftBtn) {
            scrollLeftBtn.addEventListener('click', function() {
                navList.scrollBy({ left: -220, behavior: 'smooth' });
            });
        }

        if (scrollRightBtn) {
            scrollRightBtn.addEventListener('click', function() {
                navList.scrollBy({ left: 220, behavior: 'smooth' });
            });
        }

        let isDragging = false;
        let startX = 0;
        let scrollStart = 0;

        navList.addEventListener('mousedown', function(e) {
            isDragging = true;
            startX = e.pageX;
            scrollStart = navList.scrollLeft;
            navList.style.cursor = 'grabbing';
        });

        document.addEventListener('mousemove', function(e) {
            if (!isDragging) return;
            e.preventDefault();
            const x = e.pageX;
            const walk = (x - startX) * 1.5;
            navList.scrollLeft = scrollStart - walk;
        });

        document.addEventListener('mouseup', function() {
            if (!isDragging) return;
            isDragging = false;
            navList.style.cursor = 'grab';
        });

        navList.addEventListener('touchstart', function(e) {
            isDragging = true;
            startX = e.touches[0].pageX;
            scrollStart = navList.scrollLeft;
        }, { passive: true });

        navList.addEventListener('touchmove', function(e) {
            if (!isDragging) return;
            const x = e.touches[0].pageX;
            const walk = (x - startX) * 1.5;
            navList.scrollLeft = scrollStart - walk;
        }, { passive: true });

        navList.addEventListener('touchend', function() {
            isDragging = false;
        }, { passive: true });
    });
  </script>
@endprepend
