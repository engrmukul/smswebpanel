<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailRoute extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id', 'source_domain', 'email_service_provider_id', 'cost', 'success_rate', 'status', 'created_by'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function emailServiceProvider()
    {
        return $this->belongsTo(EmailServiceProvider::class, 'email_service_provider_id', 'id');
    }
}
