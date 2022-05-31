<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sentmessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'orderid', 'source', 'mobile_no_column', 'message', 'json_data', 'senderID', 'recipient', 'group_id', 'date',
        'pages', 'status', 'units', 'sentFrom', 'sms_count', 'is_mms', 'is_unicode', 'IP', 'gateway_id', 'sms_type',
        'scheduleDateTime', 'search_param', 'error', 'file', 'priority'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
