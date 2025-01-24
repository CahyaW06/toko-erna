<?php

use App\Models\Barang;
use App\Models\LogToko;
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
        Schema::create('barang_toko', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(LogToko::class);
            $table->foreignIdFor(Barang::class);
            $table->integer('jumlah')->default(0);
            $table->integer('omset')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_toko');
    }
};
