<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Yajra\DataTables\DataTables;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Country List';
        $datas = Country::query();
        $tableHeaders = ["id"=> "#", "name"=>"Country Name", 'nickname'=>"Nickname", 'phonecode'=>'Phone Code', 'action'=>'Manage'];

        $ajaxUrl = route('country.list');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn = '<a href="' . route('country.edit', $row['id']) . '" class="warning ajax-form"><i class="fa fa-pencil"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.pages.country.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Add Country';
        return view('backend.pages.country.add', compact('title'));
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
            'name' => 'required',
            'nickname' => 'required',
            'phonecode' => 'required'
        ]);

        Country::create($request->all());
        Session::flash('message', 'Country Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('country.list')]);
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
        $country = Country::findOrFail($id);
        $title = 'Edit Country (' . $country->name . ')';
        return view('backend.pages.country.edit', compact('country', 'title'));
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
        $country = Country::findOrFail($id);
        $this->validate($request, [
            'name' => 'required',
            'nickname' => 'required',
            'phonecode' => 'required'
        ]);

        $country->update($request->all());
        Session::flash('message', 'Country Added Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('country.list')]);
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
}
