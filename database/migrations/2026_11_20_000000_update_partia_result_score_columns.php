<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('partie', function (Blueprint $table) {
            $table->string('biale_wynik_punktowy')->nullable()->change();
            $table->string('czarne_wynik_punktowy')->nullable()->change();
        });

        foreach (['biale_wynik_punktowy', 'czarne_wynik_punktowy'] as $column) {
            DB::table('partie')->where($column, '2')->update([$column => '1']);
            DB::table('partie')->where($column, '1')->update([$column => '0.5']);
        }

        Schema::table('turniej_zawodnik', function (Blueprint $table) {
            $table->decimal('punkty', 8, 1)->default(0)->change();
        });
    }

    public function down(): void
    {
        foreach (['biale_wynik_punktowy', 'czarne_wynik_punktowy'] as $column) {
            DB::table('partie')->where($column, '0.5')->update([$column => '1']);
            DB::table('partie')->where($column, '1')->update([$column => '2']);
        }

        Schema::table('partie', function (Blueprint $table) {
            $table->integer('biale_wynik_punktowy')->nullable()->change();
            $table->integer('czarne_wynik_punktowy')->nullable()->change();
        });

        Schema::table('turniej_zawodnik', function (Blueprint $table) {
            $table->integer('punkty')->default(0)->change();
        });
    }
};
