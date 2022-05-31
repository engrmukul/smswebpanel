<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\DataTables;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $title = 'Blacklisted word List';
        if(\auth()->user()->id_user_group == 1 || \auth()->user()->id_user_group == 2){
            $datas = Keyword::with('user', 'user.userType')->get();
        }if(\auth()->user()->id_user_group == 3 || \auth()->user()->id_user_group == 4){
            $datas = Keyword::with('user', 'user.userType')->where('user_id', \auth()->user()->id)->get();
        }
        $tableHeaders = ["id"=> "#", 'title'=>"Title", 'keywords'=>"Keywords", "user"=>"User", "userType"=>"User Type", 'action' => 'Manage'];

        $ajaxUrl = route('keywords.index');

        if ($this->ajaxDatatable()) {
            return DataTables::of($datas)
                ->addIndexColumn()
                ->addColumn('action', function($row){
                    $btn= '<a class="btn btn-primary btn-xs float-left mr-1" href="' . route('keywords.edit', [$row->id]) . '" title="Food Edit"><i class="fa fa-pencil"></i> Edit</a>';
                    $btn.= '
                    <form action="'.route('keywords.destroy', [$row->id]).'" method="POST">
                        <input type="hidden" name="_method" value="delete">
                        <input type="hidden" name="_token" value="'.csrf_token().'">
                        <button type="submit" id="complexConfirm" class="btn btn-danger btn-xs"><i class="fa fa-remove"></i> Delete</button>
                    </form>
                ';

                    return $btn;
                })->editColumn('keywords', function ($row) {
                    return  \Str::words($row->keywords, '10');
                })->editColumn('user', function ($row) {
                    return $row->user->name;
                })->editColumn('userType', function ($row) {
                    return $row->user->userType->title;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('backend.pages.keyword.list', compact('title', 'tableHeaders', 'ajaxUrl'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $title = 'Blacklisted Word Add';
        return view('backend.pages.keyword.add', compact('title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'title'=>'required',
            'keywords'=>'required',
            'status'=>'required|in:Active,Inactive'
        ]);

        $data = $request->all();
        $data['user_id'] = Auth::user()->id;

        Keyword::create($data);
        Session::flash('message', 'Data Edit Successfully!');
        Session::flash('m-class', 'alert-success');

        return redirect()->route('keywords.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $keyword = Keyword::findOrFail($id);
        $title = 'View Blacklisted Words';
        return view('backend.pages.keyword.view', compact('title', 'keyword'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $keyword = Keyword::findOrFail($id);
        $title = 'Edit Blacklisted Words';
        return view('backend.pages.keyword.edit', compact('title', 'keyword'));
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
        $keyword = Keyword::findOrFail($id);
        $validator = Validator::make($request->all(), [
            'title'=>'required',
            'keywords'=>'required',
            'status'=>'required|in:Active,Inactive'
        ]);

        if ($validator->fails()) {
            return response()->json(array(
                'errors' => $validator->getMessageBag()->toArray()
            ), 400);
        }

        $data = $request->all();

        $keyword->update($data);
        Session::flash('message', 'Keyword Updated Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('keywords.index')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $keyword = Keyword::find($id);
        $keyword->delete();
        Session::flash('message', 'Keyword Deleted Successfully!');
        Session::flash('m-class', 'alert-success');
        return redirect()->back();
    }
}
