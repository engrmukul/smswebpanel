<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table 	= "user";
    protected $fillable = [
        'id_user_group',
        'name',
        'username',
        'email',
        'email_verified_at',
        'password',
        'address',
        'mobile',
        'last_login_time',
        'status',
        'photo',
        'tps',
        'dipping',
        'created_by',
        'APIKEY',
        'billing_type',
        'mrc_otc',
        'duration_validity',
        'bill_start',
        'sms_rate_id',
        'email_rate_id',
        'reseller_id',
        'assign_user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function reseller() {
        return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
    }

    public function userType() {
        return $this->belongsTo(UserGroup::class, 'id_user_group', 'id');
    }

    public function createBy() {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function smsRate()
    {
        return $this->belongsTo(Rate::class, 'sms_rate_id', 'id');
    }

    public function emailRate()
    {
        return $this->belongsTo(Rate::class, 'email_rate_id', 'id');
    }
}
