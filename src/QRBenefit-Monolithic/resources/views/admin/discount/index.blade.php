@extends('admin_template')
@section('discount_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Discount</h1>
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
		@if (isset($message) && !empty($message))
			<div class="col-12">
				<div class="alert alert-success" role="alert">
					{{ $message }}
				</div>
			</div>
		@endif
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<a class="btn btn-info" href="{{ route('admin_discount_show_view_add') }}" data-shuffle=""> Add </a>
						<button type="button" data-toggle="tooltip" title="Delete" class="btn btn-danger" onclick="confirm('Are you sure') ? $('#form-discount').submit() : false;"> Delete</button>
					</div>
					<div class="card-header">
						<form action="{{ route('admin_discount_index') }}" method="GET" id="form-filter">
							<input type="hidden" value="@if( isset($order_parameters['sort'])){{ $order_parameters['sort'] }}@endif"  name="sort">
							<input type="hidden" value="@if( isset($order_parameters['order'])){{ $order_parameters['order'] }}@endif"  name="order">
							<div class="row">
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-id">Id </label>
										<?php $test = 1 ?>
										<input type="number" value="@if( isset($filter_parameters['id'])){{ $filter_parameters['id'] }}@endif"  name="id" class="form-control" id="input-id" placeholder="Id" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="inputname">Code</label>
										<input type="text" value="@if( isset($filter_parameters['code']) ){{ $filter_parameters['code'] }}@endif" name="code" class="form-control" id="input-code" placeholder="Code" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-type">Type</label>
										<select name="type" class="form-control" id="input-type">
											<option value="">All</option>
											<option value="%" @if ( isset($filter_parameters['type']) && $filter_parameters['type'] == "%" ) selected @endif>%</option>
											<option value="$" @if ( isset($filter_parameters['type']) && $filter_parameters['type'] == "$" ) selected @endif>$</option>
										</select>
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-discount-amount">Discount Amount</label>
										<select name="discount_amount_compare" class="form-control" id="input-type">
											<option value="=" @if ( isset($filter_parameters['discount_amount_compare']) && $filter_parameters['discount_amount_compare'] == "=" ) selected @endif>=</option>
											<option value=">=" @if ( isset($filter_parameters['discount_amount_compare']) && $filter_parameters['discount_amount_compare'] == ">=" ) selected @endif>>=</option>
											<option value="<=" @if ( isset($filter_parameters['discount_amount_compare']) && $filter_parameters['discount_amount_compare'] == "<=" ) selected @endif><=</option>
										</select>
										<input step="0.01" type="number" value="@if(isset($filter_parameters['discount_amount'])){{$filter_parameters['discount_amount']}}@endif" name="discount_amount" class="form-control" id="input-discount-amount" placeholder="Discount Amount" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-store-name">Store Name</label>
										<input type="text" value="@if(isset($filter_parameters['store_name'])){{$filter_parameters['store_name']}}@endif" name="store_name" class="form-control" id="input-store-name" placeholder="Store Name" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-name" style="width:100%">Action</label>
										<button type="submit" data-toggle="tooltip" title="Filter" class="btn btn-primary " > Filter</button>
									</div>
								</div>
							</div>
						</form>
					</div>
					<!-- /.card-header -->
					<div class="card-body">
					<form action="{{ route('admin_discount_delete',array_merge($filter_parameters, $order_parameters)) }}" method="post" enctype="multipart/form-data" id="form-discount">
						<div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
							<div class="row">
							@csrf
								<div class="col-sm-12">
									<div class="table-responsive">
										<table style="border: 1px solid #dee2e6;" id="example2" class="table table-bordered table-hover dataTable dtr-inline"
											role="grid" aria-describedby="example2_info">
											<thead>
												<tr role="row">
													<th class="text-center" style="padding-right: .75rem;">
														<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
													</th>
													<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'id') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2"
														rowspan="1" colspan="1">
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'id' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_discount_index',array_merge($filter_parameters,['sort' => 'id', 'order' => 'desc'])) }}" data-shuffle=""> Id </a>
														@else
															<a href="{{ route('admin_discount_index',array_merge($filter_parameters,['sort' => 'id', 'order' => 'asc'])) }}" data-shuffle=""> Id </a>
														@endif
													</th>
													<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'code') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
															colspan="1" >
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'code' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_discount_index',array_merge($filter_parameters,['sort' => 'code', 'order' => 'desc'])) }}" data-shuffle=""> Code </a>
														@else
															<a href="{{ route('admin_discount_index',array_merge($filter_parameters,['sort' => 'code', 'order' => 'asc'])) }}" data-shuffle=""> Code </a>
														@endif
													</th>
													<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'type') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
															colspan="1" >
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'type' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_discount_index',array_merge($filter_parameters,['sort' => 'type', 'order' => 'desc'])) }}" data-shuffle=""> Type </a>
														@else
															<a href="{{ route('admin_discount_index',array_merge($filter_parameters,['sort' => 'type', 'order' => 'asc'])) }}" data-shuffle=""> Type </a>
														@endif
													</th>
													<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'discount_amount') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
															colspan="1" >
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'discount_amount' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_discount_index',array_merge($filter_parameters,['sort' => 'discount_amount', 'order' => 'desc'])) }}" data-shuffle=""> Discount Amount </a>
														@else
															<a href="{{ route('admin_discount_index',array_merge($filter_parameters,['sort' => 'discount_amount', 'order' => 'asc'])) }}" data-shuffle=""> Discount Amount </a>
														@endif
													</th>
													<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'store_name') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
															colspan="1" >
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'store_name' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_discount_index',array_merge($filter_parameters,['sort' => 'store_name', 'order' => 'desc'])) }}" data-shuffle=""> Store Name </a>
														@else
															<a href="{{ route('admin_discount_index',array_merge($filter_parameters,['sort' => 'store_name', 'order' => 'asc'])) }}" data-shuffle=""> Store Name </a>
														@endif
													</th>
													<th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
														colspan="1"
														aria-label="CSS grade: activate to sort column ascending">
														Action
													</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($discounts as $discount)
													<tr class="odd">
														<td class="text-center">
															<input type="checkbox" name="selected[]" value="{{ $discount->discount_id }}" />
														</td>
														<td>{{ $discount->discount_id }}</td>
														<td>{{ $discount->code }}</td>
														<td>{{ $discount->type }}</td>
														<td>{{ $discount->discount_amount }}</td>
														<td>{{ $discount->store->name }}</td>
														<td class="text-center">
															<a class="btn btn-info m-1" href="{{ route('admin_discount_show_view_info', ['id' =>$discount->discount_id ]) }}" data-shuffle="">
																<i class="fa fa-eye" aria-hidden="true"></i>
															</a>
															<a class="btn btn-info m-1" href="{{ route('admin_discount_show_view_edit', ['id' =>$discount->discount_id ]) }}" data-shuffle="">
																<i class="fa fa-edit" aria-hidden="true"></i>
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
									@if ($discounts->count() > 0)
										Showing {{ (($discounts->currentPage() - 1) * $discounts->perPage()) + 1 }} to {{ (($discounts->currentPage() - 1) * $discounts->perPage()) + $discounts->count() }} of {{ $discounts->total() }} entries
									@endif
									</div>
								</div>
								<div class="col-sm-12 col-md-7">
									<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
									{!! $discounts->render('admin.pagination.bootstrap-4') !!}
									</div>
								</div>
							</div>
						</div>
					</form>
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
<script type="text/javascript">
$( document ).ready(function() {
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