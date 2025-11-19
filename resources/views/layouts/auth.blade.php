<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Login')</title>

    <link rel="shortcut icon" href="/mazer/assets/static/images/logo/favicon.svg" type="image/x-icon" />

    <!-- Use compiled CSS from CDN so files work without Vite -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css" />

    <style>
        html,
        body {
            height: 100%;
        }
    </style>
    <link rel="stylesheet" href="/mazer/assets/custom/login.css" />
</head>

<body>
    <script src="/mazer/assets/static/js/initTheme.js"></script>

    {{-- <div class="auth-wrapper"> --}}
    <div class="">
        @yield('content')
    </div>
    {{-- </div> --}}

    <script src="/mazer/assets/static/js/components/dark.js" type="module"></script>
    <script src="/mazer/assets/static/js/components/sidebar.js" type="module"></script>
</body>

</html>
