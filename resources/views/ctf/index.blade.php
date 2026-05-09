@extends('layouts.app')
@section('title', 'PARDOX CTF — Challenges Cybersécurité Automobile')

@section('extra-css')
<link rel="stylesheet" href="/css/ctf.css?v=20260509">
<style>
    .ctf-card,
    .ctf-sidebar-card,
    .ctf-progress-card,
    .ctf-about-item {
        opacity: 1 !important;
        transform: none !important;
        visibility: visible !important;
    }
</style>
@endsection

@section('body')
{{-- Navbar existante --}}
@include('layouts.navbar')

{{-- ═══ HERO CTF ═══ --}}
<section class="ctf-hero">
    <div class="ctf-hero-dots"></div>
    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center g-5">

            {{-- Colonne gauche : texte hero --}}
            <div class="col-lg-6">

                {{-- Alerte flash : challenge résolu --}}
                @if(session('flag_result') && session('flag_result')['status'] === 'correct')
                <div class="ctf-alert-success mb-4" id="ctf-flash-alert">
                    <div class="d-flex align-items-center gap-3">
                        <div class="ctf-alert-icon">
                            <i class="bi bi-check-circle-fill"></i>
                        </div>
                        <div>
                            <div class="ctf-alert-title">
                                Challenge résolu ! <span class="ctf-pts-badge">+{{ session('flag_result')['points'] }} pts gagnés</span>
                            </div>
                            <div class="ctf-alert-sub">Excellent travail ! Continuez comme ça et grimpez dans le classement.</div>
                        </div>
                        <button class="ctf-alert-close ms-auto" onclick="document.getElementById('ctf-flash-alert').remove()">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                </div>
                @endif

                <h1 class="ctf-hero-title">
                    <span class="ctf-glitch" data-text="PRENEZ LA">PRENEZ LA</span><br>
                    <span class="ctf-glitch" data-text="ROUTE DU CTF">ROUTE DU CTF</span>
                </h1>

                <p class="ctf-hero-sub">
                    Des challenges inspirés du monde automobile :<br>
                    CAN bus, GPS, ECU, diagnostics, forensics et web.
                </p>

                <div class="d-flex flex-wrap gap-3 mt-4">
                    <a href="{{ route('ctf.challenges') }}" class="ctf-btn-primary" id="ctf-start-btn">
                        Commencer l'aventure <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                    <a href="#ctf-about" class="ctf-btn-outline" id="ctf-intro-btn">
                        <i class="bi bi-play-circle me-2"></i> Voir la présentation
                    </a>
                </div>
            </div>

            {{-- Colonne droite : image de la voiture CTF --}}
            <div class="col-lg-6 text-center d-none d-lg-block position-relative">
                <img src="{{ asset('images/ctf-car.png') }}"
                     alt="PARDOX CTF Automotive Cyber Car"
                     class="ctf-hero-car"
                     onerror="this.src='https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800&q=90'; this.style.borderRadius='20px'">

                {{-- Labels holographiques --}}
                <span class="ctf-holo-label ctf-holo-left">CAN BUS</span>
                <span class="ctf-holo-label ctf-holo-top">ECU</span>
                <span class="ctf-holo-label ctf-holo-right">GPS</span>
                <span class="ctf-holo-label ctf-holo-bottom">DIAGNOSTICS</span>
            </div>

        </div>
    </div>
</section>

{{-- ═══ PROGRESSION UTILISATEUR (si connecté) ═══ --}}
@if(Auth::check() && $userStats)
<div class="container my-4" style="position:relative; z-index:2;">
    <div class="row justify-content-end">
        <div class="col-lg-3 col-md-5">
            <div class="ctf-progress-card">
                <div class="ctf-progress-trophy">
                    <i class="bi bi-trophy-fill"></i>
                </div>

                <div class="ctf-progress-pts">{{ number_format($userStats['total_points']) }} pts</div>

                <div class="ctf-progress-label">
                    {{ $userStats['solved_count'] }} challenge{{ $userStats['solved_count'] != 1 ? 's' : '' }} résolu{{ $userStats['solved_count'] != 1 ? 's' : '' }}
                </div>

                <div class="ctf-progress-bar-wrap">
                    @php
                        $level = floor($userStats['total_points'] / 500) + 1;
                        $nextLevel = $level * 500;
                        $pct = min(100, ($userStats['total_points'] % 500) / 5);
                    @endphp

                    <div class="ctf-progress-meta">
                        <span>Niveau {{ $level }}</span>
                        <span>{{ $nextLevel - $userStats['total_points'] }} pts avant le niveau {{ $level + 1 }}</span>
                    </div>

                    <div class="ctf-progress-bar">
                        <div class="ctf-progress-fill" style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{-- ═══ CHALLENGES POPULAIRES + SIDEBAR ═══ --}}
