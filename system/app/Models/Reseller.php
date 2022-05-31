<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reseller extends Model
{
    use HasFactory;
    protected $fillable = [
        'reseller_name',
        'available_balance',
        'tps',
        'due',
        'phone',
        'email',
        'address',
        'thana',
        'district',
        'sms_rate_id',
        'email_rate_id',
        'status'
    ];

    public function smsRate()
    {
        return $this->belongsTo(Rate::class, 'sms_rate_id', 'id');
    }

    public function emailRate()
    {
        return $this->belongsTo(Rate::class, 'email_rate_id', 'id');
    }
}
