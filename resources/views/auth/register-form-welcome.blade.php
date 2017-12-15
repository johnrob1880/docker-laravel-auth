<form id="registerForm" class="form-horizontal" method="POST" novalidate action="{{ LocaleRoute::route('welcome') }}">
    {{ csrf_field() }}

    <div class="row">
        <div class="col-sm-6"><!-- start #firstname -->
            <div class="form-group{{ $errors->has('firstname') ? ' has-error' : '' }}">
                <input id="firstname" type="text" class="form-control input-lg" name="firstname" value="{{ old('firstname') }}" placeholder="{{ __('forms.fields.firstname') }}" required autofocus>

                @if ($errors->has('firstname'))
                    <span class="help-block">
                        <strong>{{ $errors->first('firstname') }}</strong>
                    </span>
                @endif
            </div>
        </div><!-- end #firstname -->
        <div class="col-sm-6"><!-- start #lastname -->
            <div class="form-group{{ $errors->has('lastname') ? ' has-error' : '' }}">
                 <input id="lastname" type="text" class="form-control input-lg" name="lastname" value="{{ old('lastname') }}" placeholder="{{ __('forms.fields.lastname') }}" required>

                    @if ($errors->has('lastname'))
                        <span class="help-block">
                            <strong>{{ $errors->first('lastname') }}</strong>
                        </span>
                    @endif
            </div>
        </div><!-- end #lastname -->
    </div>
    <div class="row">
        <div class="col-sm-6"><!-- start #email -->
            <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                <input id="email" type="email" class="form-control input-lg" name="email" value="{{ old('email') }}" placeholder="{{ __('forms.fields.email') }}" required>

                    @if ($errors->has('email'))
                        <span class="help-block">
                            <strong>{{ $errors->first('email') }}</strong>
                        </span>
                    @endif
            </div>
        </div><!-- end #email -->
        <div class="col-sm-6"><!-- start #date_of_birth -->
            <div class="form-group{{ $errors->has('date_of_birth') ? ' has-error' : '' }}">
                <input id="date_of_birth" type="text" onfocus="(this.type='date')" title="{{ __('forms.fields.date-of-birth') }}" class="form-control input-lg" name="date_of_birth" value="{{ old('date_of_birth') }}"  placeholder="{{ __('forms.fields.date-of-birth') }}" required>

                @if ($errors->has('date_of_birth'))
                    <span class="help-block">
                        <strong>{{ $errors->first('date_of_birth') }}</strong>
                    </span>
                @endif
            </div>
        </div><!-- end #date_of_birth -->
    </div>
    <div>
        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="g-recaptcha btn btn-primary btn-lg btn-block" data-sitekey="{{ Config::get('recaptcha.public_key')}}" data-callback="onSubmit">
                    @lang('forms.buttons.sign-up')
                </button>
            </div>
        </div>
        <h5 class="text-center">@lang('forms.phrases.terms-accept')&nbsp;<a target="_blank" href="{{ LocaleRoute::route('terms-conditions') }}">@lang('forms.fields.terms-conditions')</a></h5>
    </div>
    <input id="terms" type="hidden"  name="terms" value="1">
    <input id="results_via_email" type="hidden" value="0" name="results_via_email">
    <div id="working" style="display: none">
        <i class="fa fa-spinner fa-spin"></i> @lang('forms.phrases.validating')
    </div>
</form>