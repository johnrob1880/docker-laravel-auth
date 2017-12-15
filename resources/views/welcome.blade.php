@extends('layouts.landing')

@section('title', 'Welcome')

@push('header_scripts')
<script src='https://www.google.com/recaptcha/api.js?hl={{ App::getLocale() }}' async defer></script>
@endpush

@section('content')
    <div id="register">
        <h1 class="text-center">@lang('registration.register-kit')</h1>
        <p class="text-center">@lang('registration.register-message')</p>
        <div class="row">
            <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 col-xs-10 col-xs-push-1">
                {{--  <div class="row">
                    <div class="col-sm-4">
                        <img class="img-rounded" data-src="holder.js/100px100?text=Basic Kit Image">
                    </div>
                    <div class="col-sm-4">
                        <img class="img-rounded" data-src="holder.js/100px100?text=Plus Kit Image">
                    </div>
                    <div class="col-sm-4">
                        <img class="img-rounded" data-src="holder.js/100px100?text=Complete Kit Image">
                    </div>
                </div>
                <br>  --}}
                @include('auth.register-form-welcome')
            </div>
        </div>
        <br><br>
        
    </div>
@endsection
@push('footer_scripts')
<script src="{{ asset('js/register.js') }}"></script>
@endpush
