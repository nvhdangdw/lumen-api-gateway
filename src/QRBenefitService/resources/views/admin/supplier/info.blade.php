@extends('admin_template')
@section('supplier_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Detail Supplier</h1>
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
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td>Full Name</td>
								<td>{{ $supplier->name }}</td>
							</tr>
							<tr>
								<td>Email</td>
								<td>{{ $supplier->email }}</td>
							</tr>
							<tr>
								<td>Phone Number</td>
								<td>{{ $supplier->phone_number }}</td>
							</tr>
							<tr>
								<td>Logo</td>
								<td>
									@if (!empty($supplier->logo))
									<img src="{{ asset('storage/'.$supplier->logo) }}" style="width: 140px;">
									@endif
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