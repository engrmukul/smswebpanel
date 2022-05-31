<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Menu extends Model
{
    use HasFactory;

    protected $table = 'menus';

    protected $fillable = array('parent_id','title','route_name', 'active_route', 'order_no', 'icon', 'status', 'user_group_id');

    public function parent()
    {
        return $this->belongsTo(Menu::class, 'parent_id', 'id');
    }

    public function children()
    {
        $group_id = Auth::user()->id_user_group;
        return $this->hasMany(Menu::class, 'parent_id')->where('status', 'Active')->orderBy('order_no','ASC')->whereRaw("find_in_set($group_id, user_group_id)", );
    }
}
