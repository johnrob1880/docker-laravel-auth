<form class="form-horizontal" method="POST" action="{{ route('register') }}">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <label for="email" class="col-md-4 control-label">@lang('forms.fields.email')</label>

        <div class="col-md-6">
            <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
        <label for="firstname" class="col-md-4 control-label">@lang('forms.fields.firstname')</label>

        <div class="col-md-6">
            <input id="firstname" type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required>

            @if ($errors->has('firstname'))
                <span class="help-block">
                    <strong>{{ $errors->first('firstname') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
        <label for="lastname" class="col-md-4 control-label">@lang('forms.fields.lastname')</label>

        <div class="col-md-6">
            <input id="lastname" type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required>

            @if ($errors->has('lastname'))
                <span class="help-block">
                    <strong>{{ $errors->first('lastname') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
        <label for="date_of_birth" class="col-md-4 control-label">@lang('forms.fields.date-of-birth')</label>

        <div class="col-md-6">
            <input id="date_of_birth" type="date" class="form-control" name="date_of_birth" value="{{ old('date_of_birth') }}" required>

            @if ($errors->has('date_of_birth'))
                <span class="help-block">
                    <strong>{{ $errors->first('date_of_birth') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('terms') ? ' has-error' : '' }}">
        <label for="terms" class="col-md-4 control-label">@lang('forms.fields.terms-conditions')</label>

        <div class="col-md-6">            
            <div class="terms-conditions">@include('auth.terms-conditions')</div>
            <p class="text-left">
                <input id="terms" type="checkbox"  name="terms" value="1" @if (old('terms' )) checked="checked" @endif required>
                <span> @lang('forms.phrases.terms-accept')</span>
            </p>

            @if ($errors->has('terms'))
                <span class="help-block">
                    <strong>{{ $errors->first('terms') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('results_via_email') ? ' has-error' : '' }}">
        <label for="results_via_email" class="col-md-4 control-label"></label>

        <div class="col-md-6">
            <p class="text-left">
                <input id="results_via_email" type="checkbox" value="1" name="results_via_email"  @if (old('results_via_email' )) checked="checked" @endif>
                <span> @lang('forms.phrases.results-via-email')</span>
            </p>
        </div>
    </div>
    <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
        <div class="col-md-6 col-md-offset-4">
           <div class="g-recaptcha" data-sitekey="{{ Config::get('recaptcha.public_key')}}"></div>
           @if ($errors->has('g-recaptcha-response'))
                <span class="help-block">
                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                </span>
            @endif
        </div>
    </div>    
    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-primary btn-block">
                @lang('forms.buttons.sign-up')
            </button>
        </div>
    </div>

    
</form>