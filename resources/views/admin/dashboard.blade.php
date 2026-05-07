@extends('layouts.admin')
@section('title','Tableau de bord')
@section('title','Tableau de bord')
@section('page-title','Tableau de bord')

@section('extra-css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<style>
@keyframes pulse { 0% { opacity: 1; transform: scale(1); } 50% { opacity: 0.5; transform: scale(1.5); } 100% { opacity: 1; transform: scale(1); } }
.car-list-item { display:flex; align-items:center; gap:1rem; padding:0.75rem; border-radius:var(--radius-sm); border:1px solid var(--cre-border); background:var(--cre-bg); }
.car-list-img { width:60px; height:60px; border-radius:8px; object-fit:cover; }
</style>
@endsection

@section('content')
{{-- Stats --}}
<div class="row g-3 mb-4">
  @foreach([
    ['icon'=>'bi-calendar3','label'=>'Total réservations','value'=>$stats['total']],
    ['icon'=>'bi-clock','label'=>'En attente','value'=>$stats['pending']],
    ['icon'=>'bi-check-circle','label'=>'Confirmées','value'=>$stats['confirmed']],
    ['icon'=>'bi-currency-exchange','label'=>'Revenu confirmé','value'=>number_format($stats['revenue'],0,',',' ').' DH'],
    ['icon'=>'bi-car-front','label'=>'Véhicules','value'=>$stats['vehicles']],
    ['icon'=>'bi-people','label'=>'Utilisateurs','value'=>$stats['users']],
  ] as $s)
  <div class="col-6 col-md-4 col-xl-2">
    <div class="stat-card d-flex align-items-center gap-3 h-100">
      <div class="stat-icon" style="background:#f1f5f9;color:#000;">
        <i class="bi {{ $s['icon'] }}"></i>
      </div>
      <div>
        <div class="stat-label">{{ $s['label'] }}</div>
        <div class="stat-value" style="white-space:nowrap;font-size:1.6rem;">{{ $s['value'] }}</div>
      </div>
    </div>
  </div>
  @endforeach
</div>

<div class="row g-4 mb-4">
  {{-- 1. STATISTICS GRAPH --}}
  <div class="col-lg-8">
    <div class="table-card p-4 h-100">
      <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <h3 class="table-card-title m-0"><i class="bi bi-bar-chart-fill me-2" style="color:var(--cre-blue);"></i> Statistiques des Ventes</h3>
        <div class="btn-group" id="chartFilters" role="group">
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadStats('today', this)">Aujourd'hui</button>
          <button type="button" class="btn btn-sm btn-outline-secondary" onclick="loadStats('week', this)">Semaine</button>
          <button type="button" class="btn btn-sm btn-outline-primary active" onclick="loadStats('month', this)">Mois</button>
        </div>
      </div>
      <div style="height:320px;position:relative;">
        <canvas id="statsChart"></canvas>
      </div>
    </div>
  </div>

  {{-- 2. MOST PREFERRED CAR --}}
  <div class="col-lg-4">
    <div class="table-card p-4 h-100 d-flex flex-col">
      <h3 class="table-card-title mb-4"><i class="bi bi-trophy-fill me-2" style="color:#fbbf24;"></i> Véhicule Préféré</h3>
      <div class="text-center py-4" id="topCarLoader">
        <div class="spinner-border" style="color:var(--cre-blue);" role="status"></div>
      </div>
      <div id="topCarsContent" class="d-none">
        <!-- Main Top Car -->
        <div id="mainTopCar" class="mb-4 text-center"></div>
        <!-- List of other top cars -->
        <div id="otherTopCars" class="d-flex flex-column gap-3"></div>
      </div>
    </div>
  </div>
</div>

{{-- 3. MAP TRACKING --}}
<div class="row mb-4">
  <div class="col-12">
    <div class="table-card p-4">
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="table-card-title m-0"><i class="bi bi-geo-alt-fill me-2" style="color:#ef4444;"></i> Suivi des Véhicules en Circulation</h3>
        <span class="badge" style="background:#fef2f2;color:#ef4444;border:1px solid #fecaca;padding:.4rem .8rem;font-size:.75rem;">
          <i class="bi bi-circle-fill me-1" style="font-size:.5rem;animation:pulse 2s infinite;"></i> En direct
        </span>
      </div>
      <div id="trackingMap" style="height:450px;border-radius:var(--radius-sm);border:1px solid var(--cre-border);z-index:1;"></div>
    </div>
  </div>
</div>

{{-- Pending reservations --}}
@if($pending->count())
<div class="table-card">
  <div class="table-card-header">
    <span class="table-card-title">
      <i class="bi bi-clock me-2" style="color:var(--cre-orange);"></i>
      Réservations en attente ({{ $pending->count() }})
    </span>
    <a href="{{ route('admin.reservations', ['status'=>'pending']) }}" class="btn-pardo-outline btn btn-sm">Voir toutes</a>
  </div>
  <table class="table table-pardo mb-0">
    <thead>
      <tr>
        <th>N° Réservation</th>
        <th>Client</th>
        <th>Véhicule</th>
        <th>Dates</th>
        <th>Total</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pending as $res)
      <tr>
        <td><span class="res-id">{{ $res->reservation_number }}</span></td>
        <td>
          <div class="d-flex align-items-center gap-2">
            <span class="cl-avatar">{{ strtoupper(substr($res->user->name,0,1)) }}</span>
            <div>
              <div class="fw-semibold" style="color:var(--cre-dark);">{{ $res->user->name }}</div>
              <div class="text-muted" style="font-size:.75rem;">{{ $res->user->phone }}</div>
            </div>
          </div>
        </td>
        <td>{{ $res->vehicle->name }}</td>
        <td style="font-size:.8rem;">
          {{ $res->start_date->format('d/m/Y') }} → {{ $res->end_date->format('d/m/Y') }}<br>
          <span class="text-muted">{{ $res->days }} jour(s)</span>
        </td>
        <td><strong style="color:var(--cre-blue);">{{ number_format($res->total_price,0,',',' ') }} DH</strong></td>
        <td>
          <div class="d-flex gap-1 flex-wrap">
            <form action="{{ route('admin.reservations.status', $res) }}" method="POST" class="d-inline">
              @csrf @method('PATCH')
              <input type="hidden" name="status" value="confirmed">
              <button type="submit" class="btn btn-sm btn-success">
                <i class="bi bi-check-lg"></i> Confirmer
              </button>
            </form>
            <form action="{{ route('admin.reservations.status', $res) }}" method="POST" class="d-inline">
              @csrf @method('PATCH')
              <input type="hidden" name="status" value="rejected">
              <button type="submit" class="btn btn-sm btn-danger">
                <i class="bi bi-x-lg"></i> Refuser
              </button>
            </form>
            <a href="{{ route('admin.voucher', $res) }}" class="btn btn-sm btn-pardo-outline">
              <i class="bi bi-file-earmark-text"></i>
            </a>
          </div>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@else
