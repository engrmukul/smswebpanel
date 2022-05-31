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
    <section id="configuration">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <button link="{{ route('user.add') }}" class="btn btn-primary btn-md submit"><i class="d-md-none d-block feather icon-plus"></i>
                            <span class="d-md-block d-none">Add User</span>
                        </button>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="feather icon-minus"></i></a></li>
                                <li><a data-action="reload"><i class="feather icon-rotate-cw"></i></a></li>
                                <li><a data-action="expand"><i class="feather icon-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body card-dashboard">
                            <div class="table-responsive">
                                @include('backend.pages.elements.tableboard')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('pageJS')
    <script>
        (function($){
            $.fn.extend({
                //plugin name - statusmenu
                statusMenu: function(options) {
                    //Settings list and the default values
                    var defaults = {
                        delay: 500,
                        items: [{value:'#',title:'Active'},{value:'#',title:'Pending'},{value:'#',title:'Inactive'},{value:'#',title:'Processing'}],
                        offset:[26,10],
                        cycle:['#FFFFFF','#ffffff']
                    };
                    var options = $.extend(defaults, options || {});
                    var list='';
                    $.each(options.items,function(i,el){
                        list +='<li id="change_status_menu" class="cursor-pointer">'+el['title']+'</li>';
                    });
                    var container=$('<ul class="dropdown-menu" role="menu">'+list+'</ul>');
                    $('body').append(container);
                    return this.bind('click',function(e) {
                        var o =options;
                        //Assign current element to variable,
                        var obj = $(this),menu = $(container);
                        var offset = $(this).offset(),sid=obj.data('id'),stype=obj.data('type');
                        $('div.statusmenu').hide();
                        menu.css({'top':(offset.top+o.offset[0])+'px','left':(offset.left+o.offset[1])+'px'});
                        //Get all LI in the UL
                        //var items = $("li", obj);
                        //Change the color according to odd and even rows
                        $("li:even", menu).css({
                            'background-color' : o.cycle[0],
                            'padding-left' : '15px'
                        });
                        $("li:odd", menu).css({
                            'background-color' : o.cycle[1],
                            'padding-left' : '15px'
                        });
                        var i=0;
                        $('li', menu).each(function(){
                            val=o.items[i++]['value'];
                            //if there is any javascript function in value;
                            $(this).data('id', sid);
                            $(this).data('value', val);
                            $(this).data('type', stype);
                        });
                        e.stopPropagation();
                        $(document).click(function(){
                            menu.hide();
                        });
                        menu.show();
                        return false;
                    })

                } // Function to close

            });
        })(jQuery);
    </script>

    @include('backend.layouts.datatable-dynamic_without_button')

    <script>
        function myFunction(name, url, id) {
            if(confirm("Are you want to login as "+ name +"?")){
                event.preventDefault();
                document.getElementById('cloneuser'+id).action = url;
                document.getElementById('cloneuser'+id).submit();
            } else {
                event.preventDefault();
            }
        }
    </script>

    <script>
        $(document).ready(function ($) {
            var menuItems=[
                    {title:'<i class="fa fa-check-circle text-success"> Active</i>',value:'Active'},
                    {title:'<i class="fa fa-times-circle text-danger"> Inactive</i>',value:'Inactive'}
                ];
            $(document).on("click", "#status", function () {
                $("span#status").statusMenu({items: menuItems});
            });
            $(document).on("click", "#dipping", function () {
                $("span#dipping").statusMenu({items: menuItems});
            });
        });

        $(document).on("click", "#change_status_menu", function () {
            var type = $(this).data('type');
            if (type == 'status') {
                var route = '{{ route('user.status', ':id') }}';
                var change_id = '.status_change_id_';
            } else {
                var route = '{{ route('user.dipping', ':id') }}';
                var change_id = '.dipping_change_id_';
            }

            var status_id = $(this).data('id');
            var status_value = $(this).data('value');
            route = route.replace(':id', status_id);

            console.log(route);
            // var parent = $(this).parent().parent();
            $.ajax(
                {
                    type: "POST",
                    url: route,
                    data: {status: status_value, _token: '{{ csrf_token() }}'},
                    cache: false,
                    success: function (data) {
                        var obj = JSON.parse(data);
                        $('.dropdown-menu').hide();
                        $(change_id + obj.id).html(obj.data);
                    }
                });
            return false;
        });

    </script>
@endsection
