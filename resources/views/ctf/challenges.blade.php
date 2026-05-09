@extends('layouts.app')
@section('title', 'Challenges CTF — PARDOX CTF')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/ctf.css') }}">
@endsection

@section('body')
@include('layouts.navbar')

{{-- ═══ HEADER PAGE ═══ --}}
<section class="ctf-page-header">
    <div class="container">
        <h1 class="ctf-page-title">Tous les Challenges</h1>
        <p class="ctf-page-sub">{{ $challenges->count() }} challenges disponibles — filtrez et trouvez votre prochain défi.</p>
    </div>
</section>

{{-- ═══ FILTRES ═══ --}}
<section class="py-4" style="background:#fff; border-bottom: 1px solid #eee; position: sticky; top: 70px; z-index: 100;">
    <div class="container">
        <form method="GET" action="{{ route('ctf.challenges') }}" id="ctf-filter-form">
            <div class="row g-3 align-items-end">

                {{-- Recherche --}}
                <div class="col-md-4">
                    <div class="ctf-filter-group">
                        <label class="ctf-filter-label">Rechercher</label>
                        <div class="ctf-search-wrap">
                            <i class="bi bi-search ctf-search-icon"></i>
                            <input type="text"
                                   name="search"
                                   id="ctf-search-input"
                                   class="ctf-filter-input ctf-search-input"
                                   placeholder="Nom du challenge..."
                                   value="{{ request('search') }}"
                                   autocomplete="off">
                        </div>
                    </div>
                </div>

                {{-- Catégorie --}}
                <div class="col-md-2">
                    <div class="ctf-filter-group">
                        <label class="ctf-filter-label">Catégorie</label>
                        <select name="category" id="ctf-cat-filter" class="ctf-filter-select" onchange="this.form.submit()">
                            <option value="">Toutes</option>
                            @foreach($categories as $cat)
                            <option value="{{ $cat }}" {{ request('category') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Difficulté --}}
                <div class="col-md-2">
                    <div class="ctf-filter-group">
                        <label class="ctf-filter-label">Difficulté</label>
                        <select name="difficulty" id="ctf-diff-filter" class="ctf-filter-select" onchange="this.form.submit()">
                            <option value="">Toutes</option>
                            <option value="easy"   {{ request('difficulty') === 'easy'   ? 'selected' : '' }}>Facile</option>
                            <option value="medium" {{ request('difficulty') === 'medium' ? 'selected' : '' }}>Moyen</option>
                            <option value="hard"   {{ request('difficulty') === 'hard'   ? 'selected' : '' }}>Difficile</option>
                        </select>
                    </div>
                </div>

                {{-- Statut --}}
                @auth
                <div class="col-md-2">
                    <div class="ctf-filter-group">
                        <label class="ctf-filter-label">Statut</label>
                        <select name="status" id="ctf-status-filter" class="ctf-filter-select" onchange="this.form.submit()">
                            <option value="">Tous</option>
                            <option value="solved"   {{ request('status') === 'solved'   ? 'selected' : '' }}>Résolus</option>
                            <option value="unsolved" {{ request('status') === 'unsolved' ? 'selected' : '' }}>Non résolus</option>
                        </select>
                    </div>
                </div>
                @endauth

                {{-- Bouton reset --}}
                <div class="col-md-2">
                    @if(request()->hasAny(['search', 'category', 'difficulty', 'status']))
                    <a href="{{ route('ctf.challenges') }}" class="ctf-btn-outline d-block text-center" id="ctf-reset-filters">
                        <i class="bi bi-x-circle me-1"></i> Réinitialiser
                    </a>
                    @else
                    <button type="submit" class="ctf-btn-primary d-block w-100" id="ctf-filter-submit">
                        <i class="bi bi-funnel me-1"></i> Filtrer
                    </button>
                    @endif
                </div>

            </div>
        </form>
    </div>
</section>

