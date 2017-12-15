@extends('layouts.app')

@section('title', __('forms.titles.instructions'))
@push('footer_scripts')
<script src="{{ asset('js/register.js') }}"></script>
@endpush
@section('content')
    <div id="register">
        <p>Omega-3 Index Plus Instructions</p>
        @include('partials.instructions')
    </div>
@endsection

@push('styles')
<style></style>
@endpush