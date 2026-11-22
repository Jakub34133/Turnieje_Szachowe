<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rundy', function (Blueprint $table) {
            $table->foreignId('wolny_los_zawodnik_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('rundy', function (Blueprint $table) {
            $table->dropConstrainedForeignId('wolny_los_zawodnik_id');
        });
    }
};
