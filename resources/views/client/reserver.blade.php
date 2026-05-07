@extends('layouts.app')
@section('title', 'Réserver — '.$vehicle->name)

@push('styles')
<style>
.reservation-hero { background:linear-gradient(105deg,#0f172a,#1a3a6b);padding:2rem 0; }
.res-card { background:#fff;border-radius:16px;padding:2rem;border:1px solid #e2e8f0;box-shadow:0 4px 20px rgba(0,0,0,.08); }
.vehicle-summary { background:#f8faff;border-radius:12px;padding:1.25rem;border:1px solid #e2e8f0; }
.step-badge { width:28px;height:28px;background:#1a56ff;color:#fff;border-radius:50%;
    display:inline-flex;align-items:center;justify-content:center;font-size:.75rem;font-weight:700;flex-shrink:0; }
</style>
@endpush

@section('content')

<section class="reservation-hero">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-2">
                <li class="breadcrumb-item"><a href="{{ url('/') }}" class="text-warning">Accueil</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vehicules') }}" class="text-warning">Véhicules</a></li>
                <li class="breadcrumb-item"><a href="{{ route('vehicules.show',$vehicle->id) }}" class="text-warning">{{ $vehicle->name }}</a></li>
                <li class="breadcrumb-item active text-white-50">Réservation</li>
            </ol>
        </nav>
        <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:2rem;color:#fff">
            RÉSERVER : {{ strtoupper($vehicle->name) }}
        </h1>
    </div>
</section>

<section class="py-5">
    <div class="container">
        @if($errors->any())
        <div class="alert alert-danger mb-4">
            <strong>Veuillez corriger les erreurs :</strong>
            @foreach($errors->all() as $e)<div class="small mt-1">• {{ $e }}</div>@endforeach
        </div>
        @endif

        <div class="row g-4">
            {{-- FORM --}}
            <div class="col-lg-8">
                <div class="res-card">
                    <form action="{{ route('reservations.store', $vehicle->id) }}" method="POST">
                        @csrf

                        {{-- STEP 1: Client Info --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="step-badge">1</span>
                            <h5 class="fw-bold mb-0">Vos informations</h5>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Nom complet <span class="text-danger">*</span></label>
                                <input type="text" name="client_name" class="form-control @error('client_name') is-invalid @enderror"
                                    value="{{ old('client_name') }}" placeholder="Jean Dupont" required>
                                @error('client_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Email <span class="text-danger">*</span></label>
                                <input type="email" name="client_email" class="form-control @error('client_email') is-invalid @enderror"
                                    value="{{ old('client_email') }}" placeholder="jean@email.com" required>
                                @error('client_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Téléphone</label>
                                <input type="tel" name="client_phone" class="form-control"
                                    value="{{ old('client_phone') }}" placeholder="+33 6 00 00 00 00">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Type de demande</label>
                                <select name="type" class="form-select">
                                    <option value="location" {{ old('type','location')=='location'?'selected':'' }}>Location</option>
                                    <option value="achat"    {{ old('type')=='achat'?'selected':'' }}>Achat</option>
                                </select>
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- STEP 2: Dates --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="step-badge">2</span>
                            <h5 class="fw-bold mb-0">Dates & Lieu</h5>
                        </div>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-semibold">Lieu de prise en charge <span class="text-danger">*</span></label>
                                <input type="text" name="pickup_location" class="form-control @error('pickup_location') is-invalid @enderror"
                                    value="{{ old('pickup_location', $searchParams['lieu'] ?? '') }}"
                                    placeholder="Paris Orly (ORY)" required>
                                @error('pickup_location')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Date de prise <span class="text-danger">*</span></label>
                                <input type="date" name="pickup_date" class="form-control @error('pickup_date') is-invalid @enderror"
                                    value="{{ old('pickup_date', $searchParams['date_prise'] ?? date('Y-m-d', strtotime('+1 day'))) }}"
                                    min="{{ date('Y-m-d') }}" required id="pickupDate">
                                @error('pickup_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-md-3">
                                <label class="form-label small fw-semibold">Date de retour <span class="text-danger">*</span></label>
                                <input type="date" name="return_date" class="form-control @error('return_date') is-invalid @enderror"
                                    value="{{ old('return_date', $searchParams['date_retour'] ?? date('Y-m-d', strtotime('+7 days'))) }}"
                                    required id="returnDate">
                                @error('return_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                            </div>
                        </div>

                        <hr class="my-4">

                        {{-- STEP 3: Notes --}}
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <span class="step-badge">3</span>
                            <h5 class="fw-bold mb-0">Notes additionnelles</h5>
                        </div>
                        <div class="mb-4">
                            <textarea name="notes" class="form-control" rows="3"
                                placeholder="Demandes particulières, heure de prise en charge, etc.">{{ old('notes') }}</textarea>
                        </div>

                        {{-- PRICE ESTIMATE --}}
                        <div class="alert alert-primary d-flex align-items-center gap-3 mb-4" id="priceEstimate">
                            <i class="bi bi-calculator fs-4"></i>
                            <div>
                                <strong>Estimation : </strong>
                                <span id="estimateText">Choisissez vos dates</span>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary btn-lg w-100 py-3 fw-bold">
                            <i class="bi bi-check-circle me-2"></i>Confirmer la réservation
                        </button>
                    </form>
                </div>
            </div>

            {{-- SUMMARY --}}
            <div class="col-lg-4">
                <div class="res-card sticky-top" style="top:80px">
                    <h6 class="fw-bold mb-3">Récapitulatif</h6>
                    <div class="vehicle-summary mb-3">
                        @if($vehicle->image_url)
                        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}"
                            class="rounded w-100 mb-3" style="height:140px;object-fit:cover">
                        @endif
                        <div class="fw-bold">{{ $vehicle->name }}</div>
                        <div class="badge bg-{{ $vehicle->category_badge_color }} mb-2">{{ $vehicle->category }}</div>
                        <div class="row text-center g-2 mt-1">
                            <div class="col-4">
                                <div style="background:#fff;border-radius:8px;padding:.5rem">
                                    <div class="small text-muted">Places</div>
                                    <div class="fw-bold small">{{ $vehicle->seats }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="background:#fff;border-radius:8px;padding:.5rem">
                                    <div class="small text-muted">Boîte</div>
                                    <div class="fw-bold" style="font-size:.65rem">{{ $vehicle->transmission }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div style="background:#fff;border-radius:8px;padding:.5rem">
                                    <div class="small text-muted">Clim</div>
                                    <div class="fw-bold small">{{ $vehicle->has_ac ? 'Oui' : 'Non' }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Prix/jour</span>
                        <strong>€{{ number_format($vehicle->price_per_day,2,',','') }}</strong>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted small">Durée estimée</span>
                        <strong id="daysCount">— jours</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold">Total estimé</span>
                        <span class="fw-bold text-primary fs-5" id="totalPrice">—</span>
                    </div>
                    <small class="text-muted d-block mt-2">* Prix définitif confirmé après validation</small>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
const pricePerDay = {{ $vehicle->price_per_day }};
const pickupDate  = document.getElementById('pickupDate');
const returnDate  = document.getElementById('returnDate');
const daysEl      = document.getElementById('daysCount');
const totalEl     = document.getElementById('totalPrice');
const estimateEl  = document.getElementById('estimateText');

function calcPrice() {
    const p = new Date(pickupDate.value);
    const r = new Date(returnDate.value);
    if (!pickupDate.value || !returnDate.value || r <= p) {
        daysEl.textContent = '— jours'; totalEl.textContent = '—';
        estimateEl.textContent = 'Choisissez des dates valides'; return;
    }
    const days  = Math.max(1, Math.ceil((r - p) / 86400000));
    const total = days * pricePerDay;
    daysEl.textContent    = days + ' jour(s)';
    totalEl.textContent   = '€' + total.toLocaleString('fr-FR', {minimumFractionDigits:2});
    estimateEl.textContent= days + ' jour(s) × €' + pricePerDay + ' = €' + total.toLocaleString('fr-FR', {minimumFractionDigits:2});

    // Enforce return > pickup
    returnDate.min = new Date(p.getTime() + 86400000).toISOString().split('T')[0];
}

pickupDate.addEventListener('change', calcPrice);
returnDate.addEventListener('change', calcPrice);
calcPrice();
</script>
@endpush
