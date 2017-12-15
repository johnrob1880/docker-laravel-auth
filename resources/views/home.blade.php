@extends('layouts.app')

@section('title', 'Home')

@section('content')
<div id="home" class="container">
    <div class="row">
        <div class="col-md-6">
            <div class="panel panel-primary">
            
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left">@lang('forms.titles.kits')</h4>
                    <div class="btn-group pull-right">
                        <a href="{{ LocaleRoute::route('kit.new')}}" class="btn btn-transparent btn-sm">@lang('forms.buttons.add-test')</a>
                    </div>
                </div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                        @foreach ($kits as $kit) 
                            <div class="media">
                                <div class="pull-left text-center">
                                    <img class="img-rounded" data-src="holder.js/128x128?theme=default&text=Kit Image"><br>
                                    <i class="fa fa-barcode"></i> {{ $kit->barcode }}
                                </div>
                                <div class="media-body">
                                    <h4 class="title">
                                        {{ $kit->test_name }}
                                        <span>@lang('products.registered-on') {{ Carbon\Carbon::parse($kit->created_at)->format(__('dates.format')) }}</span>
                                    </h4>
                                    @if ($kit->is_complete)
                                        <a class="btn btn-sm btn-transparent btn-transparent-dark" href="{{ LocaleRoute::route('kit.view', [ 'id' => $kit->id]) }}">@lang('forms.buttons.details')</a>
                                    @else
                                        <a class="btn btn-sm btn-primary" href="{{ LocaleRoute::route('kit.continue', [ 'id' => $kit->id]) }}">@lang('forms.buttons.complete')</a>
                                        <form id="removeKitForm" method="post" action="{{ LocaleRoute::url('/kit/remove') }}">
                                            {{ csrf_field() }}
                                            <input type="hidden" name="kit_id" id="kit_id" value="{{ $kit->id }}">
                                            <button type="submit" class="btn btn-sm btn-warning">@lang('forms.buttons.cancel')</button>
                                        </form>
                                    @endif
                                    &nbsp;<a class="btn btn-sm btn-transparent btn-transparent-dark" href="{{ LocaleRoute::route('kit.instructions', [ 'name' => __('products.' . $kit->test_id . '.slug')]) }}">@lang('forms.buttons.instructions')</a>
                                </div>
                            </div>
                        @endforeach

                    <br><br>
                </div>
            </div>
           
        </div>
        <div class="col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading clearfix">
                    <h4 class="panel-title pull-left">@lang('forms.titles.profile')</h4>
                    <div class="btn-group pull-right">
                        <a href="{{ LocaleRoute::route('profile.edit') }}" class="btn btn-transparent btn-sm">@lang('forms.buttons.edit')</a>
                    </div>
                </div>
                <div class="panel-body">
                    <div class="media">
                        <div class="pull-left text-center">
                            <img class="img-circle" data-src="holder.js/128x128?theme=default&text=Profile Image">                                    
                        </div>
                        <div class="media-body">
                            <span class="media-meta pull-right"></span>
                            <h4 class="title">
                                {{ Auth::user()->fullName}}  
                                <span>{{ Auth::user()->email }}</span>                              
                            </h4>
                            <p class="summary">                                
                                @lang('forms.fields.date-of-birth'): {{ Carbon\Carbon::parse(Auth::user()->date_of_birth)->format(__('dates.format')) }}<br>                                
                            </p>                            
                        </div>
                    </div>
                </div>
            </div>
            @include('partials.preferences')
        </div>
    </div>
</div>
@endsection
@push('footer_scripts')
<script src="{{ asset('js/home.js') }}"></script>
@endpush
