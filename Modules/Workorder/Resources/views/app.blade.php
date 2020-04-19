<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">

  $( document ).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-Token': $('input[name=_token]').val()
          }
        });
    });

  $(function () {
    $("#luas").number(true);
    $(".number").number(true);
    $(".nilai_budget").number(true);
    
    $('#start_date').datepicker({
      "dateFormat" : "yy-mm-dd"
    });

    $('#end_date').datepicker({
      "dateFormat" : "yy-mm-dd"
    });

    //$(".select2").select2();
    $("#tanggal_workorder").datepicker({
      "dateFormat" : "dd-mm-yy"
    });
  });

  $("#department_from").change(function(){
    $("#detail_item").html("");
    var request = $.ajax({
      url : "{{ url('/')}}/workorder/budget-tahunan",
      data : { 
        department_id : $("#department_from").val()
      },
      type : "post",
      dataType : "json"
    });

    request.done(function(data){
      $("#budget_tahunan").html(data.html);
    })
  });

  $("#budget_tahunan").change(function(){
    $("#tdsa").html("");
    var request = $.ajax({
      url : "{{ url('/')}}/workorder/budget-tahunan/item",
      dataType : "json",
      data : {
        id : $("#budget_tahunan").val()
      },
      type : "post"
    });

    request.done(function(data){
      //$("#tdsa").html(data.html);
      //$(".nilai_budgets").number(true);
    });
  });

  function removeunitswo(id){
  	if ( confirm("Apakah anda yakin ingin menghapus data ini ? ")){
  		var request = $.ajax({
  			url : "{{ url('/')}}/workorder/delete-unit",
	      dataType : "json",
	      data : {
	        id : id
	      },
	      type : "post"
  		});

  		request.done(function(data){

  			window.location.reload();
  		})
  	}else{
  		return false;
  	}
  }

  function woapprove(id){
  		var request = $.ajax({
  			url : "{{ url('/')}}/workorder/approve",
	      dataType : "json",
	      data : {
	        id : id
	      },
	      type : "post",
        beforeSend: function() {
          waitingDialog.show();
          },
          success: function(data) { 
            waitingDialog.hide();
            window.location.replace("{{ url('/')}}/rab/detail?id="+data.rab_id+"&idpkr="+data.idpkr);
          }
  		});

  		// request.done(function(data){
  		// 	window.location.reload();
  		// })
  }

  function woupdapprove(id){
      var request = $.ajax({
        url : "{{ url('/')}}/workorder/updapprove",
        dataType : "json",
        data : {
          id : id
        },
        type : "post"
      });

      request.done(function(data){

        window.location.reload();
      });
  
  }

 function calculatewo(id,parent_id){
    var nilai = $("#nilai_" + id).val();
    var volume = $("#volume_" + id).val();
    var limit = $("#limit_" +parent_id).val();
    var total_value = 0;

    var remStringNilai = nilai.replace(",","");
    var remStringVolume = volume.replace(",","");
    var remStringLimit = limit.replace(",","");

    var intNilai = parseInt(remStringNilai);
    var intVolume = parseInt(remStringVolume);
    var intLimit = parseInt(remStringLimit);
    var intSum = parseInt($("#sum_" + parent_id).val());
    var subtotal = intNilai * intVolume;

    if ( subtotal == "NaN"){
      subtotal = 0;
    }
    
    var total = parseInt(intSum) + parseInt(subtotal);
    $("#subtotal_" + id).text(subtotal);
    $("#subtotals_" + id).val(subtotal);
    if ( intLimit < total ){
      //$("#btn_submit").hide();
      $("#message_" + parent_id).text("Item pekerjaan ini melewati budget tahunan");
    }else{
      $("#btn_submit").show();
      $("#message_" + parent_id).text("");
    }   

    $(".subtotal_" + parent_id).each(function() {
        total_value = parseInt(total_value) + parseInt($(this).val());
    });

    $("#total_" + parent_id).text(total_value);
    $("#subtotal_" + id).number(true);
    $("#total_" + parent_id).number(true); 
 }

 function removeDokumen(id){
    var request = $.ajax({
      url : "{{ url('/')}}/workorder/deletedocument",
      dataType : "json",
      data : {
        id : id
      },
      type : "post"
    });

    request.done(function(data){

      window.location.reload();
    })
 }
</script>