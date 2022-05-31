<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    use HasFactory;
    protected $table 	= "user_group";

    public function usesTimestamps() : bool{
        return false;
    }

    protected $fillable = [
        'title',
        'comment',
        'status'
    ];

}
