<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SentEmail extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'orderid', 'source', 'message', 'domain', 'from_email', 'recipients', 'group_id', 'date',
        'status', 'IP', 'email_type', 'schedule_date_time', 'search_param', 'error', 'file', 'priority', 'subject', 'attachment',
        'template_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function template()
    {
        return $this->belongsTo(User::class, 'template_id', 'id');
    }
}
