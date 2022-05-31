<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outbox extends Model
{
    use HasFactory;
    protected $table = 'outbox';

    protected $fillable = [
        'srcmn', 'mask', 'destmn', 'message', 'country_code', 'operator_prefix', 'status', 'write_time', 'sent_time', 'ton', 'npi', 'message_type',
        'is_unicode', 'smscount', 'esm_class', 'data_coding', 'reference_id', 'last_updated', 'schedule_time', 'retry_count', 'user_id', 'remarks', 'uuid', 'channel_id', 'priority'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class, 'channel_id', 'id');
    }
}
