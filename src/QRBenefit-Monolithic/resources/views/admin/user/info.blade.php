@extends('admin_template')
@section('user_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Detail User</h1>
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
				@if (!$user)
					<div class="alert alert-danger" role="alert">
						Page not found
					</div>
				@else
				<div class="card card-primary">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td>Full Name</td>
								<td>{{ $user->name }}</td>
							</tr>
							<tr>
								<td>Email</td>
								<td>{{ $user->email }}</td>
							</tr>
							<tr>
								<td>Group User</td>
								<td>{{ $user->groupUser->name }}</td>
							</tr>
						</tbody>
					</table>
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