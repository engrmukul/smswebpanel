<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'has_mask', 'channel_id', 'operator_prefix', 'cost', 'success_rate', 'default_mask', 'status', 'created_by'
    ];

    protected $casts = [
        'operator_prefix' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id', 'id');
    }

    // public function operator()
    // {
    //     return $this->belongsTo(Operator::class, 'operator_id', 'id');
    // }
}
