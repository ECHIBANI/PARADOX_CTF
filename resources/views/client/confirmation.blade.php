@extends('layouts.app')
@section('title','Réservation Confirmée')
@section('content')
<div style="min-height:100vh;background:var(--dark);display:flex;align-items:center;padding:6rem 0 3rem">
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-6">
        <div style="text-align:center;margin-bottom:2.5rem">
          <div style="width:80px;height:80px;background:rgba(34,197,94,.15);border:2px solid rgba(34,197,94,.3);border-radius:50%;display:inline-flex;align-items:center;justify-content:center;font-size:2rem;color:#4ade80;margin-bottom:1.5rem">
            <i class="bi bi-check-lg"></i>
          </div>
          <h1 style="font-family:'Syne',sans-serif;font-weight:800;font-size:2rem;color:var(--white)">Réservation Confirmée !</h1>
          <p style="color:var(--white40)">Votre demande a bien été enregistrée.</p>
        </div>

        <div style="background:var(--dark2);border:1px solid var(--border);border-radius:20px;padding:2rem">
          <div style="text-align:center;background:rgba(201,169,110,.08);border:1px solid rgba(201,169,110,.2);border-radius:12px;padding:1.25rem;margin-bottom:1.75rem">
            <div style="font-size:.65rem;letter-spacing:.15em;text-transform:uppercase;color:var(--white40)">Numéro de réservation</div>
            <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:1.8rem;color:var(--gold);letter-spacing:.05em">{{ $reservation->numero }}</div>
          </div>

          <div class="row g-3 mb-4">
            @foreach([
              ['label'=>'Véhicule','val'=>$reservation->vehicle->name,'icon'=>'car-front'],
              ['label'=>'Client','val'=>$reservation->client_name,'icon'=>'person'],
              ['label'=>'Prise en charge','val'=>$reservation->date_debut->format('d/m/Y').' — '.$reservation->ville_prise_en_charge,'icon'=>'calendar-event'],
              ['label'=>'Retour','val'=>$reservation->date_fin->format('d/m/Y').' ('.$reservation->jours.' jours)','icon'=>'calendar-check'],
            ] as $row)
            <div class="col-6">
              <div style="background:var(--dark3);border:1px solid var(--border);border-radius:12px;padding:.9rem">
                <div style="font-size:.62rem;letter-spacing:.1em;text-transform:uppercase;color:var(--white40);margin-bottom:.3rem"><i class="bi bi-{{ $row['icon'] }} me-1" style="color:var(--gold)"></i>{{ $row['label'] }}</div>
                <div style="font-weight:600;font-size:.85rem;color:var(--white)">{{ $row['val'] }}</div>
              </div>
            </div>
            @endforeach
          </div>

          <div style="background:linear-gradient(135deg,rgba(201,169,110,.15),rgba(201,169,110,.05));border:1px solid rgba(201,169,110,.25);border-radius:14px;padding:1.25rem;text-align:center;margin-bottom:1.75rem">
            <div style="font-size:.65rem;letter-spacing:.12em;text-transform:uppercase;color:var(--white40)">Total à régler</div>
            <div style="font-family:'Syne',sans-serif;font-weight:800;font-size:2.2rem;color:var(--gold)">{{ number_format($reservation->total_mad,2) }} DH</div>
            <div style="font-size:.75rem;color:var(--white40);margin-top:.2rem">Statut : <span style="color:#fbbf24;font-weight:600">{{ ucfirst(str_replace('_',' ',$reservation->statut)) }}</span></div>
          </div>

          <div class="d-flex flex-column gap-2">
            <a href="{{ route('reservations.pdf',$reservation->id) }}" target="_blank" class="btn-gold justify-content-center" style="padding:.9rem;text-decoration:none">
              <i class="bi bi-file-earmark-pdf"></i> Télécharger le bon PDF
            </a>
            <a href="{{ route('voitures') }}" class="btn-dark-outline justify-content-center" style="text-decoration:none">
              <i class="bi bi-car-front"></i> Voir d'autres voitures
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
