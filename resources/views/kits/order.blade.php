@extends('layouts.app')

@section('title', __('forms.titles.order'))

@section('content')
    @if ($invoice)
        <h1>@lang('registration.thank-you-payment')</h1>
        <h2>@lang('registration.order-confirmation-number'){{ $invoice->paypal_invoice_id }}</h2>
        
        <p>@lang('registration.confirmation-email-msg')</p>
        <br>
        <a href="{{LocaleRoute::route('kit.verify') }}" class="btn btn-primary">@lang('forms.buttons.continue')</a>
    @endif
@endsection

@push('styles')
<style></style>
@endpush