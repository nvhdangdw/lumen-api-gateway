@extends('admin_template')
@section('content')
@section('user_active') active @endsection
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">New User</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{ route('admin_dashbroard') }}">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('admin_user_index') }}">User</a></li>
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
					<form action="{{ route('admin_user_create') }}" method="POST">
						@csrf
						<div class="card-body">
							<div class="form-group">
								<label for="input-name">Full Name</label>
								<input type="text" value="{{ old('name') }}" name="name" class="form-control" id="input-name" placeholder="Full Name" >
								@if (!empty($errors->first('name')))
								<small id="name-error" class="form-text text-danger">{{ $errors->first('name') }}</small>
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
								<label for="input-password">Password</label>
								<input type="password" value="{{ old('password') }}" name="password" class="form-control" id="input-password" placeholder="Password" >
								@if (!empty($errors->first('password')))
								<small id="password-error" class="form-text text-danger">{{ $errors->first('password') }}</small>
								@endif
							</div>
							<div class="form-group">
								<label for="input-password">Password Confirm</label>
								<input type="password" value="{{ old('password_confirmation') }}" name="password_confirmation" class="form-control" id="input-password-confirm" placeholder="Password Confirm" >
								@if (!empty($errors->first('password_confirmation')))
								<small id="password-confirm-error" class="form-text text-danger">{{ $errors->first('password_confirmation') }}</small>
								@endif
							</div>
							<div class="form-group">
								<label for="input-discount-unit">Store</label>
								<select name="store_id" class="form-control" id="input-store-id">
								@foreach ($stores as $store)
									<option value="{{ $store->store_id }}" @if(old("store_id")==$store->store_id) selected @endif >{{$store->name}}</option>
								@endforeach
								</select>
								@if (!empty($errors->first('store_id')))
								<small id="discount-unit-error" class="form-text text-danger">{{ $errors->first('store_id') }}</small>
								@endif
							</div>
							<div class="form-group">
								<label for="input-discount-unit">Group User</label>
								<select name="group_user_id" class="form-control" id="input-group-id">
								@foreach ($group_users as $group_user)
									<option value="{{ $group_user->group_user_id }}" @if(old("group_user_id")==$group_user->group_user_id) selected @endif >{{$group_user->name}}</option>
								@endforeach
								</select>
								@if (!empty($errors->first('group_user_id')))
								<small id="discount-unit-error" class="form-text text-danger">{{ $errors->first('group_user_id') }}</small>
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