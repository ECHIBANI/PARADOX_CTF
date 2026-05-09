@extends('layouts.admin')

@section('title', isset($challenge) ? 'Modifier Challenge' : 'Ajouter Challenge')
@section('page-title', isset($challenge) ? 'Modifier Challenge' : 'Ajouter Challenge')

@section('content')
<div class="table-card p-4">
    <div class="mb-4">
        <a href="{{ route('admin.ctf.index') }}" class="btn btn-sm btn-pardo-outline">
            <i class="bi bi-arrow-left me-1"></i> Retour à la liste
        </a>
    </div>

    <form action="{{ isset($challenge) ? route('admin.ctf.update', $challenge) : route('admin.ctf.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($challenge))
            @method('PUT')
        @endif

        <div class="row g-4">
            <div class="col-md-6">
                <label class="form-label fw-bold">Titre du challenge</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $challenge->title ?? '') }}" required>
            </div>
            
            <div class="col-md-3">
                <label class="form-label fw-bold">Catégorie</label>
                <input type="text" name="category" class="form-control" placeholder="ex: Web, Reverse..." value="{{ old('category', $challenge->category ?? '') }}" required>
            </div>

            <div class="col-md-3">
                <label class="form-label fw-bold">Difficulté</label>
                <select name="difficulty" class="form-select" required>
                    <option value="easy" {{ old('difficulty', $challenge->difficulty ?? '') == 'easy' ? 'selected' : '' }}>Facile</option>
                    <option value="medium" {{ old('difficulty', $challenge->difficulty ?? '') == 'medium' ? 'selected' : '' }}>Moyen</option>
                    <option value="hard" {{ old('difficulty', $challenge->difficulty ?? '') == 'hard' ? 'selected' : '' }}>Difficile</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Points</label>
                <input type="number" name="points" class="form-control" value="{{ old('points', $challenge->points ?? 100) }}" required min="0">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Auteur</label>
                <input type="text" name="author" class="form-control" value="{{ old('author', $challenge->author ?? '') }}">
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Thème</label>
                <input type="text" name="theme" class="form-control" value="{{ old('theme', $challenge->theme ?? '') }}">
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">Flag {{ isset($challenge) ? '(Laisser vide pour ne pas modifier)' : '' }}</label>
                <input type="text" name="flag" class="form-control" placeholder="PARDOX{votre_flag_ici}" {{ isset($challenge) ? '' : 'required' }}>
                @if(isset($challenge))
                <small class="text-muted">Attention : Le flag actuel est crypté, vous ne pouvez pas le voir. Saisissez-en un nouveau uniquement si vous souhaitez le changer.</small>
                @endif
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">Courte Description</label>
                <textarea name="description" class="form-control" rows="2" required>{{ old('description', $challenge->description ?? '') }}</textarea>
            </div>

            <div class="col-12">
                <label class="form-label fw-bold">Énoncé complet (HTML supporté)</label>
                <textarea name="statement" class="form-control" rows="6" required>{{ old('statement', $challenge->statement ?? '') }}</textarea>
                <small class="text-muted">Vous pouvez utiliser des balises HTML pour mettre en forme le texte (ex: &lt;b&gt;, &lt;p&gt;, &lt;code&gt;).</small>
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Image de couverture</label>
                <input type="file" name="image" class="form-control" accept="image/*">
                @if(isset($challenge) && $challenge->image)
                    <div class="mt-2">
                        <img src="{{ asset('images/ctf/' . $challenge->image) }}" alt="Couverture" style="max-height:80px; border-radius: 8px;">
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Fichier 1 (PDF, ZIP, etc.)</label>
                <input type="file" name="file_1" class="form-control">
                @if(isset($challenge) && $challenge->file_1)
                    <div class="mt-1">
                        <small class="text-success"><i class="bi bi-check-circle"></i> Fichier actuel : <a href="{{ asset($challenge->file_1) }}" target="_blank">Voir le fichier</a></small>
                    </div>
                @endif
            </div>

            <div class="col-md-4">
                <label class="form-label fw-bold">Fichier 2 (Vidéo, EXE, etc.)</label>
                <input type="file" name="file_2" class="form-control">
                @if(isset($challenge) && $challenge->file_2)
                    <div class="mt-1">
                        <small class="text-success"><i class="bi bi-check-circle"></i> Fichier actuel : <a href="{{ asset($challenge->file_2) }}" target="_blank">Voir le fichier</a></small>
                    </div>
                @endif
            </div>

            <div class="col-12 mt-4 pt-3 border-top">
                <h5 class="fw-bold"><i class="bi bi-lightbulb-fill text-warning me-2"></i> Indices (Optionnels)</h5>
                <p class="text-muted small mb-4">Si vous remplissez ces champs, les indices seront disponibles pour les joueurs contre une pénalité de points (-25 pts pour l'indice 1, -50 pts pour l'indice 2).</p>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Titre Indice 1</label>
                        <input type="text" name="hint_1_title" class="form-control" placeholder="ex: Structure du message cible" value="{{ old('hint_1_title', $challenge->hint_1_title ?? '') }}">
                        <label class="form-label fw-bold mt-2">Contenu Indice 1</label>
                        <textarea name="hint_1_content" class="form-control" rows="3">{{ old('hint_1_content', $challenge->hint_1_content ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Titre Indice 2</label>
                        <input type="text" name="hint_2_title" class="form-control" placeholder="ex: Champs à surveiller" value="{{ old('hint_2_title', $challenge->hint_2_title ?? '') }}">
                        <label class="form-label fw-bold mt-2">Contenu Indice 2</label>
                        <textarea name="hint_2_content" class="form-control" rows="3">{{ old('hint_2_content', $challenge->hint_2_content ?? '') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="col-12 mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                <div class="form-check form-switch" style="font-size: 1.1rem;">
                    <input class="form-check-input" type="checkbox" role="switch" id="isVisibleSwitch" name="is_visible" value="1" {{ old('is_visible', $challenge->is_visible ?? true) ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold ms-2" for="isVisibleSwitch">Challenge visible par les joueurs</label>
                </div>
                <button type="submit" class="btn-pardo-primary px-4">
                    <i class="bi bi-save me-2"></i> {{ isset($challenge) ? 'Mettre à jour' : 'Enregistrer' }}
                </button>
            </div>
        </div>
    </form>
</div>
@endsection
