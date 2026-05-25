<ul class="nav-list">
  <li class="nav-item">
    <a href="/" class="nav-link">Novedades</a>
  </li>
  <li class="nav-item">
    <a href="{{ route('clientProducts') }}" class="nav-link">Productos</a>
  </li>
  <li class="nav-item">
    <a href="{{ route('clientCategory') }}" class="nav-link">Categor&iacute;as</a>
  </li>
  <li class="nav-item">
    <a href="{{ route('clientAbout') }}" class="nav-link">Acerca de Nosotros</a>
  </li>
  <li class="nav-item">
    <a href="{{ route('clientCheckOrder') }}" class="nav-link">&Oacute;rdenes</a>
  </li>
  @auth('customer')
    <li class="nav-item">
      <a href="{{ route('clientMyOrders') }}" class="nav-link">Mis &Oacute;rdenes</a>
    </li>
  @endauth
  <li class="nav-item">
    <a href="{{ route('contact') }}" class="nav-link">Contacto</a>
  </li>
</ul>
