@extends('layouts.landing')
@section('title', __('registration.titles.login'))
@section('content')
<div class="row">
    <h1 class="text-center">@lang('registration.login')</h1>
    <div class="row">
        <div class="col-md-6 col-md-push-3 col-sm-8 col-sm-push-2 col-xs-10 col-xs-push-1">
            @include('auth.login-form')
        </div>
        
    </div>    
</div>
@endsection
