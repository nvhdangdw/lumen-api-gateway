@extends('admin_template')
@section('order_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Detail Order</h1>
			</div><!-- /.col -->
			<div class="col-sm-6">
				<ol class="breadcrumb float-sm-right">
					<li class="breadcrumb-item"><a href="{{ route('admin_dashbroard') }}">Home</a></li>
					<li class="breadcrumb-item"><a href="{{ route('admin_order_index') }}">Order</a></li>
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
				@if (!$order)
					<div class="alert alert-danger" role="alert">
						Page not found
					</div>
				@else
				<div class="card card-primary">
					<table class="table table-bordered">
						<tbody>
							<tr>
								<td>Customer Name</td>
								<td> {{ $order->customer->lastname }} {{ $order->customer->firstname }}</td>
							</tr>
							<tr>
								<td>Customer Email</td>
								<td>{{ $order->customer->email }}</td>
							</tr>
							<tr>
								<td>Store Name</td>
								<td>{{ $order->store->name }}</td>
							</tr>
							<tr>
								<td>Store Email</td>
								<td>{{ $order->store->email }}</td>
							</tr>
							<tr>
								<td>Total Tax</td>
								<td>
									{{ $order->total_tax }}
								</td>
							</tr>
							<tr>
								<td>Total Discount</td>
								<td>
									{{ $order->total_discount }}
								</td>
							</tr>
							<tr>
								<td>Total Amount</td>
								<td>
									{{ $order->total_amount }}
								</td>
							</tr>
							<tr>
								<td>Date</td>
								<td>
									{{ $order->created_at }}
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