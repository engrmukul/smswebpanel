<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTables;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $datas = Template::where('user_id', Auth::user()->id)->get();
        $title = 'All Template';
        $tableHeaders = ["id"=> "#", 'title'=>"Title", "description"=>"Message", "status"=>"Status", 'action' => 'Manage'];

        $ajaxUrl = route('template.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('status', function($row){
                    $st = (ucfirst(strtolower($row['status'])) == 'Active') ? 'primary' : 'danger';
                    $status_btn = '<span class="cursor-pointer text-center status_change_id_' . $row['id'] . ' ' . $st . '" id="status" data-id="' . $row['id'] . '">' . ucfirst(strtolower($row['status'])) . '</span>';
                    return $status_btn;
                })
                ->addColumn('action', function($row){
                    $btn = '<a href="' . route('template.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['status', 'action'])
                ->make(true);
        }

        return view('backend.pages.template.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add Template';

        return view('backend.pages.template.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => ['required', Rule::unique('template')->where(function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })],
            'description' => 'required'
        ]);

        $data = $request->all();

        $data['user_id'] = Auth::user()->id;

        Template::create($data);

        Session::flash('message', 'Template Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('template.list')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $template = Template::where('user_id', Auth::user()->id)->findOrFail($id);
        $title = 'Edit Template ('. $template->title .')';
        return view('backend.pages.template.edit', compact('title', 'template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $template = Template::findOrFail($id);
        $this->validate($request, [
            'title' => ['required', Rule::unique('template')->whereNot('id', $id)->where(function ($query) {
                return $query->where('user_id', Auth::user()->id);
            })],
            'description' => 'required'
        ]);

        $data = $request->all();

        $template->update($data);

        Session::flash('message', 'Template Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('template.list')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeStatus(Request $request, $id)
    {
        $query = Template::findOrFail($id);
        $data['status'] = $request->status;
        $query->update($data);
        echo Controller::status_change($id, $request->status);
    }
}
