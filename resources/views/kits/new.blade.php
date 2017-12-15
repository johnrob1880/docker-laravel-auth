@extends('layouts.app')

@section('title', __('forms.titles.new-barcode'))
@push('footer_scripts')
<script src="{{ asset('js/register.js') }}"></script>
@endpush
@section('content')
    <div id="register">
        <busy-panel :busy="isLoading" :opacity="'.1'">
            <div class="row">                
                <div class="col-md-6">
                    <div>
                        <h1 class="text-center">@lang('forms.phrases.enter-barcode')</h1>
                        <form id="searchForm" class="form-horizontal" method="POST" action="{{ LocaleRoute::route('kit.new') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('barcode') ? ' has-error' : '' }}">
                                <div class="input-group col-xs-10 col-xs-push-1">
                                    <input id="barcode" type="text" class="form-control" name="barcode" value="{{ $barcode }}" placeholder="ex. 123456789" required autofocus>
                                    <span class="input-group-btn">
                                        <button class="btn btn-primary" onclick="window.omegaquant.submitForm(this, 'working', 'searchForm')" type="submit">@lang('forms.buttons.continue')</button>
                                    </span>
                                    
                                </div>
                                <div class="input-group col-xs-10 col-xs-push-1">
                                    @if ($errors->has('barcode'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('barcode') }}</strong>
                                        </span>
                                    @endif
                                </div>                                
                            </div>
                            <div id="working" style="display: none">
                                <span style="display: inline-block; width: 60px;"><i class="fa fa-spinner fa-spin"></i></span>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="col-md-6">
                    <img class="img-rounded" data-src="holder.js/100px300?theme=default&text=Barcode Image">
                </div>
            </div>
        </busy-panel>
    </div>
@endsection

@push('styles')
<style>
    #app > div.content {
        padding-bottom: 50px;
    }
</style>
@endpush