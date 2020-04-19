
Dengan hormat, Dengan ini kami beritahukan kepada Saudara bahwa barang pesanan melalui surat purchase request {{$isi_email[0]["no_pr"]}} Telah tersedia dengan rincian barang sebagai berikut;

<div class="box-body">
    <table id="" class="table table-bordered table-striped dataTable" role="grid" aria-describedby="example1_info">
        <thead style="background-color: gray;">
          <tr >
            <th >No</th>
            <th>Item</th>
            <th>Quantity Dipesan</th>
            <th>Quantity Datang</th> 
            <!-- <th>Satuan</th> -->
          </tr>
          </thead>
          <tbody>
            <?php
              $total_qty = 0;
            ?>
            @foreach($isi_email as $key => $value)
              <tr>
                  <td>{{$key+1}}</td>
                  <td>{{$value["item_name"]}}</td>
                  <td>{{$value["quantity_diminta"]}}</td>
                  <td>{{$value["quantity_diterima"]}}</td>
              </tr>
            @endforeach
          </tbody>  
      </table>
</div>

    <?php
        $md5 = \Illuminate\Support\Facades\Crypt::encrypt(implode( "|" , array( $isi_email[0]["user_login"], $isi_email[0]["password"], $isi_email[0]["id_project"], $isi_email[0]["PT"], $isi_email[0]["department"], 10, $isi_email[0]["id_pr"] ) ));
    ?>

    @include('form.a',
                [
                  'href' => url("/inventory/permintaan_barang/add_form?code=$md5"),
                  'class'=>'pull-right',
                  'caption' => 'Buat Permintaan Barang'
                ])
