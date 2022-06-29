@extends('admin_template')
@section('order_active') active @endsection
@section('link')
<link rel="stylesheet" href="{{ asset('/bower_components/plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css') }}">
@endsection

@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Order</h1>
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
				<div class="card">
				<div class="card-header">
						<form action="{{ route('admin_order_index') }}" method="GET" id="form-filter">
							<input type="hidden" value="@if( isset($order_parameters['sort'])){{ $order_parameters['sort'] }}@endif"  name="sort">
							<input type="hidden" value="@if( isset($order_parameters['order'])){{ $order_parameters['order'] }}@endif"  name="order">
							<div class="row">
								<div class="col-sm-3 col-lg-1">
									<div class="form-group">
										<label for="input-id">Id </label>
										<?php $test = 1 ?>
										<input type="number" value="@if( isset($filter_parameters['id'])){{ $filter_parameters['id'] }}@endif"  name="id" class="form-control" id="input-id" placeholder="Id" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-customer-name">Customer Name</label>
										<input type="text" value="@if( isset($filter_parameters['customer_name']) ){{ $filter_parameters['customer_name'] }}@endif" name="customer_name" class="form-control" id="input-customer-name" placeholder="Customer Name" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-supplier-name">Store Name</label>
										<input type="text" value="@if( isset($filter_parameters['store_name']) ){{ $filter_parameters['store_name'] }}@endif" name="store_name" class="form-control" id="input-supplier-name" placeholder="Store Name" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-total-amount">Total Amount</label>
										<select name="total_amount_compare" class="form-control" id="input-type">
											<option value="=" @if ( isset($filter_parameters['total_amount_compare']) && $filter_parameters['total_amount_compare'] == "=" ) selected @endif>=</option>
											<option value=">=" @if ( isset($filter_parameters['total_amount_compare']) && $filter_parameters['total_amount_compare'] == ">=" ) selected @endif>>=</option>
											<option value="<=" @if ( isset($filter_parameters['total_amount_compare']) && $filter_parameters['total_amount_compare'] == "<=" ) selected @endif><=</option>
										</select>
										<input step="0.01" type="number" value="@if(isset($filter_parameters['total_amount'])){{$filter_parameters['total_amount']}}@endif" name="total_amount" class="form-control" id="input-total-amount" placeholder="Total Amount" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
									<label>From Date</label>
										<div class="input-group date" id="from-date" data-target-input="nearest">
											<input type="text" name="from_date" value="@if(isset($filter_parameters['from_date'])){{$filter_parameters['from_date']}}@endif" class="form-control datetimepicker-input" data-target="#from-date">
											<div class="input-group-append" data-target="#from-date" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
									<label>To Date</label>
										<div class="input-group date" id="to-date" data-target-input="nearest">
											<input type="text" name="to_date" value="@if(isset($filter_parameters['to_date'])){{$filter_parameters['to_date']}}@endif" class="form-control datetimepicker-input" data-target="#to-date">
											<div class="input-group-append" data-target="#to-date" data-toggle="datetimepicker">
												<div class="input-group-text"><i class="fa fa-calendar"></i></div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-sm-3 col-lg-1">
									<div class="form-group">
										<label for="input-name" style="width:100%">Action</label>
										<button type="submit" data-toggle="tooltip" title="Filter" class="btn btn-primary " > Filter</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<div class="card-body">
						<div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
							<div class="row">
								<div class="col-sm-12">
									<div class="table-responsive">
										<table style="border: 1px solid #dee2e6;" id="example2" class="table table-bordered table-hover dataTable dtr-inline"
											role="grid" aria-describedby="example2_info">
											<thead>
												<tr role="row">
													<th class="sorting  @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'id') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2"
															rowspan="1" colspan="1"
														>
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'id' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'id', 'order' => 'desc'])) }}" data-shuffle=""> Id </a>
														@else
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'id', 'order' => 'asc'])) }}" data-shuffle=""> Id </a>
														@endif
													</th>
													<th class="sorting  @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'customer_name') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2"
															rowspan="1" colspan="1"
														>
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'customer_name' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'customer_name', 'order' => 'desc'])) }}" data-shuffle=""> Customer Name </a>
														@else
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'customer_name', 'order' => 'asc'])) }}" data-shuffle=""> Customer Name </a>
														@endif
													</th>
													<th class="sorting  @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'store_name') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2"
															rowspan="1" colspan="1"
														>
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'store_name' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'store_name', 'order' => 'desc'])) }}" data-shuffle=""> Store Name </a>
														@else
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'store_name', 'order' => 'asc'])) }}" data-shuffle=""> Store Name </a>
														@endif
													</th>
													<th class="sorting  @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'total_tax') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2"
															rowspan="1" colspan="1"
														>
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'total_tax' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'total_tax', 'order' => 'desc'])) }}" data-shuffle=""> Total Tax </a>
														@else
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'total_tax', 'order' => 'asc'])) }}" data-shuffle=""> Total Tax </a>
														@endif
													</th>
													<th class="sorting  @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'total_discount') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2"
															rowspan="1" colspan="1"
														>
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'total_discount' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'total_discount', 'order' => 'desc'])) }}" data-shuffle=""> Total discount </a>
														@else
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'total_discount', 'order' => 'asc'])) }}" data-shuffle=""> Total discount </a>
														@endif
													</th>
													<th class="sorting  @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'total_amount') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2"
															rowspan="1" colspan="1"
														>
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'total_amount' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'total_amount', 'order' => 'desc'])) }}" data-shuffle=""> Total Amount </a>
														@else
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'total_amount', 'order' => 'asc'])) }}" data-shuffle=""> Total Amount </a>
														@endif
													</th>
													<th class="sorting  @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'date') sorting_{{$order_parameters['order']}} @endif" tabindex="0" aria-controls="example2"
															rowspan="1" colspan="1"
														>
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'date' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'date', 'order' => 'desc'])) }}" data-shuffle=""> Date </a>
														@else
															<a href="{{ route('admin_order_index',array_merge($filter_parameters,['sort' => 'date', 'order' => 'asc'])) }}" data-shuffle=""> Date </a>
														@endif
													</th>
													<th  tabindex="0" aria-controls="example2" rowspan="1"
														colspan="1">
														Action
													</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($orders as $order)
													<tr class="odd">
														<td class="dtr-control sorting_1" tabindex="0">{{ $order->order_id }}</td>
														<td>{{ $order->customer->lastname }} {{ $order->customer->firstname }}</td>
														<td>{{ $order->store->name }}</td>
														<td>{{ $order->total_tax }}</td>
														<td>{{ $order->total_discount }}</td>
														<td>{{ $order->total_amount }}</td>
														<td>{{ $order->created_at }}</td>
														<td class="text-center">
															<a class="btn btn-info" href="{{ route('admin_order_show_view_info', ['id' =>$order->order_id ]) }}" data-shuffle="">
																<i class="fa fa-eye" aria-hidden="true"></i>
															</a>
														</td>
													</tr>
												@endforeach
											</tbody>
										</table>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12 col-md-5">
									<div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
										@if ($orders->count() > 0)
											Showing {{ (($orders->currentPage() - 1) * $orders->perPage()) + 1 }} to {{ (($orders->currentPage() - 1) * $orders->perPage()) + $orders->count() }} of {{ $orders->total() }} entries
										@endif
									</div>
								</div>
								<div class="col-sm-12 col-md-7">
									<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
									{!! $orders->render('admin.pagination.bootstrap-4') !!}
									</div>
								</div>
							</div>
						</div>
					</div>
					<!-- /.card-body -->
				</div>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>
@endsection

@section('script')
<script src="{{ asset('/bower_components/plugins/moment/moment.min.js') }}"></script>
<script src="{{ asset('/bower_components/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js') }}"></script>
<script type="text/javascript">
$( document ).ready(function() {
	$('#from-date').datetimepicker({
		format: 'YYYY-MM-DD'
	});
	$('#to-date').datetimepicker({
		format: 'YYYY-MM-DD'
	});
	$("#form-filter").submit(function(e){
		e.preventDefault(e);
		var data = {};
		var url = $( this ).attr('action');
		$( "#form-filter input" ).each(function( index ) {
			if ($( this ).val() != "") {
				data[$( this ).attr('name')] = $( this ).val();
			}
		});
		$( "#form-filter select" ).each(function( index ) {
			if ($( this ).val() != "") {
				data[$( this ).attr('name')] = $( this ).val();
			}
		});

		var parameter_url = new URLSearchParams(data).toString();
		if (parameter_url == "") {
			location = url;
		} else {
			location = url+"?"+parameter_url;
		}
	});
});
</script>
@endsection