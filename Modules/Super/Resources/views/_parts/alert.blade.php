@if (Session::has('success'))
<script>
	var msg = "{{Session::get('success')}}";
    success(msg);
</script>
@endif
  
@if (Session::has('error'))
<script>
	var msg = "{{Session::get('error')}}";
    error(msg);
</script>
@endif
   
@if (Session::has('warning'))
<script>
	var msg = "{{Session::get('warning')}}";
    warningAlert(msg);
</script>
@endif
   
@if (Session::has('info'))
<script>
	var msg = "{{Session::get('info')}}";
    infoAlert(msg);
</script>
@endif
  
@if ($errors->any())
	@foreach($errors->all() as $error)
	<script>
		var msg = '{{$error}}';
		error(msg);
	</script>
	@endforeach
@endif
