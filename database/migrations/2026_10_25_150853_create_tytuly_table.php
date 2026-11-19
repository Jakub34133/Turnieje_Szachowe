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
        Schema::create('tytuly', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('zawodnik_id')->constrained('users')->onDelete('cascade');
            $table->string('tytul_typ'); // (kategoria_krajowa, tytul_fide)
            $table->string('nazwa'); // (np. Kategoria I, IM, GM)
            $table->date('data_nadania');
        });
    }

    /*TODO: przemyśleć pola: 
        Tytul(
            numer_zaswiadczenia (opcjonalnie),
            status (nadana, wysłana, odrzucona),
            id osoby, (lub string) która nadała
        )*/
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tytuly');
    }
};
