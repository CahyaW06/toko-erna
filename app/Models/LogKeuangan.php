<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogKeuangan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function barang() {
        return $this->belongsTo(Barang::class);
    }

    public function retail() {
        return $this->belongsTo(Retail::class);
    }

}
