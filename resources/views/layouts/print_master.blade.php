<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Sifcargo Tonight</title>
    <meta name="viewport" content="width=device-width, initial-scale=0.9, maximum-scale=0.9, user-scalable=0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="shortcut icon" href="{{asset('assets/images/logo.png')}}">
    <link href="/css/style.css" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/app-assets/css/bootstrap.css">

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

        /* Adjust print styles to account for zoom */
        @media print {
            html, body {
                transform: none !important;
                width: 100% !important;
                height: auto !important;
                font-size: 100% !important;
                -ms-text-size-adjust: 100% !important;
                -webkit-text-size-adjust: 100% !important;
            }
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

            // Disable zoom for print view
            window.addEventListener('beforeprint', function() {
                // Reset transform for printing
                document.documentElement.style.transform = 'none';
                document.documentElement.style.width = '100%';
                document.documentElement.style.height = 'auto';

                // Reset viewport for printing
                var viewport = document.querySelector('meta[name="viewport"]');
                if (viewport) {
                    viewport.setAttribute('content', 'width=device-width, initial-scale=1.0');
                }
            });

            // Re-apply zoom after printing
            window.addEventListener('afterprint', resetZoom);
        })();
    </script>

    <style>
        @media print {
            #header, #footer {
                display: none;
            }
            #hide_form {
                display: none;
            }
        }

        @media print {
            @page {
                margin-left: 0.5in;
                margin-right: 0.5in;
                margin-top: 0.3in;
                margin-bottom: 0.3in;
            }
        }
    </style>
</head>

<body>
    <!-- Header -->
    <header id="header">
        <!-- Your header content here -->
    </header>

    <!-- Main content -->
    @yield('content')

    <!-- Footer -->
    <footer id="footer">
        <!-- Your footer content here -->
    </footer>

    <script>
        window.print();
    </script>
</body>
</html>
