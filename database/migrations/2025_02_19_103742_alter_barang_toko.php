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
        Schema::table('barang_toko', function (Blueprint $table) {
            $table->integer('konsinyasi')->after('omset')->default(0);
            $table->integer('nominal_konsinyasi')->after('konsinyasi')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_toko', function (Blueprint $table) {
            $table->dropColumn('konsinyasi');
            $table->dropColumn('nominal_konsinyasi');
        });
    }
};
