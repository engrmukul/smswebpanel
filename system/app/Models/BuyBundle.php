<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuyBundle extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'masking_balance', 'non_masking_balance', 'email_balance', 'masking_rate', 'non_masking_rate', 'email_rate', 'total_price'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
