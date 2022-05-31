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
                    <li class="breadcrumb-item"><a class="ajax-form" href="{{ route('config.domain.list') }}">Domains</a>
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
                            <form class="form form-horizontal" method="post" action="{{ route('config.assign.domain.user.post') }}" enctype="multipart/form-data">
                                {{ csrf_field() }}
                                <div class="form-body">
                                    <h4 class="form-section">{{ $title }}</h4>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label label-control" for="name">Domain Name</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('name') ? 'has-error' : '' }}">
                                            <select class="select-domain form-control custom-select block border-primary" id="name" name="name">
                                                <option value="">Select Domain Name</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('name') }}</span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-md-2 pl-3 col-form-label label-control" for="user">Assign User Name</label>
                                        <div class="col-md-10 pr-3 pl-3 input-element {{ $errors->has('user') ? 'has-error' : '' }}">
                                            <select class="select2 form-control block border-primary" id="user" multiple name="user[]">
                                                <option value="">Select User</option>
                                            </select>
                                            <span class="text-danger">{{ $errors->first('user') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-actions">
                                    <div class="form-group mb-0 justify-content-end row">
                                        <div class="col-md-10 pl-3">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="fa fa-check-square-o"></i> Add
                                            </button>
                                            <button type="button" class="btn btn-warning mr-1">
                                                <a href="{{ route('config.domain.list') }}" class="ajax-form"><i class="feather icon-x"></i> Cancel</a>
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
        $(document).ready(function (){
            $('.select2').select2({
                placeholder: "Select User"
            });

            $(".select-domain").select2({
                width: '100%',
                ajax:{
                    url: '{{ route('select.domain') }}',
                    type: 'GET',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return{
                            searchTerm : params.term,
                            _token : '{{ csrf_token() }}'
                        };
                    },
                    processResults: function (response) {
                        return {
                            results: response
                        };
                    },
                    cache: true
                }
            });

            $('#name').change(function (){
                var name = $(this).val();
                if (name){
                    $.ajax({
                        url: "{{ route('get.domain.user.data') }}",
                        data: {id: name, _token: '{{ csrf_token() }}'},
                        type: "GET",
                        dataType: "json",
                        success: function (data) {
                            $('#user').empty();

                            $.each(data, function (index, regenciesObj) {
                                $('#user').append('<option value="' + regenciesObj.id + '">' + regenciesObj.name + '('+ regenciesObj.username +')</option>');
                            })

                        }
                    });
                } else {
                    $('#user').empty().append('<option value="">Select User</option>');
                }
            });
        });
    </script>
@endsection

