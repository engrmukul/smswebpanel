<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;

    protected $table = 'group';

    protected $fillable = [
        'user_id', 'name', 'type', 'status', 'reseller_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reseller()
    {
        return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
    }

    public function contacts()
    {
        return $this->hasMany(Contact::class, 'group_id', 'id');
    }
}
