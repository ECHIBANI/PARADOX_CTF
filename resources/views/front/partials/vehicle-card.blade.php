<div class="vehicle-card bg-white h-100">
  <div class="vehicle-img-wrap">
    <img src="{{ $vehicle->image_url }}" alt="{{ $vehicle->name }}" class="vehicle-img" loading="lazy">
    <span class="cat-badge">{{ $vehicle->category }}</span>
    @php
      $isFavorite = auth()->check() && auth()->user()->favorites()->where('vehicle_id', $vehicle->id)->exists();
    @endphp
    <button type="button" class="btn position-absolute top-0 end-0 m-2 p-1 rounded-circle fav-btn" 
            style="width:34px;height:34px;background:rgba(255,255,255,.9);line-height:1;" 
            title="Favori" 
            data-id="{{ $vehicle->id }}" 
            data-url="{{ route('client.favorites.toggle', $vehicle->id) }}"
            onclick="toggleFavorite(this, {{ auth()->check() ? 'true' : 'false' }})">
      <i class="bi {{ $isFavorite ? 'bi-heart-fill' : 'bi-heart' }} fav-icon" style="color:{{ $isFavorite ? '#ef4444' : '#94a3b8' }};"></i>
    </button>
  </div>
  <div class="p-3">
    <div class="d-flex justify-content-between align-items-start mb-2">
      <div class="vehicle-name">{{ $vehicle->name }}</div>
      <div class="text-end">
        <div class="price-amount">{{ number_format($vehicle->price_per_day,0,',',' ') }} DH</div>
        <div style="font-size:.72rem;color:var(--cre-muted);">/jour</div>
      </div>
    </div>
    <div class="d-flex flex-wrap gap-1 mb-3">
      <span class="feat-tag"><i class="bi bi-people me-1"></i>{{ $vehicle->seats }} places</span>
      <span class="feat-tag"><i class="bi bi-gear me-1"></i>{{ $vehicle->transmission }}</span>
      @if($vehicle->ac)<span class="feat-tag"><i class="bi bi-snow me-1"></i>Clim</span>@endif
    </div>
    <a href="{{ route('vehicles.show', $vehicle) }}" class="btn btn-pardo-primary w-100" style="font-family:'Barlow Condensed',sans-serif;font-size:.95rem;font-weight:700;letter-spacing:.05em;">
      SÉLECTIONNER
    </a>
  </div>
</div>
