@extends('layouts.app')
@section('title', $vehicle->name . ' — PARDOX')

@section('body')
@include('layouts.navbar')

<section class="py-5">
  <div class="container">
    <a href="{{ route('vehicles.index') }}" class="btn btn-pardo-outline btn-sm mb-4">
      <i class="bi bi-arrow-left me-1"></i> Retour aux véhicules
    </a>

    <div class="row g-4 align-items-start">
      {{-- LEFT: Vehicle Info --}}
      <div class="col-lg-7">
        <div style="border-radius:var(--radius-lg);overflow:hidden;height:320px;margin-bottom:1.5rem;">
          <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}"
               style="width:100%;height:100%;object-fit:cover;">
        </div>

        <div class="d-flex align-items-center gap-2 mb-2 flex-wrap">
          <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:2rem;color:var(--cre-dark);margin:0;">
            {{ $vehicle->name }}
          </h1>
          <span class="cat-badge position-static" style="position:relative!important;bottom:auto!important;left:auto!important;">{{ $vehicle->category }}</span>
        </div>

        <div class="d-flex flex-wrap gap-2 mb-4">
          <span class="feat-tag"><i class="bi bi-people me-1"></i>{{ $vehicle->seats }} places</span>
          <span class="feat-tag"><i class="bi bi-gear me-1"></i>{{ $vehicle->transmission }}</span>
          @if($vehicle->ac)<span class="feat-tag"><i class="bi bi-snow me-1"></i>Climatisation</span>@endif
          <span class="feat-tag" style="color:var(--cre-blue);border-color:var(--cre-blue);background:rgba(26,86,255,.05);">
            <i class="bi bi-currency-dollar me-1"></i>{{ number_format($vehicle->price_per_day,0,',',' ') }} DH/jour
          </span>
        </div>

        @if($vehicle->description)
        <p class="text-muted" style="line-height:1.7;">{{ $vehicle->description }}</p>
        @endif

        {{-- Occupied periods --}}
        @if($occupied->count())
        <div class="table-card mt-4">
          <div class="p-3 border-bottom" style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1rem;">
            <i class="bi bi-calendar-x me-2" style="color:var(--cre-orange);"></i>Périodes non disponibles
          </div>
          <div class="p-3">
            @foreach($occupied as $period)
            <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
              <span style="font-size:.875rem;">
                <i class="bi bi-arrow-right me-1"></i>
                {{ \Carbon\Carbon::parse($period->start_date)->format('d/m/Y') }}
                &nbsp;→&nbsp;
                {{ \Carbon\Carbon::parse($period->end_date)->format('d/m/Y') }}
              </span>
              <span class="status-pill badge-{{ $period->status }}">
                {{ match($period->status) { 'pending'=>'En attente','confirmed'=>'Confirmée',default=>ucfirst($period->status) } }}
              </span>
            </div>
            @endforeach
          </div>
        </div>
        @endif

        {{-- REVIEW FORM --}}
        @auth
        
        @if(session('success'))
        <div class="alert alert-success mt-4 mb-0" style="border-radius:var(--radius-sm);">
          <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
        </div>
        @endif

        <div class="table-card mt-4 p-4 border bg-white" style="border-radius:var(--radius-lg);">
          <h4 style="font-family:'Barlow Condensed',sans-serif;font-weight:700;"><i class="bi bi-chat-right-quote me-2" style="color:var(--cre-blue);"></i>Laisser un avis</h4>
          <p class="text-muted small mb-3">Partagez votre expérience avec ce véhicule. Votre avis apparaîtra sur la page d'accueil !</p>
          <form action="{{ route('vehicles.comment', $vehicle) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="form-label fw-bold small" style="color:var(--cre-muted);">Note globale</label>
              <select name="rating" class="form-select w-auto" style="border-radius:var(--radius-sm);" required>
                <option value="5" selected>5 - Excellent ★★★★★</option>
                <option value="4">4 - Très bien ★★★★☆</option>
                <option value="3">3 - Moyen ★★★☆☆</option>
                <option value="2">2 - Mauvais ★★☆☆☆</option>
                <option value="1">1 - Catastrophique ★☆☆☆☆</option>
              </select>
            </div>
            <div class="mb-3">
              <label class="form-label fw-bold small" style="color:var(--cre-muted);">Votre commentaire</label>
              <textarea name="body" rows="3" class="form-control" style="border-radius:var(--radius-sm);" required placeholder="Comment s'est passée votre location avec ce véhicule ?"></textarea>
            </div>
            <button type="submit" class="btn btn-pardo-outline px-4">
              <i class="bi bi-send me-1"></i> Publier mon avis
            </button>
          </form>
        </div>
        @endauth
      </div>

      {{-- RIGHT: Booking Card --}}
      <div class="col-lg-5">
        <div class="booking-summary p-4 sticky-top" style="top:80px;">
          <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}"
               style="width:100%;height:160px;object-fit:cover;border-radius:var(--radius-md);margin-bottom:1.25rem;">
          <h3 style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.4rem;margin-bottom:1.25rem;">
            {{ $vehicle->name }}
          </h3>

          @auth
          {{-- BOOKING FORM --}}
          @if($errors->has('general'))
          <div class="alert alert-danger py-2 px-3 mb-3" style="border-radius:var(--radius-sm);">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>{{ $errors->first('general') }}
          </div>
          @endif

          <form action="{{ route('reservations.store', $vehicle) }}" method="POST" id="bookingForm">
            @csrf
            <div class="mb-3">
              <label class="form-label" style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.6);">
                <i class="bi bi-calendar me-1"></i> Date de début *
              </label>
              <input type="date" name="start_date" id="startDate"
                     class="form-control @error('start_date') is-invalid @enderror"
                     min="{{ date('Y-m-d') }}" value="{{ old('start_date') }}"
                     required
                     style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);color:#fff;border-radius:var(--radius-sm);color-scheme:dark;">
              @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
              <label class="form-label" style="font-size:.72rem;font-weight:700;text-transform:uppercase;letter-spacing:.07em;color:rgba(255,255,255,.6);">
                <i class="bi bi-calendar-check me-1"></i> Date de fin *
              </label>
              <input type="date" name="end_date" id="endDate"
                     class="form-control @error('end_date') is-invalid @enderror"
                     min="{{ date('Y-m-d', strtotime('+1 day')) }}" value="{{ old('end_date') }}"
                     required
                     style="background:rgba(255,255,255,.08);border:1px solid rgba(255,255,255,.15);color:#fff;border-radius:var(--radius-sm);color-scheme:dark;">
              @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Overlap Error (JS) --}}
            <div id="overlapError" class="alert alert-danger py-2 px-3 d-none mb-3" style="border-radius:var(--radius-sm);font-size:0.85rem;">
              <i class="bi bi-calendar-x me-2"></i> Ce véhicule est déjà réservé pour ces dates.
            </div>

            {{-- Price Preview (JS) --}}
            <div id="pricePreview" class="d-none">
              <div class="summary-price-row">
                <span id="daysLabel" style="color:rgba(255,255,255,.7);">Durée</span>
                <span id="totalLine" style="color:#fff;font-weight:700;"></span>
              </div>
              <div class="summary-price-row">
                <span style="color:rgba(255,255,255,.7);">Acompte (30%)</span>
                <span id="acompteLine" style="color:#fbbf24;font-weight:700;"></span>
              </div>
              <div class="summary-price-row">
                <span style="color:rgba(255,255,255,.7);">Reste à payer</span>
                <span id="resteLine" style="color:#fff;font-weight:700;"></span>
              </div>
              <div class="summary-total mt-3">
                <span style="font-weight:700;">TOTAL</span>
                <span id="totalBig" style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.3rem;"></span>
              </div>
            </div>

            <button type="submit" id="submitBtn" class="btn btn-pardo-orange w-100 btn-lg mt-3"
                    style="font-family:'Barlow Condensed',sans-serif;font-size:1.05rem;font-weight:700;letter-spacing:.06em;" disabled>
              <i class="bi bi-calendar-plus me-2"></i>CONFIRMER LA RÉSERVATION
            </button>
          </form>

          @else
          <div class="text-center py-3">
            <p style="color:rgba(255,255,255,.7);margin-bottom:1rem;">
              <i class="bi bi-lock me-1"></i> Connectez-vous pour réserver ce véhicule.
            </p>
            <a href="{{ route('login') }}" class="btn btn-pardo-orange w-100 mb-2">
              <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
            </a>
            <a href="{{ route('register') }}" class="btn w-100" style="border:1.5px solid rgba(255,255,255,.3);color:#fff;border-radius:var(--radius-sm);font-weight:600;">
              Créer un compte
            </a>
          </div>
          @endauth
        </div>
      </div>

    </div>
  </div>
