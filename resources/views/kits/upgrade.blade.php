@extends('layouts.app')

@section('title', __('forms.titles.upgrade'))

@section('content')
    <h2 class="text-center">@lang('registration.choose-test-thankyou', ['test' => $kit['test_name']])</h2>
    <br>
    <div class="text-center">
        @if ($upgrade)
        <form method="POST" action="{{ route('upgrade.cancel') }}">
            {{ csrf_field() }}
            <input type="hidden" value="{{ $upgrade->testId }}" name="upgrade" />
            <input type="hidden" value="{{ $kit['barcode'] }}" name="barcode" />
            <input type="submit" class="btn btn-primary" value="{{ __('forms.buttons.no-thanks') }}" />
        </form>
        @else
            <a href="{{ route('kit.payment', [ 'barcode' => $barcode ]) }}" class="btn btn-primary">@lang('forms.buttons.no-thanks')</a>
        @endif
    </div>
    <br>
    <div class="row">
        <div class="col-sm-4">
            <h4><strong>{{ $kit['test_name'] }}</strong></h4>
            <p>${{ number_format($kit['test_price'], 2, '.', ',') }}</p>
            <button class="btn btn-primary" disabled>@lang('forms.buttons.selected')</button>
        </div>
    @foreach ( $upgrades as $upgrade)
        <div class="col-sm-4 upgrade-column">
            <form method="POST" action="{{route('kit.upgrade', ['barcode' => $barcode])}}">
                {{ csrf_field() }}
                <h4><strong>{{ $upgrade->test }}</strong></h4>
                <p>${{ number_format($upgrade->testPrice, 2, '.', ',') }}</p>
                <input type="hidden" name="testId" id="testId" value="{{ $upgrade->testId }}" />
                <input type="hidden" name="test" id="test" value="{{ $upgrade->test }}" />
                <input type="submit" class="btn btn-primary" value="{{ __('forms.buttons.upgrade') }}" />
            </form>
        </div>
    @endforeach
    </div>

    
@endsection

@push('styles')
<style></style>
@endpush