@extends('layouts.app')

@section('title', __('forms.titles.edit-preferences'))

@section('content')
<div class="row">
    <div class="col-sm-10 col-sm-push-1">
        <h1 class="title">@lang('forms.titles.preferences')</h1>
        <div>
            <form id="editPreferencesForm" method="POST" action="{{LocaleRoute::route('preferences.edit')}}">
                {{ csrf_field() }}
                <div id="destinationGroup" class="form-group{{ $errors->has('destination') ? ' has-error' : '' }}">
                    <label for="destination">@lang('forms.fields.results'):</label>
                    <input id="destination" type="checkbox" name="destination" value="1"{{ Auth::user()->results_via_email ? ' checked="checked"' : '' }}>
                    {{ Auth::user()->email}}
                    @if ($errors->has('destination'))
                        <span class="help-block">
                            <strong>{{ $errors->first('destination') }}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    <button type="submit" onclick="window.omegaquant.submitForm(this, '', 'editPreferencesForm', true);" class="btn btn-primary">
                        @lang('forms.buttons.save-changes')
                    </button>
                    <a href="{{ LocaleRoute::route('home') }}" class="btn btn-default pull-right">@lang('forms.buttons.cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
