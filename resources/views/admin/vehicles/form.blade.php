@extends('layouts.admin')
@section('title', $vehicle->exists ? 'Modifier '.$vehicle->name : 'Nouveau véhicule')
@section('page-title', $vehicle->exists ? 'Modifier un véhicule' : 'Ajouter un véhicule')

@section('content')
<div class="row justify-content-center">
  <div class="col-lg-8">
    <div class="table-card p-0 overflow-hidden">
      <div class="table-card-header">
        <span class="table-card-title">
          <i class="bi bi-car-front me-2" style="color:var(--cre-blue);"></i>
          {{ $vehicle->exists ? 'Modifier : '.$vehicle->name : 'Nouveau véhicule' }}
        </span>
        <a href="{{ route('admin.vehicles') }}" class="btn btn-pardo-outline btn-sm">
          <i class="bi bi-arrow-left me-1"></i>Retour
        </a>
      </div>
      <div class="p-4">
        <form action="{{ $vehicle->exists ? route('admin.vehicles.update',$vehicle) : route('admin.vehicles.store') }}"
              method="POST" enctype="multipart/form-data">
          @csrf
          @if($vehicle->exists) @method('PUT') @endif

          <div class="row g-3">
            {{-- Name --}}
            <div class="col-12">
              <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">Nom du véhicule *</label>
              <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                     placeholder="ex: Dacia Logan" value="{{ old('name',$vehicle->name) }}" required
                     style="border-radius:var(--radius-sm);border:1.5px solid var(--cre-border);">
              @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            {{-- Category + Price --}}
            <div class="col-md-6">
              <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">Catégorie *</label>
              <select name="category" class="form-select @error('category') is-invalid @enderror"
                      style="border-radius:var(--radius-sm);border:1.5px solid var(--cre-border);">
                @foreach(['Économique','Citadine','SUV','Premium','Utilitaire','Berline'] as $cat)
                <option value="{{ $cat }}" {{ old('category',$vehicle->category)===$cat ? 'selected' : '' }}>{{ $cat }}</option>
                @endforeach
              </select>
              @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">Prix / jour (DH) *</label>
              <div class="input-group">
                <input type="number" name="price_per_day" class="form-control @error('price_per_day') is-invalid @enderror"
                       min="50" max="99999" value="{{ old('price_per_day',$vehicle->price_per_day) }}" required
                       style="border-radius:var(--radius-sm) 0 0 var(--radius-sm);border:1.5px solid var(--cre-border);border-right:0;">
                <span class="input-group-text" style="background:var(--cre-bg);border:1.5px solid var(--cre-border);border-left:0;border-radius:0 var(--radius-sm) var(--radius-sm) 0;">DH</span>
              </div>
              @error('price_per_day')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
            </div>

            {{-- Seats + Transmission --}}
            <div class="col-md-6">
              <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">Nombre de places *</label>
              <input type="number" name="seats" class="form-control @error('seats') is-invalid @enderror"
                     min="2" max="15" value="{{ old('seats',$vehicle->seats ?? 5) }}" required
                     style="border-radius:var(--radius-sm);border:1.5px solid var(--cre-border);">
              @error('seats')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">Transmission *</label>
              <select name="transmission" class="form-select" style="border-radius:var(--radius-sm);border:1.5px solid var(--cre-border);">
                <option value="Manuelle"    {{ old('transmission',$vehicle->transmission)==='Manuelle'    ? 'selected' : '' }}>Manuelle</option>
                <option value="Automatique" {{ old('transmission',$vehicle->transmission)==='Automatique' ? 'selected' : '' }}>Automatique</option>
              </select>
            </div>

            {{-- Options --}}
            <div class="col-md-6">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="ac" id="ac" value="1"
                       {{ old('ac', $vehicle->ac ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="ac">Climatisation</label>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="available" id="available" value="1"
                       {{ old('available', $vehicle->available ?? true) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="available">Disponible à la location</label>
              </div>
            </div>

            {{-- Image --}}
            <div class="col-12">
              <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">Image du véhicule</label>
              <div class="row g-2">
                <div class="col-md-6">
                  <label class="form-label text-muted" style="font-size:.8rem;">Upload fichier (JPG/PNG)</label>
                  <input type="file" name="image_file" class="form-control @error('image_file') is-invalid @enderror"
                         accept="image/jpeg,image/png,image/webp"
                         style="border-radius:var(--radius-sm);border:1.5px solid var(--cre-border);">
                  @error('image_file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                  <label class="form-label text-muted" style="font-size:.8rem;">— ou — URL d'image</label>
                  <input type="url" name="image_url" class="form-control"
                         placeholder="https://…" value="{{ old('image_url', str_starts_with($vehicle->image ?? '', 'http') ? $vehicle->image : '') }}"
                         style="border-radius:var(--radius-sm);border:1.5px solid var(--cre-border);">
                </div>
              </div>
              @if($vehicle->exists && $vehicle->image)
              <div class="mt-2">
                <img src="{{ $vehicle->image_url }}" alt="preview" style="height:100px;border-radius:var(--radius-sm);object-fit:cover;">
                <div class="text-muted" style="font-size:.75rem;margin-top:.3rem;">Image actuelle</div>
              </div>
              @endif
            </div>

            {{-- Description --}}
            <div class="col-12">
              <label class="form-label fw-semibold" style="font-size:.8rem;text-transform:uppercase;letter-spacing:.06em;color:var(--cre-muted);">Description (optionnel)</label>
              <textarea name="description" class="form-control" rows="3" placeholder="Description du véhicule, équipements, etc."
                        style="border-radius:var(--radius-sm);border:1.5px solid var(--cre-border);">{{ old('description',$vehicle->description) }}</textarea>
            </div>

            {{-- Submit --}}
            <div class="col-12 d-flex gap-3 mt-2">
              <button type="submit" class="btn btn-pardo-orange flex-fill btn-lg"
                      style="font-family:'Barlow Condensed',sans-serif;font-size:1.05rem;font-weight:700;letter-spacing:.05em;">
                <i class="bi bi-check-lg me-2"></i>{{ $vehicle->exists ? 'ENREGISTRER LES MODIFICATIONS' : 'AJOUTER LE VÉHICULE' }}
              </button>
              <a href="{{ route('admin.vehicles') }}" class="btn btn-pardo-outline btn-lg">Annuler</a>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
