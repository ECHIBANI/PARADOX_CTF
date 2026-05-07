<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title','Admin') — PARDOX</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root{--cre-blue:#000000;--cre-blue-dark:#1a1a1a;--cre-orange:#666666;--cre-dark:#000000;--cre-text:#1e293b;--cre-muted:#64748b;--cre-border:#e2e8f0;--cre-bg:#f8fafc;--radius-sm:8px;--radius-md:14px;--radius-lg:20px;--shadow-sm:0 2px 8px rgba(0,0,0,.03);--shadow-md:0 8px 30px rgba(0,0,0,.08);}
body{font-family:'DM Sans',sans-serif;color:var(--cre-text);background:var(--cre-bg);}
.logo-box{width:36px;height:36px;background:#000;border-radius:8px;display:inline-flex;align-items:center;justify-content:center;color:#fff;font-weight:900;font-size:1rem;}
.admin-layout{display:flex;min-height:100vh;}
.admin-sidebar{width:240px;min-height:100vh;background:#fff;border-right:1px solid var(--cre-border);display:flex;flex-direction:column;flex-shrink:0;position:sticky;top:0;height:100vh;overflow-y:auto;}
.sidebar-brand{display:flex;align-items:center;gap:.6rem;padding:1.25rem;border-bottom:1px solid var(--cre-border);}
.sidebar-brand-name{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.1rem;color:var(--cre-dark);}
.sidebar-brand-sub{font-size:.62rem;color:var(--cre-muted);font-weight:700;text-transform:uppercase;letter-spacing:.1em;}
.sidebar-nav{padding:1rem 0;flex:1;}
.sidebar-nav ul{list-style:none;padding:0;margin:0;}
.sidebar-nav li{padding:.1rem .75rem;}
.sidebar-link{display:flex;align-items:center;gap:.75rem;padding:.65rem .9rem;border-radius:50px;color:var(--cre-muted);font-size:.875rem;font-weight:500;transition:all .2s;text-decoration:none;border:none;background:transparent;width:100%;text-align:left;}
.sidebar-link:hover{background:var(--cre-border);color:var(--cre-dark);}
.sidebar-link.active{background:#000;color:#fff;font-weight:600;}
.sidebar-link.active i{color:#fff;}
.admin-main{flex:1;min-width:0;display:flex;flex-direction:column;}
.admin-topbar{height:64px;background:#fff;border-bottom:1px solid var(--cre-border);display:flex;align-items:center;justify-content:space-between;padding:0 1.5rem;box-shadow:var(--shadow-sm);position:sticky;top:0;z-index:50;}
.topbar-title{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.4rem;color:var(--cre-dark);}
.admin-content{padding:1.75rem;flex:1;}
.stat-card{background:#fff;border-radius:var(--radius-md);border:1px solid var(--cre-border);box-shadow:var(--shadow-sm);padding:1.4rem;transition:transform .2s,box-shadow .2s;}
.stat-card:hover{transform:translateY(-3px);box-shadow:var(--shadow-md);}
.stat-icon{width:52px;height:52px;border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;font-size:1.4rem;flex-shrink:0;background:#f1f5f9;color:#000;}
.stat-label{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--cre-muted);}
.stat-value{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.8rem;color:var(--cre-dark);line-height:1;}
.table-card{background:#fff;border-radius:var(--radius-md);border:1px solid var(--cre-border);box-shadow:var(--shadow-sm);overflow:hidden;}
.table-card-header{display:flex;align-items:center;justify-content:space-between;padding:1.1rem 1.5rem;border-bottom:1px solid var(--cre-border);flex-wrap:wrap;gap:.75rem;}
.table-card-title{font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.1rem;color:var(--cre-dark);}
.table-pardo thead th{font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:var(--cre-muted);background:var(--cre-bg);border-bottom:1px solid var(--cre-border);padding:.75rem 1rem;white-space:nowrap;}
.table-pardo tbody td{padding:.85rem 1rem;vertical-align:middle;font-size:.875rem;}
.table-pardo tbody tr{border-bottom:1px solid var(--cre-border);transition:background .15s;}
.table-pardo tbody tr:last-child{border-bottom:none;}
.table-pardo tbody tr:hover{background:#f8fafc;}
.res-id{font-family:'Barlow Condensed',sans-serif;font-weight:700;color:var(--cre-dark);background:#f1f5f9;padding:.2rem .55rem;border-radius:var(--radius-sm);font-size:.85rem;}
.cl-avatar{width:30px;height:30px;background:#000;color:#fff;border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-weight:700;font-size:.75rem;flex-shrink:0;}
.status-pill{display:inline-flex;align-items:center;gap:.35rem;font-size:.75rem;font-weight:600;padding:.3rem .75rem;border-radius:50px;}
.badge-pending{background:#f1f5f9;color:#000;border:1px solid #cbd5e1;}
.badge-confirmed{background:#000;color:#fff;border:1px solid #000;}
.badge-rejected{background:#fff;color:#ef4444;border:1px solid #fecaca;}
.badge-completed{background:#1e293b;color:#f8fafc;border:1px solid #1e293b;}
.btn-pardo-primary{background:var(--cre-dark);color:#fff;border:none;border-radius:50px;font-weight:600;padding:.5rem 1.2rem;transition:all .2s;text-decoration:none;display:inline-flex;align-items:center;}
.btn-pardo-primary:hover{background:#1e293b;color:#fff;transform:translateY(-1px);}
.btn-pardo-orange{background:var(--cre-dark);color:#fff;border:none;border-radius:50px;font-weight:600;padding:.5rem 1.2rem;transition:all .2s;text-decoration:none;display:inline-flex;align-items:center;}
.btn-pardo-orange:hover{background:#334155;color:#fff;}
.btn-pardo-outline{border:1.5px solid var(--cre-dark);background:transparent;color:var(--cre-dark);border-radius:50px;font-weight:600;padding:.5rem 1.2rem;transition:all .2s;text-decoration:none;display:inline-flex;align-items:center;}
.btn-pardo-outline:hover{background:var(--cre-dark);color:#fff;}
.vehicle-card-admin{background:#fff;border-radius:var(--radius-lg);border:1px solid var(--cre-border);overflow:hidden;box-shadow:var(--shadow-sm);}
.vehicle-img-wrap{height:180px;overflow:hidden;position:relative;background:#f1f5f9;}
.vehicle-img{width:100%;height:100%;object-fit:cover;transition:transform .4s;}
.vehicle-card-admin:hover .vehicle-img{transform:scale(1.04);}
.cat-badge{position:absolute;bottom:.75rem;left:.75rem;background:#000;color:#fff;font-size:.68rem;font-weight:700;padding:.2rem .6rem;border-radius:50px;text-transform:uppercase;letter-spacing:.06em;}
.section-title-admin{font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.6rem;color:var(--cre-dark);}
</style>
@yield('extra-css')
</head>
<body>
<div class="admin-layout">

  {{-- SIDEBAR --}}
  <aside class="admin-sidebar">
    <div class="sidebar-brand">
      <img src="{{ asset('images/logo.png') }}" style="height: 38px; object-fit: contain;" alt="PARDOX">
    </div>
    <nav class="sidebar-nav">
      <ul>
        <li>
          <a href="{{ route('admin.dashboard') }}" class="sidebar-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Tableau de bord
          </a>
        </li>
        <li>
          <a href="{{ route('admin.reservations') }}" class="sidebar-link {{ request()->routeIs('admin.reservations*') ? 'active' : '' }}">
            <i class="bi bi-calendar3"></i> Réservations
            @php $pending = \App\Models\Reservation::where('status','pending')->count(); @endphp
            @if($pending > 0)
            <span class="badge ms-auto" style="background:var(--cre-orange);border-radius:50px;">{{ $pending }}</span>
            @endif
          </a>
        </li>
        <li>
          <a href="{{ route('admin.vehicles') }}" class="sidebar-link {{ request()->routeIs('admin.vehicles*') ? 'active' : '' }}">
            <i class="bi bi-car-front"></i> Véhicules
          </a>
        </li>
        <li>
          <a href="{{ route('admin.users') }}" class="sidebar-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Utilisateurs
          </a>
        </li>

        <li>
          <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="sidebar-link" style="color:#ef4444;width:100%;border:none;background:none;cursor:pointer;">
              <i class="bi bi-box-arrow-right"></i> Déconnexion
            </button>
          </form>
        </li>
      </ul>
    </nav>
  </aside>

  {{-- MAIN --}}
  <main class="admin-main">
    <div class="admin-topbar">
      <span class="topbar-title">@yield('page-title','Dashboard')</span>
      <div class="d-flex align-items-center gap-3">
        @if(session('success'))
        <span class="badge text-bg-success py-2 px-3">✓ {{ session('success') }}</span>
        @endif
        <div class="d-flex align-items-center gap-2">
          <div class="cl-avatar">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
          <div>
            <div style="font-size:.875rem;font-weight:700;color:var(--cre-dark);">{{ auth()->user()->name }}</div>
            <div style="font-size:.65rem;color:var(--cre-muted);text-transform:uppercase;letter-spacing:.08em;">Administrateur</div>
          </div>
        </div>
      </div>
    </div>

    <div class="admin-content">
      @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show mb-4" style="border-radius:var(--radius-sm);">
        <i class="bi bi-exclamation-triangle-fill me-2"></i>
        @foreach($errors->all() as $err)<div>{{ $err }}</div>@endforeach
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
      @endif

      @yield('content')
    </div>
  </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
@yield('scripts')
</body>
</html>
