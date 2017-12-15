@extends('layouts.app')

@section('title', __('forms.title.invalid'))

@section('content')
    <p>This kit: {{ $barcode }} is currently in an invalid state!</p>
    <hr>
    <div class="row">
        <div class="col-sm-6">
            <a href="{{ LocaleRoute::route('kit.new', [ 'barcode' => $barcode ]) }}" class="btn btn-default">@lang('forms.buttons.start-over')</a>
        </div>
        <div class="col-sm-6">
        </div>
    </div>
@endsection

@push('styles')
<style></style>
@endpush