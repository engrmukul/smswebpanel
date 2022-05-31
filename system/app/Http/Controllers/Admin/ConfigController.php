<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SiteConfig;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request as UrlRequest;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class ConfigController extends Controller
{
    public function setting(){
        $configer = SiteConfig::where('reseller_id', Auth::user()->reseller_id)->first();
        if ($configer->site_url != UrlRequest::getHttpHost()){
            Session::flash('message', 'Please Login With Your Own Domain To Update Settings!');
            Session::flash('m-class', 'alert-danger');
            return redirect()->back();
        }
        $title = 'Setting';
        return view('backend.pages.config', compact('title'));
    }

    public function store(Request $request){
        $config = SiteConfig::findOrFail($request->id);

        $this->validate($request,
            [
                'brand_name' => 'required|unique:site_configs,brand_name,'.$config->id.'',
                'logo' => 'image|mimes:jpeg,jpg,png|max:1024|file',
                'address' => 'required',
                'thana' => 'required|alpha',
                'district' => 'required|alpha',
                'phone' => 'required|numeric',
                'email' => 'required|email'
            ],
            [
                'image.dimensions' => 'Please Upload 200x200 pixel size image!'
            ]
        );
        $data = $request->all();
        if (request()->hasFile('logo')) {
            $logo = $config->logo;
            $file = request()->file('logo');
            $fileName = md5($file->getClientOriginalName() . time()) . "." . $file->getClientOriginalExtension();
            $path = ('assets/images/');
            if ($logo != '') {
                File::delete('assets/images/' . $logo);
            }
            $file->move($path, $fileName);
            $data['logo'] = $fileName;
        }

        $config->update($data);
        Session::flash('message', 'Setting Update Successfully!');
        Session::flash('m-class', 'alert-success');
        return json_encode(['url' => route('setting')]);
    }

    public function createApi()
    {
        $title = 'Create Api';
        $user = User::findOrFail(Auth::user()->id);

        if (empty($user->APIKEY)) {
            $key = [
                'APIKEY' => bcrypt($user->username.time()).$user->id
            ];
            $user->update($key);
        }

        $sample = array("api_key" => 'xxxxxxxxxxxxxxxxxxxxx',
            "sms_data" => array(
                array("recipient" => "01711xxxxxx", "mask" => "test-mask", "message" => "Test Message 1"),
                array("recipient" => "01911xxxxxx", "mask" => "", "message" => "Test Message 2"),
                array("recipient" => "01811xxxxxx", "mask" => "Mask Name", "message" => "Test Message 3"),
            )
        );

        $message = json_encode($sample);

        $success = '{"messageid":"58026daf44542","status":"success","message":"Request has been accepted successfully"}';

        $faild = '{"messageid":"","status":"failed","message":"Please check your input data"}';

        $jsonFormat = $this->responseJson($message);
        $successResponse = $this->responseJson($success);
        $faildResponse = $this->responseJson($faild);

        return view('backend.pages.create-api', compact('title', 'user', 'jsonFormat', 'successResponse', 'faildResponse'));
    }

    public function storeApi(Request $request)
    {
        $user = User::findOrFail($request->id);
        $keyValue = bcrypt($user->username.time()).$user->id;
        $key = [
            'APIKEY' => $keyValue
        ];
        $user->update($key);
        return $keyValue;

    }

    public function responseJson($json)
    {

        $result = '';
        $pos = 0;
        $strLen = strlen($json);
        $indentStr = '  ';
        $newLine = "\n";
        $prevChar = '';
        $outOfQuotes = true;

        for ($i = 0; $i <= $strLen; $i++) {

            // Grab the next character in the string.
            $char = substr($json, $i, 1);

            // Are we inside a quoted string?
            if ($char == '"' && $prevChar != '\\') {
                $outOfQuotes = !$outOfQuotes;

                // If this character is the end of an element,
                // output a new line and indent the next line.
            } else if (($char == '}' || $char == ']') && $outOfQuotes) {
                $result .= $newLine;
                $pos--;
                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            // Add the character to the result string.
            $result .= $char;

            // If the last character was the beginning of an element,
            // output a new line and indent the next line.
            if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
                $result .= $newLine;
                if ($char == '{' || $char == '[') {
                    $pos++;
                }

                for ($j = 0; $j < $pos; $j++) {
                    $result .= $indentStr;
                }
            }

            $prevChar = $char;
        }

        return $result;
    }
}
