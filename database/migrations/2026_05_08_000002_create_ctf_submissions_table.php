<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : Table ctf_submissions
 * Enregistre chaque tentative de soumission de flag.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ctf_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null'); // Null si non connecté
            $table->foreignId('challenge_id')->constrained('ctf_challenges')->onDelete('cascade');
            $table->string('submitted_flag');                         // Flag soumis par l'utilisateur
            $table->boolean('is_correct')->default(false);           // Correct ou non
            $table->string('ip_address')->nullable();                // IP pour anti-triche
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ctf_submissions');
    }
};
