<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SenderId extends Model
{
    use HasFactory;

    protected $table = 'senderid';

    protected $fillable = [
        'user_id', 'reseller_id', 'senderID', 'status'
    ];

    public function reseller()
    {
        return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
    }
}
