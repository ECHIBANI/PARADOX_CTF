<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'PARDOX — Location de Véhicules Maroc')</title>
<link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<!-- Bootstrap Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
<!-- Google Fonts -->
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@400;600;700;800&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>
:root {
    --cre-blue:#000000; --cre-blue-dark:#111111; --cre-orange:#000000;
    --cre-dark:#050505; --cre-text:#111111; --cre-muted:#666666;
    --cre-border:#e5e5e5; --cre-bg:#ffffff;
    --radius-sm:8px; --radius-md:14px; --radius-lg:20px;
    --shadow-sm:0 2px 8px rgba(0,0,0,.04); --shadow-md:0 8px 30px rgba(0,0,0,.08); --shadow-lg:0 20px 60px rgba(0,0,0,.1);
}
body { font-family:'DM Sans',sans-serif; color:var(--cre-text); background:var(--cre-bg); }

/* ─── NAVBAR ─── */
.navbar-pardo { background:#ffffff; border-bottom:none; padding:1.2rem 0; z-index: 1050 !important; }
.navbar-brand-custom { font-family:'DM Sans',sans-serif; font-weight:800; font-size:1.2rem; color:#000!important; display:flex; align-items:center; gap:4px; }
.logo-icon-svg { width:32px; height:auto; }

.nav-link-pardo { font-weight:600; font-size:1rem; color:#000!important; padding:.5rem 1.5rem!important; border-radius:50px; transition:all .2s; }
.nav-link-pardo:hover, .nav-link-pardo.active { background:rgba(0,0,0,.04); }

.btn-pardo-primary { background:#000; color:#fff; border:1px solid #000; border-radius:50px; font-weight:600; padding:.6rem 1.5rem; transition:all .2s; display:inline-flex; align-items:center; gap:10px; }
.btn-pardo-primary:hover { background:#222; border-color:#222; color:#fff; }

.btn-pardo-orange { background:#000; color:#fff; border:1px solid #000; border-radius:50px; font-weight:600; padding:.7rem 1.8rem; transition:all .2s; }
.btn-pardo-orange:hover { background:#222; border-color:#222; color:#fff; transform:translateY(-1px); box-shadow:0 8px 25px rgba(0,0,0,.12); }

.btn-pardo-outline { border:1.5px solid #000; background:transparent; color:#000; border-radius:50px; font-weight:600; padding:.6rem 1.5rem; transition:all .2s; display:inline-flex; align-items:center; justify-content:center; gap:8px;}
.btn-pardo-outline:hover { background:rgba(0,0,0,.03); color:#000; }
.btn-pardo-active { border:1.5px solid #222; background:#222; color:#fff; border-radius:50px; font-weight:600; padding:.6rem 1.5rem; transition:all .2s; display:inline-flex; align-items:center; justify-content:center; gap:8px;}
.btn-pardo-active:hover { color:#fff; background:#000; border-color:#000; }

/* ─── HERO ─── */
.hero-section { min-height:85vh; background:#fff; display:flex; align-items:center; position:relative; overflow:hidden;}
.hero-bg-dots { position:absolute; inset:0; background-image: radial-gradient(#d1d5db 2px, transparent 2px); background-size: 24px 24px; opacity: 0.6; z-index: 1; mask-image: radial-gradient(ellipse at center, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 70%); -webkit-mask-image: radial-gradient(ellipse at center, rgba(0,0,0,1) 0%, rgba(0,0,0,0) 70%); transition: background-position 0.6s cubic-bezier(0.25, 1, 0.5, 1); }
.hero-content { position:relative; z-index:2; width:100%; text-align:center; padding-top:2rem; }
.hero-title { font-family:'DM Sans',sans-serif; font-weight:900; font-size:clamp(2.5rem,6vw,4.5rem); line-height:1.2; color:#000; text-transform:uppercase; letter-spacing:0.02em; margin-bottom:2rem; }
.hero-logo-large { display:block; margin:0 auto 2rem; width:140px; height:auto; }

/* ─── SECTIONS ─── */
.section-eyebrow { font-family:'Barlow Condensed',sans-serif; font-weight:700; font-size:.75rem; letter-spacing:.2em; text-transform:uppercase; color:var(--cre-orange); }
.section-title { font-family:'Barlow Condensed',sans-serif; font-weight:800; font-size:clamp(1.6rem,3vw,2.4rem); color:var(--cre-dark); }
.bg-pardo-light { background:var(--cre-bg); }

/* ─── ADVANTAGE STRIP ─── */
.adv-strip { background:linear-gradient(135deg,var(--cre-blue),#2563eb); border-radius:var(--radius-md); }
.adv-item { color:#fff; font-weight:600; font-size:.9rem; }
.adv-icon { color:#86efac; }

/* ─── VEHICLE CARDS ─── */
.vehicle-card { border-radius:var(--radius-lg); border:1px solid var(--cre-border); overflow:hidden; transition:transform .3s,box-shadow .3s; box-shadow:var(--shadow-sm); }
.vehicle-card:hover { transform:translateY(-8px); box-shadow:var(--shadow-lg); }
.vehicle-img-wrap { height:200px; overflow:hidden; background:linear-gradient(135deg,#f1f5ff,#e8f0ff); position:relative; }
.vehicle-img { width:100%; height:100%; object-fit:cover; transition:transform .5s; }
.vehicle-card:hover .vehicle-img { transform:scale(1.05); }
.cat-badge { position:absolute; bottom:.75rem; left:.75rem; background:rgba(26,86,255,.85); color:#fff; font-size:.68rem; font-weight:700; padding:.2rem .6rem; border-radius:50px; text-transform:uppercase; letter-spacing:.06em; }
.vehicle-name { font-family:'Barlow Condensed',sans-serif; font-weight:700; font-size:1.2rem; color:var(--cre-dark); }
.price-amount { font-family:'Barlow Condensed',sans-serif; font-weight:800; font-size:1.4rem; color:var(--cre-blue); }
.feat-tag { font-size:.7rem; font-weight:500; color:var(--cre-muted); background:var(--cre-bg); padding:.2rem .55rem; border-radius:50px; border:1px solid var(--cre-border); }

/* ─── REVIEWS ─── */
.review-card { border-radius:var(--radius-lg); border:1px solid var(--cre-border); transition:transform .3s,box-shadow .3s; }
.review-card:hover { transform:translateY(-5px); box-shadow:var(--shadow-md); }
.rev-avatar { width:38px; height:38px; background:linear-gradient(135deg,var(--cre-blue),#4f78ff); border-radius:50%; display:flex; align-items:center; justify-content:center; color:#fff; font-weight:700; font-size:.9rem; flex-shrink:0; }

/* ─── STATUS BADGES ─── */
.badge-pending   { background:#fffbeb; color:#d97706; border:1px solid #fde68a; }
.badge-confirmed { background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; }
.badge-rejected  { background:#fef2f2; color:#dc2626; border:1px solid #fecaca; }
.badge-completed { background:#f0f9ff; color:#0284c7; border:1px solid #bae6fd; }
.status-pill { display:inline-flex; align-items:center; gap:.35rem; font-size:.75rem; font-weight:600; padding:.3rem .75rem; border-radius:50px; }

/* ─── ADMIN SIDEBAR ─── */
.admin-sidebar { width:240px; min-height:100vh; background:#fff; border-right:1px solid var(--cre-border); }
.sidebar-link { display:flex; align-items:center; gap:.75rem; padding:.65rem .9rem; border-radius:var(--radius-sm); color:var(--cre-muted); font-size:.875rem; font-weight:500; transition:all .2s; text-decoration:none; }
.sidebar-link:hover, .sidebar-link.active { background:rgba(26,86,255,.08); color:var(--cre-blue); }
.sidebar-link.active { font-weight:600; }
.admin-topbar { height:64px; background:#fff; border-bottom:1px solid var(--cre-border); box-shadow:var(--shadow-sm); position:sticky; top:0; z-index:50; }
.topbar-title { font-family:'Barlow Condensed',sans-serif; font-weight:800; font-size:1.4rem; color:var(--cre-dark); }
.stat-card { background:#fff; border-radius:var(--radius-md); border:1px solid var(--cre-border); box-shadow:var(--shadow-sm); padding:1.4rem; }
.stat-icon { width:52px; height:52px; border-radius:var(--radius-sm); display:flex; align-items:center; justify-content:center; font-size:1.4rem; flex-shrink:0; }
.stat-label { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--cre-muted); }
.stat-value { font-family:'Barlow Condensed',sans-serif; font-weight:800; font-size:1.8rem; color:var(--cre-dark); line-height:1; }
.table-card { background:#fff; border-radius:var(--radius-md); border:1px solid var(--cre-border); box-shadow:var(--shadow-sm); overflow:hidden; }
.table-pardo thead th { font-size:.72rem; font-weight:700; text-transform:uppercase; letter-spacing:.07em; color:var(--cre-muted); background:var(--cre-bg); border-bottom:1px solid var(--cre-border); padding:.75rem 1rem; white-space:nowrap; }
.table-pardo tbody td { padding:.9rem 1rem; vertical-align:middle; font-size:.875rem; }
.table-pardo tbody tr { border-bottom:1px solid var(--cre-border); transition:background .15s; }
.table-pardo tbody tr:hover { background:rgba(26,86,255,.02); }
.res-id { font-family:'Barlow Condensed',sans-serif; font-weight:700; color:var(--cre-blue); background:rgba(26,86,255,.07); padding:.2rem .55rem; border-radius:var(--radius-sm); }
.cl-avatar { width:30px; height:30px; background:linear-gradient(135deg,var(--cre-blue),#4f78ff); color:#fff; border-radius:50%; display:inline-flex; align-items:center; justify-content:center; font-weight:700; font-size:.75rem; }

/* ─── BOOKING ─── */
.booking-summary { background:var(--cre-dark); border-radius:var(--radius-lg); color:#fff; }
.summary-price-row { display:flex; justify-content:space-between; padding:.5rem 0; border-bottom:1px solid rgba(255,255,255,.1); font-size:.875rem; }
.summary-total { background:var(--cre-orange); border-radius:var(--radius-sm); padding:1rem; display:flex; justify-content:space-between; align-items:center; }

/* ─── AUTH ─── */
.auth-wrapper { min-height:100vh; background:linear-gradient(135deg,#f8faff,#e8f0ff); }
.auth-card { background:#fff; border-radius:var(--radius-lg); padding:2.5rem; box-shadow:var(--shadow-lg); width:100%; max-width:420px; }
.auth-title { font-family:'Barlow Condensed',sans-serif; font-weight:800; font-size:1.8rem; color:var(--cre-dark); }
.tab-custom { display:flex; background:var(--cre-bg); border-radius:var(--radius-sm); padding:.25rem; }
.tab-custom .tab-btn { flex:1; padding:.5rem; border-radius:6px; font-weight:600; font-size:.875rem; border:none; background:transparent; color:var(--cre-muted); transition:all .2s; cursor:pointer; }
.tab-custom .tab-btn.active { background:#fff; color:var(--cre-dark); box-shadow:var(--shadow-sm); }

/* ─── FOOTER ─── */
.footer-pardo { background:#0f172a; }
.footer-link { color:rgba(255,255,255,.55); font-size:.875rem; text-decoration:none; transition:color .2s; }
.footer-link:hover { color:var(--cre-orange); }
.footer-heading { font-family:'Barlow Condensed',sans-serif; font-weight:700; font-size:.8rem; letter-spacing:.15em; text-transform:uppercase; color:rgba(255,255,255,.4); }

/* ─── VOUCHER PRINT ─── */
@media print {
    .no-print { display:none!important; }
    .voucher-section { page-break-inside:avoid; }
}
.voucher-wrapper { max-width:700px; margin:0 auto; background:#fff; padding:2.5rem; font-family:'DM Sans',sans-serif; border:1px solid #ddd; }
.voucher-logo-name { font-family:'Barlow Condensed',sans-serif; font-weight:900; font-size:2rem; letter-spacing:.05em; }
.voucher-section-title { font-family:'Barlow Condensed',sans-serif; font-weight:700; font-size:.72rem; letter-spacing:.15em; text-transform:uppercase; border-bottom:1px solid #000; padding-bottom:.3rem; margin-bottom:.75rem; }
.voucher-row { display:flex; justify-content:space-between; padding:.3rem 0; border-bottom:1px solid #f5f5f5; font-size:.875rem; }
.voucher-total-bar { background:#000; color:#fff; padding:.65rem 1rem; border-radius:4px; display:flex; justify-content:space-between; font-weight:700; font-size:1rem; margin-top:.5rem; }
.sig-line { border-bottom:1px solid #000; height:50px; margin-bottom:.4rem; }
</style>

<link rel="stylesheet" href="{{ asset('css/ctf.css') }}">
@yield('extra-css')
</head>
<body>

@yield('body')

<!-- Global Toast Container -->
<div class="toast-container position-fixed bottom-0 end-0 p-3" style="z-index: 1080;">
  <div id="favToast" class="toast align-items-center text-bg-dark border-0" role="alert" aria-live="assertive" aria-atomic="true">
    <div class="d-flex">
      <div class="toast-body" id="favToastMessage">
        Véhicule ajouté aux favoris.
      </div>
      <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
  </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function toggleFavorite(btn, isAuth) {
    if (!isAuth) {
      window.location.href = "{{ route('login') }}";
      return;
    }
    
    const url = btn.getAttribute('data-url');
    const icon = btn.querySelector('.fav-icon');
    
    fetch(url, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
        'Content-Type': 'application/json'
      }
    })
    .then(response => response.json())
    .then(data => {
      // Update UI
      if (data.status === 'added') {
        icon.classList.remove('bi-heart');
        icon.classList.add('bi-heart-fill');
        icon.style.color = '#ef4444'; // Red
      } else {
        icon.classList.remove('bi-heart-fill');
        icon.classList.add('bi-heart');
        icon.style.color = '#94a3b8'; // Gray
      }
      
      // Show Toast
      document.getElementById('favToastMessage').textContent = data.message;
      const toastEl = document.getElementById('favToast');
      const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
      toast.show();
    })
    .catch(error => console.error('Error toggling favorite:', error));
  }
</script>

<script src="{{ asset('js/ctf.js') }}"></script>
@yield('scripts')
</body>
</html>
