<script type="text/javascript">
    $.ajaxSetup({
      headers: {
          'X-CSRF-Token': $('input[name=_token]').val()
      }
    });

    $("#btn_submit").click(function(){
        $(".submitbtn").hide();
        $("#loading").show();

        $.ajax({
        url : "{{ url('/partner/store') }}",
        type : "post",
        dataType : "json",
        data : {
            nama : $("#nama").val()
        },
        beforeSend: function() {
            waitingDialog.show();
        },
        success: function(data) {
            window.location.href = "{{ url('/')}}" + data.url;
        },
        complete: function() {
            waitingDialog.hide();
        }
        });
    });

</script>
