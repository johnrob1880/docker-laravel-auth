<div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left">@lang('forms.titles.preferences')</h4>
                    <div class="btn-group pull-right">
                        <a href="{{ LocaleRoute::route('preferences.edit') }}" class="btn btn-transparent btn-sm">@lang('forms.buttons.edit')</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="media">
                        <div class="media-body">
                            @lang('forms.fields.results'): @if (Auth::user()->results_via_email ) <span class="text-success"><strong>&check;</strong></span> <span>{{ Auth::user()->email}}</span> @else <span class="text-danger"><strong>----------</strong></span> @endif                            
                        </div>
                    </div>
                </div>
            </div>