<script src="{{ URL::asset('assets/global/plugins/moment.min.js')}}" type="text/javascript"></script>
    <script 
	src="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/js/bootstrap-datetimepicker.min.js" 
	type="text/javascript">
	</script> 
<link href="https://cdnjs.cloudflare.com/ajax/libs/eonasdan-bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
	$(function(){
		$('.datePicker_').datetimepicker({
			format:'YYYY-MM-DD',
			allowInputToggle: true
		});
	});
</script>