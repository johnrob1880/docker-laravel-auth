@extends('layouts.app')

@section('title', __('forms.titles.mail'))

@section('content')
<div id="mail">
    <h1 class="text-center">@lang('registration.thank-you', [ 'company' => Config::get('app.company_name')])</h1>

    <div class="row">
        <div class="col-sm-8 col-sm-push-2">
            <p class="text-center padding-md">@lang('registration.mail-instructions')</p>
        </div>
    </div>
    <div class="text-center padding-lg">
        <a href="{{LocaleRoute::route('home') }}" class="btn btn-primary">@lang('forms.buttons.go-to-dashboard')</a>
    </div>
</div>
@endsection