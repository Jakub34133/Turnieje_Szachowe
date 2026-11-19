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

            // Ogólne informacje o turnieju
            $table->foreignId('organizator_id')->constrained('users');
            
            $table->string('nazwa');
            $table->string('miejsce');
            $table->date('data_rozpoczecia');
            $table->date('data_zakonczenia');
            $table->integer('liczba_rund');
            $table->string('tempo_gry');
            $table->enum('rodzaj_szachow', ['Klasyczne', 'Szybkie', 'Błyskawiczne', 'Bullet']);
            
            // Informacje związane z rozgrywką
            $table->foreignId('status_id')->constrained('turniej_statusy');
            $table->integer('liczba_zawodnikow');
            $table->integer('limit_zawodnikow');
            
            // Dodatkowe informacje
            $table->text('opis')->nullable();
            $table->string('komunikat_path')->nullable();

            // Ograniczenia
            $table->integer('limit_wiek')->nullable();
            $table->integer('limit_ranking_od')->nullable();
            $table->integer('limit_ranking_do')->nullable();
            $table->enum('limit_plec', ['M', 'K'])->nullable();
            $table->boolean('limit_klub')->default(true);

            // Zgłoszenia do organizacji
            $table->boolean('zgloszony_do_pzszach')->default(false);
            $table->boolean('zgloszony_do_fide')->default(false);
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
