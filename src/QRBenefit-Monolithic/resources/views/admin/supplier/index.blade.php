@extends('admin_template')
@section('supplier_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Supplier</h1>
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
						<a class="btn btn-info" href="{{ route('admin_supplier_show_view_add') }}" data-shuffle=""> Add </a>
						<button type="button" data-toggle="tooltip" title="Delete" class="btn btn-danger" onclick="confirm('Are you sure') ? $('#form-supplier').submit() : false;"> Delete</button>
					</div>
					<div class="card-header">
						<form action="{{ route('admin_supplier_index') }}" method="GET" id="form-filter">
							<input type="hidden" value="@if( isset($order_parameters['sort'])){{ $order_parameters['sort'] }}@endif"  name="sort">
							<input type="hidden" value="@if( isset($order_parameters['order'])){{ $order_parameters['order'] }}@endif"  name="order">
							<div class="row">
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-id">Id </label>
										<?php $test = 1 ?>
										<input type="number" value="@if(isset($filter_parameters['id'])){{ $filter_parameters['id']}}@endif"  name="id" class="form-control" id="input-id" placeholder="Id" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-3">
									<div class="form-group">
										<label for="input-name">Full Name</label>
										<input type="text" value="@if(isset($filter_parameters['name'])){{ $filter_parameters['name']}}@endif" name="name" class="form-control" id="input-name" placeholder="Full name" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-3">
									<div class="form-group">
										<label for="input-email">Email</label>
										<input type="text" value="@if( isset($filter_parameters['email'])){{ $filter_parameters['email'] }}@endif" name="email" class="form-control" id="input-email" placeholder="Email" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-phone-number">Phone Number</label>
										<input type="text" value="@if( isset($filter_parameters['phone_number']) ){{ $filter_parameters['phone_number'] }}@endif" name="phone_number" class="form-control" id="input-phone-number" placeholder="Phone number" >
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
						<form action="{{ route('admin_supplier_delete',array_merge($filter_parameters, $order_parameters)) }}" method="post" enctype="multipart/form-data" id="form-supplier">
							<div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
							@csrf
								<div class="row">
									<div class="col-sm-12">
										<div class="table-responsive">
											<table id="example2" style="border: 1px solid #dee2e6;" class="table table-bordered table-hover dataTable dtr-inline"
												role="grid" aria-describedby="example2_info">
												<thead>
													<tr role="row">
														<th class="text-center" style="padding-right: .75rem;">
															<input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" />
														</th>
														<th class="sorting  @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'id') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2"
															rowspan="1" colspan="1"
														>
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'id' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_supplier_index',array_merge($filter_parameters,['sort' => 'id', 'order' => 'desc'])) }}" data-shuffle=""> Id </a>
														@else
															<a href="{{ route('admin_supplier_index',array_merge($filter_parameters,['sort' => 'id', 'order' => 'asc'])) }}" data-shuffle=""> Id </a>
														@endif
														</th>
														<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'name') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
															colspan="1" >
															@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'name' && $order_parameters['order'] == 'asc')
																<a href="{{ route('admin_supplier_index',array_merge($filter_parameters,['sort' => 'name', 'order' => 'desc'])) }}" data-shuffle=""> Full Name </a>
															@else
																<a href="{{ route('admin_supplier_index',array_merge($filter_parameters,['sort' => 'name', 'order' => 'asc'])) }}" data-shuffle=""> Full Name </a>
															@endif
														</th>
														<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'email') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
															colspan="1">
															@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'email' && $order_parameters['order'] == 'asc')
																<a href="{{ route('admin_supplier_index',array_merge($filter_parameters,['sort' => 'email', 'order' => 'desc'])) }}" data-shuffle=""> Email </a>
															@else
																<a href="{{ route('admin_supplier_index',array_merge($filter_parameters,['sort' => 'email', 'order' => 'asc'])) }}" data-shuffle=""> Email </a>
															@endif
														</th>
														<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'phone_number') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
														colspan="1">
															@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'phone_number' && $order_parameters['order'] == 'asc')
																<a href="{{ route('admin_supplier_index',array_merge($filter_parameters,['sort' => 'phone_number', 'order' => 'desc'])) }}" data-shuffle=""> Phone Number </a>
															@else
																<a href="{{ route('admin_supplier_index',array_merge($filter_parameters,['sort' => 'phone_number', 'order' => 'asc'])) }}" data-shuffle=""> Phone Number  </a>
															@endif
														</th>
														<th  tabindex="0" aria-controls="example2" rowspan="1"
															colspan="1">
															Logo
														</th>
														<th  tabindex="0" aria-controls="example2" rowspan="1"
															colspan="1">
															Action
														</th>
													</tr>
												</thead>
												<tbody>
													@foreach ($suppliers as $supplier)
														<tr class="odd">
															<td class="text-center">
																<input type="checkbox" name="selected[]" value="{{ $supplier->supplier_id }}" />
															</td>
															<td>{{ $supplier->supplier_id }}</td>
															<td>{{ $supplier->name }}</td>
															<td>{{ $supplier->email }}</td>
															<td>{{ $supplier->phone_number }}</td>
															<td>
															@if (!empty($supplier->logo))
															<img src="{{ asset('storage/'.$supplier->logo) }}" style="width: 60px;">
															@endif
															</td>
															<td class="text-center">
																<a class="btn btn-info m-1" href="{{ route('admin_supplier_show_view_info', ['id' =>$supplier->supplier_id ]) }}" data-shuffle="">
																	<i class="fa fa-eye" aria-hidden="true"></i>
																</a>
																<a class="btn btn-info m-1" href="{{ route('admin_supplier_show_view_edit', ['id' =>$supplier->supplier_id ]) }}" data-shuffle="">
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
										@if ($suppliers->count() > 0)
											Showing {{ (($suppliers->currentPage() - 1) * $suppliers->perPage()) + 1 }} to {{ (($suppliers->currentPage() - 1) * $suppliers->perPage()) + $suppliers->count() }} of {{ $suppliers->total() }} entries
										@endif
										</div>
									</div>
									<div class="col-sm-12 col-md-7">
										<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
										{!! $suppliers->render('admin.pagination.bootstrap-4') !!}
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