<div class="text-center py-5" style="background:#fff;border-radius:var(--radius-lg);border:1px solid var(--cre-border);">
  <i class="bi bi-check-circle" style="font-size:3rem;color:#16a34a;opacity:.5;"></i>
  <p class="mt-3 text-muted">Aucune réservation en attente. Tout est à jour !</p>
</div>
@endif
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
// 1. Chart.js Implementation
let statsChart = null;

function loadStats(filter, btn) {
    if(btn) {
        document.querySelectorAll('#chartFilters button').forEach(b => {
           b.classList.remove('btn-outline-primary', 'active');
           b.classList.add('btn-outline-secondary');
        });
        btn.classList.add('btn-outline-primary', 'active');
        btn.classList.remove('btn-outline-secondary');
    }

    axios.get(`/api/stats?filter=${filter}`).then(res => {
        const data = res.data.chart;
        if(statsChart) statsChart.destroy();
        
        const ctx = document.getElementById('statsChart').getContext('2d');
        statsChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: Object.keys(data.labels).length ? data.labels : ['Aucune donnée'],
                datasets: [
                    {
                        label: 'Revenus (DH)',
                        data: Object.keys(data.revenues).length ? data.revenues : [0],
                        borderColor: '#1a56ff',
                        backgroundColor: 'rgba(26, 86, 255, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                        yAxisID: 'y'
                    },
                    {
                        label: 'Réservations',
                        data: Object.keys(data.tickets).length ? data.tickets : [0],
                        borderColor: '#f97316',
                        backgroundColor: 'rgba(249, 115, 22, 0.1)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false,
                        tension: 0.3,
                        yAxisID: 'y1'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { type: 'linear', position: 'left', beginAtZero: true },
                    y1: { type: 'linear', position: 'right', beginAtZero: true, grid: { drawOnChartArea: false } }
                }
            }
        });
    });
}

