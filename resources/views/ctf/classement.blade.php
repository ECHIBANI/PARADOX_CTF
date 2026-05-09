@extends('layouts.app')
@section('title', 'Classement CTF — PARDOX CTF')

@section('extra-css')
<link rel="stylesheet" href="{{ asset('css/ctf.css') }}">
@endsection

@section('body')
@include('layouts.navbar')

{{-- ═══ HEADER ═══ --}}
<section class="ctf-page-header">
    <div class="container text-center">
        <i class="bi bi-trophy-fill" style="font-size:2.5rem; color:#f59e0b; display:block; margin-bottom:.75rem;"></i>
        <h1 class="ctf-page-title">Classement CTF</h1>
        <p class="ctf-page-sub">Les meilleurs joueurs PARDOX CTF classés par total de points.</p>
    </div>
</section>

{{-- ═══ PODIUM TOP 3 ═══ --}}
@if($players->count() >= 3)
<section class="py-5" style="background: linear-gradient(135deg, #f8f9fa, #fff);">
    <div class="container">
        <div class="ctf-podium">

            {{-- 2ème place --}}
            <div class="ctf-podium-item ctf-podium-2">
                <div class="ctf-podium-avatar" style="background: linear-gradient(135deg, #94a3b8, #cbd5e1);">
                    {{ strtoupper(substr($players[1]->name, 0, 1)) }}
                </div>
                <div class="ctf-podium-medal" style="color: #94a3b8;"><i class="bi bi-trophy-fill"></i></div>
                <div class="ctf-podium-name">{{ $players[1]->name }}</div>
                <div class="ctf-podium-pts">{{ number_format($players[1]->total_points) }} pts</div>
                <div class="ctf-podium-solved">{{ $players[1]->solved_count }} résolus</div>
                <div class="ctf-podium-block" style="height: 80px; background: #94a3b8; border-radius: 8px 8px 0 0;">
                    <span class="ctf-podium-rank">2</span>
                </div>
            </div>

            {{-- 1ère place --}}
            <div class="ctf-podium-item ctf-podium-1">
                <div class="ctf-podium-crown"><i class="bi bi-crown-fill"></i></div>
                <div class="ctf-podium-avatar ctf-podium-avatar-1" style="background: linear-gradient(135deg, #f59e0b, #fbbf24);">
                    {{ strtoupper(substr($players[0]->name, 0, 1)) }}
                </div>
                <div class="ctf-podium-medal" style="color: #f59e0b;"><i class="bi bi-trophy-fill"></i></div>
                <div class="ctf-podium-name">{{ $players[0]->name }}</div>
                <div class="ctf-podium-pts ctf-podium-pts-1">{{ number_format($players[0]->total_points) }} pts</div>
                <div class="ctf-podium-solved">{{ $players[0]->solved_count }} résolus</div>
                <div class="ctf-podium-block ctf-podium-block-1" style="height: 120px; background: #f59e0b; border-radius: 8px 8px 0 0;">
                    <span class="ctf-podium-rank">1</span>
                </div>
            </div>

            {{-- 3ème place --}}
            <div class="ctf-podium-item ctf-podium-3">
                <div class="ctf-podium-avatar" style="background: linear-gradient(135deg, #a16207, #ca8a04);">
                    {{ strtoupper(substr($players[2]->name, 0, 1)) }}
                </div>
                <div class="ctf-podium-medal" style="color: #a16207;"><i class="bi bi-trophy-fill"></i></div>
                <div class="ctf-podium-name">{{ $players[2]->name }}</div>
                <div class="ctf-podium-pts">{{ number_format($players[2]->total_points) }} pts</div>
                <div class="ctf-podium-solved">{{ $players[2]->solved_count }} résolus</div>
                <div class="ctf-podium-block" style="height: 60px; background: #a16207; border-radius: 8px 8px 0 0;">
                    <span class="ctf-podium-rank">3</span>
                </div>
            </div>

        </div>
    </div>
