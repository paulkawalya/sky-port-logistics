<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'SIFCARGO TONIGHT') }}</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.9, maximum-scale=0.9, user-scalable=0">
    <meta content="We handle cargo of any size" name="description" />
    <meta content="appcellon@gmail.com" name="author" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{asset('app-assets/img/logo/favicon.png')}}">

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

    <!-- <link href="/assets/libs/flatpickr/flatpickr.min.css" rel="stylesheet" type="text/css" /> -->

    <!-- <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/icons.min.css" rel="stylesheet" type="text/css" />
        <link href="/assets/css/app.min.css" rel="stylesheet" type="text/css" /> -->

    <link href="/css/style.css" rel="stylesheet" type="text/css" />

    <!-- Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet" />

    <!-- Plugin -->
    <link rel="stylesheet" type="text/css" href="/app-assets/css/plugin/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/icons/iconly/index.min.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/icons/remix-icon/remixicon.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/bootstrap.css">
    {{-- <link rel="stylesheet" type="text/css" href="/app-assets/css/colors.css"> --}}

    <!-- Base -->
    <link rel="stylesheet" type="text/css" href="/app-assets/css/base/typography.css">
    <link rel="stylesheet" type="text/css" href="/app-assets/css/base/base.css">

    <!-- Theme -->
    {{-- <link rel="stylesheet" type="text/css" href="/app-assets/css/theme/colors-dark.css"> --}}
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

    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.bootstrap5.min.css">
    <link rel="stylesheet" type="text/css" href="/css/responsive-tables.css">

    <!-- Custom CSS with darker success color -->
    <link rel="stylesheet" type="text/css" href="/css/custom.css">

    @yield("styles")

</head>

<body class="collapse-btn-none">
    <main class="hp-bg-color-dark-0 d-flex min-vh-100">
        <!-- side bar goes here -->
        @include('layouts.sidebar')

        <div class="hp-main-layout  bg-white">
            <!-- header goes here -->
            @include('layouts.header')

            <!-- Mobile Floating Logout Button (visible only on small screens) -->
            <div class="d-md-none position-fixed" style="bottom: 20px; right: 20px; z-index: 1050;">
                <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('floating-logout-form').submit();"
                   class="btn btn-danger btn-sm d-flex align-items-center justify-content-center"
                   style="width: 50px; height: 50px; border-radius: 50%;">
                    <i class="ri-logout-box-r-line ri-lg"></i>
                </a>
                <form id="floating-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
            </div>


            <div class="hp-main-layout-content">

                @if (session('status'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="tabler tabler-check-circle"></i> <!-- Tabler check icon -->
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if (session('bad'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="tabler tabler-x-circle"></i> <!-- Tabler x icon -->
                    {{ session('bad') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif

                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="tabler tabler-alert-circle"></i> <!-- Tabler alert icon -->
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                @endif




                @yield('content')



            </div>

            @guest

            @else
            <footer class="w-100 py-18 px-16 py-sm-24 px-sm-32 hp-bg-color-black-20 hp-bg-color-dark-90">
                <div class="row">
                    <div class="col-12">
                        <p class="hp-badge-text fw-semibold mb-0 text-center text-sm-start hp-text-color-dark-30">COPYRIGHT {{ date('Y') }} SIFCARGO TONIGHT, All rights Reserved</p>
                    </div>
                </div>
            </footer>
            @endguest
        </div>
    </main>




    <div class="rightbar-overlay"></div>

    <script src="/assets/js/vendor.min.js"></script>
    <script src="/assets/js/app.min.js"></script>

    <!-- Plugin -->
    <script src="/app-assets/js/plugin/jquery.min.js"></script>
    <script src="/app-assets/js/plugin/bootstrap.bundle.min.js"></script>
    <script src="/app-assets/js/plugin/swiper-bundle.min.js"></script>
    <script src="/app-assets/js/plugin/jquery.mask.min.js"></script>
    <script src="/app-assets/js/plugin/autocomplete.min.js"></script>
    <script src="/app-assets/js/plugin/moment.min.js"></script>

    <!-- Layouts -->
    <script src="/app-assets/js/layouts/sider.js"></script>
    <script src="/app-assets/js/components/input-number.js"></script>

    <!-- Base -->
    <script src="/app-assets/js/base/index.js"></script>

    <!-- Charts -->
    <script src="/app-assets/js/plugin/apexcharts.min.js"></script>
    <script src="/app-assets/js/charts/apex-chart.js"></script>

    <!-- Cards -->
    <script src="/app-assets/js/cards/card-analytic.js"></script>
    <script src="/app-assets/js/cards/card-advance.js"></script>
    <script src="/app-assets/js/cards/card-statistic.js"></script>


    <script src="/js/choosen.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.bootstrap5.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>

    @stack('scripts')

</body>
</html>
