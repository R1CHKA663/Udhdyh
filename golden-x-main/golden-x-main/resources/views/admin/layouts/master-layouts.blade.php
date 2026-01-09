<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <title> @yield('title')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="GX" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('/images/favicon.png') }}">
    @include('layouts.head-css')
</head>

@section('body')

<body class="has-navbar-vertical-aside navbar-vertical-aside-show-xl   footer-offset">
    @show

    <div id="layout-wrapper">
        @include('layouts.horizontal')

        <main id="content" role="main" class="main">
            <div class="content container-fluid">
                @yield('content')
            </div>
    </div>
    @include('layouts.footer')
    </main>
    @include('layouts.vendor-scripts')
</body>

</html>