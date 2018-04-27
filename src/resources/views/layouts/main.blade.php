<!doctype html>
<html lang="en">

<head>
    @include('sgateway::partials.header')
</head>

<body class="bg-light">

    <div class="container">

        @yield('content')

        @include('sgateway::partials.footer')

    </div>

    @include('sgateway::partials.scripts')

</body>

</html>