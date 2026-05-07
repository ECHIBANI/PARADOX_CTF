@extends('layouts.app')
@section('title','Bon de Réservation ' . $reservation->reservation_number . ' — PARDOX')

@section('body')

{{-- Print controls (hidden on print) --}}
<div class="no-print bg-light border-bottom py-3 px-4 d-flex justify-content-between align-items-center">
  <a href="{{ auth()->user()->isAdmin() ? route('admin.reservations') : route('client.reservations') }}"
     class="btn btn-pardo-outline btn-sm">
    <i class="bi bi-arrow-left me-1"></i> Retour
  </a>
  <div class="d-flex gap-2">
    <button onclick="window.print()" class="btn btn-pardo-primary btn-sm">
      <i class="bi bi-printer me-1"></i> Imprimer / PDF
    </button>
  </div>
</div>

{{-- VOUCHER --}}
<div class="py-4 px-3" style="background:#f1f5f9;min-height:calc(100vh - 60px);">
<div class="voucher-wrapper shadow-sm">

  {{-- Header --}}
  <div class="d-flex justify-content-between align-items-start pb-4 mb-4" style="border-bottom:3px double #000;">
    <div>
      <img src="{{ asset('images/logo.png') }}" style="height: 45px; margin-bottom: 0.5rem;" alt="PARDOX">
      <div style="font-size:.7rem;font-weight:600;letter-spacing:.15em;text-transform:uppercase;color:#555;">Location de Véhicules · Maroc</div>
      <div style="font-size:.75rem;color:#777;margin-top:.3rem;">
        <i class="bi bi-telephone me-1"></i>+212 617-889657 &nbsp;·&nbsp;
        <i class="bi bi-globe me-1"></i>www.pardox.com
      </div>
    </div>
    <div class="text-end">
      <div style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.4rem;letter-spacing:.05em;text-transform:uppercase;">BON DE RÉSERVATION</div>
      <div style="font-size:.9rem;font-weight:700;color:var(--cre-blue);">{{ $reservation->reservation_number }}</div>
      <div style="font-size:.75rem;color:#777;margin-top:.2rem;">Émis le {{ $reservation->created_at->format('d/m/Y à H:i') }}</div>
      <div class="mt-2">
        <span class="status-pill badge-{{ $reservation->status }}" style="font-size:.72rem;">
          {{ $reservation->status_label }}
        </span>
      </div>
    </div>
  </div>

  {{-- Client + Vehicle --}}
  <div class="row g-4 mb-4">
    <div class="col-6">
      <div class="voucher-section-title">Informations Client</div>
      <div class="voucher-row"><span style="color:#555;">Nom complet</span><strong>{{ $reservation->user->name }}</strong></div>
      <div class="voucher-row"><span style="color:#555;">Téléphone</span><strong>{{ $reservation->user->phone }}</strong></div>
      <div class="voucher-row"><span style="color:#555;">Réf. Client</span><strong>CLI-{{ str_pad($reservation->user->id, 4, '0', STR_PAD_LEFT) }}</strong></div>
    </div>
    <div class="col-6">
      <div class="voucher-section-title">Véhicule Loué</div>
      <div class="voucher-row"><span style="color:#555;">Modèle</span><strong>{{ $reservation->vehicle->name }}</strong></div>
      <div class="voucher-row"><span style="color:#555;">Catégorie</span><strong>{{ $reservation->vehicle->category }}</strong></div>
      <div class="voucher-row"><span style="color:#555;">Transmission</span><strong>{{ $reservation->vehicle->transmission }}</strong></div>
      <div class="voucher-row"><span style="color:#555;">Climatisation</span><strong>{{ $reservation->vehicle->ac ? 'Oui' : 'Non' }}</strong></div>
    </div>
  </div>

  {{-- Rental Details + Pricing --}}
  <div class="mb-4">
    <div class="voucher-section-title">Détails de la Location & Tarification</div>
    <div class="row g-3">
      <div class="col-6">
        <div class="voucher-row"><span style="color:#555;">Date de début</span><strong>{{ $reservation->start_date->format('d/m/Y') }}</strong></div>
        <div class="voucher-row"><span style="color:#555;">Date de fin</span><strong>{{ $reservation->end_date->format('d/m/Y') }}</strong></div>
        <div class="voucher-row"><span style="color:#555;">Durée totale</span><strong>{{ $reservation->days }} jour(s)</strong></div>
      </div>
      <div class="col-6">
        <div class="voucher-row"><span style="color:#555;">Prix / jour</span><strong>{{ number_format($reservation->vehicle->price_per_day,0,',',' ') }} DH</strong></div>
        <div class="voucher-row"><span style="color:#555;">Acompte à verser (30%)</span><strong style="color:var(--cre-orange);">{{ number_format($reservation->acompte,0,',',' ') }} DH</strong></div>
        <div class="voucher-row"><span style="color:#555;">Reste à payer</span><strong>{{ number_format($reservation->reste,0,',',' ') }} DH</strong></div>
      </div>
    </div>
    <div class="voucher-total-bar mt-3">
      <span>MONTANT TOTAL</span>
      <span style="font-family:'Barlow Condensed',sans-serif;font-size:1.3rem;letter-spacing:.03em;">
        {{ number_format($reservation->total_price,0,',',' ') }} DH
      </span>
    </div>
  </div>

  @if($reservation->admin_note)
  <div class="mb-4 p-3 rounded-2" style="background:#fffbeb;border:1px solid #fde68a;">
    <strong style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;">Note de l'administrateur :</strong>
    <p class="mb-0 mt-1" style="font-size:.875rem;">{{ $reservation->admin_note }}</p>
  </div>
  @endif

  {{-- Conditions --}}
  <div class="mb-4">
    <div class="voucher-section-title">Conditions Générales de Location</div>
    <ol style="font-size:.75rem;color:#555;line-height:1.9;padding-left:1.2rem;margin:0;">
      <li>Le client doit présenter ce bon + CIN/Passeport + Permis de conduire valide lors de la prise en charge du véhicule.</li>
      <li>L'acompte de {{ number_format($reservation->acompte,0,',',' ') }} DH est dû à la signature du présent bon.</li>
      <li>Annulation gratuite jusqu'à 48h avant la date de début. Au-delà, l'acompte est conservé.</li>
      <li>Tout dommage constaté sur le véhicule sera à la charge du client conformément au contrat de location signé en agence.</li>
      <li>Le carburant est à la charge du locataire. Le véhicule sera rendu avec le même niveau de carburant qu'à la prise en charge.</li>
      <li>En cas de retard de restitution, une pénalité de {{ number_format($reservation->vehicle->price_per_day,0,',',' ') }} DH par jour sera facturée.</li>
      <li>Ce bon est valable uniquement pour les dates et le véhicule mentionnés ci-dessus.</li>
      <li>Kilométrage illimité inclus. Aucun frais supplémentaire hors dommages et carburant.</li>
    </ol>
  </div>

  {{-- Signatures --}}
  <div class="row mt-4 pt-4" style="border-top:1px solid #000;">
    <div class="col-6 text-center">
      <div class="sig-line" style="border-top:1px solid #000; width:60%; margin:0 auto .5rem;"></div>
      <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;color:#777;">Signature du Client</div>
      <div style="font-size:.7rem;color:#aaa;margin-top:.2rem;">{{ $reservation->user->name }}</div>
    </div>
    <div class="col-6 text-center position-relative" style="min-height:120px;">
      <div class="sig-line" style="border-top:1px solid #000; width:60%; margin:0 auto .5rem;"></div>
      <div style="font-size:.72rem;text-transform:uppercase;letter-spacing:.1em;color:#777;position:relative;">Cachet & Signature PARDOX</div>
      <div style="font-size:.7rem;color:#aaa;margin-top:.2rem;position:relative;">Directeur d'Agence</div>
      
      <div style="position:absolute; top:20px; left:50%; transform:translateX(-50%); width:200px; z-index:10;">
        <img src="{{ asset('images/signature.png') }}" alt="Signature" style="position:absolute; width:140px; top:0; left:10px; mix-blend-mode:multiply;">
        <img src="{{ asset('images/stamp.png') }}" alt="Cachet" style="position:absolute; width:120px; top:5px; left:80px; transform:rotate(-10deg); mix-blend-mode:multiply;">
      </div>
    </div>
  </div>

  {{-- Footer --}}
  <div class="text-center mt-4 pt-3" style="border-top:1px solid #ddd;font-size:.7rem;color:#aaa;line-height:1.8;">
    Ce document est un bon de réservation officiel PARDOX — Il ne constitue pas une facture.<br>
    PARDOX · RC: 123456 · ICE: 000000000000000 · Agadir, Souss-Massa, Maroc<br>
    © {{ date('Y') }} PARDOX. Tous droits réservés.
  </div>

</div>
</div>

@endsection
