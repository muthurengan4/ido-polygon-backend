@if(Session::has('flash_error'))

    <div class="alert alert-danger alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> {{Session::get('flash_error')}} 
	</div>              

@endif


@if(Session::has('flash_success'))

   	<div class="alert alert-success alert-dismissable">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button> {{Session::get('flash_success')}} 
	</div>

@endif
