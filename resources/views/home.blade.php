@extends('layouts.app')
@section('title','Location Voitures Premium Maroc')

@push('styles')
<style>
/* HERO */
.hero{
  min-height:100vh;display:flex;align-items:center;position:relative;
  overflow:hidden;padding-top:80px;
}
.hero-bg{
  position:absolute;inset:0;
  background:radial-gradient(ellipse 80% 60% at 70% 50%, rgba(201,169,110,.08) 0%, transparent 65%),
             radial-gradient(ellipse 50% 80% at 10% 80%, rgba(201,169,110,.05) 0%, transparent 60%);
}
.hero-grid{
  position:absolute;inset:0;
  background-image:linear-gradient(rgba(255,255,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.025) 1px,transparent 1px);
  background-size:60px 60px;
  mask-image:radial-gradient(ellipse 80% 80% at 50% 50%,black 0%,transparent 100%);
}
.hero-eyebrow{
  display:inline-flex;align-items:center;gap:.5rem;
  background:rgba(201,169,110,.1);border:1px solid rgba(201,169,110,.25);
  color:var(--gold);font-size:.7rem;font-weight:700;letter-spacing:.15em;
  text-transform:uppercase;padding:.45rem 1rem;border-radius:50px;margin-bottom:1.5rem;
}
.hero-title{
  font-family:'Syne',sans-serif;font-weight:800;
  font-size:clamp(3rem,8vw,7rem);line-height:.95;
  letter-spacing:-.03em;color:var(--white);
}
.hero-title .accent{
  display:block;
  background:linear-gradient(135deg,var(--gold),var(--gold2),var(--gold));
  -webkit-background-clip:text;-webkit-text-fill-color:transparent;background-clip:text;
}
.hero-sub{
  color:var(--white40);font-size:1rem;max-width:420px;line-height:1.7;margin:1.5rem 0 2.5rem;
}
.hero-car-img{
  width:100%;max-width:680px;
  filter:drop-shadow(0 30px 60px rgba(0,0,0,.8));
  animation:float 6s ease-in-out infinite;
}
@keyframes float{0%,100%{transform:translateY(0)}50%{transform:translateY(-16px)}}

/* SEARCH BAR */
.search-bar{
  background:var(--dark2);border:1px solid var(--border);
  border-radius:20px;padding:1.25rem 1.5rem;
  display:flex;flex-wrap:wrap;gap:1rem;align-items:flex-end;
}
.search-field{flex:1;min-width:150px}

/* STATS STRIP */
.stats-strip{
  display:flex;gap:0;background:var(--dark2);border:1px solid var(--border);border-radius:16px;overflow:hidden;
}
.stat-item{flex:1;padding:1.25rem 1.5rem;border-right:1px solid var(--border);text-align:center}
.stat-item:last-child{border-right:none}
.stat-num{font-family:'Syne',sans-serif;font-weight:800;font-size:1.8rem;color:var(--gold)}
.stat-lbl{font-size:.7rem;letter-spacing:.1em;text-transform:uppercase;color:var(--white40);margin-top:.2rem}

