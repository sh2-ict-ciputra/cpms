<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Admin QS | Dashboard</title>
  @include("master/header")
</head>
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

  @include("master/sidebar_project")

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>Data Proyek {{ $project->name }}</h1>

    </section>

    <!-- Main content -->
    <section class="content">
      {{ csrf_field() }}
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title">Data Tender</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
              <a href="{{ url('/')}}/tender/add" class="btn-lg btn-primary"><i class="glyphicon glyphicon-plus-sign"></i>Tambah Data Tender</a><br><br>
              <table id="index" class="table table-bordered table-hover">
                <thead class="head_table">
                <tr>
                  <th>No</th>
                  <th>No. Tender </th>
                  <th>No. Rab</th>
                  <th>Pekerjaan</th>
                  <th>Nilai(Rp)</th>
                  <th>Dibuat oleh</th>
                  <th>Tanggal Dibuat</th>
                  <th>Tanggal RAB Approved</th>
                  <th>Detail</th>
                  <th>Status Pemenang</th>
                </tr>
                </thead>
                <tfoot id="tfoot" style="display:table-header-group">
                  <tr>
                    <th></th>
                    <th>No. Tender </th>
                    <th>No. Rab</th>
                    <th>Item Pekerjaan</th>
                    <th>Nilai(Rp)</th>
                    <th>Dibuat oleh</th>
                    <th>Tanggal</th>
                    <th>Tanggal</th>
                    <th>Detail</th>
                    <th>Status</th>
                  </tr>
                </tfoot>
                <tbody>
                  @foreach ( $tenders as $key => $value )
                 
                  <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $value->no }}</td>
                    <td>{{ $value->rab->no or '-' }}</td>
                    <td>{{ $value->name }}</td>
                    <td>{{ number_format($value->rab->nilai)}}</td>
                    <td>
                      @if ( \App\User::find($value->created_by) != "" )
                      {{ \App\User::find($value->created_by)->user_name }}
                      @endif
                    </td>
                    <td>{{ date("d/M/Y", strtotime($value->created_at)) }}</td>
                    <td>@if ( $value->rab->approval != "" ) {{ date("d/M/Y",strtotime($value->rab->approval->updated_at))}} @endif</td>
                    <td><a href="{{ url('/')}}/tender/detail/?id={{ $value->id }}" class="btn btn-warning">Detail</a></td>
                    <td style="text-align:center;">
                      @if ( count($value->menangs) > 0 )
                        {{ $value->menangs->first()->rekanan->group->name }}<br/>                  
                        @if ( count($value->spks) <= 0 )
                          @if ( $value->approval->approval_action_id == "6")
                            <a href="{{ url('/')}}/spk/create/?id={{ $value->id }}" class="btn btn-info">Buat SPK</a>
                          @else
                            Menunggu Approval
                          @endif
                        @else                      
                          <a href="{{ url('/')}}/spk/detail/?id={{ $value->spks->first()->id }}" class="btn btn-info">Detail SPK</a>            
                        @endif
                      @else
                        @php
                          $array = array (
                            "6" => array("label" => "Telah ditunjuk Pemenang", "class" => "label label-success"),
                            "7" => array("label" => "Ditolak", "class" => "label label-danger"),
                            "1" => array("label" => "Pengajuan Rekanan", "class" => "label label-warning"),
                            "8" => array("label" => "Close", "class" => "label label-danger")
                          )
                        @endphp
                          @if ($value->approval != null)
                          @if ($value->rekanans[0]->approval->approval_action_id != 1)
                            @if($value->approval->approval_action_id == 8)
                              <span class="label label-danger" style="font-size: 12px">Tender Closed</span>
                            @else
                              @if (count($value->tender_jadwal_penawaran) > 0)
                                  @if ($value->tunjuk_pemenang_tender != null)
                                    <span class="label label-success">Tunjuk Pemenang</span>
                                  @else
                                    <span class="label label-warning">
                                      Penawaran {{count($value->tender_jadwal_penawaran)}} :
                                      @if( date("Y-m-d",strtotime($tanggal_sekarang)) <= date("Y-m-d",strtotime($value->tender_jadwal_penawaran[count($value->tender_jadwal_penawaran)-1]->penawaran_date)) )
                                        Open
                                      @else
                                        Close
                                      @endif
                                    </span>
                                  @endif
                              @else
                                <span class="label label-success">Hasil Approval Rekanan</span>
                              @endif
                            @endif
                          @else
                            <span  class="{{ $array[$value->approval->approval_action_id]['class'] }}">{{ $array[$value->approval->approval_action_id]['label'] }}</span>
                          @endif
                          @else
                            <span class="label label-warning">Dalam Proses</span>
                          @endif
                      @endif
                    </td>
                  </tr>
                  
                  @endforeach 
                </tbody>
                
              </table>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  @include("master/copyright")
  
  <!-- Add the sidebar's background. This div must be placed
       immediately after the control sidebar -->
  <div class="control-sidebar-bg"></div>
</div>
<!-- ./wrapper -->

@include("master/footer_table")
@include("rab::app")
</body>
<script>
  $(document).ready(function() {
    $('#index tfoot th').each(function (){
      var title = $(this).text();
      var n = (6+title.length)*7;
      $(this).html('<input type="text" placeholder="Filter '+title+'" / style="width:'+n+'px;">' );
    });

    var table = $('#index').DataTable( {
        // "pageLength" : 5,
        // "order": [[ 5, 'desc' ]],
        // "scrollX": true
    } );
    
    var table = $('#index').DataTable();

    table.columns().every(function (){
      var that = this;
      $('input', this.footer()).on('keyup change', function(){
        if(that.search() !== this.value){
          that
            .search(this.value)
            .draw();
        }
      });
    });
    
  } );
</script>
</html>

