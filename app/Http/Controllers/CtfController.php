<?php

namespace App\Http\Controllers;

use App\Models\CtfChallenge;
use App\Models\CtfSubmission;
use App\Models\CtfScore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

/**
 * CtfController
 * Gère toutes les pages et actions du module CTF PARDOX.
 */
class CtfController extends Controller
{
    /**
     * Page d'accueil CTF — /ctf
     * Affiche le hero, les challenges populaires et le top classement.
     */
    public function index()
    {
        // Récupérer 6 challenges pour l'affichage en homepage
        $challenges = CtfChallenge::where('is_visible', true)->orderBy('points')->take(6)->get();

        // Top 3 du classement pour la sidebar
        $topPlayers = \App\Models\User::withSum('ctfScores', 'total_points')
            ->withSum('ctfUnlockedHints', 'penalty')
            ->withCount('ctfScores')
            ->whereHas('ctfScores')
            ->get()
            ->map(function ($user) {
                $scoreBrut = $user->ctf_scores_sum_total_points ?? 0;
                $penalites = $user->ctf_unlocked_hints_sum_penalty ?? 0;
                $user->total_points = max(0, $scoreBrut - $penalites);
                $user->solved_count = $user->ctf_scores_count ?? 0;
                return $user;
            })
            ->sortByDesc('total_points')
            ->take(3)
            ->values();

        // Progression de l'utilisateur connecté
        $userStats = null;
        if (Auth::check()) {
            $userStats = [
                'total_points' => Auth::user()->totalCtfPoints(),
                'solved_count' => Auth::user()->ctfScores()->count(),
            ];
        }

        // IDs des challenges résolus par l'utilisateur connecté
        $solvedIds = Auth::check()
            ? CtfScore::where('user_id', Auth::id())->pluck('challenge_id')->toArray()
            : [];

        return view('ctf.index', compact('challenges', 'topPlayers', 'userStats', 'solvedIds'));
    }

    /**
     * Liste des challenges — /ctf/challenges
     * Affiche tous les challenges avec filtres de recherche.
     */
    public function challenges(Request $request)
    {
        $query = CtfChallenge::where('is_visible', true);

        // Filtre par recherche textuelle
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // Filtre par catégorie
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filtre par difficulté
        if ($request->filled('difficulty')) {
            $query->where('difficulty', $request->difficulty);
        }

        // IDs des challenges résolus par l'utilisateur connecté
        $solvedIds = Auth::check()
            ? CtfScore::where('user_id', Auth::id())->pluck('challenge_id')->toArray()
            : [];

        // Filtre par statut (résolu/non résolu)
        if ($request->filled('status') && Auth::check()) {
            if ($request->status === 'solved') {
                $query->whereIn('id', $solvedIds);
            } elseif ($request->status === 'unsolved') {
                $query->whereNotIn('id', $solvedIds);
            }
        }

        $challenges = $query->orderBy('points')->get();

        // Liste des catégories pour les filtres
        $categories = CtfChallenge::where('is_visible', true)->select('category')->distinct()->pluck('category');

        return view('ctf.challenges', compact('challenges', 'solvedIds', 'categories'));
    }

    /**
     * Détail d'un challenge — /ctf/challenges/{slug}
     */
    public function show(string $slug)
    {
        // Trouver le challenge par son slug ou retourner 404
        $challenge = CtfChallenge::where('slug', $slug)->where('is_visible', true)->firstOrFail();

        // Vérifier si l'utilisateur connecté a déjà résolu ce challenge
        $alreadySolved = Auth::check()
            ? CtfScore::where('user_id', Auth::id())
                      ->where('challenge_id', $challenge->id)
                      ->exists()
            : false;

        // Premiers résolveurs (top 5 par ordre d'arrivée)
        $lastSolvers = CtfScore::where('challenge_id', $challenge->id)
            ->with('user')
            ->oldest()
            ->take(5)
            ->get();

        // Challenges liés (même catégorie, sauf celui-ci)
        $relatedChallenges = CtfChallenge::where('category', $challenge->category)
            ->where('id', '!=', $challenge->id)
            ->where('is_visible', true)
            ->take(3)
            ->get();

        // Message flash après soumission
        $flagResult = session('flag_result');

        // Récupérer les indices débloqués
        $unlockedHints = Auth::check()
            ? \App\Models\CtfUnlockedHint::where('user_id', Auth::id())
                ->where('challenge_id', $challenge->id)
                ->pluck('hint_number')
                ->toArray()
            : [];

        // Pénalités totales
        $totalPenalty = Auth::check()
            ? \App\Models\CtfUnlockedHint::where('user_id', Auth::id())
                ->where('challenge_id', $challenge->id)
                ->sum('penalty')
            : 0;

        return view('ctf.show', compact('challenge', 'alreadySolved', 'lastSolvers', 'relatedChallenges', 'flagResult', 'unlockedHints', 'totalPenalty'));
    }

    /**
     * Débloquer un indice
     */
    public function unlockHint(Request $request, string $slug, int $hintNumber)
    {
        $challenge = CtfChallenge::where('slug', $slug)->where('is_visible', true)->firstOrFail();
        
        if (!Auth::check()) {
            return back()->withErrors(['flag' => 'Vous devez être connecté pour débloquer un indice.']);
        }

        // Vérifier s'il n'est pas déjà résolu
        if (CtfScore::where('user_id', Auth::id())->where('challenge_id', $challenge->id)->exists()) {
            return back()->withErrors(['flag' => 'Challenge déjà résolu. Vous ne pouvez plus débloquer d\'indices.']);
        }

        $penalty = $hintNumber === 1 ? 25 : 50;

        \App\Models\CtfUnlockedHint::firstOrCreate([
            'user_id'      => Auth::id(),
            'challenge_id' => $challenge->id,
            'hint_number'  => $hintNumber,
        ], [
            'penalty'      => $penalty
        ]);

        return back();
    }

