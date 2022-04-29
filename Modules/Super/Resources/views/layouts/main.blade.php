<!DOCTYPE html>
<html lang="en" prefix="og: http://ogp.me/ns# fb: http://ogp.me/ns/fb#">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="author" content="fp">
  <meta name="token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" type="image/png" href="{{ asset('favicon.png') }}" sizes="128x128" />
  <title>{{ empty($title) ? '' : $title.' |' }} Admin - WYI</title>

  <!-- Bootstrap core CSS -->
  <link href="{{ asset( 'assets/bootstrap.min.css' ) }}" rel="stylesheet">
  <link href="{{ asset( 'assets/font-awesome.min.css' ) }}" rel="stylesheet">

  <!-- Custom styles for this template -->
  <link href="{{ asset('assets/sweetalert.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/socialicons.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/select2.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/datatables/jquery.dataTables.min.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/datatables/dataTables.bootstrap.css') }}" rel="stylesheet">
  <link href="{{ asset('assets/bootstrap-wysihtml5.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/toastr.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/admin.css')}}" rel="stylesheet">
  <link href="{{ asset('assets/custom.css')}}" rel="stylesheet">
  <link href="{{ asset('css/ekko-lightbox.css') }}" rel="stylesheet">

  @stack('header-css')
  
  <script>
    window.Laravel = {!! json_encode([
        'csrfToken' => csrf_token(),
    ]) !!};
</script> 
</head>
<body>   
  @include('super::_parts.sidebar', ['active' => $active ?? ''])
  <div class="main">
    @include('super::_parts.header')
    @if( session('msg') )
    <div class="row">
      <div class="col-xs-12">
        <div class="alert alert-info alert-dismissible">
          <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
          <h4><i class="icon fa fa-info"></i> Alert!</h4>
          {{ session('msg') }}
        </div>
      </div>
    </div>
    @endif

    @if (count($errors) > 0)
    <div class="alert alert-danger alert-dismissible">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @yield('extra_top') 
    @yield('content')

    @yield('extra_bottom') 
        
  </div><!-- container fluid -->   

  </body>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js" type="text/javascript"></script>
  <script src="https://unpkg.com/@popperjs/core@2" type="text/javascript"></script>
  <script src="{{ asset( 'assets/js/bootstrap.min.js' ) }}" type="text/javascript"></script>
  <script src="{{ asset('assets/js/sweetalert.min.js') }}"></script>
  <script src="{{ asset('assets/js/raphael-min.js') }}"></script>
  <script src="{{ asset('assets/js/morris.min.js') }}"></script>
  <script src="{{ asset( 'assets/js/select2.full.min.js' ) }}" type="text/javascript"></script>
  <script src="{{ asset('assets/datatables/jquery.dataTables.min.js') }}"></script>
  <script src="{{ asset('assets/datatables/dataTables.bootstrap.min.js') }}"></script>
  {{-- <script src="{{ asset('assets/js/jquery-ui.js') }}"></script> --}}
  <script src="{{ asset('assets/js/jscolor.js') }}"></script>
  <script src="{{ asset('js/ekko-lightbox.min.js') }}"></script>
  <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
  <script src="{{ asset('assets/js/jQuery.config.js') }}"></script>
  {{-- <script src="{{ asset('assets/js/admin.js') }}"></script> --}}
  @include('super::_parts.alert')
  <script>
    $('body').on('click', '[data-toggle="lightbox"]', function (event) {
      event.preventDefault();
      $(this).ekkoLightbox();
    });
    $(function(){
      $('[data-toggle="tooltip"]').tooltip();
    })
  </script>
  @stack('scripts')
  @stack('footer-script')
</html>
