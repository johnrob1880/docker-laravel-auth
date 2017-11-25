@extends('layouts.app')

@section('title', __('forms.titles.new-barcode'))

@section('content')
    <div class="row">
        <div class="col-md-6">
            <img class="img-responsive" src="/images/kit.jpg" />
        </div>
        <div class="col-md-6">
            <h1>@lang('forms.phrases.enter-barcode')</h1>
            <form class="form-horizontal" method="POST" action="{{ route('kit.new') }}">
                {{ csrf_field() }}
                <div class="form-group{{ $errors->has('barcode') ? ' has-error' : '' }}">
                    <div class="input-group col-md-6">
                        <input id="barcode" type="text" class="form-control" name="barcode" value="{{ old('barcode') }}" placeholder="ex. 123456789" required autofocus>
                        <span class="input-group-btn">
                            <button class="btn btn-primary" type="submit">@lang('forms.buttons.continue')</button>
                        </span>
                    </div>
                    @if ($errors->has('barcode'))
                        <span class="help-block">
                            <strong>{{ $errors->first('barcode') }}</strong>
                        </span>
                    @endif
                </div>
            </form>
        </div>
    </div>
@endsection

@push('styles')
<style></style>
@endpush