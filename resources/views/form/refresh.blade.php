<btn class="navy-button btn sbold blue pull-right" style="margin-left: 10px; margin-right: 10px;" type="button" id="btn-refresh" data-href="{{ url()->full() }}">Refresh</btn>

@if( session('previous') )
	<btn class="navy-button btn sbold blue pull-right" style="margin-left: 10px; margin-right: 10px;" type="button" id="btn-previous" data-href="{{ session('previous') }}">Back</btn>
@endif

{{-- Jquery ada di sidebar menu --}}