@extends('admin_template')
@section('customer_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">Customer</h1>
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
						<form action="{{ route('admin_customer_index') }}" method="GET" id="form-filter">
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
										<label for="input-first-name">First Name</label>
										<input type="text" value="@if( isset($filter_parameters['first_name']) ){{ $filter_parameters['first_name'] }}@endif" name="first_name" class="form-control" id="input-first-name" placeholder="First name" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-last-name">Last Name</label>
										<input type="text" value="@if( isset($filter_parameters['last_name']) ){{ $filter_parameters['last_name'] }}@endif" name="last_name" class="form-control" id="input-last-name" placeholder="Last name" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-3">
									<div class="form-group">
										<label for="input-email">Email</label>
										<input type="text" value="@if ( isset($filter_parameters['email'])){{ $filter_parameters['email'] }}@endif" name="email" class="form-control" id="input-email" placeholder="Email" >
									</div>
								</div>
								<div class="col-sm-3 col-lg-2">
									<div class="form-group">
										<label for="input-phone-number">Telephone</label>
										<input type="text" value="@if ( isset($filter_parameters['telephone']) ){{ $filter_parameters['telephone'] }}@endif" name="telephone" class="form-control" id="input-phone-number" placeholder="Telephone" >
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
					<!-- /.card-header -->
					<div class="card-body">
					<form action="{{ route('admin_customer_delete',array_merge($filter_parameters, $order_parameters)) }}" method="post" enctype="multipart/form-data" id="form-customer">
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
															<a href="{{ route('admin_customer_index',array_merge($filter_parameters,['sort' => 'id', 'order' => 'desc'])) }}" data-shuffle=""> Id </a>
														@else
															<a href="{{ route('admin_customer_index',array_merge($filter_parameters,['sort' => 'id', 'order' => 'asc'])) }}" data-shuffle=""> Id </a>
														@endif
													</th>
													<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'first_name') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
														colspan="1">
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'first_name' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_customer_index',array_merge($filter_parameters,['sort' => 'first_name', 'order' => 'desc'])) }}" data-shuffle=""> First Name </a>
														@else
															<a href="{{ route('admin_customer_index',array_merge($filter_parameters,['sort' => 'first_name', 'order' => 'asc'])) }}" data-shuffle=""> First Name </a>
														@endif
													</th>
													<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'last_name') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
														colspan="1"
														aria-label="Platform(s): activate to sort column ascending">
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'last_name' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_customer_index',array_merge($filter_parameters,['sort' => 'last_name', 'order' => 'desc'])) }}" data-shuffle=""> Last Name </a>
														@else
															<a href="{{ route('admin_customer_index',array_merge($filter_parameters,['sort' => 'last_name', 'order' => 'asc'])) }}" data-shuffle=""> Last Name </a>
														@endif
													</th>
													<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'email') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
														colspan="1">
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'email' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_customer_index',array_merge($filter_parameters,['sort' => 'email', 'order' => 'desc'])) }}" data-shuffle=""> Email </a>
														@else
															<a href="{{ route('admin_customer_index',array_merge($filter_parameters,['sort' => 'email', 'order' => 'asc'])) }}" data-shuffle=""> Email </a>
														@endif
													</th>
													<th class="sorting @if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'telephone') sorting_{{$order_parameters['order']}}  @endif" tabindex="0" aria-controls="example2" rowspan="1"
														colspan="1">
														@if ( isset($order_parameters['sort']) && $order_parameters['sort'] == 'telephone' && $order_parameters['order'] == 'asc')
															<a href="{{ route('admin_customer_index',array_merge($filter_parameters,['sort' => 'telephone', 'order' => 'desc'])) }}" data-shuffle=""> Telephone </a>
														@else
															<a href="{{ route('admin_customer_index',array_merge($filter_parameters,['sort' => 'telephone', 'order' => 'asc'])) }}" data-shuffle=""> Telephone  </a>
														@endif
													</th>
													<th tabindex="0" aria-controls="example2" rowspan="1"
														colspan="1">
														Action
													</th>
												</tr>
											</thead>
											<tbody>
												@foreach ($customers as $customer)
													<tr class="odd">
														<td class="text-center">
															<input type="checkbox" name="selected[]" value="{{ $customer->customer_id }}" />
														</td>
														<td>{{ $customer->customer_id }}</td>
														<td>{{ $customer->firstname }}</td>
														<td>{{ $customer->lastname }}</td>
														<td>{{ $customer->email }}</td>
														<td>{{ $customer->telephone }}</td>
														<td class="text-center">
															<a class="btn btn-info m-1" href="{{ route('admin_customer_show_view_info', ['id' =>$customer->customer_id ]) }}" data-shuffle="">
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
									@if ($customers->count() > 0)
										Showing {{ (($customers->currentPage() - 1) * $customers->perPage()) + 1 }} to {{ (($customers->currentPage() - 1) * $customers->perPage()) + $customers->count() }} of {{ $customers->total() }} entries
									@endif
									</div>
								</div>
								<div class="col-sm-12 col-md-7">
									<div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
									{!! $customers->render('admin.pagination.bootstrap-4') !!}
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