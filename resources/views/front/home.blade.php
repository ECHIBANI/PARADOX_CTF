@extends('layouts.app')
@section('title','PARDOX — Location de Véhicules au Maroc')

@section('body')
@include('layouts.navbar')

{{-- FLASH MESSAGES --}}
@if(session('success'))
<div class="alert alert-success alert-dismissible fade show m-0 rounded-0 text-center" role="alert">
  <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
  <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
@endif

{{-- HERO --}}
<section class="hero-section">
  <canvas id="hero-canvas" style="position:absolute; inset:0; z-index:1; width:100%; height:100%;"></canvas>
  <div class="container py-5">
    <div class="row align-items-center g-5">
      <div class="col-12 text-center hero-content">
        <img src="{{ asset('images/logo.png') }}" alt="PARDOX Logo" style="display:block; margin: 0 auto 2rem; width:180px; height:auto;">
        
        <h1 class="hero-title">
          <span id="typewriter-text" style="display:inline-block; min-height: 2.4em;"></span><span class="cursor-blink">|</span>
        </h1>
        
        <div class="d-flex gap-3 justify-content-center flex-wrap mt-4" style="position:relative; z-index:10;">
          @guest
          <a href="{{ route('login') }}" class="btn btn-pardo-outline">
            Espace Client
          </a>
          <a href="{{ route('register') }}" class="btn btn-pardo-primary">
            S'inscrire
          </a>
          @endguest
          @auth
          <a href="{{ route('vehicles.index') }}" class="btn btn-pardo-primary">
            Explorer les véhicules <i class="bi bi-arrow-right ms-2"></i>
          </a>
          @endauth
        </div>
      </div>


    </div>
  </div>
</section>

{{-- ADVANTAGES STRIP --}}
<div class="container py-4">
  <div class="adv-strip p-3">
    <div class="row g-2 justify-content-center text-center">
      @foreach([['bi-check-circle-fill','Annulation Gratuite (48h)'],['bi-headset','Assistance 24/7'],['bi-shield-check','Pas de frais cachés'],['bi-speedometer2','Kilométrage Illimité']] as [$icon,$label])
      <div class="col-6 col-md-3">
        <div class="adv-item d-flex align-items-center justify-content-center gap-2">
          <i class="bi {{ $icon }} adv-icon fs-5"></i>
          <span>{{ $label }}</span>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</div>

{{-- FEATURED VEHICLES --}}
<section class="py-5">
  <div class="container">
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
      <div>
        <span class="section-eyebrow d-block mb-1">Notre sélection</span>
        <h2 class="section-title mb-0">NOS VÉHICULES LES PLUS POPULAIRES</h2>
      </div>
      <a href="{{ route('vehicles.index') }}" class="btn-pardo-outline btn">Voir tout <i class="bi bi-arrow-right ms-1"></i></a>
    </div>
    <div class="row g-4">
      @foreach($featured as $vehicle)
      <div class="col-md-6 col-lg-4">
        @include('front.partials.vehicle-card', ['vehicle' => $vehicle])
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- HOW IT WORKS --}}
<section class="py-5 bg-pardo-light">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-eyebrow d-block mb-1">Simple & Rapide</span>
      <h2 class="section-title">COMMENT ÇA MARCHE ?</h2>
    </div>
    <div class="row g-4 text-center">
      @foreach([
        ['bi-person-plus','Créer un compte','Inscrivez-vous gratuitement avec votre numéro +212. Pas d\'email requis.','1'],
        ['bi-car-front','Choisir un véhicule','Parcourez notre flotte et sélectionnez le véhicule qui correspond à vos besoins.','2'],
        ['bi-calendar-check','Réserver en ligne','Choisissez vos dates, vérifiez la disponibilité et confirmez votre réservation.','3'],
        ['bi-file-earmark-text','Recevoir votre bon','Téléchargez votre bon de réservation officiel et prenez la route !','4'],
      ] as [$icon,$title,$desc,$num])
      <div class="col-md-6 col-lg-3">
        <div class="p-4 h-100 rounded-3 bg-white shadow-sm" style="transition:transform .3s;" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='translateY(0)'">
          <div class="mb-3 mx-auto d-flex align-items-center justify-content-center rounded-circle" style="width:70px;height:70px;background:rgba(26,86,255,.08);">
            <i class="bi {{ $icon }}" style="font-size:1.8rem;color:var(--cre-blue);"></i>
          </div>
          <div style="font-size:.7rem;font-weight:800;letter-spacing:.18em;color:var(--cre-muted);margin-bottom:.3rem;">ÉTAPE {{ $num }}</div>
          <h5 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.2rem;color:var(--cre-dark);">{{ $title }}</h5>
          <p class="text-muted small mb-0">{{ $desc }}</p>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- REVIEWS --}}
