<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'phone', 'phone_2', 'address', 'bank_id', 'bank_number', 'identifier_number'];

    public function bank() {
        return $this->belongsTo(Bank::class);
    }

    public function contracts() {
        return $this->hasMany(Contract::class);
    }
}
