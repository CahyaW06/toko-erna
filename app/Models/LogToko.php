<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogToko extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function barangs() {
        return $this->belongsToMany(Barang::class, 'barang_toko')->withPivot('jumlah', 'omset');
    }
}
