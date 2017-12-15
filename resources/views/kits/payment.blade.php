@extends('layouts.app')

@section('title', __('forms.titles.payment'))

@section('content')

    <div class="row">
        <div class="col-sm-10 col-sm-push-1">
   
            <?php $test_name = $upgrade ? $upgrade->test : $kit['test_name']; ?>


            <h1>{{ $test_name }}</h1>

            <table id="paymentTable" class="table">
            @if ($collectAnalysisCost) 
                <tr>
                    <td>@lang('products.analysis-cost')</td>
                    <td>@money($analysisCost)</td>
                </tr>
            @endif

            @if ($collectTestPrice)
                <tr>
                    <td>@lang('products.upgrade-cost')</td>
                    <td>@money($testPrice)</td>
                </tr>
            @endif
                <tr class="total-line">
                    <td><strong>@lang('products.total')</strong></td>
                    <td>
                        <strong>@money($testPrice + $analysisCost)</strong>                 
                    </td>
                </tr>
                <tr>
                    <td>
                        <a class="btn btn-default cancel-btn" href="{{ LocaleRoute::route('kit.new') }}">@lang('forms.buttons.start-over')</a>
                        <form id="removeKitForm" method="post" action="{{ LocaleRoute::url('/kit/remove') }}">
                            {{ csrf_field() }}
                            <input type="hidden" name="kit_id" id="kit_id" value="{{ $kit->id }}">
                            <button type="submit" class="btn btn-warning">@lang('forms.buttons.cancel')</button>
                        </form>
                    </td>
                    <td><a href="{{ LocaleRoute::route('paypal.express-checkout') }}" onclick="this.setAttribute('disabled', 'disabled');" class='btn btn-primary payment-btn pull-right'>@lang('forms.buttons.pay-with-paypal', ['amount' => ''])</a></td>
                </tr>
            </table>

         </div>
    </div>
    
@endsection

@push('styles')
<style></style>
@endpush