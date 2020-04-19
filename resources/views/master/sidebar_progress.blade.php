  <header class="main-header">
    <!-- Logo -->
    <a href="#" class="logo">
      <!-- mini logo for sidebar mini 50x50 pixels -->
      <span class="logo-mini"><b>A</b></span>
      <!-- logo for regular state and mobile devices -->
      <span class="logo-lg"><b>Admin</b></span>
    </a>
    <!-- Header Navbar: style can be found in header.less -->
    <nav class="navbar navbar-static-top">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>

      @php
        $data_project = [];
        foreach ($user->project_pt_users as $key => $value) {
            # code...
            $status = 1;
            if(count($data_project) != 0){
                for ($i=0; $i < count($data_project); $i++) { 
                    # code...
                    if($data_project[$i]["id"] == $value->project->id){
                        $status = 0;
                        $i = count($data_project) +1;
                    }
                }
            }

            if($status == 1){
                // print_r($value->project->id);
                $arr =[
                    "id" => $value->project->id,
                    "name" => $value->project->name,
                ];
                array_push($data_project, $arr);
            }
        }
      @endphp

      <div class="form-group col-md-3" style="margin-top:7px;">  
        <select class="form-control" name="ganti_project" id="ganti_project">
            @for( $i=0 ; $i < count($data_project) ; $i++)
                @if (session('project_id') == $data_project[$i]['id'])
                  <option value="{{ $data_project[$i]['id']}}" selected>{{ $data_project[$i]["name"]}}</option>
                @else
                  <option value="{{ $data_project[$i]['id']}}">{{ $data_project[$i]["name"]}}</option>
                @endif
            @endfor
        </select>
      </div>
      <div class="form-group col-md-3" style="margin-top:7px;">
        {{-- {{$user->jabatan[1]['jabatan']}} --}}
        @php
            $user_baru = \Modules\User\Entities\User::find($user->id);
        @endphp
        <input type="hidden" id="user_untuk_rubah" value="{{$user_baru->id}}">
        <select class="form-control" name="ganti_jabatan" id="ganti_jabatan">
          @foreach ( $user_baru->jabatan as $key => $value )
              @if(session('level') == $value['level'])
                <option value="{{$value['jabatan_id']}}" selected>{{ $value['jabatan']}}</option>
              @else
                <option value="{{$value['jabatan_id']}}">{{ $value['jabatan']}}</option>
              @endif
          @endforeach
        </select>
      </div>
      {{ csrf_field() }}                  

      <button class="btn btn-warning" type="button" id="ganji_project_jabatan" style="margin-top:7px;">Rubah</button>
      
    </nav>
  </header>
  <!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
      <!-- Sidebar user panel -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="{{ url('/')}}/assets/dist/img/logo-ciputra_original.png" class="img-circle" alt="User Image">
          <br>
        </div>
        <div class="pull-left info">
          <p>{{ $user->user_name }}</p>
          <i class="fa fa-circle text-success"></i> Online
        </div>
      </div>
      <!-- search form -->
      <form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
          <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form>
      <!-- /.search form -->
      <!-- sidebar menu: : style can be found in sidebar.less -->
      <ul class="sidebar-menu" data-widget="tree">
        <li class="header">MAIN NAVIGATION</li>
        @if ( Session::has('level'))
          @if ( Session::get('level') == "superadmin")
          <li><a href="{{ url('/')}}/home">Master Data</a></li>
          @endif
        @endif
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Progress Lapangan</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li><a href="{{ url('/')}}/progress/"><i class="fa fa-circle-o"></i>SPK</a></li>
              {{-- <li><a href="{{ url('/')}}/progress/pengajuan/"><i class="fa fa-circle-o"></i>Pengajuan Pengecekan</a></li> --}}
          </ul>
        </li>
        <li><a href="{{ url('/')}}/logout">Logout</a></li>
      </ul>      
    </section>
    <!-- /.sidebar -->
  </aside>