<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta id="csrf-token" name="csrf-token" value="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <title>@yield('title')</title>

        <!-- Bootstrap -->
        <link href="css/app.css" rel="stylesheet">

        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

        <script>
            window.Laravel = {!! json_encode([
                'csrfToken' => csrf_token(),
            ]) !!};
        </script>
    </head>

    <body>
    <div id="app">
        <div class="row justify-content-center">
            <div class="col-11">
                <h1 class="app-title">Super-hero manager</h1>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="js/app.js"></script>
    </body>
</html>