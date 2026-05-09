@extends('layouts.admin')

@section('title', 'Gestion CTF')
@section('page-title', 'Gestion CTF')

@section('content')
<div class="table-card p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="table-card-title m-0"><i class="bi bi-flag-fill me-2" style="color:var(--cre-blue);"></i> Tous les Challenges</h3>
        <a href="{{ route('admin.ctf.create') }}" class="btn-pardo-primary">
            <i class="bi bi-plus-lg me-2"></i> Ajouter un challenge
        </a>
    </div>

    <div class="table-responsive">
        <table class="table table-pardo">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Catégorie</th>
                    <th>Difficulté</th>
                    <th>Points</th>
                    <th>Tentatives</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($challenges as $challenge)
                <tr>
                    <td><span class="res-id">#{{ $challenge->id }}</span></td>
                    <td class="fw-bold">{{ $challenge->title }}</td>
                    <td><span class="badge bg-secondary">{{ $challenge->category }}</span></td>
                    <td>
                        @if($challenge->difficulty == 'easy')
                            <span class="badge bg-success">Facile</span>
                        @elseif($challenge->difficulty == 'medium')
                            <span class="badge bg-warning text-dark">Moyen</span>
                        @else
                            <span class="badge bg-danger">Difficile</span>
                        @endif
                    </td>
                    <td class="fw-bold" style="color:var(--cre-blue);">{{ $challenge->points }}</td>
                    <td>{{ $challenge->attempts }}</td>
                    <td>
                        <form action="{{ route('admin.ctf.toggle-visibility', $challenge) }}" method="POST">
                            @csrf
                            @method('PATCH')
                            @if($challenge->is_visible)
                                <button type="submit" class="badge bg-success border-0" title="Cliquer pour masquer">
                                    <i class="bi bi-eye"></i> Visible
                                </button>
                            @else
                                <button type="submit" class="badge bg-danger border-0" title="Cliquer pour rendre visible">
                                    <i class="bi bi-eye-slash"></i> Masqué
                                </button>
                            @endif
                        </form>
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.ctf.edit', $challenge) }}" class="btn btn-sm btn-pardo-outline">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="{{ route('admin.ctf.destroy', $challenge) }}" method="POST" onsubmit="return confirm('Voulez-vous vraiment supprimer ce challenge ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">Aucun challenge disponible.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
