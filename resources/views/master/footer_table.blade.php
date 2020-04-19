<!-- jQuery 3 -->
<script src="{{ url('/')}}/assets/bower_components/jquery/dist/jquery.min.js"></script>
<!-- Bootstrap 3.3.7 -->
<script src="{{ url('/')}}/assets/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
<!-- DataTables -->
<script src="{{ url('/')}}/assets/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
<script src="{{ url('/')}}/assets/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
<!-- SlimScroll -->
<script src="{{ url('/')}}/assets/bower_components/jquery-slimscroll/jquery.slimscroll.min.js"></script>
<!-- FastClick -->
<script src="{{ url('/')}}/assets/bower_components/fastclick/lib/fastclick.js"></script>
<!-- AdminLTE App -->
<script src="{{ url('/')}}/assets/dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{ url('/')}}/assets/dist/js/demo.js"></script>
<script src="{{ url('/')}}/assets/plugins/jquery.number.min.js" type="text/javascript"></script>

<!-- iCheck 1.0.1 -->
<script src="{{ url('/')}}/assets/plugins/iCheck/icheck.min.js"></script>
<!-- page script -->
<script>
  $(function () {
    $('#example1').DataTable();
    $('#example2').DataTable({
      'paging'      : true,
      'lengthChange': false,
      'searching'   : true,
      'ordering'    : false,
      'info'        : true,
      'autoWidth'   : false
    });

    var url = window.location;
    var element = $('ul.sidebar-menu a').filter(function () {
      return this.href == url.replace('#', '') || url.href.replace('#', '').indexOf(this.href) == 0;
    });
    $(element).parentsUntil('ul.sidebar-menu', 'li').addClass('active');
  })

var waitingDialog = waitingDialog || (function ($) {
    'use strict';

  // Creating modal dialog's DOM
  var $dialog = $(
    '<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
    '<div class="modal-dialog modal-m modal-sm">' +
    '<div class="modal-content">' +
      '<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
      '<div class="modal-body">' +
        '<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
      '</div>' +
    '</div></div></div>');

  return {
    /**
     * Opens our dialog
     * @param message Custom message
     * @param options Custom options:
     *          options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
     *          options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
     */
    show: function (message, options) {
      // Assigning defaults
      if (typeof options === 'undefined') {
        options = {};
      }
      if (typeof message === 'undefined') {
        message = 'Mohon Tunggu ...';
      }
      var settings = $.extend({
        dialogSize: 'm',
        progressType: '',
        onHide: null // This callback runs after the dialog was hidden
      }, options);

      // Configuring dialog
      $dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
      $dialog.find('.progress-bar').attr('class', 'progress-bar');
      if (settings.progressType) {
        $dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
      }
      $dialog.find('h3').text(message);
      // Adding callbacks
      if (typeof settings.onHide === 'function') {
        $dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
          settings.onHide.call($dialog);
        });
      }
      // Opening dialog
      $dialog.modal();
    },
    /**
     * Closes dialog
     */
    hide: function () {
      $dialog.modal('hide');
    }
  };

})(jQuery);

$('body').tooltip({
    selector: '[rel=tooltip]'
  });
  $('input').attr('autocomplete','off');

  $(document).ready(function() {
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
              console.log(data.data)
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
  });
</script>
