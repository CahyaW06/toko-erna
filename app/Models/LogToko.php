<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogToko extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function logToko() {
        return $this->belongsToMany(Barang::class, 'barang_toko');
    }
}
