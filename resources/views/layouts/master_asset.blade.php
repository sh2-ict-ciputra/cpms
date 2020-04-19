<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="../../favicon.ico">

    <title>@yield('title')</title>
    <!-- Bootstrap core CSS -->
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="{{ url('/') }}/assets/bower_components/bootstrap/dist/css/bootstrap.min.css">
  

  <!-- Font Awesome -->

  <link rel="stylesheet" href="{{ url('/') }}/assets/bower_components/font-awesome/css/font-awesome.min.css">
      <!-- alertify -->
      <link href="{{ URL::asset('assets/global/plugins/alertify/css/alertify.min.css')}}" rel="stylesheet" type="text/css" />
      <link href="{{ URL::asset('assets/global/plugins/alertify/css/default.min.css')}}" rel="stylesheet" type="text/css" />
    <!-- Custom styles for this template -->

    <style>
        body {
          min-height: 2000px;
          padding-top: 60px;
        }
        .push-right{
            text-align: right;
            padding-right: 50% !important;
        }
        .text-center{
            text-align: center;
          }
        .text-right{
            text-align: right;
        }
        .table_master > thead{
          background-color: #3FD5C0;
        }
    </style>
    @yield('css')
  </head>

  <body>

    <!-- Fixed navbar -->
    <nav id="navMenu" class="navbar navbar-default navbar-fixed-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">{{ $project->name }}</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
            <li><a href="{{ url('/') }}">Beranda</a></li>
            <li class="dropdown" id="rotasi">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Rotasi <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ url('/inventory/rotasi/add') }}">Tambah</a></li>
                <li><a href="{{ url('/inventory/rotasi/index') }}">Tampil</a></li>
              </ul>
            </li>
            <li class="dropdown" id="min">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mutasi In <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li role="separator" class="divider"></li>
                <li class="dropdown-header"><strong>Tambah Dari</strong></li>
                <li role="separator" class="divider"></li>
                <li><a href="{{ url('/inventory/mutasi_in/add_from_employee') }}">Individu</a></li>
                <li><a href="{{ url('/inventory/mutasi_in/add_from_proyek') }}">Proyek</a></li>
                <li><a href="{{ url('/inventory/mutasi_in/add_from_rekanan') }}">Rekanan</a></li>
                <li><a href="{{ url('/inventory/mutasi_in/add_from_other') }}">Pihak Lain</a></li>
                <li class="dropdown-header"><strong>Tampilkan</strong></li>
                <li role="separator" class="divider"></li>
                <li><a href="{{ url('/inventory/mutasi_in/index') }}">Tampil</a></li>
              </ul>
            </li>
            <li class="dropdown" id="mou">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Mutasi Out <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li role="separator" class="divider"></li>
                <li class="dropdown-header"><strong>Mutasi Out</strong></li>
                <li role="separator" class="divider"></li>
                <li><a href="{{ url('/inventory/mutasi_out/add') }}">Tambah</a></li>
                <li><a href="{{ url('/inventory/mutasi_out/index') }}">Tampil</a></li>
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li class="dropdown" id="stop">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Stock Opname <span class="caret"></span></a>
              <ul class="dropdown-menu">
                <li><a href="{{ url('/inventory/opname/create_period') }}">Tambah</a></li>
                <li><a href="{{ url('/inventory/opname/listPeriod') }}">Tampil Periode</a></li>
              </ul>
            </li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </nav>

    <div class="container">
      <br/>
      <!-- Main component for a primary marketing message or call to action -->
      @yield('content')

    </div> <!-- /container -->


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
  <script src="{{ url('/')}}/assets/bower_components/jquery/dist/jquery.min.js"></script>
  <!-- Latest compiled and minified JavaScript -->
<script src="{{ url('/')}}/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
   <script type="text/javascript" src="{{ URL::asset('assets/global/plugins/alertify/js/alertify.min.js')}}"></script>
   <script type="text/javascript">
       $.ajaxSetup({
          headers: {
              'X-CSRF-Token': $('meta[name=csrf-token]').attr('content')
          }
      });
        $('body').tooltip({
          selector: '[rel=tooltip]'
        });
   </script>
   @yield('scripts')
  </body>
</html>
