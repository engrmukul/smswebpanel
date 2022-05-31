{{--@extends($layout)--}}

{{--@section('pageCSS')--}}
    <style>
        pre{
            color: #ff5b5b !important;
            font-size: 100%;
            font-family: monospace;
        }
        @media (min-width: 576px) {
            .form-group .label-control {
                text-align: right;
            }
        }
        .well{
            border: 0;
            padding: 20px;
            -webkit-box-shadow: none!important;
            -moz-box-shadow: none!important;
            box-shadow: none!important;
        }
    </style>
@endsection

{{--@section('title')--}}
    {{ $title }}
@endsection

@section('breadcrumb')
    <div class="content-header-left col-md-6 col-12 mb-2">
        <h3 class="content-header-title mb-0">{{ $title }}</h3>
        <div class="row breadcrumbs-top">
            <div class="breadcrumb-wrapper col-12">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a>
                    </li>
                </ol>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <section id="horizontal-form-layouts">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">{{ $title }}</h4>
                <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                <div class="heading-elements">
                    <ul class="list-inline mb-0">
                        <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                        <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                        <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="card-content">
                <div class="card-body">
                    {{--<div class="row">
                        <div class="col-12">
                            <hr>
                            <div class="card">
                                <div class="card-content">
                                    <ul class="list-inline">
                                        <li class="text-bold-700">API KEY :</li>
                                        <li style="color:#ff5b5b !important"><span id="api_key">{{ $user->APIKEY }}</span></li>
                                        <li class="cursor-pointer" onclick="copyToClipboard('#api_key')"><span type="button" class="btn btn-primary text-bold-700">Copy</span></li>
                                        <li class="cursor-pointer" id="generate_key" data-id="{{ $user->id }}"><span type="button" class="btn btn-warning text-bold-700">Generate API KEY</span></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <ul class="nav nav-tabs nav-top-border no-hover-bg" id="tabMenu" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="base-get" data-toggle="tab" aria-controls="get" href="#get" role="tab" aria-selected="true">GET METHOD</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="base-post" data-toggle="tab" aria-controls="post" href="#post" role="tab" aria-selected="false">POST METHOD</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane pt-2 active" id="get" role="tabpanel" aria-labelledby="base-get">
                            <div class="form-group row">
                                <label class="col-md-2 label-control" for="number">Mask URL : <span type="button" onclick="copyToClipboard('#mask_url')" class="btn btn-primary text-bold-700"><i class="fa fa-copy danger"></i></span></label>
                                <div class="col-md-10 col-form-label" id="mask_url">
                                    {{ route('api.message.get') }}?apiKey=<span id="api_key2">{{ $user->APIKEY }}</span>&maskName={Mask Name}&mobileNo={Number}&message={Message}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 label-control" for="number">Non Mask URL : <span type="button" onclick="copyToClipboard('#non_mask_url')" class="btn btn-primary text-bold-700"><i class="fa fa-copy danger"></i></span></label>
                                <div class="col-md-10 col-form-label" id="non_mask_url">
                                    {{ route('api.message.get') }}?apiKey=<span id="api_key3">{{ $user->APIKEY }}</span>&mobileNo={Number}&message={Message}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label label-control" for="number">METHOD :</label>
                                <div class="col-md-10 col-form-label">
                                    GET
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label label-control" for="number">Response Format :</label>
                                <div class="col-md-10 col-form-label">
                                    JSON
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label label-control" for="number">Successful Response :</label>
                                <div class="col-md-10 col-form-label">
                                    <pre>{{ $successResponse }}</pre>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label label-control" for="number">Faild Response :</label>
                                <div class="col-md-10 col-form-label">
                                    <pre>{{ $faildResponse }}</pre>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane pt-2" id="post" role="tabpanel" aria-labelledby="base-post">

                            <div class="form-group row">
                                <label class="col-md-2 label-control" for="number">Post URL : <span type="button" onclick="copyToClipboard('#post_url')" class="btn btn-primary text-bold-700"><i class="fa fa-copy danger"></i></span></label>
                                <div class="col-md-10 col-form-label" id="post_url">
                                    {{ route('api.message.post') }}
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label label-control" for="number">METHOD :</label>
                                <div class="col-md-10 col-form-label">
                                    POST
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2  col-form-label label-control" for="number">Format :</label>
                                <div class="col-md-10 col-form-label">
                                    JSON
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2  col-form-label label-control" for="number">Json Format :</label>
                                <div class="col-md-10 col-form-label">
                                    <pre>{{ $jsonFormat }}</pre>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2  col-form-label label-control" for="number">Successful Response :</label>
                                <div class="col-md-10 col-form-label">
                                    <pre>{{ $successResponse }}</pre>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-md-2  col-form-label label-control" for="number">Faild Response :</label>
                                <div class="col-md-10 col-form-label">
                                    <pre>{{ $faildResponse }}</pre>
                                </div>
                            </div>
                        </div>
                    </div>--}}

                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            <div class="portlet light ">
                                <div class="portlet-body">
                                    <label>Text SMS API</label>
                                    <div class="well">
                                        Your API Key : <strong id="key_show_id"><span id="api_key">{{ $user->APIKEY }}</span>  </strong> <button type="button" id="generate_key" data-id="{{ $user->id }}" class="btn btn-warning">Regenerate Key</button>
                                    </div>

                                    <div style="padding-bottom:10px;">
                                        <strong>JSON data format :</strong> <a href="#" class="json-one-to-many">One to many</a> | <a href="#" class="json-many-to-many">Many to many</a>
                                        &nbsp;
                                        <strong>PHP Source code :</strong> <a href="#" class="php-one-to-many">One to many</a> | <a href="#" class="php-many-to-many">Many to many</a>
                                    </div>
                                    <div class="alert alert-info">
                                        <strong>API URL (GET &amp; POST)</strong> : {{ route('api.message.get') }}?api_key=(APIKEY)&amp;type=text&amp;contacts=(NUMBER)&amp;senderid=(Approved Sender ID)&amp;msg=(Message Content)
                                    </div>
                                    <div class="portlet-body flip-scroll">
                                        <table class="table table-bordered table-striped table-condensed flip-content">
                                            <thead class="flip-content">
                                            <tr>
                                                <th> Parameter Name </th>
                                                <th> Meaning/Value </th>
                                                <th> Description </th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td> api_key </td>
                                                <td> API Key </td>
                                                <td> Your API Key <strong id="key_id_ref">({{ $user->APIKEY }})</strong> </td>
                                            </tr>
                                            <tr>
                                                <td> type </td>
                                                <td> text/unicode </td>
                                                <td> text for normal SMS/unicode for Bangla SMS</td>
                                            </tr>
                                            <tr>
                                                <td> contacts </td>
                                                <td> mobile number </td>
                                                <td> Exp: 88017XXXXXXXX+88018XXXXXXXX+88019XXXXXXXX... </td>
                                            </tr>
                                            <tr>
                                                <td> msg </td>
                                                <td> SMS body </td>
                                                <td> <b>N.B:</b> Please use url encoding to send some special characters like &amp;, $, @ etc </td>
                                            </tr>
                                            <tr>
                                                <td> label </td>
                                                <td> transactional/promotional </td>
                                                <td> use transactional label for transactional sms </td>
                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>


                                    <label>Credit Balance API</label>
                                    <div class="well">
                                        <strong>API URL</strong> : {{route('api.getBalance', ['apikey=>'.$user->APIKEY])}}
                                        <br>
                                        <strong>API URL</strong> : Your API Key <strong id="key_id_ref2">({{ $user->APIKEY }})</strong>
                                    </div>
                                    <label>Delivery Report API</label>
                                    <div class="well">
                                        <strong>API URL</strong> : {{route('api.getDLR', ['apikey=>'.$user->APIKEY])}}
                                        <br>
                                        <strong>API URL</strong> : Your API Key <strong id="key_id_ref3">({{ $user->APIKEY }})</strong>
                                        <br>
                                        <strong>Chunk per Call</strong>: 100
                                        <br>
                                        <strong>SMS Shoot ID</strong> Enter the SMS ID returned when submitted SMS via API. It will return an array having mobile numbers and their DLR status.
                                        <br>{{--
                                        <strong>API URL</strong> : {{route('api.getDLR', ['apikey=>'.$user->APIKEY, 'SMS_SHOOT_ID'=>'55555'])}}--}}


                                    </div>
                                    <label>API Key Retrieval</label>
                                    <div class="well">
                                        <strong>API URL</strong> : {{route('getkey', ['username'=>'username', 'password'=>'password'])}}
                                        <br>
                                        <strong>Username</strong> : Your account User ID used to login.
                                        <br>
                                        <strong>Password</strong> : Account password that you use to login.
                                    </div>
                                    <label>Error Code &amp; Meaning</label>
                                    <div class="well portlet-body flip-scroll">
                                        <table class="table table-bordered table-striped table-condensed flip-content">
                                            <thead class="flip-content">
                                            <tr>
                                                <th> Error Code </th>
                                                <th> Meaning </th>

                                            </tr>
                                            </thead>
                                            <tbody>
                                            <tr>
                                                <td> 1002 </td>
                                                <td> Sender Id/Masking Not Found </td>

                                            </tr>
                                            <tr>
                                                <td> 1003 </td>
                                                <td> API Not Found </td>

                                            </tr>
                                            <tr>
                                                <td> 1004 </td>
                                                <td> SPAM Detected </td>

                                            </tr>
                                            <tr>
                                                <td> 1005 </td>
                                                <td> Internal Error </td>

                                            </tr>

                                            <tr>
                                                <td> 1006 </td>
                                                <td> Internal Error </td>

                                            </tr>
                                            <tr>
                                                <td> 1007 </td>
                                                <td> Balance Insufficient   </td>

                                            </tr>
                                            <tr>
                                                <td> 1008 </td>
                                                <td> Message is empty </td>

                                            </tr>
                                            <tr>
                                                <td> 1009 </td>
                                                <td> Message Type Not Set (text/unicode) </td>

                                            </tr>
                                            <tr>
                                                <td> 1010 </td>
                                                <td> Invalid User &amp; Password </td>

                                            </tr>
                                            <tr>
                                                <td> 1011 </td>
                                                <td> Invalid User Id </td>

                                            </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- BEGIN MODAL -->
    <!-- BEGIN MODAL -->
    <div id="json-one-to-many" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="btn btn-outline-warning" data-dismiss="modal" aria-label="Close">Close</span>
                    <h4 class="modal-title">One To Many JSON Format</h4>
                </div>
                <div class="modal-body"><code style="box-shadow:none; letter-spacing:1px;">
                        {<br>
                        &nbsp; type : "post",<br>
                        &nbsp; url : "{{ route('api.message.get') }}",<br>
                        &nbsp; data : {<br>
                        &nbsp; &nbsp; "api_key" : "{your api key}",<br>
                        &nbsp; &nbsp; "senderid" : "{sender id}",<br>
                        &nbsp; &nbsp; "type" : "{content type}",<br>
                        &nbsp; &nbsp; "scheduledDateTime" : "{schedule date time}",<br>
                        &nbsp; &nbsp; "msg" : "{your message}",<br>
                        &nbsp; &nbsp; "contacts" : "88017xxxxxxxx+88018xxxxxxxx"<br>
                        &nbsp; }<br>
                        }<br>
                    </code></div>
            </div>
        </div>
    </div>
    <div id="json-many-to-many" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="btn btn-outline-warning" data-dismiss="modal" aria-label="Close">Close</span>
                    <h4 class="modal-title">Many To Many JSON Format</h4>
                </div>
                <div class="modal-body"><code style="box-shadow:none; letter-spacing:1px;">
                        {<br>
                        &nbsp; type : "post",<br>
                        &nbsp; url : "{{ route('api.message.get') }}",<br>
                        &nbsp; data : {<br>
                        &nbsp; &nbsp; "api_key" : "{your api key}",<br>
                        &nbsp; &nbsp; "senderid" : "{sender id}",<br>
                        &nbsp; &nbsp; "scheduledDateTime" : "{schedule date time}",<br>
                        &nbsp; &nbsp; "messages" : [<br>
                        &nbsp; &nbsp; &nbsp; {<br>
                        &nbsp; &nbsp; &nbsp; &nbsp; "to" : "88017xxxxxxxx",<br>
                        &nbsp; &nbsp; &nbsp; &nbsp; "message" : "test sms content …"<br>
                        &nbsp; &nbsp; &nbsp; },<br>
                        &nbsp; &nbsp; &nbsp; {<br>
                        &nbsp; &nbsp; &nbsp; &nbsp; "to" : "88018xxxxxxxx",<br>
                        &nbsp; &nbsp; &nbsp; &nbsp; "message" : "test sms content …"<br>
                        &nbsp; &nbsp; &nbsp; }<br>
                        &nbsp; &nbsp; ]<br>
                        &nbsp; }<br>
                        }<br>
                    </code></div>
            </div>
        </div>
    </div>
    <!-- END MODAL -->



    <!-- Start Php modal -->

    <!-- BEGIN MODAL -->
    <div id="php-one-to-many" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="btn btn-outline-warning" data-dismiss="modal" aria-label="Close">Close</span>
                    <h4 class="modal-title">One To Many PHP Format</h4>
                </div>
                <div class="modal-body"><code style="box-shadow:none; letter-spacing:1px;">
                        &lt;?php<br>
                        function send_sms()
                        {<br>
                        &nbsp; $url = "{{ route('api.message.get') }}";<br>
                        &nbsp; $data = [<br>
                        &nbsp; &nbsp; "api_key" => "your_api-key",<br>
                        &nbsp; &nbsp; "type" => "{content type}",<br>
                        &nbsp; &nbsp; "contacts" => "88017xxxxxxxx+88018xxxxxxxx"<br>
                        &nbsp; &nbsp; "senderid" => "{sender id}",<br>
                        &nbsp; &nbsp; "msg" => "{your message}",<br>
                        &nbsp; ];<br>
                        &nbsp; $ch = curl_init();<br>
                        &nbsp; curl_setopt($ch, CURLOPT_URL, $url);<br>
                        &nbsp; curl_setopt($ch, CURLOPT_POST, 1);<br>
                        &nbsp; curl_setopt($ch, CURLOPT_POSTFIELDS, $data);<br>
                        &nbsp; curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);<br>
                        &nbsp; curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);<br>
                        &nbsp; $response = curl_exec($ch);<br>
                        &nbsp; curl_close($ch);<br>
                        &nbsp; return $response;<br>
                        }<br>
                        ?&gt;

                    </code></div>
            </div>
        </div>
    </div>
    <div id="php-many-to-many" class="modal fade" tabindex="-1" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <span class="btn btn-outline-warning" data-dismiss="modal" aria-label="Close">Close</span>
                    <h4 class="modal-title">Many To Many PHP Format</h4>
                </div>
                <div class="modal-body"><code style="box-shadow:none; letter-spacing:1px;">
                        &lt;?php<br>
                        function send_sms()
                        {<br>
                        &nbsp; $url = "{{ route('api.message.get') }}";<br>
                        &nbsp; $data = [<br>
                        &nbsp; &nbsp; "api_key" => "{your api key}",<br>
                        &nbsp; &nbsp; "senderid" => "{sender id}",<br>
                        &nbsp; &nbsp; "messages" => json_encode( [<br>
                        &nbsp; &nbsp; &nbsp; [<br>
                        &nbsp; &nbsp; &nbsp; &nbsp; "to" => "88017xxxxxxxx",<br>
                        &nbsp; &nbsp; &nbsp; &nbsp; "message" => "test sms content …"<br>
                        &nbsp; &nbsp; &nbsp; ],<br>
                        &nbsp; &nbsp; &nbsp; [<br>
                        &nbsp; &nbsp; &nbsp; &nbsp; "to" => "88018xxxxxxxx",<br>
                        &nbsp; &nbsp; &nbsp; &nbsp; "message" => "test sms content …"<br>
                        &nbsp; &nbsp; &nbsp; ]<br>
                        &nbsp; &nbsp; ])<br>
                        &nbsp; ];<br>
                        &nbsp; $ch = curl_init();<br>
                        &nbsp; curl_setopt($ch, CURLOPT_URL, $url);<br>
                        &nbsp; curl_setopt($ch, CURLOPT_POST, 1);<br>
                        &nbsp; curl_setopt($ch, CURLOPT_POSTFIELDS, $data);<br>
                        &nbsp; curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);<br>
                        &nbsp; curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);<br>
                        &nbsp; $response = curl_exec($ch);<br>
                        &nbsp; curl_close($ch);<br>
                        &nbsp; return $response;<br>
                        }<br>
                        ?&gt;
                    </code></div>
            </div>
        </div>
    </div>
    <!-- END MODAL -->


    <!-- End php modal -->
