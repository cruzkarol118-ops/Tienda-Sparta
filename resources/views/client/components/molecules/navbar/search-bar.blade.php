<form action="{{ route('clientProductSearch') }}" class="search" method="GET">
  <div class="search__inner">
    <input class="search__input" type="search" placeholder="Buscar productos..." id="searchInput" name="product">
    <label for="searchInput" class="search__label" aria-label="Buscar">
      <svg width="24" height="24" viewBox="0 0 29 29" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M28 28L21.8613 21.8503L28 28ZM25.2632 13.6316C25.2632 16.7165 24.0377 19.675 21.8563 21.8563C19.675 24.0377 16.7165 25.2632 13.6316 25.2632C10.5467 25.2632 7.58816 24.0377 5.40681 21.8563C3.22547 19.675 2 16.7165 2 13.6316C2 10.5467 3.22547 7.58816 5.40681 5.40681C7.58816 3.22547 10.5467 2 13.6316 2C16.7165 2 19.675 3.22547 21.8563 5.40681C24.0377 7.58816 25.2632 10.5467 25.2632 13.6316V13.6316Z" stroke="black" stroke-opacity="0.8" stroke-width="2.5" stroke-linecap="round"/>
      </svg>
    </label>
    <button class="search__submit" type="submit" aria-label="Buscar">
      <i class="bi bi-arrow-right"></i>
    </button>
  </div>
</form>