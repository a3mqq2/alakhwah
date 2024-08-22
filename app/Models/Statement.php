<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Statement extends Model
{
    protected $guarded = [];
    use HasFactory;


    public function bank() {
        return $this->belongsTo(Bank::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class);
    }
}
