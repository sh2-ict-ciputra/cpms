<a href="" id="last-document"><small>{{ $document->no }}</small></a>
<script type="text/javascript">
	$("#last-document").click(function() 
	{
		$("#div_content").load("{!! \Session::get('last-document') !!}");
		return false;
	});
</script>