<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepositHistory extends Model
{
    use HasFactory;

    protected $table = 'deposit_history';

    protected $fillable = [
        'user_id', 'reseller_id', 'deposit_by', 'deposit_amount', 'status', 'comment', 'approved_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function depositBy()
    {
        return $this->belongsTo(User::class, 'deposit_by', 'id');
    }

    public function reseller()
    {
        return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
    }
}
