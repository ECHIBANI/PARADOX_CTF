@extends('layouts.admin')
@section('title', 'Commentaires Clients')

@section('content')

<div class="d-flex align-items-center justify-content-between mb-4">
    <h1 class="dashboard-title mb-0">COMMENTAIRES CLIENTS</h1>
    <span class="badge bg-warning text-dark fs-6">{{ $comments->total() }} avis</span>
</div>

<div class="admin-table">
    <div class="table-card-header">
        <h6><i class="bi bi-chat-dots me-2 text-warning"></i>Tous les avis publiés</h6>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>Client</th><th>Véhicule</th><th>Note</th><th>Commentaire</th><th>Date</th><th>Action</th></tr>
            </thead>
            <tbody>
                @forelse($comments as $c)
                <tr>
                    <td><strong>{{ $c->author_name }}</strong></td>
                    <td>{{ $c->vehicle?->name ?? '—' }}</td>
                    <td>
                        @for($i=1;$i<=5;$i++)
                        <i class="bi bi-star{{ $i<=$c->rating?'-fill':'' }} text-warning"></i>
                        @endfor
                        <small class="text-muted ms-1">({{ $c->rating }}/5)</small>
                    </td>
                    <td style="max-width:300px"><small>{{ $c->body }}</small></td>
                    <td class="text-muted small">{{ $c->created_at->format('d/m/Y') }}</td>
                    <td>
                        <form action="{{ route('admin.comments.destroy',$c->id) }}" method="POST"
                              onsubmit="return confirm('Supprimer ce commentaire ?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger">
                                <i class="bi bi-trash"></i> Supprimer
                            </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        <i class="bi bi-chat-square" style="font-size:2rem;display:block;margin-bottom:.5rem"></i>
                        Aucun commentaire pour le moment.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($comments->hasPages())
    <div class="p-3 border-top">{{ $comments->links() }}</div>
    @endif
</div>

@endsection
