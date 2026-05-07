@extends('layouts.admin')
@section('title','Réservations')
@section('page-title','Réservations')

@section('content')
{{-- Filters --}}
<div class="d-flex gap-2 flex-wrap mb-4 align-items-center">
  @foreach([''=>'Toutes','pending'=>'En attente','confirmed'=>'Confirmées','rejected'=>'Refusées','completed'=>'Terminées'] as $val=>$label)
  <a href="{{ route('admin.reservations', array_merge(request()->except('page'), ['status'=>$val])) }}"
     class="btn btn-sm {{ request('status')===$val ? 'btn-dark' : 'btn-pardo-outline' }}"
     style="border-radius:50px;font-weight:600;">
    {{ $label }}
  </a>
  @endforeach

  <form action="{{ route('admin.reservations') }}" method="GET" class="ms-auto d-flex gap-2">
    @if(request('status'))<input type="hidden" name="status" value="{{ request('status') }}">@endif
    <input type="text" name="search" class="form-control form-control-sm" placeholder="Rechercher…" value="{{ request('search') }}" style="border-radius:50px;min-width:200px;">
    <button type="submit" class="btn btn-sm btn-pardo-primary" style="border-radius:50px;"><i class="bi bi-search"></i></button>
  </form>
</div>

<div class="table-card">
  <div class="table-card-header">
    <span class="table-card-title">{{ $reservations->total() }} réservation(s)</span>
  </div>
  <div class="table-responsive">
    <table class="table table-pardo mb-0">
      <thead>
        <tr>
          <th>N°</th><th>Client</th><th>Véhicule</th><th>Dates</th>
          <th>Total</th><th>Acompte</th><th>Statut</th><th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($reservations as $res)
        <tr>
          <td><span class="res-id">{{ $res->reservation_number }}</span><br><span class="text-muted" style="font-size:.7rem;">{{ $res->created_at->format('d/m/Y') }}</span></td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <span class="cl-avatar">{{ strtoupper(substr($res->user->name,0,1)) }}</span>
              <div>
                <div class="fw-semibold" style="font-size:.875rem;color:var(--cre-dark);">{{ $res->user->name }}</div>
                <div class="text-muted" style="font-size:.75rem;">{{ $res->user->phone }}</div>
              </div>
            </div>
          </td>
          <td>
            <div class="fw-semibold" style="font-size:.875rem;">{{ $res->vehicle->name }}</div>
            <div class="text-muted" style="font-size:.75rem;">{{ $res->vehicle->category }}</div>
          </td>
          <td style="font-size:.8rem;">
            <i class="bi bi-calendar me-1" style="color:var(--cre-blue);"></i>{{ $res->start_date->format('d/m/Y') }}<br>
            <i class="bi bi-calendar-check me-1" style="color:var(--cre-orange);"></i>{{ $res->end_date->format('d/m/Y') }}<br>
            <span class="text-muted">{{ $res->days }} jour(s)</span>
          </td>
          <td><strong style="color:var(--cre-blue);">{{ number_format($res->total_price,0,',',' ') }} DH</strong></td>
          <td style="color:var(--cre-orange);font-weight:600;">{{ number_format($res->acompte,0,',',' ') }} DH</td>
          <td><span class="status-pill badge-{{ $res->status }}">{{ $res->status_label }}</span></td>
          <td>
            <div class="d-flex gap-1 flex-wrap">
              @if($res->status === 'pending')
              <form action="{{ route('admin.reservations.status',$res) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="confirmed">
                <button type="submit" class="btn btn-sm btn-success" title="Confirmer"><i class="bi bi-check-lg"></i></button>
              </form>
              <form action="{{ route('admin.reservations.status',$res) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="rejected">
                <button type="submit" class="btn btn-sm btn-danger" title="Refuser"><i class="bi bi-x-lg"></i></button>
              </form>
              @endif
              @if($res->status === 'confirmed')
              <form action="{{ route('admin.reservations.status',$res) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <input type="hidden" name="status" value="completed">
                <button type="submit" class="btn btn-sm btn-info text-white" title="Marquer terminée">
                  <i class="bi bi-flag"></i>
                </button>
              </form>
              @endif
              {{-- Note modal trigger --}}
              <button type="button" class="btn btn-sm btn-pardo-outline" title="Ajouter note"
                      data-bs-toggle="modal" data-bs-target="#noteModal{{ $res->id }}">
                <i class="bi bi-chat-left-text"></i>
              </button>
              <a href="{{ route('admin.voucher',$res) }}" class="btn btn-sm btn-pardo-outline" title="Bon">
                <i class="bi bi-file-earmark-text"></i>
              </a>
            </div>
          </td>
        </tr>

        {{-- Note Modal --}}
        <div class="modal fade" id="noteModal{{ $res->id }}" tabindex="-1">
          <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius:var(--radius-lg);border:none;">
              <div class="modal-header border-0 pb-0">
                <h5 class="modal-title" style="font-family:'Barlow Condensed',sans-serif;font-weight:700;">Note — {{ $res->reservation_number }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
              </div>
              <form action="{{ route('admin.reservations.status',$res) }}" method="POST">
                @csrf @method('PATCH')
                <div class="modal-body pt-2">
                  <input type="hidden" name="status" value="{{ $res->status }}">
                  <textarea name="admin_note" class="form-control" rows="4" placeholder="Note interne ou message au client…" style="border-radius:var(--radius-sm);">{{ $res->admin_note }}</textarea>
                </div>
                <div class="modal-footer border-0 pt-0">
                  <button type="button" class="btn btn-pardo-outline btn-sm" data-bs-dismiss="modal">Annuler</button>
                  <button type="submit" class="btn btn-pardo-primary btn-sm">Enregistrer</button>
                </div>
              </form>
            </div>
          </div>
        </div>

        @empty
        <tr><td colspan="8" class="text-center py-5 text-muted">Aucune réservation trouvée.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
  @if($reservations->hasPages())
  <div class="p-3 border-top">
    {{ $reservations->withQueryString()->links('pagination::bootstrap-5') }}
  </div>
  @endif
</div>
@endsection
