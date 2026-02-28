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
        Schema::create('match_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained()->onDelete('cascade');
            $table->enum('modality', ['1x1', '2x2', '3x3']);
            $table->enum('max_touches', [2, 3])->default(3);
            $table->enum('serve_type', ['foot_only', 'hand_allowed'])->default('foot_only');
            $table->integer('points_to_win')->default(12);
            $table->integer('win_difference')->default(2);
            $table->integer('max_extensions')->default(3);
            $table->integer('extension_points')->default(5);
            $table->text('additional_notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_rules');
    }
};
