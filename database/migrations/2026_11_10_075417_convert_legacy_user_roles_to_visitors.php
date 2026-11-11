<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('legacy_role')->nullable()->after('role');
        });

        DB::table('users')
            ->whereIn('role', ['penjual', 'pembeli'])
            ->update(['legacy_role' => DB::raw('role')]);

        DB::table('users')
            ->whereIn('role', ['penjual', 'pembeli'])
            ->update(['role' => 'pengunjung']);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('pengunjung')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('users')
            ->whereNotNull('legacy_role')
            ->update(['role' => DB::raw('legacy_role')]);

        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('pembeli')->change();
            $table->dropColumn('legacy_role');
        });
    }
};