{{-- ═══ GRILLE CHALLENGES ═══ --}}
<section class="py-5" style="background:#f8f9fa;">
    <div class="container">

        {{-- Résultats --}}
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div style="font-size:.85rem; color:#666;">
                <strong>{{ $challenges->count() }}</strong> challenge{{ $challenges->count() != 1 ? 's' : '' }} trouvé{{ $challenges->count() != 1 ? 's' : '' }}
                @if(request('category')) — Catégorie : <strong>{{ request('category') }}</strong>@endif
                @if(request('difficulty')) — Difficulté : <strong>{{ ucfirst(request('difficulty')) }}</strong>@endif
            </div>
            @auth
            <div style="font-size:.85rem; color:#666;">
                <i class="bi bi-check-circle-fill text-success me-1"></i>
                {{ count($solvedIds) }} résolu{{ count($solvedIds) != 1 ? 's' : '' }} sur {{ \App\Models\CtfChallenge::count() }}
            </div>
            @endauth
        </div>

        @if($challenges->isEmpty())
        <div class="ctf-empty-state">
            <i class="bi bi-search" style="font-size: 3rem; color: #999; display:block; margin-bottom:1rem;"></i>
            <h4>Aucun challenge trouvé</h4>
            <p style="color:#666;">Modifiez vos filtres pour trouver des challenges.</p>
            <a href="{{ route('ctf.challenges') }}" class="ctf-btn-primary">Voir tous les challenges</a>
        </div>
        @else
        <div class="row g-4" id="ctf-all-challenges-grid">
            @foreach($challenges as $challenge)
            <div class="col-lg-4 col-md-6 ctf-challenge-item"
                 data-category="{{ strtolower($challenge->category) }}"
                 data-difficulty="{{ $challenge->difficulty }}"
                 data-solved="{{ in_array($challenge->id, $solvedIds) ? '1' : '0' }}">

                <div class="ctf-card {{ in_array($challenge->id, $solvedIds) ? 'ctf-card-solved' : '' }}">

                    {{-- Image / placeholder --}}
                    <div class="ctf-card-img">
                        @if($challenge->image)
                            <img src="{{ asset('images/ctf/' . $challenge->image) }}" alt="{{ $challenge->title }}">
                        @else
                            <div class="ctf-card-img-placeholder ctf-placeholder-{{ strtolower($challenge->category) }}">
                                <span class="ctf-category-icon">
                                    @if($challenge->category === 'Reverse') <i class="bi bi-cpu"></i>
                                    @elseif($challenge->category === 'Crypto') <i class="bi bi-shield-lock"></i>
                                    @elseif($challenge->category === 'Web') <i class="bi bi-globe"></i>
                                    @elseif($challenge->category === 'Forensics') <i class="bi bi-search"></i>
                                    @elseif($challenge->category === 'Pwn') <i class="bi bi-terminal"></i>
                                    @else <i class="bi bi-flag"></i>
                                    @endif
                                </span>
                                <div class="ctf-card-cat-label">{{ $challenge->category }}</div>
                            </div>
                        @endif
                        @if(in_array($challenge->id, $solvedIds))
                        <div class="ctf-solved-overlay"><i class="bi bi-check-circle-fill me-1"></i> Résolu</div>
                        @endif
                    </div>

                    {{-- Corps de la card --}}
                    <div class="ctf-card-body">
                        <div class="d-flex align-items-start justify-content-between mb-1">
                            <div class="ctf-card-title">{{ $challenge->title }}</div>
                            @if(in_array($challenge->id, $solvedIds))
                            <span class="ctf-diff-badge ctf-badge-solved"><i class="bi bi-check-circle-fill me-1"></i>Résolu</span>
                            @else
                            <span class="ctf-diff-badge {{ $challenge->difficulty_class }}">{{ $challenge->difficulty_label }}</span>
                            @endif
                        </div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <div class="ctf-card-cat">{{ $challenge->category }}</div>
                            <span class="ctf-pts">{{ $challenge->points }} pts</span>
                        </div>
                        <p class="ctf-card-desc">{{ Str::limit($challenge->description, 80) }}</p>
                        <div class="ctf-tags mb-3">
                            <span class="ctf-tag">Automobile</span>
                            <span class="ctf-tag">CTF</span>
                            <span class="ctf-tag">Cyber</span>
                        </div>
                        <div class="d-flex gap-2">
                            @if(in_array($challenge->id, $solvedIds))
                            <a href="{{ route('ctf.challenge.show', $challenge->slug) }}" class="ctf-btn-solved flex-fill text-center" id="ctf-view-{{ $challenge->id }}">
                                <i class="bi bi-check2 me-1"></i> Voir
                            </a>
                            @else
                            <a href="{{ route('ctf.challenge.show', $challenge->slug) }}" class="ctf-btn-solve flex-fill text-center" id="ctf-resolve-{{ $challenge->id }}">
                                Résoudre
                            </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            @endforeach
        </div>
        @endif

    </div>
</section>

@include('layouts.footer')
@endsection

@section('scripts')
<script src="{{ asset('js/ctf.js') }}"></script>
<script>
// Filtrage dynamique en temps réel par nom (sans rechargement)
document.getElementById('ctf-search-input')?.addEventListener('input', function() {
    const query = this.value.toLowerCase().trim();
    document.querySelectorAll('.ctf-challenge-item').forEach(function(item) {
        const title = item.querySelector('.ctf-card-title')?.textContent.toLowerCase() || '';
        item.style.display = title.includes(query) ? '' : 'none';
    });
});
</script>
@endsection
