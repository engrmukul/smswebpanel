<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id', 'name_en', 'name_bn', 'phone', 'email', 'profession', 'gender', 'dob', 'division', 'district', 'upazilla',
        'blood_group', 'user_id', 'status', 'reseller_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reseller()
    {
        return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
    }

    public function group()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
