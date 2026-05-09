<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ctf_unlocked_hints', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('challenge_id')->constrained('ctf_challenges')->onDelete('cascade');
            $table->integer('hint_number'); // 1 or 2
            $table->integer('penalty'); // e.g. 25 or 50
            $table->timestamps();

            $table->unique(['user_id', 'challenge_id', 'hint_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ctf_unlocked_hints');
    }
};
