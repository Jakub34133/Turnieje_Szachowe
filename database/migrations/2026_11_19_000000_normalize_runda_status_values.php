<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        foreach ([
            'Nie rozpoczęta' => 'NieRozpoczeta',
            'planowana' => 'NieRozpoczeta',
            'trwajaca' => 'Trwajaca',
            'trwająca' => 'Trwajaca',
            'zakonczona' => 'Zakonczona',
            'zakończona' => 'Zakonczona',
        ] as $oldStatus => $newStatus) {
            DB::table('rundy')->where('status', $oldStatus)->update(['status' => $newStatus]);
        }
    }

    public function down(): void
    {
        foreach ([
            'NieRozpoczeta' => 'Nie rozpoczęta',
            'Trwajaca' => 'trwajaca',
            'Zakonczona' => 'zakonczona',
        ] as $oldStatus => $newStatus) {
            DB::table('rundy')->where('status', $oldStatus)->update(['status' => $newStatus]);
        }
    }
};
