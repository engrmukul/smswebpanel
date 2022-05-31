<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailServiceProvider extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 'api_provider', 'provider_type', 'url', 'api_key', 'secret_key', 'username', 'password',
        'port', 'tls', 'tps', 'created_by', 'updated_by', 'status'
    ];
}