</section>

@include('layouts.footer')
@endsection

@section('scripts')
<script>
const pricePerDay = {{ $vehicle->price_per_day }};
const startInput  = document.getElementById('startDate');
const endInput    = document.getElementById('endDate');
const preview     = document.getElementById('pricePreview');
const overlapErr  = document.getElementById('overlapError');
const submitBtn   = document.getElementById('submitBtn');

const occupied = [
  @foreach($occupied as $p)
  { start: new Date("{{ \Carbon\Carbon::parse($p->start_date)->startOfDay() }}"), end: new Date("{{ \Carbon\Carbon::parse($p->end_date)->startOfDay() }}") },
  @endforeach
];

function fmtDH(n) {
  return new Intl.NumberFormat('fr-MA').format(n) + ' DH';
}

function updatePrice() {
  const s = new Date(startInput.value);
  const e = new Date(endInput.value);
  if (!startInput.value || !endInput.value || e <= s) {
    preview.classList.add('d-none');
    overlapErr.classList.add('d-none');
    submitBtn.disabled = true;
    return;
  }

  // Check overlap
  let hasOverlap = false;
  for (let p of occupied) {
     if (s < p.end && e > p.start) {
        hasOverlap = true;
        break;
     }
  }

  if (hasOverlap) {
    overlapErr.classList.remove('d-none');
    preview.classList.add('d-none');
    submitBtn.disabled = true;
    return;
  }

  overlapErr.classList.add('d-none');
  submitBtn.disabled = false;
  const days  = Math.max(1, Math.round((e - s) / 86400000));
  const total   = days * pricePerDay;
  const acompte = Math.round(total * 0.3);
  const reste   = total - acompte;

  document.getElementById('daysLabel').textContent  = days + ' jour(s) × ' + fmtDH(pricePerDay);
  document.getElementById('totalLine').textContent  = fmtDH(total);
  document.getElementById('acompteLine').textContent = fmtDH(acompte);
  document.getElementById('resteLine').textContent  = fmtDH(reste);
  document.getElementById('totalBig').textContent   = fmtDH(total);
  preview.classList.remove('d-none');

  // Update end min date
  endInput.min = new Date(s.getTime() + 86400000).toISOString().split('T')[0];
}

startInput?.addEventListener('change', updatePrice);
endInput?.addEventListener('change', updatePrice);
updatePrice();
</script>
@endsection
