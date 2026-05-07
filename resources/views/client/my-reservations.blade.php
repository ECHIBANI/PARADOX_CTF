@extends('layouts.app')
@section('title','Mes Réservations — PARDOX')

@section('body')
@include('layouts.navbar')

{{-- Profile Header --}}
<div class="py-5" style="background:linear-gradient(135deg,var(--cre-dark),#1e2d5a);color:#fff;">
  <div class="container">
    <div class="d-flex align-items-center gap-4 flex-wrap">
      <div class="d-flex align-items-center justify-content-center rounded-circle"
           style="width:72px;height:72px;background:linear-gradient(135deg,var(--cre-blue),#4f78ff);font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:2rem;color:#fff;flex-shrink:0;">
        {{ strtoupper(substr(auth()->user()->name,0,1)) }}
      </div>
      <div>
        <h1 style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:2rem;margin:0;">
          {{ auth()->user()->name }}
        </h1>
        <p style="color:rgba(255,255,255,.6);margin:0;font-size:.9rem;">
          <i class="bi bi-telephone me-1"></i>{{ auth()->user()->phone }}
        </p>
      </div>
    </div>
  </div>
</div>

<section class="py-5">
  <div class="container">

    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" style="border-radius:var(--radius-sm);">
      <i class="bi bi-check-circle-fill me-2"></i>{{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
      <h2 style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.8rem;color:var(--cre-dark);">
        Mes réservations
      </h2>
      <a href="{{ route('vehicles.index') }}" class="btn btn-pardo-orange">
        <i class="bi bi-plus-lg me-1"></i>Nouvelle réservation
      </a>
    </div>

    @if($reservations->isEmpty())
    <div class="text-center py-5">
      <i class="bi bi-calendar-x" style="font-size:3.5rem;color:var(--cre-muted);opacity:.4;"></i>
      <h4 class="mt-3 text-muted">Aucune réservation pour le moment</h4>
      <p class="text-muted">Parcourez nos véhicules et faites votre première réservation !</p>
      <a href="{{ route('vehicles.index') }}" class="btn btn-pardo-primary mt-2">Voir les véhicules</a>
    </div>
    @else
    <div class="table-card">
      <table class="table table-pardo mb-0">
        <thead>
          <tr>
            <th>N° Réservation</th>
            <th>Véhicule</th>
            <th>Dates</th>
            <th>Durée</th>
            <th>Total</th>
            <th>Acompte</th>
            <th>Statut</th>
            <th>Bon</th>
          </tr>
        </thead>
        <tbody>
          @foreach($reservations as $res)
          <tr>
            <td><span class="res-id">{{ $res->reservation_number }}</span></td>
            <td>
              <div class="fw-semibold" style="color:var(--cre-dark);">{{ $res->vehicle->name }}</div>
              <div class="text-muted" style="font-size:.75rem;">{{ $res->vehicle->category }}</div>
            </td>
            <td style="font-size:.8rem;">
              <div><i class="bi bi-calendar me-1" style="color:var(--cre-blue);"></i>{{ $res->start_date->format('d/m/Y') }}</div>
              <div><i class="bi bi-calendar-check me-1" style="color:var(--cre-orange);"></i>{{ $res->end_date->format('d/m/Y') }}</div>
            </td>
            <td><strong>{{ $res->days }}</strong> jour(s)</td>
            <td><strong style="color:var(--cre-blue);">{{ number_format($res->total_price,0,',',' ') }} DH</strong></td>
            <td style="color:var(--cre-orange);font-weight:600;">{{ number_format($res->acompte,0,',',' ') }} DH</td>
            <td>
              <span class="status-pill badge-{{ $res->status }}">
                @switch($res->status)
                  @case('pending')   <i class="bi bi-clock me-1"></i>En attente @break
                  @case('confirmed') <i class="bi bi-check-circle me-1"></i>Confirmée @break
                  @case('rejected')  <i class="bi bi-x-circle me-1"></i>Refusée @break
                  @case('completed') <i class="bi bi-flag me-1"></i>Terminée @break
                @endswitch
              </span>
            </td>
            <td>
              <a href="{{ route('reservations.voucher', $res) }}" class="btn btn-pardo-outline btn-sm">
                <i class="bi bi-file-earmark-text me-1"></i>Bon
              </a>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    @endif
  </div>
</section>

@include('layouts.footer')
@endsection
