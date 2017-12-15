@extends('layouts.app')
@section('title', 'Register')
@push('header_scripts')
<script src='https://www.google.com/recaptcha/api.js?hl={{ App::getLocale() }}'></script>
@endpush
@push('footer_scripts')
<script src="{{ asset('js/register.js') }}"></script>
@endpush
@section('content') 
<div id="register" class="row">
    <busy-panel :busy="isLoading" :opacity="0.1">
        <div class="col-md-6 text-center">
            <h1>@lang('registration.register-kit')</h1>
            <h2>@lang('registration.sign-in-up')<h2>
            <img class="img-responsive" src="/images/kit.jpg" />
        </div>
        <div class="col-md-6">
            <br>
            <ul class="nav nav-tabs auth-tabs">
                <li class="active"><a data-toggle="tab" href="#signup">@lang('forms.buttons.sign-up')</a></li>
                <li><a href="{{ LocaleRoute::url('/login') }}">@lang('forms.buttons.sign-in')</a></li>
            </ul>
            <div class="tab-content">
                <div id="signup" class="tab-pane fade active in">
                    <br>
                    @include('auth.register-form')
                </div>
            </div>
        </div>
    </busy-panel>
</div>
@endsection
