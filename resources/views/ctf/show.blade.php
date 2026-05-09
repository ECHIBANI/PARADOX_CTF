@extends('layouts.app')
@section('title', $challenge->title . ' — PARDOX CTF')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/ctf.css') }}">
@endsection

@section('body')
@include('layouts.navbar')

{{-- ═══ BREADCRUMB ═══ --}}
<div class="ctf-breadcrumb">
    <div class="container">
        <a href="{{ route('ctf.index') }}" id="ctf-breadcrumb-home">Accueil</a>
        <span class="ctf-breadcrumb-sep">/</span>
        <a href="{{ route('ctf.challenges') }}" id="ctf-breadcrumb-challenges">Challenges</a>
        <span class="ctf-breadcrumb-sep">/</span>
        <span class="ctf-breadcrumb-current">{{ $challenge->title }}</span>
    </div>
</div>

<div class="container py-5">
    <div class="row g-5">

        {{-- ═══ COLONNE PRINCIPALE ═══ --}}
        <div class="col-lg-8">

            {{-- En-tête challenge --}}
            <div class="ctf-show-header">
                <div class="row align-items-center g-4">
                    <div class="col-md-6">
                        <div class="d-flex align-items-center gap-3 mb-2">
                            <h1 class="ctf-show-title mb-0">{{ $challenge->title }}</h1>
                            @if($alreadySolved)
                            <span class="ctf-diff-badge ctf-badge-solved" id="ctf-solved-badge">
                                <i class="bi bi-check-circle-fill me-1"></i> Résolu
                            </span>
                            @endif
                        </div>
                        <p class="ctf-show-desc">{{ $challenge->description }}</p>

                        {{-- Meta info badges --}}
                        <div class="ctf-meta-badges">
                            <div class="ctf-meta-badge">
                                <span class="ctf-meta-label">Catégorie</span>
                                <span class="ctf-meta-value ctf-meta-blue">{{ $challenge->category }}</span>
                            </div>
                            <div class="ctf-meta-badge">
                                <span class="ctf-meta-label">Difficulté</span>
                                <span class="ctf-diff-badge {{ $challenge->difficulty_class }}">{{ $challenge->difficulty_label }}</span>
                            </div>
                            <div class="ctf-meta-badge">
                                <span class="ctf-meta-label">Points</span>
                                <span class="ctf-meta-value">{{ $challenge->points }} pts</span>
                            </div>
                            @if($challenge->theme)
                            <div class="ctf-meta-badge">
                                <span class="ctf-meta-label">Thème</span>
                                <span class="ctf-meta-value">{{ $challenge->theme }}</span>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        {{-- Image du challenge --}}
                        <div class="ctf-show-img-wrap">
                            @if($challenge->image)
                                <img src="{{ asset('images/ctf/' . $challenge->image) }}" alt="{{ $challenge->title }}" class="ctf-show-img">
                            @else
                                <div class="ctf-show-img-placeholder ctf-placeholder-{{ strtolower($challenge->category) }}">
                                    @if($challenge->category === 'Reverse') <i class="bi bi-cpu"></i>
                                    @elseif($challenge->category === 'Crypto') <i class="bi bi-shield-lock"></i>
                                    @elseif($challenge->category === 'Web') <i class="bi bi-globe"></i>
                                    @elseif($challenge->category === 'Forensics') <i class="bi bi-search"></i>
                                    @elseif($challenge->category === 'Pwn') <i class="bi bi-terminal"></i>
                                    @else <i class="bi bi-flag"></i>
                                    @endif
                                    <div style="font-size:.8rem; opacity:.6; margin-top:.5rem;">{{ $challenge->category }}</div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ ÉNONCÉ ═══ --}}
            <div class="ctf-section-block mt-4">
                <div class="ctf-block-title">
                    <i class="bi bi-file-text me-2"></i> ÉNONCÉ
                </div>
                <div class="row g-4 mt-1">
                    <div class="col-md-6">
                        <div style="color:#333; line-height:1.7; white-space: pre-line;">{{ $challenge->statement }}</div>
                    </div>
                    <div class="col-md-6">
                        <div class="ctf-scenario-box">
                            <div class="ctf-scenario-title">
                                <i class="bi bi-dot me-1" style="color:#2563eb; font-size:1.5rem;"></i> SCÉNARIO
                            </div>
                            <div style="color:#444; font-size:.9rem; line-height:1.7;">
                                @php
                                    // Extraire la partie "Scénario" de l'énoncé
                                    preg_match('/Scénario\s*:\s*(.*?)(?=\n\n|\z)/s', $challenge->statement, $matches);
                                    $scenario = $matches[1] ?? 'Un ingénieur a laissé un message caché comme défi pour les curieux.';
                                @endphp
                                {{ $scenario }}
                            </div>
                            <div class="ctf-info-note mt-3">
                                <i class="bi bi-info-circle me-2 text-primary"></i>
                                Le flag est sensible à la casse.
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ═══ FICHIERS TÉLÉCHARGEABLES ═══ --}}
            @if($challenge->file_1 || $challenge->file_2)
            <div class="ctf-section-block mt-4">
                <div class="ctf-block-title">FICHIERS TÉLÉCHARGEABLES</div>
                <div class="ctf-files-list mt-3">
                    @if($challenge->file_1)
                    <div class="ctf-file-item">
                        <div class="ctf-file-icon"><i class="bi bi-file-earmark-arrow-down"></i></div>
                        <div class="ctf-file-info">
                            <div class="ctf-file-name" style="word-break: break-all;">{{ basename($challenge->file_1) }}</div>
                            <div class="ctf-file-desc text-muted">Fichier joint 1</div>
                        </div>
                        <a href="{{ asset($challenge->file_1) }}" target="_blank" download class="ctf-file-dl" title="Télécharger">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                    @endif
                    @if($challenge->file_2)
                    <div class="ctf-file-item mt-3">
                        <div class="ctf-file-icon ctf-file-pdf"><i class="bi bi-file-earmark-arrow-down"></i></div>
                        <div class="ctf-file-info">
                            <div class="ctf-file-name" style="word-break: break-all;">{{ basename($challenge->file_2) }}</div>
                            <div class="ctf-file-desc text-muted">Fichier joint 2</div>
                        </div>
                        <a href="{{ asset($challenge->file_2) }}" target="_blank" download class="ctf-file-dl" title="Télécharger">
                            <i class="bi bi-download"></i>
                        </a>
                    </div>
                    @endif
                </div>
            </div>
            @endif


            {{-- ═══ SOUMISSION / CÉLÉBRATION ═══ --}}
            @if($flagResult && $flagResult['status'] === 'correct')

            {{-- ╔══════════════════════════════════════════╗ --}}
            {{-- ║         ÉCRAN DE CÉLÉBRATION             ║ --}}
            {{-- ╚══════════════════════════════════════════╝ --}}
            <div class="ctf-celebration-wrap mt-4" id="ctf-celebration">

                {{-- Particules confettis --}}
                <div class="ctf-confetti" id="ctf-confetti-container"></div>

                {{-- Icône checkmark + lauriers --}}
                <div class="ctf-celeb-icon-row">
                    <span class="ctf-laurel ctf-laurel-left">🌿</span>
                    <div class="ctf-celeb-check">
                        <i class="bi bi-check-lg"></i>
                    </div>
                    <span class="ctf-laurel ctf-laurel-right">🌿</span>
                </div>

                {{-- Titre --}}
                <h2 class="ctf-celeb-title">
                    Félicitations !&nbsp;<span class="ctf-celeb-green">Challenge résolu</span>
                </h2>
                <p class="ctf-celeb-sub">Excellente analyse, ton exploit a été enregistré.</p>

                {{-- Badges rang + résolu --}}
                <div class="ctf-celeb-badges">
                    @php $rank = $flagResult['solver_rank'] ?? 0; @endphp
                    @if($rank === 1)
                    <span class="ctf-badge-rank ctf-badge-gold"><i class="bi bi-trophy-fill me-1"></i>1er résolveur</span>
                    @elseif($rank === 2)
                    <span class="ctf-badge-rank ctf-badge-silver"><i class="bi bi-trophy-fill me-1"></i>2e résolveur</span>
                    @elseif($rank === 3)
                    <span class="ctf-badge-rank ctf-badge-bronze"><i class="bi bi-trophy-fill me-1"></i>3e résolveur</span>
                    @endif
                    <span class="ctf-badge-rank ctf-badge-solved-green">Résolu</span>
                </div>

                {{-- Points breakdown --}}
                <div class="ctf-pts-breakdown">
                    <div class="ctf-pts-item">
                        <div class="ctf-pts-icon ctf-pts-icon-star"><i class="bi bi-star-fill"></i></div>
                        <div class="ctf-pts-num">{{ $flagResult['points'] }}</div>
                        <div class="ctf-pts-label">pts de base</div>
                    </div>
                    @if(($flagResult['bonus_percent'] ?? 0) > 0)
                    <div class="ctf-pts-sep">+</div>
                    <div class="ctf-pts-item">
                        <div class="ctf-pts-icon ctf-pts-icon-gift"><i class="bi bi-gift-fill"></i></div>
                        <div class="ctf-pts-num ctf-pts-green">+{{ $flagResult['bonus_points'] }}</div>
                        <div class="ctf-pts-label">pts bonus</div>
                    </div>
                    <div class="ctf-pts-sep">+</div>
                    <div class="ctf-pts-item">
                        <div class="ctf-pts-icon ctf-pts-icon-trend"><i class="bi bi-graph-up-arrow"></i></div>
                        <div class="ctf-pts-num ctf-pts-green">+{{ $flagResult['bonus_percent'] }}%</div>
                        <div class="ctf-pts-label">bonus multiplicateur</div>
                    </div>
                    @endif
                    @if(($flagResult['penalty'] ?? 0) > 0)
                    <div class="ctf-pts-sep" style="color:#dc2626;">−</div>
                    <div class="ctf-pts-item">
                        <div class="ctf-pts-icon" style="color:#dc2626;"><i class="bi bi-eye-slash-fill"></i></div>
                        <div class="ctf-pts-num" style="color:#dc2626;">{{ $flagResult['penalty'] }}</div>
                        <div class="ctf-pts-label" style="color:#dc2626;">pénalité indices</div>
                    </div>
                    @endif
                    <div class="ctf-pts-sep">=</div>
                    <div class="ctf-pts-item ctf-pts-total">
                        <div class="ctf-pts-icon ctf-pts-icon-trophy"><i class="bi bi-trophy-fill"></i></div>
                        @php
                            $netTotal = ($flagResult['total_points'] ?? $flagResult['points']) - ($flagResult['penalty'] ?? 0);
                        @endphp
                        <div class="ctf-pts-num ctf-pts-total-num">{{ max(0, $netTotal) }} pts</div>
                        <div class="ctf-pts-label ctf-pts-total-label">nets gagnés</div>
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="ctf-celeb-actions">
                    <a href="{{ route('ctf.challenges') }}" class="ctf-btn-primary" id="ctf-next-challenge">
                        Challenge suivant <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="{{ route('ctf.challenges') }}" class="ctf-btn-outline" id="ctf-back-to-list">
                        Retour aux challenges
                    </a>
                </div>
            </div>

            {{-- Hints + Historique côte à côte --}}
            <div class="row g-4 mt-2">

                {{-- Besoin d'aide --}}
                @if($challenge->hint_1_title || $challenge->hint_2_title)
                <div class="col-md-6">
                    <div class="ctf-section-block" id="ctf-hints-section-celeb">
                        <div class="ctf-block-title">BESOIN D'AIDE ?</div>
                        <div class="ctf-hints-list mt-3">
                            @php 
                                $isUnlocked1 = in_array(1, $unlockedHints ?? []); 
                                $isUnlocked2 = in_array(2, $unlockedHints ?? []);
                            @endphp

                            {{-- Indice 1 --}}
                            @if($challenge->hint_1_title)
                            <div class="ctf-hint-item @if($isUnlocked1) ctf-hint-active @endif" onclick="this.classList.toggle('ctf-hint-active')">
                                <div class="ctf-hint-header">
                                    <i class="bi @if($isUnlocked1) bi-search @else bi-lock @endif ctf-hint-lock"></i>
                                    <span class="ctf-hint-text">Indice 1 — {{ $challenge->hint_1_title }}</span>
                                    <div class="ms-auto d-flex align-items-center">
                                        @if(!$isUnlocked1) <span class="ctf-hint-pts">25 pts</span> @endif
                                        <i class="bi bi-chevron-down ctf-hint-chevron ms-2"></i>
                                    </div>
                                </div>
                                <div class="ctf-hint-body">
                                    @if($isUnlocked1)
                                        <p class="mb-0 text-muted" style="font-size:.85rem; line-height:1.6;">{!! nl2br(e($challenge->hint_1_content)) !!}</p>
                                    @else
                                        <div class="text-center py-2">
                                            <p class="mb-2 text-muted" style="font-size:.8rem;">Voulez-vous débloquer cet indice pour 25 pts ?</p>
                                            <form action="{{ route('ctf.challenge.hint.unlock', ['slug' => $challenge->slug, 'hintNumber' => 1]) }}" method="POST" class="m-0 p-0" onsubmit="this.querySelector('button').disabled=true;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3" style="background:var(--ctf-blue); border:none; font-size:.75rem;" onclick="event.stopPropagation();">
                                                    <i class="bi bi-unlock-fill me-1"></i> Débloquer l'indice
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif

                            {{-- Indice 2 --}}
                            @if($challenge->hint_2_title)
                            <div class="ctf-hint-item @if($isUnlocked2) ctf-hint-active @endif" onclick="this.classList.toggle('ctf-hint-active')">
                                <div class="ctf-hint-header">
                                    <i class="bi @if($isUnlocked2) bi-search @else bi-lock @endif ctf-hint-lock"></i>
                                    <span class="ctf-hint-text">Indice 2 — {{ $challenge->hint_2_title }}</span>
                                    <div class="ms-auto d-flex align-items-center">
                                        @if(!$isUnlocked2) <span class="ctf-hint-pts">50 pts</span> @endif
                                        <i class="bi bi-chevron-down ctf-hint-chevron ms-2"></i>
                                    </div>
                                </div>
                                <div class="ctf-hint-body">
                                    @if($isUnlocked2)
                                        <p class="mb-0 text-muted" style="font-size:.85rem; line-height:1.6;">{!! nl2br(e($challenge->hint_2_content)) !!}</p>
                                    @else
                                        <div class="text-center py-2">
                                            <p class="mb-2 text-muted" style="font-size:.8rem;">Voulez-vous débloquer cet indice pour 50 pts ?</p>
                                            <form action="{{ route('ctf.challenge.hint.unlock', ['slug' => $challenge->slug, 'hintNumber' => 2]) }}" method="POST" class="m-0 p-0" onsubmit="this.querySelector('button').disabled=true;">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3" style="background:var(--ctf-blue); border:none; font-size:.75rem;" onclick="event.stopPropagation();">
                                                    <i class="bi bi-unlock-fill me-1"></i> Débloquer l'indice
                                                </button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            @endif
                            
                            <div class="ctf-hint-item" style="background:#f8f9fa; border-style:dashed; font-size:.8rem; color:#888; justify-content:center; padding:.6rem; text-align:center; display:block;">
                                <i class="bi bi-info-circle me-1"></i>
                                Les indices sont pénalisants. Utilise-les uniquement en cas de blocage.
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Historique du challenge --}}
                <div class="col-md-6">
                    <div class="ctf-section-block">
                        <div class="ctf-block-title">HISTORIQUE DU CHALLENGE</div>
                        <div class="ctf-history-list mt-3">

                            {{-- Résolu --}}
                            <div class="ctf-history-item">
                                <div class="ctf-history-dot ctf-history-dot-green">
                                    <i class="bi bi-check-lg"></i>
                                </div>
                                <div class="ctf-history-content">
                                    <div class="ctf-history-title">Challenge résolu</div>
                                    <div class="ctf-history-sub">Le flag a été soumis avec succès.</div>
                                </div>
                                <div class="ctf-history-time">{{ now()->format('H:i') }}</div>
                            </div>

                            {{-- 1er résolveur --}}
                            @if(($flagResult['solver_rank'] ?? 99) <= 3)
                            <div class="ctf-history-item">
                                <div class="ctf-history-dot ctf-history-dot-gold">
                                    <i class="bi bi-trophy-fill"></i>
                                </div>
                                <div class="ctf-history-content">
                                    <div class="ctf-history-title">
                                        {{ $flagResult['solver_rank'] === 1 ? '1er résolveur' : ($flagResult['solver_rank'] === 2 ? '2e résolveur' : '3e résolveur') }}
                                    </div>
                                    <div class="ctf-history-sub">
                                        Félicitations ! Tu es le {{ $flagResult['solver_rank'] === 1 ? 'premier' : ($flagResult['solver_rank'] . 'e') }} à avoir résolu ce challenge.
                                    </div>
                                </div>
                                <div class="ctf-history-time">{{ now()->format('H:i') }}</div>
                            </div>
                            @endif

                            {{-- Démarré --}}
                            <div class="ctf-history-item">
                                <div class="ctf-history-dot ctf-history-dot-blue">
                                    <i class="bi bi-flag-fill"></i>
                                </div>
                                <div class="ctf-history-content">
                                    <div class="ctf-history-title">Challenge démarré</div>
                                    <div class="ctf-history-sub">Bon courage !</div>
                                </div>
                                <div class="ctf-history-time">{{ $challenge->created_at->format('H:i') }}</div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @else

            {{-- ═══ SOUMISSION NORMALE ═══ --}}
            <div class="ctf-section-block mt-4">
                <div class="ctf-block-title">SOUMETTRE LE FLAG</div>

                {{-- Résultats erreur / already solved / etc --}}
                @if($flagResult)
                    @if($flagResult['status'] === 'already_solved')
                    <div class="ctf-result ctf-result-warning mb-3" id="ctf-flag-result-box">
                        <i class="bi bi-info-circle-fill me-2" style="color:#d97706;"></i>
                        Challenge déjà résolu. Les points ont déjà été attribués.
                    </div>
                    @elseif($flagResult['status'] === 'correct_no_auth')
                    <div class="ctf-result ctf-result-info mb-3" id="ctf-flag-result-box">
                        <i class="bi bi-check-circle me-2" style="color:#2563eb;"></i>
                        Flag correct ! <a href="{{ route('login') }}" class="fw-bold">Connectez-vous</a> pour enregistrer vos {{ $flagResult['points'] }} points.
                    </div>
                    @elseif($flagResult['status'] === 'incorrect')
                    <div class="ctf-result ctf-result-incorrect mb-3" id="ctf-flag-result-box">
                        <div class="ctf-result-title"><i class="bi bi-x-circle-fill me-2"></i>Flag incorrect. Réessayez.</div>
                        <div class="ctf-result-sub">Vérifiez le format PARDOX{...} et respectez la casse.</div>
                    </div>
                    @endif
                @endif

                {{-- Formulaire --}}
                @if(!$alreadySolved)
                <form action="{{ route('ctf.challenge.submit', $challenge->slug) }}" method="POST" id="ctf-flag-form" class="mt-3">
                    @csrf
                    @if($errors->any())
                    <div class="ctf-result ctf-result-incorrect mb-3">
                        @foreach($errors->all() as $error)<div>{{ $error }}</div>@endforeach
                    </div>
                    @endif
                    <div class="d-flex gap-3 align-items-center">
                        <input type="text"
                               name="flag"
                               id="ctf-flag-input"
                               class="ctf-flag-input"
                               placeholder="Entrez le flag (ex: PARDOX{...})"
                               autocomplete="off"
                               required>
                        <button type="submit" class="ctf-btn-primary ctf-submit-btn" id="ctf-flag-submit">
                            Soumettre <i class="bi bi-send ms-1"></i>
                        </button>
                    </div>
                </form>
                @else
                <div class="d-flex gap-3 mt-3">
                    <a href="{{ route('ctf.challenges') }}" class="ctf-btn-primary" id="ctf-next-challenge">
                        Challenge suivant <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                    <a href="{{ route('ctf.challenges') }}" class="ctf-btn-outline" id="ctf-back-to-list">
                        Retour aux challenges
                    </a>
                </div>
                @endif
            </div>

            {{-- ═══ BESOIN D'AIDE ═══ --}}
            @if($challenge->hint_1_title || $challenge->hint_2_title)
            <div class="ctf-section-block mt-4" id="ctf-hints-section-main">
                <div class="ctf-block-title">BESOIN D'AIDE ?</div>
                <div class="ctf-hints-list mt-3">
                    @php 
                        $isUnlocked1 = in_array(1, $unlockedHints ?? []); 
                        $isUnlocked2 = in_array(2, $unlockedHints ?? []);
                    @endphp

                    {{-- Indice 1 --}}
                    @if($challenge->hint_1_title)
                    <div class="ctf-hint-item @if($isUnlocked1) ctf-hint-active @endif" onclick="this.classList.toggle('ctf-hint-active')">
                        <div class="ctf-hint-header">
                            <i class="bi @if($isUnlocked1) bi-search @else bi-lock @endif ctf-hint-lock"></i>
                            <span class="ctf-hint-text">Indice 1 — {{ $challenge->hint_1_title }}</span>
                            <div class="ms-auto d-flex align-items-center">
                                @if(!$isUnlocked1) <span class="ctf-hint-pts">25 pts</span> @endif
                                <i class="bi bi-chevron-down ctf-hint-chevron ms-2"></i>
                            </div>
                        </div>
                        <div class="ctf-hint-body">
                            @if($isUnlocked1)
                                <p class="mb-0 text-muted" style="font-size:.85rem; line-height:1.6;">{!! nl2br(e($challenge->hint_1_content)) !!}</p>
                            @else
                                <div class="text-center py-2">
                                    <p class="mb-2 text-muted" style="font-size:.8rem;">Voulez-vous débloquer cet indice pour 25 pts ?</p>
                                    <form action="{{ route('ctf.challenge.hint.unlock', ['slug' => $challenge->slug, 'hintNumber' => 1]) }}" method="POST" class="m-0 p-0" onsubmit="this.querySelector('button').disabled=true;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3" style="background:var(--ctf-blue); border:none; font-size:.75rem;" onclick="event.stopPropagation();">
                                            <i class="bi bi-unlock-fill me-1"></i> Débloquer l'indice
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    {{-- Indice 2 --}}
                    @if($challenge->hint_2_title)
                    <div class="ctf-hint-item @if($isUnlocked2) ctf-hint-active @endif" onclick="this.classList.toggle('ctf-hint-active')">
                        <div class="ctf-hint-header">
                            <i class="bi @if($isUnlocked2) bi-search @else bi-lock @endif ctf-hint-lock"></i>
                            <span class="ctf-hint-text">Indice 2 — {{ $challenge->hint_2_title }}</span>
                            <div class="ms-auto d-flex align-items-center">
                                @if(!$isUnlocked2) <span class="ctf-hint-pts">50 pts</span> @endif
                                <i class="bi bi-chevron-down ctf-hint-chevron ms-2"></i>
                            </div>
                        </div>
                        <div class="ctf-hint-body">
                            @if($isUnlocked2)
                                <p class="mb-0 text-muted" style="font-size:.85rem; line-height:1.6;">{!! nl2br(e($challenge->hint_2_content)) !!}</p>
                            @else
                                <div class="text-center py-2">
                                    <p class="mb-2 text-muted" style="font-size:.8rem;">Voulez-vous débloquer cet indice pour 50 pts ?</p>
                                    <form action="{{ route('ctf.challenge.hint.unlock', ['slug' => $challenge->slug, 'hintNumber' => 2]) }}" method="POST" class="m-0 p-0" onsubmit="this.querySelector('button').disabled=true;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary rounded-pill px-3" style="background:var(--ctf-blue); border:none; font-size:.75rem;" onclick="event.stopPropagation();">
                                            <i class="bi bi-unlock-fill me-1"></i> Débloquer l'indice
                                        </button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="ctf-hint-item" style="background:#f8f9fa; border-style:dashed; font-size:.8rem; color:#888; justify-content:center; padding:.6rem; text-align:center; display:block;">
                        <i class="bi bi-info-circle me-1"></i>
                        Les indices sont pénalisants. Utilise-les uniquement en cas de blocage.
                    </div>
                </div>
            </div>
            @endif

            @endif

        </div>

        {{-- ═══ SIDEBAR DROITE ═══ --}}
        <div class="col-lg-4">

            {{-- Informations --}}
            <div class="ctf-sidebar-card mb-4">
                <div class="ctf-sidebar-title">INFORMATIONS</div>
                <div class="ctf-info-table">
                    <div class="ctf-info-row">
                        <span class="ctf-info-label"><i class="bi bi-clock me-2"></i>Estimation</span>
                        <span class="ctf-info-value">Moyenne (45–60 min)</span>
                    </div>
                    <div class="ctf-info-row">
                        <span class="ctf-info-label"><i class="bi bi-plus-circle me-2"></i>Première résolution</span>
                        <span class="ctf-info-value">
                            @if($lastSolvers->isNotEmpty())
                                {{ $lastSolvers->last()->created_at->format('d M Y') }}
                            @else
                                —
                            @endif
                        </span>
                    </div>
                    <div class="ctf-info-row">
                        <span class="ctf-info-label"><i class="bi bi-arrow-repeat me-2"></i>Tentatives</span>
                        <span class="ctf-info-value">{{ number_format($challenge->attempts) }}</span>
                    </div>
                    <div class="ctf-info-row">
                        <span class="ctf-info-label"><i class="bi bi-person me-2"></i>Auteur</span>
                        <span class="ctf-info-value">{{ $challenge->author ?? 'pardox_dev' }}</span>
                    </div>
                    <div class="ctf-info-row">
                        <span class="ctf-info-label"><i class="bi bi-star me-2"></i>Points</span>
                        <span class="ctf-info-value ctf-meta-blue fw-bold">
                            {{ $challenge->points }} pts
                            @if($totalPenalty > 0)
                                <span style="color:#dc2626; font-size:.75rem; font-weight:600; display:block;">−{{ $totalPenalty }} pts (indices)</span>
                                <span style="color:#16a34a; font-size:.8rem; font-weight:700; display:block;">= {{ max(0, $challenge->points - $totalPenalty) }} pts nets</span>
                            @endif
                        </span>
                    </div>
                    <div class="ctf-info-row">
                        <span class="ctf-info-label"><i class="bi bi-check-circle me-2"></i>Statut</span>
                        <span class="ctf-info-value">
                            @if($alreadySolved)
                                <span class="ctf-diff-badge ctf-badge-solved"><i class="bi bi-check-circle-fill me-1"></i>Résolu</span>
                            @else
                                <span style="color:#666;">Non résolu</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            {{-- Premiers résolveurs (données réelles) --}}
            <div class="ctf-sidebar-card mb-4">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="ctf-sidebar-title mb-0">PREMIERS RÉSOLVEURS</div>
                    <a href="{{ route('ctf.classement') }}" class="ctf-link-arrow" style="font-size:.75rem;" id="ctf-ranking-sidebar-link">
                        Voir le classement <i class="bi bi-arrow-right"></i>
                    </a>
                </div>
                @forelse($lastSolvers as $index => $score)
                <div class="ctf-top-player">
                    <div class="ctf-player-rank" style="font-weight:700; color:#666; font-size:.85rem;">
                        @if($index < 3)
                            <i class="bi bi-trophy-fill" style="color:{{ $index === 0 ? '#f59e0b' : ($index === 1 ? '#94a3b8' : '#a16207') }}"></i>
                        @else
                            {{ $index + 1 }}
                        @endif
                    </div>
                    <div class="ctf-player-avatar">{{ strtoupper(substr($score->user->name ?? 'A', 0, 1)) }}</div>
                    <div class="ctf-player-info">
                        <div class="ctf-player-name">{{ $score->user->name ?? 'Anonyme' }}</div>
                        <div style="font-size:.7rem; color:#888; display:flex; align-items:center; gap:.4rem;">
                            @php
                                $minutes = (int) $challenge->created_at->diffInMinutes($score->created_at);
                            @endphp
                            {{ $minutes > 0 ? $minutes . ' min' : 'Tout juste' }}
                            @if($score->bonus_percent > 0)
                                <span style="background:#fef3c7; color:#b45309; font-size:.65rem; font-weight:700; border-radius:99px; padding:.05rem .4rem; line-height:1.4;">
                                    +{{ $score->bonus_percent }}%
                                </span>
                            @endif
                        </div>
                    </div>
                    {{-- Points totaux (base + bonus) --}}
                    <div class="ctf-player-pts" style="text-align:right; line-height:1.3;">
                        <div>+{{ $score->total_points ?? $score->points }} pts</div>
                        @if($score->bonus_percent > 0)
                            <div style="font-size:.65rem; color:#d97706; font-weight:600;">dont +{{ $score->bonus_points }} bonus</div>
                        @endif
                    </div>
                </div>
            @empty
                <div style="text-align:center; padding:1.5rem 0; color:#aaa; font-size:.85rem;">
                    <i class="bi bi-flag" style="font-size:1.8rem; display:block; margin-bottom:.5rem; color:#d1d5db;"></i>
                    Aucun résolveur pour l'instant.<br>
                    <span style="color:#2563eb; font-weight:500;">Sois le premier !</span>
                </div>
            @endforelse
            </div>

            {{-- Challenges liés --}}
            @if($relatedChallenges->isNotEmpty())
            <div class="ctf-sidebar-card">
                <div class="ctf-sidebar-title mb-3">CHALLENGES LIÉS</div>
                @foreach($relatedChallenges as $related)
                <a href="{{ route('ctf.challenge.show', $related->slug) }}" class="ctf-related-item" id="ctf-related-{{ $related->id }}">
                    <img src="{{ asset('images/ctf/' . ($related->image ?? 'ctf-default.jpg')) }}" alt="{{ $related->title }}" class="ctf-related-thumb" style="object-fit: cover; padding: 0; background: transparent;">
                    <div class="ctf-related-info">
                        <div class="ctf-related-title">{{ $related->title }}</div>
                        <div class="d-flex align-items-center gap-2 mt-1">
                            <span class="ctf-tag" style="font-size:.65rem;">{{ $related->category }}</span>
                            <span class="ctf-diff-badge {{ $related->difficulty_class }}" style="font-size:.65rem; padding:.1rem .4rem;">{{ $related->difficulty_label }}</span>
                        </div>
                    </div>
                    <div class="ctf-related-pts">{{ $related->points }} pts</div>
                </a>
                @endforeach
                <a href="{{ route('ctf.challenges') }}" class="ctf-link-arrow mt-3 d-block" id="ctf-all-related-link">
                    Voir tous les challenges <i class="bi bi-arrow-right"></i>
                </a>
            </div>
            @endif

        </div>
    </div>
</div>



@include('layouts.footer')
@endsection

@section('scripts')
<script src="{{ asset('js/ctf.js') }}"></script>
<script>

// Animation du formulaire de soumission
document.getElementById('ctf-flag-form')?.addEventListener('submit', function(e) {
    const btn = document.getElementById('ctf-flag-submit');
    if (btn) {
        btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span> Vérification...';
        btn.disabled = true;
    }
});

// Auto-scroll vers le résultat si présent
document.addEventListener('DOMContentLoaded', function() {
    const resultBox = document.getElementById('ctf-flag-result-box');
    if (resultBox) {
        setTimeout(() => { resultBox.scrollIntoView({ behavior: 'smooth', block: 'center' }); }, 300);
    }

    // ══ CONFETTIS (TOUTE LA PAGE) ══
    const container = document.getElementById('ctf-confetti-container');
    if (!container) return;
    const colors = ['#16a34a','#22c55e','#f59e0b','#fbbf24','#2563eb','#60a5fa','#d1d5db','#94a3b8'];
    const shapes = ['diamond','square','circle'];
    for (let i = 0; i < 120; i++) {
        const p = document.createElement('div');
        const shape = shapes[Math.floor(Math.random() * shapes.length)];
        p.className = 'ctf-confetti-p ctf-confetti-' + shape;
        p.style.cssText = [
            'left:' + Math.random() * 100 + 'vw',
            'top:' + (Math.random() * -100) + 'vh', /* Start above the screen */
            'background:' + colors[Math.floor(Math.random() * colors.length)],
            'width:' + (Math.random() * 8 + 6) + 'px',
            'height:' + (Math.random() * 8 + 6) + 'px',
            'animation-delay:' + Math.random() * 4 + 's',
            'animation-duration:' + (Math.random() * 3 + 3) + 's',
            'opacity:' + (Math.random() * 0.5 + 0.5)
        ].join(';');
        container.appendChild(p);
    }
});
</script>
@endsection
