<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>@yield('title', 'Dashboard')</title>

    <!-- CDN CSS kept for simplicity (you can download these into public later) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/app-dark.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/compiled/css/iconly.css" />

    <style>
        html,
        body {
            height: 100%;
        }
    </style>
</head>

<body>
    <script src="/mazer/assets/static/js/initTheme.js"></script>

    <div id="app">
        @include('layouts.sidebar')

        <div id="main">
            @include('layouts.header')
            <style>
                /* Ensure main area stretches so footer stays at bottom */
                html,
                body,
                #app {
                    height: 100%;
                }

                #main {
                    display: flex;
                    flex-direction: column;
                    min-height: 100vh;
                }

                main {
                    flex: 1 0 auto;
                }

                footer {
                    flex-shrink: 0;
                }
            </style>

            <main>
                @yield('content')
            </main>

            @include('layouts.footer')
        </div>
    </div>

    <!-- Local JS assets copied from template -->
    <script src="/mazer/assets/static/js/components/dark.js" type="module"></script>
    <script src="/mazer/assets/static/js/pages/dashboard.js"></script>

    <!-- CDN dependencies used by template -->
    <script src="https://cdn.jsdelivr.net/gh/zuramai/mazer@docs/demo/assets/extensions/apexcharts/apexcharts.min.js">
    </script>

    <!-- Compiled template script (enables sidebar submenu behavior, etc) -->
    <script src="/mazer/assets/compiled/js/app.js"></script>

</body>

</html>
