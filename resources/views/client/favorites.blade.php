@extends('layouts.app')
@section('title', 'Mes favoris — PARDOX')

@section('body')
@include('layouts.navbar')

<section class="py-5 bg-pardo-light" style="min-height: 80vh;">
  <div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
      <h2 style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:2rem;color:var(--cre-dark);margin:0;">
        <i class="bi bi-heart-fill me-2" style="color:#ef4444;"></i>Mes véhicules favoris
      </h2>
    </div>

    @if($favorites->isEmpty())
    <div class="text-center py-5">
      <div class="mb-4 text-muted">
        <i class="bi bi-heart text-muted" style="font-size: 4rem; opacity: 0.5;"></i>
      </div>
      <h4 class="fw-bold mb-3">Aucun véhicule favori</h4>
      <p class="text-muted mb-4">Vous n'avez pas encore ajouté de véhicules à vos favoris.</p>
      <a href="{{ route('vehicles.index') }}" class="btn btn-pardo-primary px-4">
        Découvrir nos véhicules
      </a>
    </div>
    @else
    <div class="row g-4">
      @foreach($favorites as $vehicle)
      <div class="col-md-6 col-lg-4" id="fav-card-{{ $vehicle->id }}">
        @include('front.partials.vehicle-card', ['vehicle' => $vehicle])
      </div>
      @endforeach
    </div>
    @endif
  </div>
</section>

@include('layouts.footer')
@endsection
