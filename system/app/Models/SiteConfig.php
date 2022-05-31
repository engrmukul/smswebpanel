<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteConfig extends Model
{
    use HasFactory;

    protected $fillable = array('brand_name','logo','phone','email','address','thana','district','site_url', 'reseller_id');
}