@endsection

@section('pageJS')
    <script>
        $(document).ready(function () {
            $(document).on("click", "#generate_key", function () {
                var id = $(this).data('id');
                var route = "{{ route('config.create.api.post') }}"
                $.ajax(
                    {
                        type: "POST",
                        url: route,
                        data: {id: id, _token: '{{ csrf_token() }}'},
                        cache: false,
                        success: function (response) {
                            console.log(response);

                            $('#api_key').html(response);
                            $('#api_key2').html(response);
                            $('#api_key3').html(response);
                        }
                    });
                return false;
            });

            // $(document).on("click", "#copy_api", function () {
            //     var apikey = $('#api_key').html();
            //     apikey.select();
            //     apikey.setSelectionRange(0, 99999); /* For mobile devices */
            //
            //     /* Copy the text inside the text field */
            //     document.execCommand("copy");
            // });

        });

        $(document).on('click', '.json-one-to-many', function (e) {
            e.preventDefault();
            $("#json-one-to-many").modal("toggle");
        });
        $(document).on('click', '.json-many-to-many', function (e) {
            e.preventDefault();
            $("#json-many-to-many").modal("toggle");
        });

        $(document).on('click', '.php-one-to-many', function (e) {
            e.preventDefault();
            $("#php-one-to-many").modal("toggle");
        });
        $(document).on('click', '.php-many-to-many', function (e) {
            e.preventDefault();
            $("#php-many-to-many").modal("toggle");
        });

        function copyToClipboard(element) {
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(element).text()).select();
            document.execCommand("copy");
            $temp.remove();
        }

    </script>
@endsection
