<style>
	body #wrap{margin: 0; background: #222222;
}
	#main{height: 100%; display: flex; align-items: center; justify-content: center;}

	/*login*/
 .admin-login-container { width: 100%; }
 .admin-login-container .logo{ text-align: center;margin: 0 0 15px; }
 .admin-login-block { margin: auto; max-width: 500px; background: #000; padding: 30px 30px 20px; border-radius: 30px; -webkit-box-shadow: 6px 6px 13px rgb(0 0 0 / 25%), -4px -4px 8px rgb(255 255 255 / 60%);
    box-shadow: 6px 6px 13px rgb(0 0 0 / 25%), -4px -4px 8px rgb(255 255 255 / 60%);}
 h3.heading { font-size: 20px; font-weight: 900; text-align: center; margin: 0 0 10px; text-transform: uppercase; letter-spacing: .01em; }
 .admin-login-block label { font-weight: 700; font-size: 18px; color: #8f8d8d;margin: 0 0 5px; }
 .admin-login-block .form-control{background: #f1f0ef;
    height: 48px;
    -webkit-box-shadow: inset 6px 6px 4px rgb(0 0 0 / 15%), inset -6px -6px 4px rgb(255 255 255 / 60%);
    box-shadow: inset 6px 6px 4px rgb(0 0 0 / 15%), inset -6px -6px 4px rgb(255 255 255 / 60%);
    border-radius: 59px;
    padding: 9px 20px;}
 .admin-login-block .btn { padding: 2px 18px; line-height: 34px; border-radius: 4px;}
 .admin-login-block .btn:hover { background: #6476ff}
</style>
@extends('super::layouts.login')


@section('content')
<div class="admin-login-container">
	<div class="container">
		
		<div class="admin-login-block">
			<div class="logo">
				<img src="{{ asset('images/logo.svg') }}" alt="logo">
			</div>
			<!-- <h3 class="heading"><i class="glyphicon glyphicon-lock"></i> Login</h3> -->
			
			@if( isset( $message ) AND !empty( $message ) )
			<div class="alert alert-info">
				{{ $message }}
			</div>
			@endif

			<form method="POST" action="{{  route('super.verify') }}" id="admin-login-form">
				{{ csrf_field() }}

				<div class="form-group">
					<label>Email</label>
					<input type="email" name="ausername" placeholder="joedon@gmail.com" class="form-control" required />
				</div>

				<div class="form-group">
					<label>Password</label>
					<input type="password" name="apassword" placeholder="Enter Password" class="form-control" required />
				</div>

				<div class="form-group">
					<button type="submit" class="btn btn-primary">Sign In</button>
				</div>
			</form>
		</div>

	</div>
</div>
@endsection