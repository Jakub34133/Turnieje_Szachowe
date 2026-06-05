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
        Schema::create('turnieje', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('organizator_id')->constrained('users');
            $table->foreignId('status_id')->constrained('turniej_statusy');
            $table->string('nazwa');
            $table->integer('liczba_rund');
            $table->string('miejsce');
            $table->date('data_rozpoczecia');
            $table->date('data_zakonczenia');
            $table->string('tempo_gry');
            $table->integer('liczba_zawodnikow');
            $table->integer('limit_zawodnikow');
            $table->text('opis')->nullable();
            $table->string('komunikat_path')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turnieje');
    }
};