<section class="py-5" style="background:#fff;">
    <div class="container">
        <div class="row g-4">

            {{-- Grille des challenges --}}
            <div class="col-lg-8">
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <div>
                        <h2 class="ctf-section-title">CHALLENGES POPULAIRES</h2>
                    </div>

                    <a href="{{ route('ctf.challenges') }}" class="ctf-link-arrow" id="ctf-see-all-link">
                        Voir tous les challenges <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                <div class="row g-3" id="ctf-challenges-grid">
                    @foreach($challenges as $challenge)
                    <div class="col-md-6 col-lg-4">
                        <div class="ctf-card {{ in_array($challenge->id, $solvedIds) ? 'ctf-card-solved' : '' }}" id="ctf-card-{{ $challenge->id }}">

                            {{-- Image challenge --}}
                            <div class="ctf-card-img">
                                @if($challenge->image)
                                    <img src="{{ asset('images/ctf/' . $challenge->image) }}" alt="{{ $challenge->title }}">
                                @else
                                    <div class="ctf-card-img-placeholder ctf-placeholder-{{ strtolower($challenge->category) }}">
                                        <span class="ctf-category-icon">
                                            @if($challenge->category === 'Reverse')
                                                <i class="bi bi-cpu"></i>
                                            @elseif($challenge->category === 'Crypto')
                                                <i class="bi bi-shield-lock"></i>
                                            @elseif($challenge->category === 'Web')
                                                <i class="bi bi-globe"></i>
                                            @elseif($challenge->category === 'Forensics')
                                                <i class="bi bi-search"></i>
                                            @elseif($challenge->category === 'Pwn')
                                                <i class="bi bi-terminal"></i>
                                            @else
                                                <i class="bi bi-flag"></i>
                                            @endif
                                        </span>
                                        <div class="ctf-card-cat-label">{{ $challenge->category }}</div>
                                    </div>
                                @endif

                                {{-- Badge résolu --}}
                                @if(in_array($challenge->id, $solvedIds))
                                <div class="ctf-solved-overlay">
                                    <i class="bi bi-check-circle-fill me-1"></i> Résolu
                                </div>
                                @endif
                            </div>

                            {{-- Infos challenge --}}
                            <div class="ctf-card-body">
                                <div class="d-flex align-items-center justify-content-between mb-1">
                                    <div class="ctf-card-title">{{ $challenge->title }}</div>

                                    <div class="d-flex align-items-center gap-2">
                                        <span class="ctf-diff-badge {{ $challenge->difficulty_class }}">
                                            {{ $challenge->difficulty_label }}
                                        </span>
                                        <span class="ctf-pts">{{ $challenge->points }} pts</span>
                                    </div>
                                </div>

                                <div class="ctf-card-cat">{{ $challenge->category }}</div>

                                <div class="ctf-tags mt-2">
                                    <span class="ctf-tag">Automobile</span>
                                    <span class="ctf-tag">CTF</span>
                                    <span class="ctf-tag">Cyber</span>
                                </div>

                                <div class="mt-3">
                                    @if(in_array($challenge->id, $solvedIds))
                                    <a href="{{ route('ctf.challenge.show', $challenge->slug) }}" class="ctf-btn-solved w-100" id="ctf-solved-{{ $challenge->id }}">
                                        <i class="bi bi-check2 me-1"></i> Voir
                                    </a>
                                    @else
                                    <a href="{{ route('ctf.challenge.show', $challenge->slug) }}" class="ctf-btn-solve w-100" id="ctf-solve-{{ $challenge->id }}">
                                        Résoudre
                                    </a>
                                    @endif
                                </div>
                            </div>

                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="col-lg-4">

                {{-- TOP JOUEURS --}}
                <div class="ctf-sidebar-card mb-4">
                    <div class="ctf-sidebar-title">TOP JOUEURS</div>

                    @forelse($topPlayers as $index => $player)
                    <div class="ctf-top-player">
                        <div class="ctf-player-rank">
                            @if($index === 0)
                                <i class="bi bi-trophy-fill" style="color:#f59e0b"></i>
                            @elseif($index === 1)
                                <i class="bi bi-trophy-fill" style="color:#94a3b8"></i>
                            @else
                                <i class="bi bi-trophy-fill" style="color:#a16207"></i>
                            @endif
                        </div>

                        <div class="ctf-player-avatar">{{ strtoupper(substr($player->name, 0, 1)) }}</div>

                        <div class="ctf-player-info">
                            <div class="ctf-player-name">{{ $player->name }}</div>
                        </div>

                        <div class="ctf-player-pts">{{ number_format($player->total_points) }} pts</div>
                    </div>
                    @empty
                    {{-- Affichage de démonstration si pas encore de joueurs --}}
                    <div class="ctf-top-player">
                        <div class="ctf-player-rank">
                            <i class="bi bi-trophy-fill" style="color:#f59e0b"></i>
                        </div>
                        <div class="ctf-player-avatar">N</div>
                        <div class="ctf-player-info">
                            <div class="ctf-player-name">NitroMonkey</div>
                        </div>
                        <div class="ctf-player-pts">4 250 pts</div>
                    </div>

                    <div class="ctf-top-player">
                        <div class="ctf-player-rank">
                            <i class="bi bi-trophy-fill" style="color:#94a3b8"></i>
                        </div>
                        <div class="ctf-player-avatar">0</div>
                        <div class="ctf-player-info">
                            <div class="ctf-player-name">0xSpeedy</div>
                        </div>
                        <div class="ctf-player-pts">3 750 pts</div>
                    </div>

                    <div class="ctf-top-player">
                        <div class="ctf-player-rank">
                            <i class="bi bi-trophy-fill" style="color:#a16207"></i>
                        </div>
                        <div class="ctf-player-avatar">H</div>
                        <div class="ctf-player-info">
                            <div class="ctf-player-name">HexRacer</div>
                        </div>
                        <div class="ctf-player-pts">3 100 pts</div>
                    </div>
                    @endforelse

                    <a href="{{ route('ctf.classement') }}" class="ctf-link-arrow mt-3 d-block" id="ctf-ranking-link">
                        Voir le classement complet <i class="bi bi-arrow-right"></i>
                    </a>
                </div>

                {{-- CATÉGORIES --}}
                <div class="ctf-sidebar-card">
                    <div class="ctf-sidebar-title">CATÉGORIES</div>

                    <div class="ctf-categories-grid">
                        <a href="{{ route('ctf.challenges', ['category' => 'Web']) }}" class="ctf-cat-item" id="ctf-cat-web">
                            <i class="bi bi-globe2"></i><span>Web</span>
                        </a>

                        <a href="{{ route('ctf.challenges', ['category' => 'Crypto']) }}" class="ctf-cat-item" id="ctf-cat-crypto">
                            <i class="bi bi-lock"></i><span>Crypto</span>
                        </a>

                        <a href="{{ route('ctf.challenges', ['category' => 'Reverse']) }}" class="ctf-cat-item" id="ctf-cat-reverse">
                            <i class="bi bi-code-slash"></i><span>Reverse</span>
                        </a>

                        <a href="{{ route('ctf.challenges', ['category' => 'Forensics']) }}" class="ctf-cat-item" id="ctf-cat-forensics">
                            <i class="bi bi-search"></i><span>Forensics</span>
                        </a>

                        <a href="{{ route('ctf.challenges', ['category' => 'Stego']) }}" class="ctf-cat-item" id="ctf-cat-stego">
                            <i class="bi bi-image"></i><span>Stego</span>
                        </a>

                        <a href="{{ route('ctf.challenges', ['category' => 'Pwn']) }}" class="ctf-cat-item" id="ctf-cat-pwn">
                            <i class="bi bi-terminal"></i><span>Pwn</span>
                        </a>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

