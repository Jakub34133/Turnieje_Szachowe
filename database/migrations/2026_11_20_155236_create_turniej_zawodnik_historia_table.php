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
        Schema::create('turniej_zawodnik_historia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turniej_id')->constrained('turnieje');
            $table->foreignId('zawodnik_id')->constrained('users');
            $table->foreignId('runda_id')->constrained('rundy');
            $table->decimal('punkty_historia', 8, 1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turniej_zawodnik_historia');
    }
};
