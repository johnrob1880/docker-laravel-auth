@extends('layouts.app')

@section('title', __('forms.titles.payment'))

@section('content')

    <h1>{{ $kit['test_name'] }}</h1>

    @if ($collectAnalysisCost) 
    <h1>Collect Analysis Cost - ${{ $analysisCost }}
    @endif

    @if ($collectTestPrice)
    <h1>Collect Test Price - ${{ $testPrice }}
    @endif

    
    <hr>
    <div class="row">
        <div class="col-sm-6">
            <form method="POST" action="{{ route('kit.cancel', ['barcode' => $barcode]) }}">
                {{ csrf_field() }}
                <input type="hidden" value="{{ $kit->testId }}" name="kit" />
                <input type="hidden" value="{{ $kit['barcode'] }}" name="barcode" />
                <input type="submit" class="btn btn-default" value="{{ __('forms.buttons.start-over') }}" />
            </form>            
        </div>
        <div class="col-sm-6">
            @if ($collectAnalysisCost || $collectTestPrice)
                <a href="{{ route('paypal.express-checkout') }}" class='btn-info btn pull-right'>@lang('forms.buttons.pay-with-paypal')</a>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style></style>
@endpush