@extends('frontend.layouts.none')


@section('content')
    <!-- Content
            ============================================= -->
    <section id="content">
        <div class="content-wrap">
            <div class="container">

                <div class="fancy-title title-border title-center">
                    <h2><span style="text-decoration: underline;"><strong>YOU WILL BE MISSED!</strong></span></h2>
                </div>

                @if(Session::has('message'))
                    <div class="style-msg {{ Session::get('m-class') }}">
                        <div class="sb-msg">{!! Session::get('message') !!}</div>
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    </div>
                @endif


                <form action="{{ route('contact.unsubscribe.post') }}" method="post">
                    {{ csrf_field() }}
                    <div class="form-group row">
                        <label class="col-md-2 pl-3 col-form-label">Email</label>
                        <div class="col-md-10 pl-3 pr-3 input-element {{ $errors->has('email') ? 'has-error' : '' }}">
                            <input type="text" class="form-control" placeholder="Email" name="email" value="{{ old('email')?old('email'):$email }}">
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                    </div>
                    <div class="row skin skin-flat">
                        <div class="col-md-6 col-sm-12">
                            <input type="radio" name="remarks" id="remarks-0" value="I am not comfortable" required >
                            <label for="remarks-0">I am not comfortable</label>
                            <br>
                            <input type="radio" name="remarks" id="remarks-1" value="I am receiving too many emails" required>
                            <label for="remarks-1">I am receiving too many emails</label>
                            <br>
                            <input type="radio" name="remarks" id="remarks-2" value="I don't think the content is relevant" required>
                            <label for="remarks-2">I don't think the content is relevant</label>
                            <br>
                            <input type="radio" name="remarks" id="remarks-3" value="I signed up during a promotion" required>
                            <label for="remarks-3">I signed up during a promotion</label>
                        </div>
                    </div>

                    <div style="background: rgb(238, 238, 238) none repeat scroll 0% 0%; border: 1px solid rgb(204, 204, 204); padding: 5px 10px; text-align: center;">
                        <span style="font-family:lucida sans unicode,lucida grande,sans-serif;">
                            <span style="font-size: 36px;">
                                <button type="submit" class='button button2'>
                                    <span><strong>Unsubscribe</strong></span>
                                </button>
                            </span>
                        </span>
                    </div>
                </form>

            </div>
        </div>
    </section><!-- #content end -->
@endsection
