@extends('admin_template')
@section('discount_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Detail Discount</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{ route('admin_dashbroard') }}">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('admin_discount_index') }}">Discount</a></li>
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
				@if (!$discount)
					<div class="alert alert-danger" role="alert">
						Page not found
					</div>
				@else
				<div class="card card-primary">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td>Code</td>
								<td>{{ $discount->code }}</td>
							</tr>
							<tr>
								<td>Description</td>
								<td>{{ $discount->description }}</td>
							</tr>
							<tr>
								<td>Type</td>
								<td>{{ $discount->type }}</td>
							</tr>
							<tr>
								<td>Discount Amount</td>
								<td>{{ $discount->discount_amount }}</td>
							</tr>
							<tr>
								<td>Store Name</td>
								<td>
									{{ $discount->store->name }}
								</td>
							</tr>
							<tr>
								<td>Store Email</td>
								<td>
									{{ $discount->store->email }}
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