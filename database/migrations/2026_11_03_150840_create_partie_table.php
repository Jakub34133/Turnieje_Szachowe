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
        Schema::create('partie', function (Blueprint $table) {
            $table->id();
            $table->foreignId('runda_id')->constrained('rundy')->onDelete('cascade');
            $table->string('stolik');
            $table->foreignId('biale_zawodnik_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('czarne_zawodnik_id')->constrained('users')->onDelete('cascade');
            $table->integer('biale_wynik_punktowy')->nullable();
            $table->integer('czarne_wynik_punktowy')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('partie');
    }
};