// 2. Most Preferred Car
function loadTopCars() {
    axios.get('/api/cars').then(res => {
        document.getElementById('topCarLoader').classList.add('d-none');
        const content = document.getElementById('topCarsContent');
        content.classList.remove('d-none');
        
        const cars = res.data;
        if(cars.length === 0) {
            content.innerHTML = '<p class="text-muted text-center py-3">Aucun véhicule réservé.</p>';
            return;
        }

        const top = cars[0];
        document.getElementById('mainTopCar').innerHTML = `
            <img src="${top.image.startsWith('http') ? top.image : '/storage/'+top.image}" alt="${top.name}" style="width:100%;height:140px;object-fit:cover;border-radius:var(--radius-sm);opacity:0.9;margin-bottom:1rem;">
            <div style="font-family:'Barlow Condensed',sans-serif;font-size:1.4rem;font-weight:800;color:var(--cre-dark);">${top.name}</div>
            <div style="font-size:0.8rem;color:var(--cre-muted);margin-bottom:0.5rem;text-transform:uppercase;letter-spacing:0.05em;">${top.category}</div>
            <div style="color:#fbbf24;font-size:1.1rem;margin-bottom:0.5rem;">★★★★★ <span style="font-size:0.8rem;color:var(--cre-text);">(${top.reservations_count} réservations)</span></div>
        `;

        let othersHTML = '';
        for(let i = 1; i < cars.length; i++) {
            const c = cars[i];
            othersHTML += `
            <div class="car-list-item">
                <img src="${c.image.startsWith('http') ? c.image : '/storage/'+c.image}" alt="${c.name}" class="car-list-img">
                <div>
                    <div style="font-weight:700;color:var(--cre-dark);line-height:1.2;">#${i+1} - ${c.name}</div>
                    <div style="font-size:0.75rem;color:var(--cre-muted);">${c.category}</div>
                    <div style="font-size:0.75rem;color:#fbbf24;font-weight:600;"><i class="bi bi-calendar-check me-1"></i>${c.reservations_count} fois</div>
                </div>
            </div>`;
        }
        document.getElementById('otherTopCars').innerHTML = othersHTML;
    });
}

// 3. Map Tracking
function initMap() {
    // Center of Morocco
    const map = L.map('trackingMap').setView([31.7917, -7.0926], 6);
    
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '© OpenStreetMap contributors',
        maxZoom: 18,
    }).addTo(map);

    axios.get('/api/tracking').then(res => {
        const markers = res.data;
        markers.forEach(car => {
            const color = car.status === 'Busy' ? '#ef4444' : '#16a34a';
            
            // Create a custom icon
            const iconHtml = `
                <div style="background-color: ${color}; width: 14px; height: 14px; border-radius: 50%; border: 3px solid white; box-shadow: 0 0 5px rgba(0,0,0,0.5);"></div>
            `;
            const customIcon = L.divIcon({ html: iconHtml, className: '', iconSize: [14, 14], iconAnchor: [7, 7] });

            const marker = L.marker([car.latitude, car.longitude], { icon: customIcon }).addTo(map);
            
            const badgeClass = car.status === 'Busy' ? 'bg-danger' : 'bg-success';
            const statusLabel = car.status === 'Busy' ? 'En location' : 'Disponible';
            
            marker.bindPopup(`
                <div style="text-align:center;min-width:120px;">
                    <strong style="display:block;margin-bottom:5px;font-family:'Barlow Condensed',sans-serif;font-size:1.1rem;">${car.car_name}</strong>
                    <div style="font-size:0.75rem;color:#666;margin-bottom:5px;">${car.car_model}</div>
                    <span class="badge ${badgeClass}" style="font-size:0.7rem;">${statusLabel}</span>
                </div>
            `);
        });
    });
}

document.addEventListener('DOMContentLoaded', () => {
    loadStats('month', null);
    loadTopCars();
    initMap();
});
</script>
@endsection
