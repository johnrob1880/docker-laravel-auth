@extends('layouts.app')

@section('content')
    @yield('content')
@endsection

@push('styles')
<style lang="css" scoped>
    #app {
        min-height: 600px;
        background: url('/images/bg_connected.png');
    }
    #app > .navbar.navbar-default {
        background-color: transparent !important;
        border-color: transparent;
    }
    #app > .navbar #loginBtn {
        font-weight: normal;
        color: #000000;
        line-height: 22px;
        border: 1px solid #dcdcdc;
    }
</style>
@endpush