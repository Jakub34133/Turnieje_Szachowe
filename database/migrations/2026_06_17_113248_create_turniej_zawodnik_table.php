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
        Schema::create('turniej_zawodnik', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('turniej_id')->constrained('turnieje')->onDelete('cascade');
            $table->foreignId('zawodnik_id')->constrained('users')->onDelete('cascade');
            $table->integer('punkty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('turniej_zawodnik');
    }
};
