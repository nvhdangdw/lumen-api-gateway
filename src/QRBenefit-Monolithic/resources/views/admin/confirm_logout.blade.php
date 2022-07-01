@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-8">
			<div class="card">

				<div class="card-body">
					<h5>You must login by account administrator</h3>
					<h5>Do you want to logout?</h3>
					<a class="btn btn-primary" href="{{ route('admin_logout') }}">
						Logout
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
