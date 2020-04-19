<div align="center">
	<btn type="submit" class="btn green showtoast" name="btn-save" id="showtoast" style="min-width: 100px;">
		{{ isset($is_edit) ? 'EDIT' : 'ADD' }}
	</btn>
</div>

<div class="alert alert-danger alert-div" style="display: none;">
    <ul id="alert-ul"></ul>
</div>

<script type="application/javascript">

	$(function() {

		$('#showtoast').click(function()
		{
			if ( $(this).attr("disabled") == "disabled" ) {
				return false;
			}

			$("#showtoast").html('LOADING').attr('disabled','disabled');

			@foreach($variables as $variable)
				var {{ $variable }} = $("#{{ $variable }}").val();
			@endforeach

			@foreach($arrays as $array)
				var {{ $array }} = $("input[name^='{{ $array }}'],select[name^='{{ $array }}']").serializeArray();
			@endforeach

			var token = $('input[name=_token]').val();
			
			$.post("{{ $url }}", 
				{
					@foreach($variables as $variable)
						{{ $variable }}: {{ $variable }},
					@endforeach
					@foreach($arrays as $array)
						{{ $array }}: {{ $array }},
					@endforeach
					_token: token
				},
				function(data) 
				{
					$("#div_content").html(data, function()
						{
							$("#div_content").fadeIn('fast');
						}
					);
				}
			).fail(function(data){
				
				var errors = data.responseJSON;
	        	console.log(errors);

	        	$('.alert-div').show().prepend( errors.message );
	        	$('#alert-ul').html("");

	        	$.each(errors.errors, function (key, val) 
	        	{
					$('#alert-ul').append('<li>'+val[0]+'</li>');
				});

				@if(isset($is_edit))
					$("#showtoast").html("EDIT").removeAttr("disabled"); 
				@else
					$("#showtoast").html("SAVE").removeAttr("disabled"); 
				@endif
			});
		});
	});


</script>