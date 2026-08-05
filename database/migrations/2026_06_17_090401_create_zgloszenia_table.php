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
        Schema::create('zgloszenia', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->foreignId('zawodnik_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('turniej_id')->constrained('turnieje')->onDelete('cascade');
            $table->foreignId('status_id')->constrained('zgloszenie_statusy')->onDelete('cascade');
            $table->dateTime('data_wyslania');
            $table->text('komentarz')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('zgloszenia');
    }
};
