<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\CtfChallenge;
use App\Models\CtfScore;

/**
 * CtfScoreSeeder
 * Insère des joueurs CTF de démonstration avec des scores réels en base,
 * afin que le classement principal et le widget TOP JOUEURS affichent
 * les mêmes données cohérentes.
 */
class CtfScoreSeeder extends Seeder
{
    public function run(): void
    {
        // Récupérer les challenges disponibles
        $challenges = CtfChallenge::where('is_visible', true)->get();

        if ($challenges->isEmpty()) {
            $this->command->warn('Aucun challenge CTF trouvé. Lancez d\'abord CtfChallengeSeeder.');
            return;
        }

        // Joueurs CTF de démonstration
        $players = [
            [
                'phone'  => '+212699000001',
                'name'   => 'NitroMonkey',
                'solved' => 5, // Nombre de challenges résolus
            ],
            [
                'phone'  => '+212699000002',
                'name'   => '0xSpeedy',
                'solved' => 4,
            ],
            [
                'phone'  => '+212699000003',
                'name'   => 'HexRacer',
                'solved' => 3,
            ],
            [
                'phone'  => '+212699000004',
                'name'   => 'ByteKnight',
                'solved' => 2,
            ],
            [
                'phone'  => '+212699000005',
                'name'   => 'CipherWolf',
                'solved' => 1,
            ],
        ];

        foreach ($players as $rank => $playerData) {
            // Créer ou récupérer l'utilisateur
            $user = User::updateOrCreate(
                ['phone' => $playerData['phone']],
                [
                    'name'     => $playerData['name'],
                    'password' => Hash::make('ctfplayer123'),
                    'role'     => 'user',
                ]
            );

            // Attribuer des scores pour N challenges (sans doublon)
            $challengesToSolve = $challenges->take($playerData['solved']);
            $solverRankBonus   = [0 => 25, 1 => 15, 2 => 5]; // bonus pour les 3 premiers résolveurs

            foreach ($challengesToSolve as $i => $challenge) {
                // Éviter les doublons
                $alreadyScored = CtfScore::where('user_id', $user->id)
                    ->where('challenge_id', $challenge->id)
                    ->exists();

                if (!$alreadyScored) {
                    $bonusPercent = $solverRankBonus[$rank] ?? 0;
                    $bonusPoints  = (int) round($challenge->points * $bonusPercent / 100);

                    CtfScore::create([
                        'user_id'      => $user->id,
                        'challenge_id' => $challenge->id,
                        'points'       => $challenge->points,
                        'bonus_percent'=> $bonusPercent,
                        'bonus_points' => $bonusPoints,
                    ]);
                }
            }

            $total = CtfScore::where('user_id', $user->id)->sum('total_points');
            $this->command->info("✓ {$playerData['name']} : {$playerData['solved']} challenge(s) résolu(s) — {$total} pts");
        }
    }
}
