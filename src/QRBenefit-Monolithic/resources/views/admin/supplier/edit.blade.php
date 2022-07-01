@extends('admin_template')
@section('supplier_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Edit Supplier</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{ route('admin_dashbroard') }}">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('admin_supplier_index') }}">Supplier</a></li>
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
				@if (!$supplier)
					<div class="alert alert-danger" role="alert">
						Page not found
					</div>
				@else
					<div class="card card-primary">
						<form action="{{ route('admin_supplier_update',['id' =>$supplier->supplier_id ]) }}" method="POST" enctype="multipart/form-data">
							@csrf
							<div class="card-body">
								<div class="form-group">
									<label for="input-name">Full Name</label>
									<input type="text" value="@if (!empty(old('name'))){{ old('name') }}@else{{ $supplier->name }}@endif" name="name" class="form-control" id="input-name" placeholder="Full Name" >
									@if (!empty($errors->first('name')))
									<small id="name-error" class="form-text text-danger">{{ $errors->first('name') }}</small>
									@endif
								</div>
								<div class="form-group">
									<label for="input-email">Email</label>
									<input type="email" value="@if (!empty(old('email'))){{ old('email') }}@else{{ $supplier->email }}@endif" name="email" class="form-control" id="input-email" placeholder="Email" >
									@if (!empty($errors->first('email')))
									<small id="email-error" class="form-text text-danger">{{ $errors->first('email') }}</small>
									@endif
								</div>
								<div class="form-group">
									<label for="input-phone-number">Phone Number</label>
									<input type="text" value="@if (!empty(old('phone_number'))){{ old('phone_number') }}@else{{ $supplier->phone_number }}@endif" name="phone_number" class="form-control" id="input-phone-number" placeholder="Phone Number" >
									@if (!empty($errors->first('phone_number')))
									<small id="phone-number-error" class="form-text text-danger">{{ $errors->first('phone_number') }}</small>
									@endif
								</div>
								<div class="form-group">
									<label for="input-logo">Logo</label>
									<input type="file"  name="logo" class="form-control" id="input-password-confirm" >
									@if (!empty($errors->first('logo')))
									<small id="logo-error" class="form-text text-danger">{{ $errors->first('logo') }}</small>
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