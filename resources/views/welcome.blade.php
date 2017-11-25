@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <div class="row">
        <div class="col-md-6">
            <img class="img-responsive" src="/images/kit.jpg" />
        </div>
        <div class="col-md-6 text-center" v-cloak>
            <register-options>
                <h1 slot="heading">Register Your Kit</h1>
                <div slot="register">
                    <h1>Register</h1>
                    @include('auth.register-form')
                </div>
                <div slot="login">
                    <h1>Sign In</h1>
                    @include('auth.login-form')
                </div>
            </register-options>
        </div>
    </div>
@endsection

@push('styles')
<style></style>
@endpush