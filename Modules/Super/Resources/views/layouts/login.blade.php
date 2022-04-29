<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="author" content="crivion">
    <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}" sizes="128x128" />
    <meta name="_token" content="{{ csrf_token() }}" />

    <title>{{ empty($title) ? 'Admin - WYI' : $title.' | Admin - WYI' }}</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">

    <!-- FA CSS -->
    <link rel="stylesheet" href="{{ asset('css/fa/css/all.min.css') }}">

    <!-- jQuery UI CSS -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css') }}" />

    <!-- APP CSS -->
    <link href="{{ asset('css/app-v2x.css') }}" rel="stylesheet">


    <!-- Chrome for Android theme color -->
    <meta name="theme-color" content="{{ config('pwa.manifest.theme_color') }}">

    <!-- Views CSS -->
    @stack( 'extraCSS' )

    <!-- Custom CSS from admin panel -->
    


  </head>
  <body>
  <div id="wrap">
    <div id="main">

        @yield('content')

    </div>
  </div>
    <script src="{{ asset('js/jquery.min.js') }}"></script>

    <!-- Popper JS -->
    <script src="{{ asset('js/popper.min.js') }}"></script>

    <!-- Twitter Bootstrap 4 Lib -->
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>

    <!-- jQuery UI JS -->
    <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui-touch-punch/0.2.3/jquery.ui.touch-punch.min.js"></script>

    <!-- FA JS -->
    <script src="{{ asset('css/fa/js/all.min.js') }}"></script>

    <!-- Clipboard JS -->
    <script src="{{ asset('js/clipboard.min.js') }}"></script>

    <!-- Growl JS -->
    <script src="{{ asset('js/jquery.growl.js') }}"></script>

    <!-- Ajax Form -->
    <script src="{{ asset('js/jquery.form.min.js') }}"></script>

    <!-- jquery jscroll -->
    <script src="{{ asset('js/jquery.jscroll.min.js') }}"></script>

    <!-- SweetAlert JS -->
    <script src="{{ asset('js/sweetalert.min.js') }}"></script>

    <!-- App JS -->

    @if($errors->any())
    <script type="text/javascript">
        var errorList = '';
        @foreach ($errors->all() as $error)
            errorList += '{{ $error }}. ';
        @endforeach

        swal({ title   : '', icon    : 'error', text : errorList });
        
    </script>
    @endif

    

    <!-- Extra JS -->
    @stack( 'extraJS' )

  </body>
</html>