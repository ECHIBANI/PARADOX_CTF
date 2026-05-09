<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration : Table ctf_challenges
 * Stocke tous les challenges CTF du module PARDOX CTF.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ctf_challenges', function (Blueprint $table) {
            $table->id();
            $table->string('title');                                    // Titre du challenge
            $table->string('slug')->unique();                          // Identifiant URL unique
            $table->text('description');                               // Courte description
            $table->longText('statement');                             // Énoncé complet
            $table->string('category');                                // Catégorie : Web, Crypto, Reverse...
            $table->enum('difficulty', ['easy', 'medium', 'hard']);    // Niveau de difficulté
            $table->integer('points');                                 // Points attribués
            $table->string('theme')->nullable();                       // Thème (ex: Automobile)
            $table->string('flag_hash');                               // Hash du flag (JAMAIS en clair)
            $table->string('image')->nullable();                       // Image de couverture
            $table->string('file_1')->nullable();                      // Fichier téléchargeable 1
            $table->string('file_2')->nullable();                      // Fichier téléchargeable 2
            $table->string('author')->nullable();                      // Auteur du challenge
            $table->unsignedInteger('attempts')->default(0);          // Nombre de tentatives total
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ctf_challenges');
    }
};
