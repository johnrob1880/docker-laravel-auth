@extends('layouts.app')

@section('title', __('forms.titles.upgrade'))

@section('content')
    
    <h2 class="text-center">@lang('registration.choose-test-thankyou', ['test' => $kit['test_name']])</h2>
    <h3 class="text-center">@lang('registration.choose-upgrade') <a class="link-reset" href="{{ LocaleRoute::route('kit.payment', [ 'barcode' => $barcode ]) }}">@lang('forms.buttons.continue')</a></h3>
       <br>
    {{--  <div class="text-center">
        @if ($upgrade)
        <form method="POST" action="{{ LocaleRoute::route('upgrade.cancel') }}">
            {{ csrf_field() }}
            <input type="hidden" value="{{ $upgrade->testId }}" name="upgrade" />
            <input type="hidden" value="{{ $kit['barcode'] }}" name="barcode" />
            <div class="row">
                <div class="col-sm-10 col-sm-push-1">
                    <input type="submit" class="btn btn-default btn-block" value="{{ __('forms.buttons.no-thanks') }}" />
                </div>
            </div>
        </form>
        @else
            <div class="row">
                <div class="col-sm-10 col-sm-push-1">
                    <a href="{{ LocaleRoute::route('kit.payment', [ 'barcode' => $barcode ]) }}" class="btn btn-default btn-block">@lang('forms.buttons.no-thanks')</a>
                </div>
            </div>
        @endif
    </div>  --}}
    <br>
    <div class="row">
        <div class="col-sm-10 col-sm-push-1">
            @include('partials.compare-tests', [ 'barcode' => $barcode, 'features' => $features, 'products' => $products, 'column_size' => '4' ])
        </div>
    </div>
    
@endsection

@push('styles')
<style></style>
@endpush