<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : Table ctf_scores
 * Stocke les points gagnés par chaque utilisateur pour chaque challenge.
 * Contrainte unique user_id + challenge_id pour éviter le double gain de points.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ctf_scores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained('ctf_challenges')->onDelete('cascade');
            $table->integer('points');                                 // Points gagnés
            $table->timestamps();

            // Contrainte unique : un utilisateur ne peut gagner les points qu'une seule fois
            $table->unique(['user_id', 'challenge_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ctf_scores');
    }
};
