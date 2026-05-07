<nav class="navbar navbar-pardo navbar-expand-lg sticky-top">
  <div class="container align-items-center">
    <a class="navbar-brand" href="{{ route('home') }}">
      <img src="{{ asset('images/logo.png') }}" alt="PARDOX" height="35" style="object-fit: contain;">
    </a>
    <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
      <i class="bi bi-list fs-2 text-dark"></i>
    </button>
    <div class="collapse navbar-collapse" id="navMain">
      <ul class="navbar-nav mx-auto gap-2">
        <li class="nav-item">
          <a class="nav-link nav-link-pardo {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">Accueil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link nav-link-pardo {{ request()->routeIs('vehicles.*') ? 'active' : '' }}" href="{{ route('vehicles.index') }}">Véhicules</a>
        </li>
        @auth
          @if(auth()->user()->role === 'user')
          <li class="nav-item">
            <a class="nav-link nav-link-pardo {{ request()->routeIs('client.*') ? 'active' : '' }}" href="{{ route('client.reservations') }}">Mes réservations</a>
          </li>
          @endif
        @endauth
      </ul>
      <div class="d-flex gap-3 align-items-center mt-3 mt-lg-0">
        @guest
          <a href="{{ route('login') }}" class="btn-pardo-outline">Espace Client</a>
          <a href="{{ route('register') }}" class="btn-pardo-primary">
            S'inscrire
          </a>
        @endguest
        @auth
          @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="btn-pardo-outline"><i class="bi bi-speedometer2"></i> Admin</a>
          @endif
          <div class="dropdown">
            <button class="btn-pardo-primary dropdown-toggle" data-bs-toggle="dropdown" style="border-radius:50px;">
              <i class="bi bi-person-fill"></i> {{ auth()->user()->name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border" style="border-radius:12px;">
              <li><a class="dropdown-item fw-medium" href="{{ route('client.profile.edit') }}"><i class="bi bi-person-circle me-2"></i>Mon compte</a></li>
              <li><a class="dropdown-item fw-medium" href="{{ route('client.reservations') }}"><i class="bi bi-calendar3 me-2"></i>Mes réservations</a></li>
              <li><a class="dropdown-item fw-medium" href="{{ route('client.favorites') }}"><i class="bi bi-heart me-2"></i>Mes favoris</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="{{ route('logout') }}" method="POST">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger fw-medium"><i class="bi bi-box-arrow-right me-2"></i>Déconnexion</button>
                </form>
              </li>
            </ul>
          </div>
        @endauth
      </div>
    </div>
  </div>
</nav>
