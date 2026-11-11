<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('users')
            ->whereIn('legacy_role', ['pembeli', 'penjual'])
            ->update(['role' => DB::raw('legacy_role')]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->whereColumn('role', 'legacy_role')
            ->whereIn('role', ['pembeli', 'penjual'])
            ->update(['role' => 'pengunjung']);
    }
};
