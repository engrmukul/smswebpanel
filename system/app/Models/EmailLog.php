<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'domain', 'subject', 'from_email', 'to_email', 'reference_id', 'write_time', 'response', 'delivery_reports', 'opened'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function sentEmail()
    {
        return $this->belongsTo(SentEmail::class, 'reference_id', 'id');
    }
}
