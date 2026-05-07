<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bon de Réservation — {{ $reservation->reservation_number }}</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@700;800&family=DM+Sans:wght@400;500;600&display=swap');
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DM Sans',sans-serif; color:#1e293b; background:#fff; padding:2rem; font-size:14px; }
        .header { display:flex; justify-content:space-between; align-items:center;
            border-bottom:3px solid #1a56ff; padding-bottom:1.5rem; margin-bottom:2rem; }
        .logo { font-family:'Barlow Condensed',sans-serif; }
        .logo-main { font-size:2rem; font-weight:800; color:#0f172a; letter-spacing:.03em; }
        .logo-sub  { font-size:1rem; color:#f97316; font-weight:700; letter-spacing:.1em; }
        .doc-title { text-align:right; }
        .doc-title h2 { font-family:'Barlow Condensed',sans-serif; font-size:1.8rem; font-weight:800; color:#1a56ff; }
        .doc-num   { color:#64748b; font-size:.9rem; }

        .status-banner { background:#f0fdf4; border:2px solid #16a34a; border-radius:8px;
            padding:1rem 1.5rem; margin-bottom:2rem; display:flex; align-items:center; gap:1rem; }
        .status-banner .check { font-size:2rem; color:#16a34a; }
        .status-banner strong { color:#15803d; font-size:1.1rem; }

        .grid-2 { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:2rem; }
        .info-box { background:#f8faff; border-radius:10px; padding:1.25rem; border:1px solid #e2e8f0; }
        .info-box h4 { font-size:.75rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.08em; color:#64748b; margin-bottom:.75rem; }
        .info-row { display:flex; justify-content:space-between; margin-bottom:.4rem; }
        .info-row .label { color:#64748b; }
        .info-row .val   { font-weight:600; text-align:right; }

        .vehicle-section { background:#eff6ff; border-radius:10px; padding:1.5rem;
            border:1px solid #bfdbfe; margin-bottom:2rem; }
        .vehicle-section h4 { font-size:.75rem; font-weight:700; text-transform:uppercase;
            letter-spacing:.08em; color:#1d4ed8; margin-bottom:1rem; }
        .vehicle-name { font-family:'Barlow Condensed',sans-serif; font-size:1.8rem; font-weight:800; color:#1e293b; }
        .vehicle-specs { display:flex; gap:1rem; margin-top:.5rem; flex-wrap:wrap; }
        .spec-tag { background:#fff; border:1px solid #bfdbfe; border-radius:50px;
            padding:.25rem .75rem; font-size:.8rem; color:#1d4ed8; font-weight:500; }

        .total-box { background:linear-gradient(135deg,#1a56ff,#4f78ff); color:#fff;
            border-radius:12px; padding:1.5rem; text-align:center; margin-bottom:2rem; }
        .total-box .amount { font-size:2.5rem; font-weight:800; }
        .total-box .label  { font-size:.85rem; opacity:.8; margin-top:.25rem; }

        .footer-note { text-align:center; color:#94a3b8; font-size:.75rem;
            border-top:1px solid #e2e8f0; padding-top:1.5rem; }

        .print-btn { position:fixed; top:1rem; right:1rem; background:#1a56ff; color:#fff;
            border:none; border-radius:8px; padding:.75rem 1.5rem; font-family:'DM Sans',sans-serif;
            font-weight:600; cursor:pointer; font-size:.9rem; display:flex; align-items:center; gap:.5rem; }
        @media print {
            .print-btn { display:none; }
            body { padding:1rem; }
        }
    </style>
</head>
<body>

<button class="print-btn" onclick="window.print()">🖨 Imprimer / Sauvegarder PDF</button>

{{-- HEADER --}}
<div class="header">
    <div class="logo">
        <img src="{{ asset('images/logo.png') }}" style="height: 45px;" alt="PARDOX">
    </div>
    <div class="doc-title">
        <h2>BON DE RÉSERVATION</h2>
        <div class="doc-num">N° {{ $reservation->reservation_number }}</div>
        <div class="doc-num">Émis le {{ now()->format('d/m/Y à H:i') }}</div>
    </div>
</div>

{{-- STATUS --}}
<div class="status-banner">
    <span class="check">✓</span>
    <div>
        <strong>Réservation enregistrée</strong>
        <div style="font-size:.85rem;color:#166534">Statut : {{ $reservation->status }}</div>
    </div>
</div>

{{-- VEHICLE --}}
<div class="vehicle-section">
    <h4>🚗 Véhicule réservé</h4>
    <div class="vehicle-name">{{ $reservation->vehicle->name }}</div>
    <div class="vehicle-specs">
        <span class="spec-tag">{{ $reservation->vehicle->category }}</span>
        <span class="spec-tag">{{ $reservation->vehicle->seats }} places</span>
        <span class="spec-tag">{{ $reservation->vehicle->transmission }}</span>
        @if($reservation->vehicle->has_ac)<span class="spec-tag">Climatisation</span>@endif
    </div>
</div>

{{-- INFO GRID --}}
<div class="grid-2">
    <div class="info-box">
        <h4>👤 Informations client</h4>
        <div class="info-row"><span class="label">Nom</span><span class="val">{{ $reservation->client_name }}</span></div>
        <div class="info-row"><span class="label">Email</span><span class="val">{{ $reservation->client_email }}</span></div>
        @if($reservation->client_phone)
        <div class="info-row"><span class="label">Téléphone</span><span class="val">{{ $reservation->client_phone }}</span></div>
        @endif
        <div class="info-row"><span class="label">Type</span><span class="val">{{ ucfirst($reservation->type) }}</span></div>
    </div>
    <div class="info-box">
        <h4>📅 Dates & Lieu</h4>
        <div class="info-row"><span class="label">Prise en charge</span><span class="val">{{ \Carbon\Carbon::parse($reservation->pickup_date)->format('d/m/Y') }}</span></div>
        <div class="info-row"><span class="label">Retour</span><span class="val">{{ \Carbon\Carbon::parse($reservation->return_date)->format('d/m/Y') }}</span></div>
        <div class="info-row"><span class="label">Durée</span><span class="val">{{ $reservation->days }} jour(s)</span></div>
        <div class="info-row"><span class="label">Lieu</span><span class="val">{{ $reservation->pickup_location }}</span></div>
    </div>
</div>

{{-- PRICING --}}
<div class="grid-2">
    <div class="info-box">
        <h4>💶 Détail du prix</h4>
        <div class="info-row"><span class="label">Prix/jour</span><span class="val">€{{ number_format($reservation->vehicle->price_per_day,2,',','') }}</span></div>
        <div class="info-row"><span class="label">Nombre de jours</span><span class="val">{{ $reservation->days }}</span></div>
        <div class="info-row" style="border-top:1px solid #e2e8f0;padding-top:.5rem;margin-top:.5rem">
            <span class="label"><strong>Total TTC</strong></span>
            <span class="val"><strong>€{{ number_format($reservation->total_price,2,',','') }}</strong></span>
        </div>
    </div>
    <div class="total-box">
        <div class="label">MONTANT TOTAL</div>
        <div class="amount">€{{ number_format($reservation->total_price,2,',','') }}</div>
        <div class="label">Assurance tous risques incluse</div>
    </div>
</div>

@if($reservation->notes)
<div class="info-box" style="margin-bottom:2rem">
    <h4>📝 Notes</h4>
    <p style="color:#475569">{{ $reservation->notes }}</p>
</div>
@endif

{{-- FOOTER --}}
<div class="footer-note">
    <p><strong>PARDOX</strong> — La location de voitures premium, simplifiée.</p>
    <p style="margin-top:.5rem">Ce document fait office de confirmation de réservation. Présentez-le lors de la prise en charge du véhicule.</p>
    <p style="margin-top:.5rem">Annulation gratuite jusqu'à 48h avant la prise en charge • contact@pardox.ma</p>
</div>

</body>
</html>
