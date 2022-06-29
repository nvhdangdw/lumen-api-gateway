@extends('admin_template')
@section('store_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Detail Store</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{ route('admin_dashbroard') }}">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('admin_store_index') }}">Store</a></li>
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
				@if (!$store)
					<div class="alert alert-danger" role="alert">
						Page not found
					</div>
				@else
				<div class="card card-primary">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td>Full Name</td>
								<td>{{ $store->name }}</td>
							</tr>
							<tr>
								<td>Email</td>
								<td>{{ $store->email }}</td>
							</tr>
							<tr>
								<td>Phone Number</td>
								<td>{{ $store->phone_number }}</td>
							</tr>
							<tr>
								<td>Logo</td>
								<td>
									@if (!empty($store->logo))
									<img src="{{ asset('storage/'.$store->logo) }}" style="width: 140px;">
									@endif
								</td>
							</tr>
							<tr>
								<td>Default Tax</td>
								<td>{{ $store->default_tax }}</td>
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