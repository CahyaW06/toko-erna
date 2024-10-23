<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogKeuangan extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function logRetail() {
        return $this->belongsTo(LogRetail::class);
    }
}
