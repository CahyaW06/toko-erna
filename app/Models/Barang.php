<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function retails() {
        return $this->belongsToMany(Retail::class)->withPivot('jumlah');
    }

    public function logRetails() {
        return $this->hasMany(LogRetail::class);
    }

    public function logStok() {
        return $this->hasMany(LogStok::class);
    }
}
