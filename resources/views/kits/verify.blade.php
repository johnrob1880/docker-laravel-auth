@extends('layouts.app')

@section('title', __('forms.titles.verify'))

@section('content')

    @if (session('invoice-id'))
        <h1>@lang('registration.thank-you-order')</h1>
        <h2>@lang('registration.order-confirmation-number'){{ session('invoice-id') }}</h2>
        
        <p>@lang('registration.confirmation-email-msg')</p>
    @endif

    @if (!$edit_mode)
    <h2>Please verify your personal information</h2>
    <ul class="nav nav-list">
        <li>@lang('forms.fields.firstname'): {{ Auth::user()->firstname }}</li>
        <li>@lang('forms.fields.lastname'): {{ Auth::user()->lastname }}</li>
        <li>@lang('forms.fields.date-of-birth'): {{ Carbon\Carbon::parse(Auth::user()->date_of_birth)->format(__('dates.format')) }}</li>
    </ul>
    @else
        <form id="updateInfoForm" method="POST" class="form-horizontal" action="{{ LocaleRoute::route('kit.verify') }}">
            {{ csrf_field() }}
            <div>
                <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                    <label for="firstname" class="col-md-2 control-label">@lang('forms.fields.firstname'):</label>

                    <div class="col-md-6">
                        <input id="firstname" type="text" class="form-control" name="firstname" value="{{ Auth::user()->firstname  }}" required>

                        @if ($errors->has('firstname'))
                            <span class="help-block">
                                <strong>{{ $errors->first('firstname') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                    <label for="lastname" class="col-md-2 control-label">@lang('forms.fields.lastname'):</label>

                    <div class="col-md-6">
                        <input id="lastname" type="text" class="form-control" name="lastname" value="{{ Auth::user()->lastname  }}" required>

                        @if ($errors->has('lastname'))
                            <span class="help-block">
                                <strong>{{ $errors->first('lastname') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>

                <div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
                    <label for="date_of_birth" class="col-md-2 control-label">@lang('forms.fields.date-of-birth'):</label>

                    <div class="col-md-6">
                        <input id="date_of_birth" type="date" class="form-control" name="date_of_birth" value="{{ Auth::user()->date_of_birth }}" required>

                        @if ($errors->has('date_of_birth'))
                            <span class="help-block">
                                <strong>{{ $errors->first('date_of_birth') }}</strong>
                            </span>
                        @endif
                    </div>
                </div>
                <input type="submit" name="update" id="update" class="btn btn-primary" value="{{ __('forms.buttons.update') }}" />
            </div>

        </form>
    @endif

    @if (!$edit_mode)
    <form method="POST" action="{{ LocaleRoute::route('kit.verify') }}">
        {{ csrf_field() }}
        <div>
            <input type="submit" name="edit" id="edit" class="btn btn-default" value="{{ __('forms.buttons.change') }}" />
        </div>
        <div>
            <br><br>
            <input type="submit" name="complete" id="complete" class="btn btn-primary" value="{{ __('forms.buttons.complete') }}" />
        </div>
    </form>
    @endif
@endsection

@push('styles')
<style></style>
@endpush