@extends($layout)

@section('title')
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
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collpase show">
                        <div class="card-body">
                            <form class="form form-horizontal" method="post" action="{{ route('balance.buy.bundle.post') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>

                                    <div class="form-group row">
                                        <label class="col-md-2 label-control" for="amount">Available Balance</label>
                                        <div class="col-md-10">
                                            <span>{{ $userWallet['available_balance'] }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 label-control" for="amount">Remain Balance</label>
                                        <div class="col-md-10">
                                            <span class="col-form-label text-danger text-bold-700" id="balance">{{ $userWallet['available_balance'] }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label label-control" for="mSms">Masking SMS</label>
                                                <div class="col-md-8 input-element {{ $errors->has('masking_sms') ? 'has-error' : '' }}">
                                                    <input type="text" id="mSms" class="form-control border-primary" onkeyup="rate_count()" placeholder="Masking SMS Count" name="masking_sms" autocomplete="off" value="0">
                                                    <span class="text-danger">{{ $errors->first('masking_sms') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label text-md-right" for="nSms">Non Masking SMS</label>
                                                <div class="col-md-8 input-element {{ $errors->has('non_masking_sms') ? 'has-error' : '' }}">
                                                    <input type="text" id="nSms" class="form-control border-primary" onkeyup="rate_count()" placeholder="Non Masking SMS Count" name="non_masking_sms" autocomplete="off" value="0">
                                                    <span class="text-danger">{{ $errors->first('non_masking_sms') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @if(config('constants.EMAIL_SERVICE'))
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <div class="form-group row">
                                                <label class="col-md-4 col-form-label text-md-right" for="email">Email</label>
                                                <div class="col-md-8 input-element {{ $errors->has('email') ? 'has-error' : '' }}">
                                                    <input type="text" id="email" class="form-control border-primary" onkeyup="rate_count()" placeholder="Email" name="email" autocomplete="off" value="0">
                                                    <span class="text-danger">{{ $errors->first('email') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-10 pl-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Add
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('balance.buy.bundle.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


@section('pageJS')
    <script>
        function rate_count (){
            var msms = $('#mSms').val();
            var nsms = $('#nSms').val();
            var email = $('#email').val();
            if(msms == '') {
                msms = 0;
                //$('#mSms').val(0);
            }
            if(nsms == '') {
                nsms = 0;
                //$('#nSms').val(0);
            }
            if(email == '') {
                email = 0;
                //$('#email').val(0);
            }

            $.ajax({
                type: "GET",
                url: "{{ route('get.remaining.balance') }}",
                data: {msms: msms, nsms: nsms, email: email, _token: '{{ csrf_token() }}'},
                dataType: "json",
                cache: false,
                success: function (data) {
                    if (data < 0) {
                        alert('Insufficient balance for this bundle');
                        $('#balance').html('{{ $userWallet['available_balance'] }}');
                    } else {
                        $('#balance').html(data);
                    }
                }
            });
        }
    </script>
@endsection
