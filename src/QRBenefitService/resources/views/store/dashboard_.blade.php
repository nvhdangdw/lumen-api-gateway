@extends('store_template')
@section('active_dashboard') active @endsection
@section('content')
<div class="card shadow mb-4">
	<div class="card-header py-3">
		<div class="row">
			<div class="col-md-6">
				<h6 class="m-0 font-weight-bold text-primary">Transactions</h6>
			</div>
			<div class="col-md-6 text-right">
				<button type="button" class="btn btn-primary">Checkout</button>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div class="table-responsive">
			<div id="dataTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
				<div class="row">
						<form class="col-sm-12">
							<div class="row">
								<div class="col-md-3 col-lg-3 col-xl-2">
									<div class="form-group">
										<label for="exampleInputEmail1">First Name</label>
										<input type="text" class="form-control" id="exampleInputEmail1"
											aria-describedby="emailHelp" placeholder="">
									</div>
								</div>
								<div class="col-md-3 col-lg-3 col-xl-2">
									<div class="form-group">
										<label for="exampleInputEmail1">Last Name</label>
										<input type="text" class="form-control" id="exampleInputEmail1"
											aria-describedby="emailHelp" placeholder="">
									</div>
								</div>
								<div class="col-md-6 col-lg-5 col-xl-2">
									<div class="form-group ">
										<label for="exampleInputEmail1">Total Amount</label>
										<div class="input-group mb-3">
											<div class="input-group-prepend">
												<select class="input-group-text" id="basic-addon1">
													<option>>=</option>
													<option><= </option>
												</select>
											</div>
											<input type="text" class="form-control" placeholder=""
												aria-label="Username" aria-describedby="basic-addon1">
										</div>
									</div>
								</div>
								<div class="col-md-8 col-lg-6 col-xl-4">
									<div class="form-group ">
										<label for="exampleInputEmail1">Date</label>
										<div class="input-group mb-">
											<div class="input-group-prepend">
												<select class="input-group-text" id="basic-addon1">
													<option selected="selected" value="">Select</option>
													<option value=">=">FROM</option>
													<option value="<=">TO</option>
												</select>
											</div>
											<input type="date" class="form-control" placeholder=""
												aria-label="Username" aria-describedby="basic-addon1">
										</div>
									</div>
								</div>
								<div class="col-md-3 col-lg-2 col-xl-2">
									<div class="form-group">
										<label for="exampleInputEmail1" style="width:100%">Action</label>
										<button type="button" class="btn btn-success">Search</button>
									</div>
								</div>
							</div>
						</form>
				</div>
				<div class="row">
					<div class="col-sm-12">
						<table class="table table-bordered dataTable" id="dataTable" width="100%" cellspacing="0"
							role="grid" aria-describedby="dataTable_info" style="width: 100%;">
							<thead>
								<tr role="row">
									<th class="sorting sorting_asc" tabindex="0" aria-controls="dataTable" rowspan="1"
										colspan="1" aria-sort="ascending">
										Id
									</th>
									<th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
										aria-label="Position: activate to sort column ascending" ">
										Customer</th>
									<th class=" sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
										aria-label="Office: activate to sort column ascending">
										Total Amount</th>
									<th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
										aria-label="Age: activate to sort column ascending">Tax
									</th>
									<th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
										aria-label="Start date: activate to sort column ascending">Discounted</th>
									<th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
										aria-label="Salary: activate to sort column ascending">
										Paid
									</th>
									<th class="sorting" tabindex="0" aria-controls="dataTable" rowspan="1" colspan="1"
										aria-label="Salary: activate to sort column ascending">
										Created At
									</th>
								</tr>
							</thead>
							<tbody>
								<tr class="odd">
									<td class="sorting_1">Airi Satou</td>
									<td>Accountant</td>
									<td>Tokyo</td>
									<td>33</td>
									<td>2008/11/28</td>
									<td>$162,700</td>
									<td>$162,700</td>
								</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<br>
		<div class="row">
					<div class="col-sm-12 col-md-5">
						<div class="dataTables_info" id="dataTable_info" role="status" aria-live="polite">Showing 1 to
							10 of 57 entries</div>
					</div>
					<div class="col-sm-12 col-md-7">
						<div class="dataTables_paginate paging_simple_numbers" id="dataTable_paginate">
							<ul class="pagination" style="justify-content: flex-end;">
								<li class="paginate_button page-item previous disabled" id="dataTable_previous"><a
										href="#" aria-controls="dataTable" data-dt-idx="0" tabindex="0"
										class="page-link">Previous</a></li>
								<li class="paginate_button page-item active"><a href="#" aria-controls="dataTable"
										data-dt-idx="1" tabindex="0" class="page-link">1</a></li>
								<li class="paginate_button page-item "><a href="#" aria-controls="dataTable"
										data-dt-idx="2" tabindex="0" class="page-link">2</a></li>
								<li class="paginate_button page-item "><a href="#" aria-controls="dataTable"
										data-dt-idx="3" tabindex="0" class="page-link">3</a></li>
								<li class="paginate_button page-item "><a href="#" aria-controls="dataTable"
										data-dt-idx="4" tabindex="0" class="page-link">4</a></li>
								<li class="paginate_button page-item next" id="dataTable_next"><a href="#"
										aria-controls="dataTable" data-dt-idx="7" tabindex="0"
										class="page-link">Next</a></li>
							</ul>
						</div>
					</div>
				</div>
	</div>
</div>
@endsection