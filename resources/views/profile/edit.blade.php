@extends('layouts.app')

@section('title', __('forms.title.edit-profile'))

@section('content')
<div class="row">
    <div class="col-sm-10 col-sm-push-1">
        <h1 class="title">@lang('forms.titles.edit-profile')</h1>
        <div>
            <form id="editProfileForm" method="POST" action="{{LocaleRoute::route('profile.edit')}}">
                {{ csrf_field() }}
                <div class="row">
                    <div class="col-sm-6"><!-- start #firstname -->
                        <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                            <input id="firstname" type="text" class="form-control input-lg" name="firstname" value="{{ old('firstname') ? old('firstname') : Auth::user()->firstname }}" placeholder="{{ __('forms.fields.firstname') }}" required autofocus>

                            @if ($errors->has('firstname'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('firstname') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div><!-- end #firstname -->
                    <div class="col-sm-6"><!-- start #lastname -->
                        <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                            <input id="lastname" type="text" class="form-control input-lg" name="lastname" value="{{ old('lastname') ? old('lastname') : Auth::user()->lastname }}" placeholder="{{ __('forms.fields.lastname') }}" required>

                                @if ($errors->has('lastname'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('lastname') }}</strong>
                                    </span>
                                @endif
                        </div>
                    </div><!-- end #lastname -->
                </div>
                <div class="row">
                    <div class="col-sm-6"><!-- start #date_of_birth -->
                        <div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
                            <input id="date_of_birth" type="text" onfocus="(this.type='date')" title="{{ __('forms.fields.date-of-birth') }}" class="form-control input-lg" name="date_of_birth" value="{{ old('date_of_birth') ? old('date_of_birth') : Auth::user()->date_of_birth }}"  placeholder="{{ __('forms.fields.date-of-birth') }}" required>

                            @if ($errors->has('date_of_birth'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('date_of_birth') }}</strong>
                                </span>
                            @endif
                        </div>
                    </div><!-- end #date_of_birth -->
                </div>
                <div class="form-group">
                    <button type="submit" onclick="window.omegaquant.submitForm(this, '', 'editProfileForm', true);" class="btn btn-primary">
                        @lang('forms.buttons.save-changes')
                    </button>
                    <a href="{{ LocaleRoute::route('home') }}" class="btn btn-default pull-right">@lang('forms.buttons.cancel')</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
