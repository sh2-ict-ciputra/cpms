
<!-- jQuery 3 -->
<script src="{{ url('/') }}/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ url('/') }}/assets/bower_components/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button);
  $('input').attr('autocomplete','off');
</script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ url('/') }}/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- Morris.js charts -->
<script src="{{ url('/') }}/assets/bower_components/raphael/raphael.min.js"></script>
<script src="{{ url('/') }}/assets/bower_components/morris.js/morris.min.js"></script>
<!-- Sparkline -->
<script src="{{ url('/') }}/assets/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js"></script>
<!-- jvectormap -->
<script src="{{ url('/') }}/assets/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js"></script>
<script src="{{ url('/') }}/assets/plugins/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<!-- jQuery Knob Chart -->
<script src="{{ url('/') }}/assets/bower_components/jquery-knob/dist/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="{{ url('/') }}/assets/bower_components/moment/min/moment.min.js"></script>
<script src="{{ url('/') }}/assets/bower_components/bootstrap-daterangepicker/daterangepicker.js"></script>
<!-- datepicker -->
<script src="{{ url('/') }}/assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<!-- Bootstrap WYSIHTML5 -->
<script src="{{ url('/') }}/assets/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js"></script>
<!-- Slimscroll -->
<script src="{{ url('/') }}/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="{{ url('/') }}/assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{ url('/') }}/assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ url('/') }}/assets/dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ url('/') }}/assets/dist/js/demo.js"></script>
<script src="{{ url('/')}}/assets/plugins/jquery.number.min.js" type="text/javascript"></script>

<script>
  $(document).ready(function() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-Token': $('input[name=_token]').val()
        }
      });
    $('#ganti_project').change(function(){
      var url = "{{ url('/')}}/getJabatan";
      $.ajax({
          type: 'post',
          dataType: 'json',
          url: url,
          data: {
            project_id: $(this).val(),
            user_id: $('#user_untuk_rubah').val(),
          },

          success: function(data) { 
            var html = '';
              var i;
              for(i=0; i<data.data.length; i++){
                  html += '<option value="'+data.data[i]['jabatan_id']+'">'+data.data[i]['jabatan_name']+'</option></br>';
              }
              $('#ganti_jabatan').html(html);   
          },

      });
    });

    $('#ganji_project_jabatan').click(function(){
      var url = "{{ url('/')}}/changeProjectJabatan";
      $.ajax({
          type: 'post',
          dataType: 'json',
          url: url,
          data: {
            project_id: $('#ganti_project').val(),
            user_id: $('#user_untuk_rubah').val(),
            jabatan_id: $('#ganti_jabatan').val(),
          },

          success: function(data) { 
            // console.log(data.data)
            window.location.replace("{{ url('/')}}"+data.data);
          },

      });
    //   var project_id= $('#ganti_project').val();
    //   var user_id= $('#user_untuk_rubah').val();
    //   var jabatan_id= $('#ganti_jabatan').val();
    //   if ( jabatan_id == "10" || jabatan_id == "1016" ){
    //       $request->session()->put('level', $value['level'] );
    //       return redirect("/project/detail?id=".$value['project_id']);
    //   }else{
    //       $request->session()->put('level', '');
    //       return redirect("/access");
    //   }
    });
  });
</script>