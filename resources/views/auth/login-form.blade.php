<form id="loginForm" class="form-horizontal" method="POST" action="{{ LocaleRoute::route('login') }}">
    {{ csrf_field() }}

    <div id="emailGroup" class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <input id="email" type="email" class="form-control input-lg" name="email" value="{{ old('email') }}" placeholder="{{ __('forms.fields.email') }}" required autofocus>

        @if ($errors->has('email'))
            <span class="help-block">
                <strong>{{ $errors->first('email') }}</strong>
            </span>
        @endif
    </div>

    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
        <input id="password" type="password" class="form-control input-lg" name="password" placeholder="{{ __('forms.fields.password') }}" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
    </div>

    <input type="hidden" name="remember" value="0">
    
    <div class="form-group">
        <button type="submit" onclick="window.omegaquant.submitForm(this, 'loggingIn', 'loginForm');" class="btn btn-primary btn-lg btn-block">
            @lang('forms.buttons.sign-in')
        </button>
    </div>
    <p class="text-center">
        <a class="btn btn-link" href="{{ route('password.request') }}">
                @lang('forms.phrases.forgot-password')
            </a>
    </p>
    <p class="text-center">
            @lang('forms.phrases.not-member') <a href="{{ LocaleRoute::route('register') }}">@lang('forms.phrases.register-now')</a>
    </p>
    <div id="loggingIn" style="display: none">
        <i class="fa fa-spinner fa-spin"></i> @lang('forms.phrases.logging-in')
    </div>
</form>
