<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Operator extends Model
{
    use HasFactory;

    protected $table = 'operator';

    protected $fillable = [
        'full_name', 'short_name', 'prefix', 'country_id', 'created_by', 'updated_by', 'status', 'ton', 'npi'
    ];

    public function channels()
    {
        return $this->hasMany(Channel::class, 'operator_id');
    }
}
