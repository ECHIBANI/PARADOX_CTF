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
        Schema::table('ctf_challenges', function (Blueprint $table) {
            $table->string('hint_1_title')->nullable()->after('file_2');
            $table->text('hint_1_content')->nullable()->after('hint_1_title');
            $table->string('hint_2_title')->nullable()->after('hint_1_content');
            $table->text('hint_2_content')->nullable()->after('hint_2_title');
            $table->boolean('is_visible')->default(true)->after('hint_2_content');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ctf_challenges', function (Blueprint $table) {
            $table->dropColumn(['hint_1_title', 'hint_1_content', 'hint_2_title', 'hint_2_content', 'is_visible']);
        });
    }
};
