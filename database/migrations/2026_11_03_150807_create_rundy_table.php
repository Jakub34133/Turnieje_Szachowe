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
        Schema::create('rundy', function (Blueprint $table) {
            $table->id();
            $table->foreignId('turniej_id')->constrained('turnieje')->onDelete('cascade');
            $table->integer('numer');
            $table->string('status'); // 'planowana', 'trwajaca', 'zakonczona'
            $table->dateTime('data_rozpoczecia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rundy');
    }
};
