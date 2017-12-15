@extends('layouts.app')
@section('title', 'Create Password')
@section('content')
@push('footer_scripts')
<script src="{{ asset('js/register.js') }}"></script>
@endpush
<div id="register">
    <busy-panel :busy="isLoading" :opacity="0.1">
        <h1 class="text-center">Create a Password</h1>
        <form id="passwordCreateForm" class="form-horizontal" method="POST" action="{{ LocaleRoute::route('password.create') }}">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-group">
                <label for="email" class="col-md-4 control-label">E-Mail Address</label>

                <div class="col-md-6">
                    <span>{{ $email }}</span>                                
                </div>
            </div>

            <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                <label for="password" class="col-md-4 control-label">Password</label>

                <div class="col-md-6">
                    <input id="password" type="password" class="form-control" name="password" required>

                    @if ($errors->has('password'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('password_confirmation') ? ' has-error' : '' }}">
                <label for="password-confirm" class="col-md-4 control-label">Confirm Password</label>
                <div class="col-md-6">
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>

                    @if ($errors->has('password_confirmation'))
                        <span class="help-block">
                            <strong>{{ $errors->first('password_confirmation') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group">
                <div class="col-md-6 col-md-offset-4">
                    <button type="submit" class="btn btn-primary" onclick="window.omegaquant.submitForm(this, 'working', 'passwordCreateForm');">
                        Create Password
                    </button>
                </div>
            </div>
            <div id="working" style="display: none">
                <i class="fa fa-spinner fa-spin"></i> @lang('forms.phrases.validating')
            </div>
        </form>
    </busy-panel>
</div>
@endsection

