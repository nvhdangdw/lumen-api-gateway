@extends('admin_template')
@section('customer_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Edit Customer</h1>
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
				@if (!$customer)
					<div class="alert alert-danger" role="alert">
						Page not found
					</div>
				@else
					<div class="card card-primary">
						<form action="{{ route('admin_customer_update',['id' =>$customer->customer_id ]) }}" method="POST">
							@csrf
							<div class="card-body">
								<div class="form-group">
									<label for="input-first-name">First Name</label>
									<input type="text" value="@if(!empty(old('first_name'))){{ old('first_name') }}@else{{ $customer->first_name }}@endif" name="first_name" class="form-control" id="input-first-name" placeholder="First Name" >
									@if (!empty($errors->first('first_name')))
									<small id="first-name-error" class="form-text text-danger">{{ $errors->first('first_name') }}</small>
									@endif
								</div>
								<div class="form-group">
									<label for="input-last-name">Last Name</label>
									<input type="text" value="@if(!empty(old('last_name'))){{ old('last_name') }}@else{{ $customer->last_name }}@endif" name="last_name" class="form-control" id="input-last-name" placeholder="Last Name" >
									@if (!empty($errors->first('last_name')))
									<small id="last-name-error" class="form-text text-danger">{{ $errors->first('last_name') }}</small>
									@endif
								</div>
								<div class="form-group">
									<label for="input-email">Email</label>
									<input type="email" value="@if(!empty(old('email'))){{ old('email') }}@else{{ $customer->email }}@endif" name="email" class="form-control" id="input-email" placeholder="Email" >
									@if (!empty($errors->first('email')))
									<small id="email-error" class="form-text text-danger">{{ $errors->first('email') }}</small>
									@endif
								</div>
								<div class="form-group">
									<label for="input-phone-number">Phone Number</label>
									<input type="text" value="@if(!empty(old('phone_number'))){{ old('phone_number') }}@else{{ $customer->phone_number }}@endif" name="phone_number" class="form-control" id="input-phone-number" placeholder="Phone Number" >
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
				@endif
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>
@endsection