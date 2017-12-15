@extends('layouts.app')

@section('title', 'View Kit')

@section('content')
    <div id="view">
        <h1>{{ $kit->test_name }} @lang('products.test')</h1><br>
        <div class="row">
            <div class="col-sm-6">
                <bootstrap-panel>
                    <div slot="header">@lang('products.product-details')</div>
                    <div slot="body">
                        @include('partials.product-details')
                    </div>
                </bootstrap-panel>
            </div>
            <div class="col-sm-6">
                <bootstrap-panel>
                    <div slot="header">@lang('products.results')</div>
                    <div slot="body">
                        @if ($inquiry->linkedToResult)
                            <i class="fa fa-file-pdf-o"></i> {{ $inquiry->result }} &nbsp;&nbsp;                            
                            <a href="#download" class="pull-right"><i class="fa fa-download"></i> @lang('products.download')</a>
                        @else
                            @lang('products.results-pending')
                        @endif
                    </div>
                </bootstrap-panel>
                @if ($invoices->count())
                    @foreach ($invoices as $invoice)
                    <bootstrap-panel>
                        <div slot="header">@lang('products.invoice-number'){{ $invoice->paypal_invoice_id }}</div>
                        <div slot="body">                            
                            <div>
                                <strong>@lang('products.invoice-date'):</strong> {{ Carbon\Carbon::parse($invoice->created_at)->format(__('dates.format')) }}<br>                                
                            </div>
                            <br>
                            <table class="table">
                                <tbody>
                                    @if ($invoice->analysis_cost > 0)
                                        <tr>
                                            <td>@lang('products.analysis-cost')</td>
                                            <td>@money($invoice->analysis_cost)</td>
                                        </tr>
                                    @endif
                                    @if ($invoice->test_price > 0)
                                        <tr>
                                            <td>
                                                @lang('products.upgrade-cost')
                                                @if ($kit->upgraded_from_test_id)
                                                    <br>(<small>@lang('products.' . $kit->upgraded_from_test_id . '.product')</small>)
                                                @endif
                                            </td>
                                            <td>@money($invoice->test_price)</td>
                                        </tr>
                                    @endif
                                    <tr>
                                        <td>@lang('products.total-paid')</td>
                                        <td>@money($invoice->total) <i class="fa fa-paypal"></i></td>
                                    </tr>
                                </tbody>
                            </table>                         
                        </div>
                    </bootstrap-panel>
                    @endforeach  
                @endif
            </div>
        </div>
        
        
    </div>
@endsection

@push('footer_scripts')
<script src="{{ asset('js/view.js') }}"></script>
@endpush

@push('styles')
<style></style>
@endpush