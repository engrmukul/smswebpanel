<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keyword extends Model
{
    use HasFactory;
    protected $table 	= "keywords";

    public function usesTimestamps() : bool{
        return false;
    }

    protected $fillable = [
        'user_id',
        'title',
        'keywords',
        'status'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id','user_id');
    }

}
