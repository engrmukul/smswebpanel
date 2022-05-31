<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\UserGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class MenuController extends Controller
{
    public function index() {
        $title = 'Menu List';
        $datas = Menu::where('parent_id', 0)->orderBy('order_no', 'ASC')->get();
        $tableHeaders = ["id"=> "#", 'title'=>"Menu Title", "route_name"=>"Route Name", "active_route"=>"Active Route",
            "order_no"=>"Order No", "status"=>"Status", 'action' => 'Manage'];

        $ajaxUrl = route('menu.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $st = (ucfirst(strtolower($row['status'])) == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'. route('menu.view', $row['id']) .'" class="primary mr-1 ajax-form"><i class="fa fa-eye"></i></a>' .
                        '<a href="' . route('menu.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.pages.menu.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    public function add() {
        $title = 'Add Menu';
        $menus = Menu::where('parent_id', 0)->get();
        $user_groups = UserGroup::all();

        $activeRoutes = routeDetails()['activeRoutes'];
        return view('backend.pages.menu.add', compact('menus', 'title', 'user_groups', 'activeRoutes'));
    }

    public function store(Request $request) {
        $this->validate($request,[
            'title'=>'required|max:50|unique:menus',
            'route_name'=>'nullable|unique:menus',
            'active_route'=>'required',
            'order_no'=>'numeric|required',
            'status'=>'required|in:Active,Inactive',
        ]);
        $request->merge([
            'user_group_id' => implode(',', (array) $request->get('user_group_id'))
        ]);
        $data=$request->all();
        $data['parent_id'] = 0;

        Menu::create($data);
        Session::flash('message', 'Menu Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('menu.list')]);
    }

    public function view($id) {

        $menu = Menu::findOrFail($id);
        if(empty($menu)) {
            Session::flash('message', 'Data Not Found!');
            Session::flash('m-class', 'alert-danger');
            return redirect()->route('menu.list');
        }
        $title = 'View Menu : '.$menu->title;
        $parentMenus = Menu::where('parent_id', 0)->get();
        $user_groups = UserGroup::all();
        $active_route = $menu->active_route;
        $routes = routeDetails()['routeNames'];
        $route_names = [];
        foreach ($routes as $route) {
            if(explode('.', $route)[0] == $active_route){
                $route_names[] = $route;
            }
        }

        $assign_groups = UserGroup::select('title')->whereIn('id', explode(',', $menu->user_group_id))->get();
        foreach ($assign_groups as $assign_group) {
            $assign_group_name[] = $assign_group->title;
        }
        $datas = Menu::where('parent_id', $id)->orderBy('order_no', 'ASC');
        $tableHeaders = ["id"=> "#", 'title'=>"Menu Title", 'parent_menu' => 'Parent Menu', "route_name"=>"Route Name", "active_route"=>"Active Route",
            "order_no"=>"Order No", "status"=>"Status", 'action' => 'Manage'];

        $ajaxUrl = route('menu.view', $id);

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('parent_menu', function($row){
                    return $row->parent->title;
                })
                ->addColumn('status', function($row){
                    $st = (ucfirst(strtolower($row['status'])) == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="'. route('menu.view', $row['id']) .'" class="primary mr-1 ajax-form"><i class="fa fa-eye"></i></a>' .
                        '<a href="' . route('menu.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }
        return view('backend.pages.menu.view', compact('menu','title', 'assign_group_name', 'parentMenus', 'user_groups', 'route_names', 'tableHeaders', 'ajaxUrl'));

    }

    public function addSub(Request $request, $id) {
        $this->validate($request,[
            'title'=>'required|max:50|unique:menus',
            'parent_id'=>'required',
            'route_name'=>'required|unique:menus',
            'active_route'=>'required',
            'order_no'=>'numeric|required',
            'status'=>'required|in:Active,Inactive',
        ]);
        $request->merge([
            'user_group_id' => implode(',', (array) $request->user_group)
        ]);

        $data = $request->all();

        Menu::create($data);
        Session::flash('message', 'Sub Menu Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('menu.view', $id)]);
    }

    public function edit($id){
        $menu = Menu::findOrFail($id);
        $title = 'Edit Menu: '.$menu->title;
        $user_groups = UserGroup::all();
        $user_group_ids = explode(',',$menu->user_group_id);
        $parentMenus = Menu::where('parent_id', 0)->get();
        $activeRoutes = routeDetails()['activeRoutes'];

        $active_route = $menu->active_route;

        $routes = routeDetails()['routeNames'];
        $route_names = [];
        foreach ($routes as $route) {
            if(explode('.', $route)[0] == $active_route){
                $route_names[] = $route;
            }
        }

        return view('backend.pages.menu.edit', compact('menu', 'title', 'user_groups', 'user_group_ids', 'parentMenus', 'activeRoutes', 'route_names'));
    }

    public function update(Request $request, $id){
        $menu = Menu::findOrFail($id);
        $this->validate($request,[
            'title'=>'string|required|max:50|unique:menus,title,'.$menu->id.'',
            'route_name'=>'nullable|string|unique:menus,route_name,'.$menu->id.'',
            'active_route'=>'required',
            'order_no'=>'numeric|required',
            'status'=>'required|in:Active,Inactive',
        ]);
        $request->merge([
            'user_group_id' => implode(',', (array) $request->get('user_group'))
        ]);
        $data=$request->all();

        $menu->update($data);
        Session::flash('message', 'Data Edit Successfully!');
        Session::flash('m-class', 'alert-success');

        return json_encode(['url' => route(($menu->parent_id == 0)?'menu.list':'menu.view',''.$request->parent_id)]);
    }

    public function changeStatus(Request $request, $id) {
        $menu = Menu::findOrFail($id);
        $data['status'] = $request->status;
        $menu->update($data);
        echo Controller::status_change($id, $request->status);
    }
}