<section class="py-5">
  <div class="container">
    <div class="text-center mb-5">
      <span class="section-eyebrow d-block mb-1">Ils nous font confiance</span>
      <h2 class="section-title">AVIS DE NOS CLIENTS</h2>
    </div>
    <div class="row g-4">
      @foreach($comments as $comment)
      <div class="col-md-4">
        <div class="review-card bg-white p-4 h-100 d-flex flex-column">
          <div class="mb-3" style="color:#f59e0b;font-size:1rem;">
            @for($i=1;$i<=5;$i++){{ $i<=$comment->rating ? '★' : '☆' }}@endfor
          </div>
          <p class="fst-italic mb-3 flex-grow-1" style="font-size:.9rem;line-height:1.7;color:var(--cre-text);">"{{ $comment->body }}"</p>
          <div class="d-flex align-items-center gap-2 mt-auto">
            <div class="rev-avatar">{{ strtoupper(substr($comment->user->name, 0, 1)) }}</div>
            <div>
              <div style="font-weight:600;font-size:.85rem;color:var(--cre-dark);">{{ $comment->user->name }}</div>
              <div style="font-size:.75rem;color:var(--cre-muted);">{{ \Carbon\Carbon::parse($comment->created_at)->translatedFormat('F Y') }}</div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- CTA --}}
<section class="py-5 bg-pardo-light">
  <div class="container">
    <div class="rounded-4 p-5 text-center text-white position-relative overflow-hidden" style="background:linear-gradient(135deg,var(--cre-dark),#1e2d5a);">
      <div class="position-absolute" style="top:-40%;right:-5%;width:400px;height:400px;background:radial-gradient(circle,rgba(26,86,255,.2) 0%,transparent 70%);border-radius:50%;pointer-events:none;"></div>
      <h2 style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:clamp(1.8rem,4vw,3rem);" class="mb-3">Prêt à prendre la route ?</h2>
      <p style="color:rgba(255,255,255,.65);" class="mb-4">Réservez votre véhicule en moins de 2 minutes. Pas de carte de crédit requise.</p>
      <div class="d-flex gap-3 justify-content-center flex-wrap">
        <a href="{{ route('vehicles.index') }}" class="btn btn-pardo-orange btn-lg px-5">Réserver maintenant</a>
        @guest
        <a href="{{ route('register') }}" class="btn btn-lg px-5" style="border:1.5px solid rgba(255,255,255,.3);color:#fff;border-radius:var(--radius-sm);font-weight:600;">Créer un compte</a>
        @endguest
      </div>
    </div>
  </div>
</section>

@include('layouts.footer')

@section('scripts')
<style>
.cursor-blink { animation: blink 1s step-end infinite; font-weight: 300; }
@keyframes blink { 50% { opacity: 0; } }
</style>
<script>
  // 1. Console / Typewriter Effect
  const text = "PRENEZ LA ROUTE AVEC\nLA VOITURE IDÉALE";
  let i = 0;
  const speed = 50;
  const typeTarget = document.getElementById('typewriter-text');
  
  function typeWriter() {
    if (i < text.length) {
      typeTarget.innerHTML += text.charAt(i) === '\n' ? '<br>' : text.charAt(i);
      i++;
      setTimeout(typeWriter, speed);
    }
  }
  setTimeout(typeWriter, 500);

  // 2. Canvas Particle Swarm (Antigravity Style)
  const canvas = document.getElementById('hero-canvas');
  if(canvas) {
    const ctx = canvas.getContext('2d');
    let width, height;
    let particles = [];
    const colors = ['#000000', '#f97316', '#1a56ff', '#cbd5e1']; // Black, Orange, Blue, Gray
    
    function init() {
      width = canvas.width = window.innerWidth;
      height = canvas.height = document.querySelector('.hero-section').offsetHeight;
      particles = [];
      const particleCount = Math.floor(window.innerWidth / 6); 
      for(let i=0; i<particleCount; i++) {
        particles.push(new Particle());
      }
    }
    
    let mouse = { x: null, y: null, radius: 250 };
    document.addEventListener('mousemove', function(e) {
      mouse.x = e.clientX;
      mouse.y = e.clientY + window.scrollY;
    });
    
    class Particle {
      constructor() {
        this.x = Math.random() * width;
        this.y = Math.random() * height;
        this.size = Math.random() * 2 + 0.8;
        this.baseX = this.x;
        this.baseY = this.y;
        this.density = (Math.random() * 20) + 5;
        this.color = colors[Math.floor(Math.random() * colors.length)];
      }
      draw() {
        ctx.fillStyle = this.color;
        ctx.beginPath();
        ctx.arc(this.x, this.y, this.size, 0, Math.PI * 2);
        ctx.closePath();
        ctx.fill();
      }
      update() {
        let dx = mouse.x - this.x;
        let dy = mouse.y - this.y;
        let distance = Math.sqrt(dx * dx + dy * dy);
        
        if (distance < mouse.radius && mouse.x !== null) {
          // Attract particles to mouse (Swarm effect) - Slower
          let force = (mouse.radius - distance) / mouse.radius;
          let directionX = (dx / distance) * force * this.density;
          let directionY = (dy / distance) * force * this.density;
          
          this.x += directionX * 0.08;
          this.y += directionY * 0.08;
        } else {
          // Gently float back to origins - Slower
          if (this.x !== this.baseX) {
             let dxBase = this.x - this.baseX;
             this.x -= dxBase / 60;
          }
          if (this.y !== this.baseY) {
             let dyBase = this.y - this.baseY;
             this.y -= dyBase / 60;
          }
        }
      }
    }
    
    function animate() {
      ctx.clearRect(0, 0, width, height);
      for(let i=0; i<particles.length; i++) {
        particles[i].update();
        particles[i].draw();
      }
      requestAnimationFrame(animate);
    }
    
    window.addEventListener('resize', init);
    init();
    animate();
  }
</script>
@endsection

@endsection
