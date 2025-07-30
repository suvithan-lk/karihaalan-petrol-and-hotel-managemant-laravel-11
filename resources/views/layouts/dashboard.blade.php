    <!DOCTYPE html>
    <html lang="zxx" class="js">

    <head>

        <meta charset="utf-8">
        <meta name="author" content="Softnio">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="A powerful and conceptual apps base dashboard template that especially build for developers and programmers.">
        <!-- Fav Icon  -->
        <link rel="shortcut icon" href="{{asset('images/favicon.png')}}">
        <!-- Page Title  -->
        <title>@yield('title')</title>
        <!-- StyleSheets  -->
        <link rel="stylesheet" href="{{asset('css/dashlite.css?ver=3.2.3')}}">
        <link id="skin-default" rel="stylesheet" href="{{asset('css/theme.css?ver=3.2.3')}}">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">

    {{--  <script src="{{asset('js/tailwind.js')}}" async></script> --}}
    </head>

    <body class="nk-body bg-lighter npc-general has-sidebar ">
        <div class="nk-app-root">
            <!-- main @s -->
            <div class="nk-main ">
                @include('partials.aside')
                <!-- sidebar @e -->
                <!-- wrap @s -->
                <div class="nk-wrap ">
                    @include('partials.header')
                    <!-- content @s -->
                    @section('content')

                    @show
                    <!-- content @e -->
                    @include('partials.footer')
                </div>
                <!-- wrap @e -->
            </div>
            <!-- main @e -->
        </div>
        <!-- app-root @e -->
        @include('partials.models')
        <!-- JavaScript -->
        <!-- Add these links in the head section of your layout file -->


    <!-- Add this script at the bottom of your layout file or inside a script tag -->

        <script src="{{asset('js/bundle.js')}}"></script>
        <script src="{{asset('js/scripts.js')}}"></script>
        <script src="{{asset('js/charts/chart-ecommerce.js')}}"></script>
        <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    </body>

    </html>
