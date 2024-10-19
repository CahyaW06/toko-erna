<?php

use App\Models\Barang;
use App\Models\Retail;
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
        Schema::create('log_retails', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Barang::class);
            $table->foreignIdFor(Retail::class);
            $table->foreignId('jenis_log_stok_id')->constrained('jenis_log_stok', 'id');
            $table->integer('jumlah');
            $table->integer('nominal');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('log_retails');
    }
};
