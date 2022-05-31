<!-- BEGIN: Page Vendor CSS-->
@yield('pageVendorCSS')
<!-- END: Page Vendor CSS-->

<!-- BEGIN: Page CSS-->
@yield('pageCSS')
<!-- END: Page CSS-->

<!-- BEGIN: Custom CSS-->
@yield('customCSS')
<!-- END: Custom CSS-->

<div class="content-overlay"></div>
<div class="content-wrapper">
<div class="content-header mt-1">
    @yield('breadcrumb')
</div>
<div class="content-body">
    <section id="content-message" style="clear: both;">
        <div>
            <div class="col-md-12">
                <div class="script-message"></div>
                @if(Session::has('message'))
                    <div class="alert {{ Session::get('m-class') }} alert-dismissible"
                            style="margin-top: 10px;">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                        <!--<h4><i class="icon fa fa-check"></i> Alert!</h4>-->
                        {!! Session::get('message') !!}
                    </div>
                @endif

            </div>
        </div>
    </section>
    @yield('content')
</div>
</div>


<!-- BEGIN: Page Vendor JS-->
@yield('pageVendorJS')
<!-- END: Page Vendor JS-->

 <!-- BEGIN: Page JS-->
 @yield('pageJS')
<!-- END: Page JS-->



<!-- BEGIN: Custom JS-->
<script type="text/javascript">
    $(document).ready(function (){
        document.title = '{{ $title }} - {{ $config->brand_name }}';
        var frm = $('.form');
        frm.submit(function (e) {
            e.preventDefault();

            $('.generalFormSubmit').attr("disabled", true);
            $('.generalFormSubmitSpinner').removeClass("d-none");
            $('.generalFormSubmitButtonText').addClass("d-none");


            var formData = new FormData(this);
            $.ajax({
                url: $(this).attr('action'),
                enctype: 'multipart/form-data',
                type: 'POST',
                data: formData,
                async: false,
                beforeSend: function() {
                    var block_ele = $(this).closest('.card');
                    $(block_ele).block({
                        message: '<span class="semibold"> Loading...</span>',
                        timeout: 2000, //unblock after 2 seconds
                        overlayCSS: {
                            backgroundColor: '#fff',
                            opacity: 0.8,
                            cursor: 'wait'
                        },
                        css: {
                            border: 0,
                            padding: 0,
                            backgroundColor: 'transparent'
                        }
                    });
                },
                success: function (data) {
                    data = JSON.parse(data);
                    $('.modal').modal('toggle');
                    $('.modal .close').click();
                    $.get(data.url).done(function(page_content){
                        $('.app-content').html(page_content);
                    });

                    $('.generalFormSubmit').attr("disabled", false);
                    $('.generalFormSubmitSpinner').addClass("d-none");
                    $('.generalFormSubmitButtonText').removeClass("d-none");
                },
                error: function (reject) {
                    var errors = reject.responseJSON.errors;
                    $.each(errors, function (key, val) {
                        $parentDiv = $('[name^="'+key+'"]').parents('.input-element');
                        $parentDiv.addClass('has-error');
                        $parentDiv.find('.text-danger').html(val);
                    });
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        $('button[link]').on('click', function (e) {
            var elem = '.app-content';
            var load_url = $(this).attr('link');
            if (!load_url) {
                return true;
            }
            window.history.pushState('', '', load_url);
            $.get(load_url).done(function (page_content) {
                $(elem).html(page_content);
            }).fail(function (data, textStatus, xhr) {
                window.location.href = "/smspanel/dashboard";
            });
            return false;
        });
    });
</script>
@yield('customJs')
<!-- END: Custom JS-->

