@extends('layouts.app')
@section('title', 'Login')
@section('content')
<div class="row">
    <div class="col-md-6 text-center">
        <h1>To Register Your Kit</h1>
        <h2>Sign in or Sign Up<h2>
        <img class="img-responsive" src="/images/kit.jpg" />
    </div>
    <div class="col-md-6">
        <br>
        <ul class="nav nav-tabs auth-tabs">
            <li><a href="{{ url('/register') }}">@lang('forms.buttons.sign-up')</a></li>
            <li class="active"><a data-toggle="tab" href="#signin">@lang('forms.buttons.sign-in')</a></li>
        </ul>
        <div class="tab-content">
            <div id="signin" class="tab-pane fade active in">
                <br>
                @include('auth.login-form')
            </div>
        </div>
    </div>
</div>
@endsection
