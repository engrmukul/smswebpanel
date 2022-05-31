<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\SiteConfig;
use App\Models\ContactSubmit;
use App\Models\User;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request as UrlRequest;
use PhpParser\Node\Expr\AssignOp\Concat;

class HomeController extends Controller
{
    public function __construct()
    {
        $url = UrlRequest::getHttpHost();

//        if ($url != env("APP_HOST")) {
//            Redirect::to(RouteServiceProvider::HOME)->send();
//        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('frontend.pages.index');
    }

    public function contactUs()
    {
        return view('frontend.pages.contact');
    }

    public function contactUsPost(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'subject' => 'required',
            'phone_number' => 'required',
            'message' => 'required'
        ]);
        if(ContactSubmit::create($request->all())){
            return back()->with(['message' => 'We have <strong>successfully</strong> received your Message and will get Back to you as soon as possible.', 'm-class' => 'successmsg']);

        } else {
            return back()->with(['message' => 'Email <strong>could not</strong> be sent due to some Unexpected Error. Please Try Again later.', 'm-class' => 'errormsg']);
        }
    }

    public function price()
    {
        return view('frontend.pages.price');
    }

    public function terms()
    {
        return view('frontend.pages.tc');
    }

    public function termsPost()
    {
        Session::put('terms', true);
        return redirect()->route('sign.up');
    }

    public function signUp()
    {
        if(Session::has('terms')) {
            return view('frontend.pages.signup');
        } else {
            return redirect()->route('terms')->with(['message' => 'Please read and agree our terms & conditions', 'm-class' => 'errormsg']);
        }

    }

    public function signUpPost( Request $request)
    {
        $this->validate($request,[
            'name'=>'required|max:100',
            'username'=>'required|max:50|unique:user',
            'email'=>'required|unique:user',
            'password'=>'required|min:4|confirmed',
            'mobile'=>'numeric|required',
            'address'=>'required'
        ]);

        $user_ids = array();

        $users = User::whereIn('id_user_group', [1,2])->select('id')->get();

        foreach ($users as $user) {
            $user_ids[] = $user->id;
        }

        $reseller_id = null;

        $request->merge([
            'assign_user_id' => implode(',', $user_ids)
        ]);

        $data = [
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'mobile' => $request->mobile,
            'address' => $request->address,
            'id_user_group' => 4,
            'reseller_id' => $reseller_id,
            'assign_user_id' => $request->assign_user_id,
            'status' => 'PENDING',
            'tps' => 0,
            'dipping' => 'Inactive'
        ];

        $new_user_id = User::create($data)->id;

        $walletData = [
            'user_id' => $new_user_id
        ];
        UserWallet::create($walletData);

        Session::flash('message', 'Sign Up <strong>successfully done</strong> and we will get Back to you as soon as possible.');
        Session::flash('m-class', 'successmsg');
        return redirect()->back();
    }

    public function subscribe(Request $request)
    {
        if($request->input('email')) {
            $email = $request->input('email');
            $contact = Contact::where('email', $email)->firstOrFail();
            if ($contact) {
                $data = ['subscribed' => 0, 'remarks'=>$request->input('remarks')];
                Contact::where('email', $email)->update($data);
            }

        }

    }

    public function unsubscribe(Request $request)
    {
        $title = "Unsubscribe";
        $email = $request->input('email');
        return view('frontend.pages.unsubscribe', compact('email', 'title'));
    }

    public function unsubscribe_success(Request $request)
    {
        $title = "Unsubscribe Successful";
        return view('frontend.pages.unsubscribe_success', compact('title'));
    }

    public function unsubscribe_update(Request $request)
    {
        if($request->input('email')) {

            $email = $request->input('email');
            $contact = Contact::where('email', $email)->first();
            // print_r($contact); exit;
            if($contact) {
                $data = ['subscribed' => 0, 'remarks'=>$request->input('remarks')];
                Contact::where('email', $email)->update($data);
                return redirect('/unsubscribe_success');
            } else {
                Session::flash('message', 'No contact found!');
                Session::flash('m-class', 'failmsg');
//                dd($email);
                return redirect('/unsubscribe?email='.$email);
            }
        } else {
            return redirect()->route('contact.unsubscribe');
        }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
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
