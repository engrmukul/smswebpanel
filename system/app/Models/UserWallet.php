<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWallet extends Model
{
    use HasFactory;

    protected $table = 'user_wallet';

    protected $fillable = [
        'user_id', 'available_balance', 'non_masking_balance', 'masking_balance', 'email_balance'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
