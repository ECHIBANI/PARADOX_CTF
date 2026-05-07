@extends('layouts.app')
@section('title','Nos Véhicules — PARDOX')

@section('body')
@include('layouts.navbar')

<section class="py-5">
  <div class="container">
    {{-- Header --}}
    <div class="d-flex justify-content-between align-items-end mb-4 flex-wrap gap-3">
      <div>
        <span class="section-eyebrow d-block mb-1">Notre flotte</span>
        <h1 class="section-title mb-0">TOUS NOS VÉHICULES</h1>
      </div>
      <span class="text-muted">{{ $vehicles->count() }} véhicules disponibles</span>
    </div>

    {{-- Category filter --}}
    <div class="d-flex gap-2 flex-wrap align-items-center mb-4">
      <a href="{{ route('vehicles.index', request()->except('category')) }}"
         class="btn btn-sm {{ !request('category') ? 'btn-pardo-active' : 'btn-pardo-outline' }}"
         style="border-radius:50px;font-weight:600;">
        Tous
      </a>
      @foreach($categories as $cat)
      <a href="{{ route('vehicles.index', array_merge(request()->all(), ['category'=>$cat])) }}"
         class="btn btn-sm {{ request('category')==$cat ? 'btn-pardo-active' : 'btn-pardo-outline' }}"
         style="border-radius:50px;font-weight:600;">
        {{ $cat }}
      </a>
      @endforeach
    </div>

    {{-- Vehicles Grid --}}
    @php
      $filtered = request('category') ? $vehicles->where('category', request('category')) : $vehicles;
    @endphp
    <div class="row g-4">
      @forelse($filtered as $vehicle)
      <div class="col-md-6 col-lg-4">
        @include('front.partials.vehicle-card', ['vehicle' => $vehicle])
      </div>
      @empty
      <div class="col-12 text-center py-5 text-muted">
        <i class="bi bi-car-front" style="font-size:3rem;opacity:.3;"></i>
        <p class="mt-3">Aucun véhicule dans cette catégorie.</p>
      </div>
      @endforelse
    </div>
  </div>
</section>

@include('layouts.footer')
@endsection
