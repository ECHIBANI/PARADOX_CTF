@extends('layouts.admin')
@section('title','Véhicules')
@section('page-title','Véhicules')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
  <span class="text-muted">{{ $vehicles->count() }} véhicule(s) au total</span>
  <a href="{{ route('admin.vehicles.create') }}" class="btn btn-pardo-orange">
    <i class="bi bi-plus-lg me-2"></i>Ajouter un véhicule
  </a>
</div>

<div class="row g-4">
  @forelse($vehicles as $vehicle)
  <div class="col-md-6 col-xl-4">
    <div class="vehicle-card-admin">
      <div class="vehicle-img-wrap">
        <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}" class="vehicle-img">
        <span class="cat-badge">{{ $vehicle->category }}</span>
        @if(!$vehicle->available)
        <span class="position-absolute top-0 end-0 m-2 badge bg-danger">Indisponible</span>
        @endif
      </div>
      <div class="p-3">
        <div class="d-flex justify-content-between align-items-start mb-2">
          <div>
            <div style="font-family:'Barlow Condensed',sans-serif;font-weight:700;font-size:1.1rem;color:var(--cre-dark);">{{ $vehicle->name }}</div>
            <div class="text-muted" style="font-size:.8rem;">{{ $vehicle->seats }} places · {{ $vehicle->transmission }}{{ $vehicle->ac ? ' · Clim' : '' }}</div>
          </div>
          <div class="text-end">
            <div style="font-family:'Barlow Condensed',sans-serif;font-weight:800;font-size:1.3rem;color:var(--cre-blue);">{{ number_format($vehicle->price_per_day,0,',',' ') }} DH</div>
            <div class="text-muted" style="font-size:.72rem;">/jour</div>
          </div>
        </div>
        <div class="d-flex gap-2 flex-wrap mt-3">
          <a href="{{ route('admin.vehicles.edit',$vehicle) }}" class="btn btn-sm btn-pardo-outline flex-fill text-center">
            <i class="bi bi-pencil me-1"></i>Modifier
          </a>
          <form action="{{ route('admin.vehicles.delete',$vehicle) }}" method="POST" onsubmit="return confirm('Supprimer ce véhicule ?')">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></button>
          </form>
        </div>
      </div>
    </div>
  </div>
  @empty
  <div class="col-12 text-center py-5 text-muted">
    <i class="bi bi-car-front" style="font-size:3rem;opacity:.3;"></i>
    <p class="mt-3">Aucun véhicule. <a href="{{ route('admin.vehicles.create') }}">Ajoutez le premier !</a></p>
  </div>
  @endforelse
</div>
@endsection
