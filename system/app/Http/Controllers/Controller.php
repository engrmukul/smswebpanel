<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public static function status_change($id, $val)
    {
        if (strtolower($val) == 'publish') {
            $data = '<span class="primary">Publish</span>';
        } else if (strtolower($val) == 'unpublish') {
            $data = '<span class="danger">Un-Publish</span>';
        } else if (strtolower($val) == 'active') {
            $data = '<span class="primary">Active</span>';
        } else if (strtolower($val) == 'inactive') {
            $data = '<span class="danger">Inactive</span>';
        } else if (strtolower($val) == 'approved') {
            $data = '<span class="primary">Approved</span>';
        } else if (strtolower($val) == 'pending') {
            $data = '<span class="warning">Pending</span>';
        }
        $array = array('id' => $id, 'data' => $data);
        return json_encode($array);
    }

    public function ajaxDatatable() {
        return request()->ajax() && request()->exists('draw');
    }
}