/* VEHICLE CARD */
.v-card{
  background:var(--dark2);border:1px solid var(--border);border-radius:20px;
  overflow:hidden;transition:all .35s;cursor:pointer;
}
.v-card:hover{border-color:rgba(201,169,110,.3);transform:translateY(-6px);box-shadow:0 30px 60px rgba(0,0,0,.4)}
.v-img{height:200px;overflow:hidden;position:relative}
.v-img img{width:100%;height:100%;object-fit:cover;transition:transform .5s}
.v-card:hover .v-img img{transform:scale(1.07)}
.v-city{
  position:absolute;top:.75rem;left:.75rem;
  background:rgba(10,10,10,.8);backdrop-filter:blur(8px);
  color:var(--white70);font-size:.65rem;font-weight:600;letter-spacing:.1em;text-transform:uppercase;
  padding:.3rem .7rem;border-radius:50px;display:flex;align-items:center;gap:.35rem;
}
.v-body{padding:1.25rem}
.v-name{font-family:'Syne',sans-serif;font-weight:700;font-size:1rem;color:var(--white);margin-bottom:.35rem}
.v-specs{display:flex;gap:.4rem;flex-wrap:wrap;margin-bottom:1rem}
.v-spec{
  background:var(--dark3);border:1px solid var(--border);color:var(--white40);
  font-size:.65rem;font-weight:500;letter-spacing:.06em;text-transform:uppercase;
  padding:.25rem .65rem;border-radius:50px;display:flex;align-items:center;gap:.3rem;
}
.v-price{font-family:'Syne',sans-serif;font-weight:800;font-size:1.3rem;color:var(--gold)}
.v-price span{font-family:'DM Sans',sans-serif;font-size:.75rem;font-weight:400;color:var(--white40)}

/* COMMENTS */
.comment-card{
  background:var(--dark2);border:1px solid var(--border);border-radius:16px;
  padding:1.5rem;height:100%;
}
.comment-stars{color:var(--gold);font-size:.85rem;margin-bottom:.75rem}
.comment-body{color:var(--white70);font-size:.9rem;line-height:1.7;font-style:italic;margin-bottom:1.25rem}
.comment-author{display:flex;align-items:center;gap:.75rem}
.comment-avatar{
  width:38px;height:38px;background:var(--gold);color:var(--black);
  border-radius:50%;display:flex;align-items:center;justify-content:center;
  font-family:'Syne',sans-serif;font-weight:800;font-size:.9rem;flex-shrink:0;
}
.comment-name{font-weight:600;font-size:.85rem;color:var(--white)}
.comment-date{font-size:.73rem;color:var(--white40)}

/* COMMENT FORM */
.comment-form{
  background:var(--dark2);border:1px solid var(--border);border-radius:20px;padding:2rem;
}
.star-input{cursor:pointer;font-size:1.4rem;color:var(--dark3);transition:color .15s}
.star-input.lit,.star-input:hover{color:var(--gold)}
</style>
@endpush

@section('content')

{{-- ═══ HERO ═══ --}}
<section class="hero">
  <div class="hero-bg"></div>
  <div class="hero-grid"></div>
  <div class="container position-relative" style="z-index:2">
    <div class="row align-items-center g-5">
      <div class="col-lg-6">
        <div class="hero-eyebrow">
          <span style="width:6px;height:6px;background:var(--gold);border-radius:50%;display:inline-block"></span>
          Maroc · Location Premium · Depuis 2020
        </div>
        <h1 class="hero-title">
          DRIVE<br>THE
          <span class="accent">FUTURE</span>
        </h1>
        <p class="hero-sub">Location de voitures premium au Maroc. Des véhicules d'exception disponibles à Casablanca, Marrakech, Rabat, Agadir et Fès.</p>
        <div class="d-flex flex-wrap gap-3">
          <a href="{{ route('voitures') }}" class="btn-gold">
            <i class="bi bi-car-front-fill"></i> Réserver de voitures
          </a>
          <a href="#flotte" class="btn-dark-outline">
            Explorez notre flotte
          </a>
        </div>
      </div>
      <div class="col-lg-6 text-center d-none d-lg-block">
        <img src="https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=900&q=90"
             alt="Porsche 911" class="hero-car-img" style="border-radius:20px;object-fit:cover;height:420px;width:100%">
      </div>
    </div>

    {{-- SEARCH BAR --}}
    <div class="search-bar mt-5">
      <form action="{{ route('voitures') }}" method="GET" class="d-flex flex-wrap gap-3 align-items-end w-100">
        <div class="search-field">
          <label class="prd-label" style="color:rgba(255,255,255,.4);font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;display:block;margin-bottom:.4rem">
            <i class="bi bi-geo-alt me-1" style="color:var(--gold)"></i>Ville
          </label>
          <select name="ville" class="prd-select" style="background:var(--dark3);border:1px solid var(--border);color:var(--white);border-radius:10px;padding:.75rem 1rem;font-size:.875rem;appearance:none;min-width:160px">
            <option value="">Toutes les villes</option>
            @foreach($villes as $v)
            <option value="{{ $v }}">{{ $v }}</option>
            @endforeach
          </select>
        </div>
        <div class="search-field">
          <label class="prd-label" style="color:rgba(255,255,255,.4);font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;display:block;margin-bottom:.4rem">
            <i class="bi bi-calendar me-1" style="color:var(--gold)"></i>Date Début
          </label>
          <input type="date" name="date_debut" class="prd-input" style="background:var(--dark3);border:1px solid var(--border);color:var(--white);border-radius:10px;padding:.75rem 1rem;font-size:.875rem;min-width:160px" min="{{ date('Y-m-d') }}">
        </div>
        <div class="search-field">
          <label class="prd-label" style="color:rgba(255,255,255,.4);font-size:.65rem;letter-spacing:.1em;text-transform:uppercase;display:block;margin-bottom:.4rem">
            <i class="bi bi-calendar-check me-1" style="color:var(--gold)"></i>Date Fin
          </label>
          <input type="date" name="date_fin" class="prd-input" style="background:var(--dark3);border:1px solid var(--border);color:var(--white);border-radius:10px;padding:.75rem 1rem;font-size:.875rem;min-width:160px">
        </div>
        <button type="submit" class="btn-gold" style="white-space:nowrap">
          <i class="bi bi-search"></i> Rechercher
        </button>
      </form>
    </div>
  </div>
