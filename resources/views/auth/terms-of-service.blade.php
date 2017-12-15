@extends('layouts.app')
@section('title', __('forms.fields.terms-conditions'))
@section('content') 
    <div class="row">
        <div class="col-sm-8 col-sm-push-2 col-xs-10 col-xs-push-1">
            <h1 class="text-center">@lang('forms.fields.terms-conditions')</h1>
            @include('auth.terms-conditions')
        </div>
    </div>    
@endsection