</section>
@endif

{{-- ═══ CLASSEMENT COMPLET ═══ --}}
<section class="py-5" style="background: #fff;">
    <div class="container">

        {{-- Mon rang (si connecté) --}}
        @if(Auth::check() && $myRank)
        <div class="ctf-my-rank-card mb-4">
            <i class="bi bi-person-fill me-2" style="color:#2563eb;"></i>
            Votre position : <strong>#{{ $myRank }}</strong> sur {{ $players->count() }} joueurs
            @php $myScore = $players->firstWhere('id', Auth::id()); @endphp
            @if($myScore)
                — <strong>{{ number_format($myScore->total_points) }} pts</strong> — {{ $myScore->solved_count }} challenge{{ $myScore->solved_count != 1 ? 's' : '' }} résolu{{ $myScore->solved_count != 1 ? 's' : '' }}
            @endif
        </div>
        @endif

        <div class="ctf-leaderboard-table">
            <div class="ctf-leaderboard-header">
                <div class="ctf-lb-col-rank">Rang</div>
                <div class="ctf-lb-col-player">Joueur</div>
                <div class="ctf-lb-col-solved">Challenges Résolus</div>
                <div class="ctf-lb-col-pts">Points</div>
            </div>

            @forelse($players as $index => $player)
            <div class="ctf-leaderboard-row {{ Auth::check() && Auth::id() === $player->id ? 'ctf-lb-mine' : '' }}"
                 id="ctf-player-{{ $player->id }}">
                <div class="ctf-lb-col-rank">
                    @if($index === 0)
                        <span class="ctf-lb-rank-badge ctf-lb-gold"><i class="bi bi-trophy-fill"></i> 1</span>
                    @elseif($index === 1)
                        <span class="ctf-lb-rank-badge ctf-lb-silver"><i class="bi bi-trophy-fill"></i> 2</span>
                    @elseif($index === 2)
                        <span class="ctf-lb-rank-badge ctf-lb-bronze"><i class="bi bi-trophy-fill"></i> 3</span>
                    @else
                        <span class="ctf-lb-rank-num">#{{ $index + 1 }}</span>
                    @endif
                </div>
                <div class="ctf-lb-col-player">
                    <div class="ctf-lb-avatar">{{ strtoupper(substr($player->name, 0, 1)) }}</div>
                    <div>
                        <div class="ctf-lb-name">
                            {{ $player->name }}
                            @if(Auth::check() && Auth::id() === $player->id)
                            <span class="ctf-lb-you">Vous</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="ctf-lb-col-solved">
                    <div class="ctf-lb-solved-wrap">
                        <span class="ctf-lb-solved-num">{{ $player->solved_count }}</span>
                        <div class="ctf-lb-solved-bar">
                            <div class="ctf-lb-solved-fill"
                                 style="width: {{ $players->max('solved_count') > 0 ? ($player->solved_count / $players->max('solved_count') * 100) : 0 }}%"></div>
                        </div>
                    </div>
                </div>
                <div class="ctf-lb-col-pts">
                    <span class="ctf-lb-pts">{{ number_format($player->total_points) }} <small>pts</small></span>
                </div>
            </div>
            @empty
            <div class="ctf-empty-state" style="padding: 4rem 0;">
                <i class="bi bi-trophy" style="font-size:3rem; color:#ccc; display:block; margin-bottom:1rem;"></i>
                <h4 style="color:#666;">Aucun joueur classé pour l'instant</h4>
                <p style="color:#999;">Soyez le premier à résoudre un challenge !</p>
                <a href="{{ route('ctf.challenges') }}" class="ctf-btn-primary mt-2">Commencer les challenges</a>
            </div>
            @endforelse
        </div>
    </div>
</section>

@include('layouts.footer')
@endsection

@section('scripts')
<script src="{{ asset('js/ctf.js') }}"></script>
@endsection
