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
<aside class="main-sidebar sidebar-dark-primary">
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
        <hr class="mb-5" style="border-top:0">
        <ul class="sidebar-menu" data-widget="tree">
            <li class="header">MAIN NAVIGATION</li>
            <ul class="sidebar-menu" data-widget="tree">
                @php
                    $i = 0;
                    $approval = \Modules\Approval\Entities\ApprovalHistory::where("user_id",$user->id)->where("approval_action_id","<=",1)->orderBy("id","desc")->get();
                @endphp
                @foreach ( $approval as $key => $kunci )
                    @if ($kunci->approval != null)
                        @if ($kunci->approval->approval_action_id == 1)
                            @php
                            $bool = 0;
                            @endphp
                            @if ($kunci->document_type == "Modules\Tender\Entities\Tender")
                                @if ($kunci->document != null)
                                    @if ($kunci->document->rekanans[0]->approval->approval_action_id != 1)
                                        @php
                                            $bool = 1;
                                        @endphp
                                    @endif
                                @endif
                            @endif

                            @if ($bool == 0)
                                @if ( $kunci->document_type != "Modules\Tender\Entities\TenderRekanan" && $kunci->document_type != "Modules\Tender\Entities\TenderMenang"  && $kunci->document_type != "Modules\Budget\Entities\BudgetDetail" && $kunci->document_type != "Modules\BudgetDraft\Entities\BudgetDraft" && $kunci->document_type != "Modules\PurchaseRequest\Entities\PurchaseRequestDetail" && $kunci->document_type != "Modules\PenerimaanBarangPO\Entities\PenerimaanBarangPODetail")
                                    @if (isset($kunci->document->nilai))
                                        @if ( $kunci->document->project != "")
                                            @php
                                                $i++;
                                            @endphp
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endif
                    @endif
                @endforeach
                <li><a href="{{ url('/') }}"><i class="fa fa-file"></i>Approval 
                    @if($i > 0)
                        <span class="label label-warning">{{$i}}</span></a>
                    @endif
                </li>
                <li><a href="{{ url('/') }}/access/history"><i class="fa fa-file">
                    </i>History Approval 
                </li>
                {{-- <li class="treeview">
                    <a href="#">
                        <i class="fa fa-pie-chart"></i>
                        <span>Budget</span>
                        <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li><a href="{{ url('/')}}/budget/proyek/"><i class="fa fa-circle-o"></i>Budget</a></li>
                    </ul>
                </li> --}}
                @if (1<=$user->details->where('user_jabatan_id',1)->count())
                    <li><a href="{{ url('/') }}"><i class="fa fa-file"></i>All Approval</a></li>
                @endif
                {{-- <li class="treeview">
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
                </li> --}}
                <li><a href="{{ url('/')}}/logout"><i class="fa fa-sign-out"></i> Logout</a></li>
            </ul>
        </ul>
    </section>
</aside>