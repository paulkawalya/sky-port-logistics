<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>{{ config('app.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=0.9, maximum-scale=0.9, user-scalable=0">
        <meta content="For the Freight" name="description" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <link rel="shortcut icon" href="{{ asset('app-assets/img/logo/sifcargo-icon.png') }}">

        <!-- Force browser zoom to 90% -->
        <style>
            html {
                -ms-text-size-adjust: 90%;
                -webkit-text-size-adjust: 90%;
            }
            body {
                margin: 0;
                padding: 0;
                font-size: 111.11%; /* Compensate for the 90% zoom */
            }
        </style>
        <script>
            // Force zoom reset on every page load
            (function() {
                // Function to reset zoom level
                function resetZoom() {
                    // Try to reset the viewport
                    var viewport = document.querySelector('meta[name="viewport"]');
                    if (viewport) {
                        viewport.setAttribute('content', 'width=device-width, initial-scale=0.9, maximum-scale=0.9, user-scalable=0');
                    }

                    // Force zoom level with CSS transform as a fallback
                    document.documentElement.style.transform = 'scale(0.9)';
                    document.documentElement.style.transformOrigin = '0 0';
                    document.documentElement.style.width = '111.11%';
                    document.documentElement.style.height = '111.11%';
                    document.documentElement.style.overflow = 'auto';
                }

                // Reset zoom on page load
                resetZoom();

                // Reset zoom after DOM is fully loaded
                document.addEventListener('DOMContentLoaded', resetZoom);

                // Reset zoom after any resources finish loading
                window.addEventListener('load', resetZoom);

                // Reset zoom after any resize event (which could be caused by zoom)
                var resizeTimer;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeTimer);
                    resizeTimer = setTimeout(resetZoom, 100);
                });
            })();
        </script>

        <!-- <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" /> -->
        <!-- Plugin -->
        <link rel="stylesheet" type="text/css" href="/app-assets/css/plugin/swiper-bundle.min.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/icons/iconly/index.min.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/icons/remix-icon/index.min.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/colors.css">

        <!-- Base -->
        <link rel="stylesheet" type="text/css" href="/app-assets/css/base/typography.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/base/base.css">

        <!-- Theme -->
        <link rel="stylesheet" type="text/css" href="/app-assets/css/theme/colors-dark.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/theme/theme-dark.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/custom-rtl.css">

        <!-- Layouts -->
        <link rel="stylesheet" type="text/css" href="/app-assets/css/layouts/sider.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/layouts/header.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/layouts/page-content.css">
        <link rel="stylesheet" type="text/css" href="/app-assets/css/components.css">

        <!-- Charts -->
        <link rel="stylesheet" type="text/css" href="/app-assets/css/plugin/apex-charts.css">

        <!-- Pages -->
        <link rel="stylesheet" type="text/css" href="/app-assets/css/pages/dashboard-analytics.css">


        <style>
            body.authentication-bg-pattern {
                background-color: #FFFFFF;
            }
        </style>
    </head>

    <body>



                <!-- @if (session('bad'))
                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        {{ session('bad') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 fade show" role="alert">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif -->



                @yield("content")



        <!-- <script src="/assets/js/vendor.min.js"></script>
        <script src="/assets/js/app.min.js"></script> -->
        <!-- Plugin -->
        <script src="app-assets/js/plugin/jquery.min.js"></script>
        <script src="app-assets/js/plugin/bootstrap.bundle.min.js"></script>
        <script src="app-assets/js/plugin/swiper-bundle.min.js"></script>
        <script src="app-assets/js/plugin/jquery.mask.min.js"></script>
        <script src="app-assets/js/plugin/autocomplete.min.js"></script>
        <script src="app-assets/js/plugin/moment.min.js"></script>

        <!-- Layouts -->
        <script src="app-assets/js/layouts/header-search.js"></script>
        <script src="app-assets/js/layouts/sider.js"></script>
        <script src="app-assets/js/components/input-number.js"></script>

        <!-- Base -->
        <script src="app-assets/js/base/index.js"></script>

        <!-- Custom -->
        <script src="assets/js/main.js"></script>

    </body>
</html>