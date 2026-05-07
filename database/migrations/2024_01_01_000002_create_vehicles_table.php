<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Économique, Citadine, SUV, Premium
            $table->unsignedInteger('price_per_day'); // DH/jour
            $table->unsignedTinyInteger('seats')->default(5);
            $table->enum('transmission', ['Manuelle', 'Automatique'])->default('Manuelle');
            $table->boolean('ac')->default(true);
            $table->string('image')->nullable();
            $table->boolean('available')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
