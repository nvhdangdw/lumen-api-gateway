@extends('admin_template')
@section('customer_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Detail Customer</h1>
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
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td>First Name</td>
								<td>{{ $customer->firstname }}</td>
							</tr>
							<tr>
								<td>Last Name</td>
								<td>{{ $customer->lastname }}</td>
							</tr>
							<tr>
								<td>Email</td>
								<td>{{ $customer->email }}</td>
							</tr>
							<tr>
								<td>Phone Number</td>
								<td>{{ $customer->telephone }}</td>
							</tr>
							<tr>
								<td>QR Code</td>
								<td>
									{!! QrCode::size(200)->generate($qrString); !!}
								</td>
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