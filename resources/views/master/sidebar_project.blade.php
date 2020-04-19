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
                <option value="{{$value['jabatan_id']}}" selected>{{ $value['jabatan']}} </option>
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
        @if ( Session::get('level') == "10")
        <li><a href="{{ url('/')}}/project/detail/?id={{ $project->id }}">Home</a></li>
        @endif
        @if ( Session::get('level') == "1016")
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Master Data Proyek</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('/')}}/project/data-umum/"><i class="fa fa-circle-o"></i> Data Umum Proyek</a></li>
            <!--li><a href="{{ url('/')}}/kontraktor/"><i class="fa fa-circle-o"></i> Master Rekanan</a></li>
            <!--li><a href="{{ url('/')}}/project/unit-hadap/"><i class="fa fa-circle-o"></i> Unit Hadap</a></li-->
            
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Planning</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">            
            <li><a href="{{ url('/')}}/project/kawasan"><i class="fa fa-circle-o"></i> Kawasan</a></li>
            <li><a href="{{ url('/')}}/project/unit-type/"><i class="fa fa-circle-o"></i> Unit Type</a></li>
            <li><a href="{{ url('/')}}/project/unit-hadap/"><i class="fa fa-circle-o"></i> Unit Hadap</a></li>
          </ul>
        </li>      
        @endif  
        <!--li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Pengajuan Biaya</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('/')}}/pengajuanbiaya/"><i class="fa fa-circle-o"></i> Pengajuan Biaya</a></li>
          </ul>
        </li-->        
        @if ( Session::get('level') == "10")
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Budget</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('/')}}/budget/proyek/"><i class="fa fa-circle-o"></i>Budget DevCost</a></li>
            <li><a href="{{ url('/')}}/budget/proyek/"><i class="fa fa-circle-o"></i>Budget ConCost</a></li>
            <li><a href="{{ url('/')}}/budget/proyek/"><i class="fa fa-circle-o"></i>Budget CashOut</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Kontrak</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('/')}}/workorder/"><i class="fa fa-circle-o"></i> Workorder</a></li>
            <li><a href="{{ url('/')}}/tender/"><i class="fa fa-circle-o"></i> Tender</a></li>
            <li><a href="{{ url('/')}}/spk/"><i class="fa fa-circle-o"></i> SPK - BAP</a></li>
            <li><a href="{{ url('/')}}/spk/sik"><i class="fa fa-circle-o"></i> SPK - SIK</a></li>
            <li><a href="{{ url('/')}}/voucher/"><i class="fa fa-circle-o"></i> Voucher</a></li>
            <li><a href="{{ url('/')}}/pekerjaan/coa"><i class="fa fa-circle-o"></i>COA</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Purchase Order</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li class="treeview">
                  <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>Purchase Request</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" style="display: block;">
                      <li><a href="{{ url('/')}}/purchaserequest/"><i class="fa fa-circle-o"></i>Purchase Request</a></li>
                      <li><a href="{{ url('/')}}/tenderpurchaserequest/pengelompokan"><i class="fa fa-circle-o"></i>Pengelompokan PR</a></li>
                      <li><a href="{{ url('/')}}/tenderpurchaserequest/indexOE"><i class="fa fa-circle-o"></i>Pengelompokan OE</a></li>
                      <!-- <li><a href="#"><i class="fa fa-circle-o"></i>Tender PR</a></li> -->
                      <li><a href="{{ url('/')}}/tenderpurchaserequest/index_penawaran"><i class="fa fa-circle-o"></i>Penawaran Tender</a></li>
                      <li><a href="{{ url('/')}}/pemenangtender"><i class="fa fa-circle-o"></i>Pemenang Tender</a></li>
                  </ul>
              </li>
              <li class="treeview">
                  <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>Purchase Order</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" style="display: block;">
                      <li><a href="{{ url('/')}}/purchaseorder/"><i class="fa fa-circle-o"></i>Tambah PO</a></li>
                      <li><a href="{{ url('/')}}/downpaymentpurchaseorder/"><i class="fa fa-circle-o"></i>DP PO</a></li>
                      <li><a href="{{ url('/')}}/penerimaanbarangpo/"><i class="fa fa-circle-o"></i>Penerimaan Barang</a></li>
                      <li><a href="{{ url('/')}}/goodreceive/gr_dp"><i class="fa fa-circle-o"></i>GR DP</a></li>
                      <li><a href="{{ url('/')}}/goodreceive/gr_penerimaan_barang"><i class="fa fa-circle-o"></i>GR Penerimaan Barang</a></li>
                  </ul>
              </li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-bank"></i>
            <span>Inventory</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" >
            <li><a href="{{ url('/inventory/room/index') }}"><i class="fa fa-circle-o"></i>Ruangan</a></li>
            <li><a href="{{ url('/')}}/inventory/warehouse/index"><i class="fa fa-circle-o"></i> Gudang</a></li>
            <li><a href="{{ url('/')}}/inventory/items_project/index"><i class="fa fa-circle-o"></i> Barang Proyek</a></li>
            <li class="divider"><a href="#"> </a></li>

            <li><a href="{{ url('/')}}/inventory/stock/view_stock"><i class="fa fa-circle-o"></i> Kartu Stok</a></li>
            <li><a href="{{ url('/')}}/inventory/permintaan_barang/index"><i class="fa fa-circle-o"></i> Permintaan Barang</a></li>
            <li><a href="{{ url('/')}}/inventory/barang_keluar/index"><i class="fa fa-circle-o"></i> Barang Keluar</a></li>
            <li><a href="{{ url('/')}}/inventory/barangmasuk_hibah/index"><i class="fa fa-circle-o"></i> Barang Masuk Hibah</a></li>
            <li><a href="{{ url('/')}}/inventory/pengembalian_barang/index"><i class="fa fa-circle-o"></i> Pengembalian Barang</a></li>
            <li><a href="{{ url('/')}}/inventory/opname/listPeriod"><i class="fa fa-circle-o"></i> Stock Opname</a></li>
            <li><a href="{{ url('/')}}/inventory/asset/index"><i class="fa fa-circle-o"></i> Asset</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-pie-chart"></i>
            <span>Report</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
              <li class="treeview">
                  <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>HPP</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" >
                      <li><a href="{{ url('/')}}/report/devcost-detail"><i class="fa fa-circle-o"></i>HPP DevCost (Detail)</a></li>
                      <li><a href="{{ url('/')}}/report/devcost-summary"><i class="fa fa-circle-o"></i>HPP DevCost (Summary)</a></li>
                      <li><a href="{{ url('/')}}/report/concost-detail"><i class="fa fa-circle-o"></i>HPP ConCost (Detail Kontrak)</a></li>
                      <li><a href="{{ url('/')}}/report/concost-summary"><i class="fa fa-circle-o"></i>HPP ConCost (Summary Unit)</a></li>
                  </ul>
              </li>
              <li class="treeview">
                  <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>Kontrak</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" >
                      <li><a href="{{ url('/')}}/report/cost-report"><i class="fa fa-circle-o"></i>Cost Report</a></li>
                      <li><a href="{{ url('/')}}/report/report-kontraktor"><i class="fa fa-circle-o"></i>Kontrak by Kontraktor</a></li>
                      <li><a href="{{ url('/')}}/report/report-kawasan"><i class="fa fa-circle-o"></i>Kontrak by Proyek/Kawasan</a></li>
                      <!-- <li><a href="{{ url('/')}}/report/report-pekerjaan"><i class="fa fa-circle-o"></i>Kontrak by Pekerjaan</a></li> -->
                  </ul>
              </li>
              <li class="treeview">
                  <a href="#">
                    <i class="fa fa-pie-chart"></i>
                    <span>Budget</span>
                    <span class="pull-right-container">
                      <i class="fa fa-angle-left pull-right"></i>
                    </span>
                  </a>
                  <ul class="treeview-menu" >
                      <li><a href="#"><i class="fa fa-circle-o"></i>Cash Flow Plan</a></li>
                      <li><a href="#"><i class="fa fa-circle-o"></i>Total Cash Flow Diagram</a></li>
                      <li><a href="#"><i class="fa fa-circle-o"></i>Dev Cash FLow Diagram</a></li>
                      <li><a href="#"><i class="fa fa-circle-o"></i>Cash Flow</a></li>
                  </ul>
              </li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#">
            <i class="fa fa-book"></i>
            <span>Library</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="{{ url('/')}}/library/harga-satuan"><i class="fa fa-circle-o"></i> Harga Satuan</a></li>
            <li><a href="{{ url('/')}}/library/mou"><i class="fa fa-circle-o"></i> MOU</a></li>
            <li><a href="{{ url('/')}}/library/supplier"><i class="fa fa-circle-o"></i> Supplier</a></li>
            <li><a href="{{ url('/')}}/library/analisa-harga-satuan"><i class="fa fa-circle-o"></i> Analisa Harga Satuan</a></li>
          </ul>
        </li>
        @endif
        <li><a href="{{ url('/')}}/logout"><i class="fa fa-logout"></i> Logout</a></li>
      </ul>      
    </section>
    <!-- /.sidebar -->
  </aside>


