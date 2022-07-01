@extends('admin_template')
@section('customer_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">New Customer</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{ route('admin_dashbroard') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin_customer_index') }}">Customer</a></li>
				</ol>
			</div><!-- /.col -->
		</div><!-- /.row -->
	</div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->

<!-- Main content -->
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card card-primary">
					<form action="{{ route('admin_customer_create') }}" method="POST">
						@csrf
						<div class="card-body">
							<div class="form-group">
								<label for="input-first-name">First Name</label>
								<input type="text" value="{{ old('first_name') }}" name="first_name" class="form-control" id="input-first-name" placeholder="First Name" >
								@if (!empty($errors->first('first_name')))
								<small id="first-name-error" class="form-text text-danger">{{ $errors->first('first_name') }}</small>
								@endif
							</div>
							<div class="form-group">
								<label for="input-last-name">Last Name</label>
								<input type="text" value="{{ old('last_name') }}" name="last_name" class="form-control" id="input-last-name" placeholder="Last Name" >
								@if (!empty($errors->first('last_name')))
								<small id="last-name-error" class="form-text text-danger">{{ $errors->first('last_name') }}</small>
								@endif
							</div>
							<div class="form-group">
								<label for="input-email">Email</label>
								<input type="email" value="{{ old('email') }}" name="email" class="form-control" id="input-email" placeholder="Email" >
								@if (!empty($errors->first('email')))
								<small id="email-error" class="form-text text-danger">{{ $errors->first('email') }}</small>
								@endif
							</div>
							<div class="form-group">
								<label for="input-phone-number">Phone Number</label>
								<input type="text" value="{{ old('phone_number') }}" name="phone_number" class="form-control" id="input-phone-number" placeholder="Phone Number" >
								@if (!empty($errors->first('phone_number')))
								<small id="phone-number-error" class="form-text text-danger">{{ $errors->first('phone_number') }}</small>
								@endif
							</div>
						</div>
						<!-- /.card-body -->

						<div class="card-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
				</div>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>
@endsection