    /**
     * Soumission du flag — POST /ctf/challenges/{slug}/submit
     * Sécurisé : hash du flag, validation, anti-doublon de points.
     */
    public function submit(Request $request, string $slug)
    {
        // Validation de la requête
        $request->validate([
            'flag' => ['required', 'string', 'max:255'],
        ]);

        $challenge = CtfChallenge::where('slug', $slug)->where('is_visible', true)->firstOrFail();

        // Incrémenter le compteur de tentatives
        $challenge->increment('attempts');

        // Récupérer l'IP de l'utilisateur
        $ip = $request->ip();

        // Vérifier si le flag soumis est correct (comparaison avec le hash stocké)
        $isCorrect = Hash::check($request->flag, $challenge->flag_hash);

        // Enregistrer la soumission (qu'elle soit correcte ou non)
        CtfSubmission::create([
            'user_id'        => Auth::id(), // null si non connecté
            'challenge_id'   => $challenge->id,
            'submitted_flag' => $request->flag,
            'is_correct'     => $isCorrect,
            'ip_address'     => $ip,
        ]);

        if ($isCorrect) {
            // Vérifier si l'utilisateur est connecté pour attribuer les points
            if (Auth::check()) {
                // Vérifier qu'il n'a pas déjà gagné les points (contrainte unique)
                $alreadyScored = CtfScore::where('user_id', Auth::id())
                    ->where('challenge_id', $challenge->id)
                    ->exists();

                if (!$alreadyScored) {
                    // Déterminer le rang de ce résolveur (combien ont déjà résolu avant lui)
                    $solverRank = CtfScore::where('challenge_id', $challenge->id)->count() + 1;

                    $bonusPercent = match($solverRank) {
                        1 => 25,
                        2 => 15,
                        3 => 5,
                        default => 0,
                    };

                    // Calculer les pénalités des indices pour l'affichage uniquement
                    $totalPenalty = \App\Models\CtfUnlockedHint::where('user_id', Auth::id())
                        ->where('challenge_id', $challenge->id)
                        ->sum('penalty');

                    $basePoints   = $challenge->points;
                    $bonusPoints  = (int) round($basePoints * $bonusPercent / 100);
                    $totalAwarded = $basePoints + $bonusPoints;

                    // Attribuer les points pleins + bonus (sans déduire la pénalité ici)
                    \App\Models\CtfScore::create([
                        'user_id'       => Auth::id(),
                        'challenge_id'  => $challenge->id,
                        'points'        => $basePoints,
                        'bonus_percent' => $bonusPercent,
                        'bonus_points'  => $bonusPoints,
                    ]);

                    return redirect()
                        ->route('ctf.challenge.show', $slug)
                        ->with('flag_result', [
                            'status'        => 'correct',
                            'message'       => 'Flag correct ! Challenge résolu.',
                            'points'        => $basePoints,
                            'penalty'       => $totalPenalty, // pour affichage
                            'bonus_percent' => $bonusPercent,
                            'bonus_points'  => $bonusPoints,
                            'total_points'  => $totalAwarded,
                            'solver_rank'   => $solverRank,
                        ]);

                } else {
                    // Challenge déjà résolu, pas de double gain
                    return redirect()
                        ->route('ctf.challenge.show', $slug)
                        ->with('flag_result', [
                            'status'  => 'already_solved',
                            'message' => 'Challenge déjà résolu. Les points ont déjà été attribués.',
                            'points'  => 0,
                        ]);
                }
            } else {
                // Utilisateur non connecté : flag correct mais pas de points
                return redirect()
                    ->route('ctf.challenge.show', $slug)
                    ->with('flag_result', [
                        'status'  => 'correct_no_auth',
                        'message' => 'Flag correct ! Connectez-vous pour enregistrer vos points.',
                        'points'  => $challenge->points,
                    ]);
            }
        }

        // Flag incorrect
        return redirect()
            ->route('ctf.challenge.show', $slug)
            ->with('flag_result', [
                'status'  => 'incorrect',
                'message' => 'Flag incorrect. Réessayez.',
                'points'  => 0,
            ]);
    }

    /**
     * Classement — /ctf/classement
     * Affiche les utilisateurs classés par total de points CTF.
     */
    public function classement()
    {
        // On récupère tous les utilisateurs ayant résolu au moins un challenge
        $players = \App\Models\User::withSum('ctfScores', 'total_points')
            ->withSum('ctfScores', 'bonus_points')
            ->withSum('ctfUnlockedHints', 'penalty')
            ->withCount('ctfScores')
            ->whereHas('ctfScores')
            ->get()
            ->map(function ($user) {
                // Le score final = (Total des points gagnés) - (Total des pénalités des indices)
                $scoreBrut = $user->ctf_scores_sum_total_points ?? 0;
                $penalites = $user->ctf_unlocked_hints_sum_penalty ?? 0;

                $user->total_points = max(0, $scoreBrut - $penalites);
                $user->total_bonus  = $user->ctf_scores_sum_bonus_points ?? 0;
                $user->solved_count = $user->ctf_scores_count ?? 0;
                return $user;
            })
            ->sortByDesc('total_points')
            ->values();

        // Rang de l'utilisateur connecté
        $myRank = null;
        if (\Illuminate\Support\Facades\Auth::check()) {
            foreach ($players as $index => $player) {
                if ($player->id === \Illuminate\Support\Facades\Auth::id()) {
                    $myRank = $index + 1;
                    break;
                }
            }
        }

        return view('ctf.classement', compact('players', 'myRank'));
    }
}
