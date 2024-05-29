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
        Schema::create('game_player', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->tinyInteger('rsvp')->default(0);
            $table->smallInteger('team')->nullable();
            $table->foreignUuid('game_id')->references('id')->on('game');
            $table->foreignUuid('player_id')->references('id')->on('player');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_player');
    }
};
