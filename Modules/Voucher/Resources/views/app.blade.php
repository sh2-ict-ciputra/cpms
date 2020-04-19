<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
	$(function () {
    $('#tempo').datepicker({
      "dateFormat" : "yy-mm-dd"
    });

    $('#diserahkan').datepicker({
      "dateFormat" : "yy-mm-dd"
    });

    $('#pencairan').datepicker({
      "dateFormat" : "yy-mm-dd"
    });

    $("#tgl_faktur").datepicker({
      "dateFormat" : "yy-mm-dd"
    });

    $("#pph_percent").number(true,2);

  });

  // $(function () {
  //   $('.select2').select2();
  //   $('[data-mask]').inputmask();
  // });

   $( document ).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
      });
  });

  $("#tender_rab").change(function(){
    if ( $("#tender_rab").val() == "Bap"){
      $(".bap").show();
    }else{
      $(".bap").hide();
    }
  });

  // $("#spm").click(function(){
  //   if ( $("#spm").is(":checked")){
  //     $("#btn_simpan").show();
  //   }else{
  //     $("#btn_simpan").hide();
  //   }
  // });

  $("#bap").change(function(){
    var request = $.ajax({
      url : "{{ url('/')}}/voucher/checkbap",
      dataType : "json",
      data : {
        id : $("#bap").val()
      },
      type : "post"
    });

    request.done(function(data){
      // console.log(data.status);
      if ( data.status == 0 ){
          $("#btn_simpan").attr("disabled", true);
          var text = "Coa Finance Tidak Lengkap";
          if ( data.pekerjaan == null ){
            text = text + ", Coa Pekerjaan tidak ada";
          }
          if ( data.pph == null ){
            text = text + ", Coa PPh tidak ada";
          }
          if ( data.ppn == null ){
            text = text + ", Coa Ppn tidak ada";
          }
          if ( data.admin == null ){
            text = text + ", Coa administrasi tidak ada";
          }
          if ( data.denda == null ){
            text = text + ", Coa denda tidak ada";
          }
          $("#label_bap").text(text);
          $("#label_bap").css('color', 'red');
      }else{
          $("#btn_simpan").removeAttr("disabled");  
          $("#label_bap ").text("Coa Finance Lengkap");
          $("#label_bap").css('color', 'green');
      }
    })
  });

  function getgeneral(values){
    $("#voucher_type").val(values);
  }
</script>