</section>

{{-- ═══ STATS ═══ --}}
<section class="py-4" style="background:var(--dark);border-top:1px solid var(--border)">
  <div class="container">
    <div class="stats-strip">
      <div class="stat-item">
        <div class="stat-num">{{ $stats['vehicules'] }}+</div>
        <div class="stat-lbl">Véhicules</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">{{ $stats['villes'] }}</div>
        <div class="stat-lbl">Villes</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">{{ $stats['clients'] }}+</div>
        <div class="stat-lbl">Clients</div>
      </div>
      <div class="stat-item">
        <div class="stat-num">100%</div>
        <div class="stat-lbl">Satisfaction</div>
      </div>
    </div>
  </div>
</section>

{{-- ═══ FLOTTE ═══ --}}
<section class="py-5" id="flotte" style="background:var(--dark)">
  <div class="container">
    <div class="d-flex align-items-end justify-content-between mb-5 flex-wrap gap-3">
      <div>
        <span class="section-label">Notre Sélection</span>
        <h2 class="section-title">Explorez Notre<br>Flotte Premium</h2>
      </div>
      <a href="{{ route('voitures') }}" class="btn-dark-outline">
        Voir tout <i class="bi bi-arrow-right"></i>
      </a>
    </div>

    {{-- CITY FILTER --}}
    <div class="d-flex gap-2 mb-4 flex-wrap">
      <a href="{{ route('home') }}" class="s-badge {{ !request('ville') ? 's-confirmé' : '' }}"
         style="{{ !request('ville') ? '' : 'background:var(--dark3);border:1px solid var(--border);color:var(--white40)' }}">
        Toutes
      </a>
      @foreach($villes as $v)
      <a href="{{ route('home') }}?ville={{ $v }}" class="s-badge"
         style="background:var(--dark3);border:1px solid var(--border);color:var(--white40);text-decoration:none">
        {{ $v }}
      </a>
      @endforeach
    </div>

    <div class="row g-4">
      @forelse($featured as $vehicle)
      <div class="col-lg-4 col-md-6">
        <a href="{{ route('voitures.show', $vehicle->id) }}" style="text-decoration:none">
          <div class="v-card">
            <div class="v-img">
              <img src="{{ $vehicle->image_url ?: 'https://images.unsplash.com/photo-1492144534655-ae79c964c9d7?w=600&q=80' }}"
                   alt="{{ $vehicle->name }}">
              <div class="v-city"><i class="bi bi-geo-alt-fill" style="color:var(--gold)"></i>{{ $vehicle->city }}</div>
            </div>
            <div class="v-body">
              <div class="d-flex align-items-start justify-content-between mb-2">
                <div class="v-name">{{ $vehicle->name }}</div>
                <span class="cat-badge" style="color:var(--gold);border-color:rgba(201,169,110,.3);font-size:.6rem">{{ $vehicle->category }}</span>
              </div>
              <div class="v-specs">
                <span class="v-spec"><i class="bi bi-people-fill"></i>{{ $vehicle->seats }}</span>
                <span class="v-spec"><i class="bi bi-gear-fill"></i>{{ $vehicle->transmission }}</span>
                @if($vehicle->has_ac)<span class="v-spec"><i class="bi bi-wind"></i>Clim</span>@endif
              </div>
              <div class="d-flex align-items-center justify-content-between">
                <div>
                  <div class="v-price">{{ number_format($vehicle->price_per_day,0) }} DH<span>/jour</span></div>
                </div>
                <div style="background:var(--gold);color:var(--black);width:36px;height:36px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:.9rem">
                  <i class="bi bi-arrow-right"></i>
                </div>
              </div>
            </div>
          </div>
        </a>
      </div>
      @empty
      <div class="col-12 text-center py-5" style="color:var(--white40)">
        <i class="bi bi-car-front" style="font-size:3rem;display:block;margin-bottom:1rem"></i>
        Aucun véhicule disponible
      </div>
      @endforelse
    </div>
  </div>
