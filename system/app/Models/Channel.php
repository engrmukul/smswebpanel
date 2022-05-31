<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;

    protected $table = 'channel';

    protected $fillable = [
        'name', 'api_provider', 'method', 'channel_type', 'url', 'content_type', 'sms_parameter', 'balance_url', 'balance_parameter',
        'username', 'password', 'ip', 'port', 'account_code', 'mode', 'tps', 'default_mask', 'created_by', 'updated_by', 'status'
    ];

}
