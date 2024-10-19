<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Retail extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function barangs() {
        return $this->belongsToMany(Barang::class)->withPivot('jumlah');
    }

    public function logRetails() {
        return $this->hasMany(LogRetail::class);
    }
}
