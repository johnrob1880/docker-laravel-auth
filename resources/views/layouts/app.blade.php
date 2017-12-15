<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'OmegaQuant') }} | @yield('title')</title>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{asset('images/favicon.png')}}" />

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    @stack('styles')

    <!-- Scripts -->
    @stack('header_scripts')
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-default">
            <div class="container">
                <div class="navbar-header">

                    <!-- Collapsed Hamburger -->
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                        <span class="sr-only">Toggle Navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>

                    <!-- Branding Image -->
                    <a class="navbar-brand" href="{{ LocaleRoute::url('') }}">
                        {{ config('app.name', 'Omega Quant') }}
                    </a>
                </div>

                <div class="collapse navbar-collapse" id="app-navbar-collapse">
                    <!-- Left Side Of Navbar -->
                    <ul class="nav navbar-nav">                
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="nav navbar-nav navbar-right">
                        <!-- Authentication Links -->
                        @guest
                            @if (Route::current()->getName() != 'login')
                            <li><a id="loginBtn" class="btn btn-transparent btn-transparent-dark" href="{{ LocaleRoute::route('login') }}">@lang('auth.sign-in')</a></li>
                            @endif
                            {{--  <li><a href="{{ route('register') }}">Register</a></li>  --}}
                        @else
                            @if (Auth::user()->verified)
                                <li><a href="{{ LocaleRoute::route('home') }}">@lang('navigation.dashboard')</a></li>
                                <li><a href="{{ LocaleRoute::route('logout') }}"
                                            onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                            @lang('navigation.logout')
                                    </a>
                                    <form id="logout-form" action="{{ LocaleRoute::route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                            @endif
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content container-fluid">
        @include('partials/flash')
        @yield('content')
        </div>
        @include('partials/footer')
    </div>

    <!-- Scripts -->
    @env('local')
    <script src="{{ asset('js/manifest.js') }}"></script>
    @endenv

    <script src="https://cdnjs.cloudflare.com/ajax/libs/holder/2.9.4/holder.min.js"></script>

    <script src="{{ asset('js/vendor.js') }}"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    
    @stack('footer_scripts')
</body>
</html>