{{-- ═══ SECTION À PROPOS CTF ═══ --}}
<section class="py-5" id="ctf-about" style="background:#f8f9fa; border-top: 1px solid #eee;">
    <div class="container">
        <div class="row g-4 text-center">

            <div class="col-md-3">
                <div class="ctf-about-item">
                    <i class="bi bi-shield-check ctf-about-icon"></i>
                    <div class="ctf-about-title">Apprenez en vous amusant</div>
                    <div class="ctf-about-sub">Des défis variés pour tous les niveaux.</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="ctf-about-item">
                    <i class="bi bi-bar-chart-line ctf-about-icon"></i>
                    <div class="ctf-about-title">Progressez & grimpez</div>
                    <div class="ctf-about-sub">Gagnez des points et atteignez le top.</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="ctf-about-item">
                    <i class="bi bi-people ctf-about-icon"></i>
                    <div class="ctf-about-title">Rejoignez la communauté</div>
                    <div class="ctf-about-sub">Échangez, collaborez, partagez.</div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="ctf-about-item">
                    <i class="bi bi-lock ctf-about-icon"></i>
                    <div class="ctf-about-title">Sécurisé & fair-play</div>
                    <div class="ctf-about-sub">Plateforme fiable et compétitive.</div>
                </div>
            </div>

        </div>
    </div>
</section>

@include('layouts.footer')
@endsection

@section('scripts')
<script src="/js/ctf.js?v=20260509"></script>
@endsection