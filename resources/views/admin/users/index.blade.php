@extends('layouts.admin')
@section('title','Utilisateurs')
@section('page-title','Utilisateurs')

@section('content')
<div class="table-card">
  <div class="table-card-header">
    <span class="table-card-title">
      <i class="bi bi-people me-2" style="color:var(--cre-blue);"></i>
      {{ $users->count() }} client(s) enregistré(s)
    </span>
  </div>
  <div class="table-responsive">
    <table class="table table-pardo mb-0">
      <thead>
        <tr>
          <th>#</th>
          <th>Nom</th>
          <th>Téléphone</th>
          <th>Réservations</th>
          <th>Inscrit le</th>
          <th>Statut</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
        <tr>
          <td><span class="res-id">{{ str_pad($user->id,4,'0',STR_PAD_LEFT) }}</span></td>
          <td>
            <div class="d-flex align-items-center gap-2">
              <span class="cl-avatar">{{ strtoupper(substr($user->name,0,1)) }}</span>
              <span class="fw-semibold" style="color:var(--cre-dark);">{{ $user->name }}</span>
            </div>
          </td>
          <td>
            <a href="tel:{{ $user->phone }}" style="color:var(--cre-text);text-decoration:none;">
              <i class="bi bi-telephone me-1" style="color:var(--cre-blue);"></i>{{ $user->phone }}
            </a>
          </td>
          <td>
            <span class="badge" style="background:rgba(26,86,255,.1);color:var(--cre-blue);font-weight:600;border-radius:50px;padding:.3rem .75rem;">
              {{ $user->reservations_count }}
            </span>
          </td>
          <td style="font-size:.8rem;color:var(--cre-muted);">{{ $user->created_at->format('d/m/Y') }}</td>
          <td>
            @if($user->blocked)
            <span class="status-pill badge-rejected"><i class="bi bi-lock me-1"></i>Bloqué</span>
            @else
            <span class="status-pill badge-confirmed"><i class="bi bi-check-circle me-1"></i>Actif</span>
            @endif
          </td>
          <td>
            <div class="d-flex gap-2">
              <form action="{{ route('admin.users.toggle-block',$user) }}" method="POST" class="d-inline">
                @csrf @method('PATCH')
                <button type="submit"
                        class="btn btn-sm {{ $user->blocked ? 'btn-success' : 'btn-danger' }}"
                        onclick="return confirm('{{ $user->blocked ? 'Débloquer' : 'Bloquer' }} cet utilisateur ?')">
                  @if($user->blocked)
                    <i class="bi bi-unlock me-1"></i>Débloquer
                  @else
                    <i class="bi bi-lock me-1"></i>Bloquer
                  @endif
                </button>
              </form>
              <form action="{{ route('admin.users.delete',$user) }}" method="POST" class="d-inline">
                @csrf @method('DELETE')
                <button type="submit"
                        class="btn btn-sm btn-outline-danger"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur et toutes ses données associées (réservations, avis, etc.) ? Cette action est irréversible.')">
                  <i class="bi bi-trash"></i>
                </button>
              </form>
            </div>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="7" class="text-center py-5 text-muted">
            <i class="bi bi-people" style="font-size:3rem;opacity:.3;"></i>
            <p class="mt-3">Aucun utilisateur inscrit.</p>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection
