<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'installments',
        'monthly_deduction',
        'description',
        'start_month',
        'end_month',
        'contract_status',
        'cancel_reason',
        'notes',
        'months_count',
        'bank_id',
        'paid',
        'due',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }


    public function bank()
    {
        return $this->belongsTo(Bank::class);
    }

    public function payments() {
        return $this->hasMany(Payment::class)->orderBy('month');
    }


    public function checkIfPayment($month) {
        $payment = $this->payments()->whereMonth('month', Carbon::parse($month)->month)
        ->whereYear('month',Carbon::parse($month)->year)->first();
        
        if($payment) {
            return true;
        }

        return false;
    }


    public function getMonthsArray()
    {
        $fromMonth = Carbon::parse($this->start_month);
        $toMonth = Carbon::parse($this->end_month);

        $months = collect();
        for ($date = $fromMonth; $date->lte($toMonth); $date->addMonth()) {
            $months->push($date->format('Y-m'));
        }

        return $months->toArray();
    }

    
}
