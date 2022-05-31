<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = array('user_id','reseller_id','invoice_from', 'invoice_to', 'invoice_details', 'total', 'status');

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function reseller()
    {
        return $this->belongsTo(Reseller::class, 'reseller_id', 'id');
    }
}
