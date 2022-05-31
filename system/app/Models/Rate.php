<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rate extends Model
{
    use HasFactory;
    protected $fillable = [
        'rate_name',
        'buying_masking_rate',
        'selling_masking_rate',
        'buying_nonmasking_rate',
        'selling_nonmasking_rate',
        'email_rate',
        'rate_type',
        'created_by',
        'reseller_id'];
}