</section>

{{-- ═══ VILLES ═══ --}}
<section class="py-5" style="background:var(--black)">
  <div class="container">
    <span class="section-label text-center d-block">Disponible partout</span>
    <h2 class="section-title text-center mb-5">Nos Villes</h2>
    <div class="row g-3 justify-content-center">
      @foreach($villes as $v)
      <div class="col-lg-2 col-md-3 col-4">
        <a href="{{ route('voitures') }}?ville={{ $v }}" style="text-decoration:none">
          <div style="background:var(--dark2);border:1px solid var(--border);border-radius:14px;padding:1.25rem;text-align:center;transition:all .25s"
               onmouseover="this.style.borderColor='rgba(201,169,110,.35)';this.style.background='rgba(201,169,110,.06)'"
               onmouseout="this.style.borderColor='rgba(255,255,255,.08)';this.style.background='var(--dark2)'">
            <i class="bi bi-geo-alt-fill" style="font-size:1.5rem;color:var(--gold);display:block;margin-bottom:.5rem"></i>
            <div style="font-family:'Syne',sans-serif;font-weight:700;font-size:.85rem;color:var(--white)">{{ $v }}</div>
          </div>
        </a>
      </div>
      @endforeach
    </div>
  </div>
</section>

{{-- ═══ COMMENTAIRES ═══ --}}
<section class="py-5" id="commentaires" style="background:var(--dark)">
  <div class="container">
    <span class="section-label text-center d-block">Ils nous font confiance</span>
    <h2 class="section-title text-center mb-5">Avis de nos Clients</h2>

    @if($comments->count())
    <div class="row g-4 mb-5">
      @foreach($comments as $c)
      <div class="col-lg-4 col-md-6">
        <div class="comment-card">
          <div class="comment-stars">
            @for($i=1;$i<=5;$i++)<i class="bi bi-star{{ $i<=$c->rating?'-fill':'' }}"></i>@endfor
          </div>
          <p class="comment-body">"{{ $c->body }}"</p>
          <div class="comment-author">
            <div class="comment-avatar">{{ strtoupper(substr($c->user->name,0,1)) }}</div>
            <div>
              <div class="comment-name">{{ $c->user->name }}</div>
              <div class="comment-date">
                {{ $c->vehicle->name ?? '' }} · {{ $c->created_at->diffForHumans() }}
              </div>
            </div>
          </div>
        </div>
      </div>
      @endforeach
    </div>
    @endif

    {{-- FORM --}}
    <div class="row justify-content-center">
      <div class="col-lg-7">
        @auth
        <div class="comment-form">
          <h4 style="font-family:'Syne',sans-serif;font-weight:700;margin-bottom:1.5rem">
            <i class="bi bi-pencil-square me-2" style="color:var(--gold)"></i>Laisser un avis
          </h4>
          @if($errors->any())
          <div style="background:rgba(232,64,64,.1);border:1px solid rgba(232,64,64,.25);border-radius:10px;padding:.85rem;color:#fca5a5;font-size:.85rem;margin-bottom:1rem">
            @foreach($errors->all() as $e)<div>• {{ $e }}</div>@endforeach
          </div>
          @endif
          <form action="{{ route('comments.store') }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="prd-label">Véhicule</label>
              <select name="vehicle_id" class="prd-select" required style="background:var(--dark3);border:1px solid var(--border);color:var(--white);border-radius:10px;padding:.8rem 1rem;width:100%;appearance:none">
                <option value="">-- Choisissez un véhicule --</option>
                @foreach(\App\Models\Vehicle::available()->orderBy('name')->get() as $v)
                <option value="{{ $v->id }}" {{ old('vehicle_id')==$v->id?'selected':'' }}>
                  {{ $v->name }} — {{ $v->city }}
                </option>
                @endforeach
              </select>
            </div>
            <div class="mb-3">
              <label class="prd-label">Note</label>
              <div class="d-flex gap-1" id="stars">
                @for($i=1;$i<=5;$i++)
                <i class="bi bi-star-fill star-input" data-val="{{ $i }}"></i>
                @endfor
              </div>
              <input type="hidden" name="rating" id="rating-val" value="{{ old('rating',5) }}" required>
            </div>
            <div class="mb-4">
              <label class="prd-label">Votre avis</label>
              <textarea name="body" class="prd-input" rows="4" placeholder="Partagez votre expérience..." required style="resize:none">{{ old('body') }}</textarea>
            </div>
            <button type="submit" class="btn-gold w-100">
              <i class="bi bi-send"></i> Publier mon avis
            </button>
          </form>
        </div>
        @else
        <div class="comment-form text-center">
          <i class="bi bi-lock" style="font-size:2.5rem;color:var(--gold);display:block;margin-bottom:1rem"></i>
          <h5 style="font-family:'Syne',sans-serif;margin-bottom:.5rem">Connectez-vous pour laisser un avis</h5>
          <p style="color:var(--white40);font-size:.9rem;margin-bottom:1.5rem">Seuls les membres peuvent publier des commentaires.</p>
          <div class="d-flex gap-3 justify-content-center">
            <a href="{{ route('login') }}" class="btn-gold"><i class="bi bi-person"></i> Connexion</a>
            <a href="{{ route('register') }}" class="btn-dark-outline">Inscription</a>
          </div>
        </div>
        @endauth
      </div>
    </div>
  </div>
</section>

@endsection

@push('scripts')
<script>
// Star rating
const stars = document.querySelectorAll('.star-input');
const ratingVal = document.getElementById('rating-val');
let current = parseInt(ratingVal?.value) || 5;

function renderStars(n){
  stars.forEach((s,i) => s.style.color = i < n ? 'var(--gold)' : 'var(--dark3)');
}
if(stars.length){ renderStars(current); }
stars.forEach((s,i)=>{
  s.addEventListener('mouseenter',()=>renderStars(i+1));
  s.addEventListener('mouseleave',()=>renderStars(current));
  s.addEventListener('click',()=>{ current=i+1; ratingVal.value=current; renderStars(current); });
});
</script>
@endpush
