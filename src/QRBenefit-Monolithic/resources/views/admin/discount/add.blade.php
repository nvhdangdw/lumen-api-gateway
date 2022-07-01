@extends('admin_template')
@section('discount_active') active @endsection
@section('content')
<div class="content-header">
	<div class="container-fluid">
		<div class="row mb-2">
			<div class="col-sm-6">
				<h1 class="m-0">New Discount</h1>
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
				<div class="card card-primary">
					<form action="{{ route('admin_discount_create') }}" method="POST">
						@csrf
						<div class="card-body">
							<div class="form-group">
								<label for="input-name">Code</label>
								<input type="text" value="{{ old('code') }}" name="code" class="form-control" id="input-name" placeholder="Code" >
								@if (!empty($errors->first('code')))
								<small id="code-error" class="form-text text-danger">{{ $errors->first('code') }}</small>
								@endif
							</div>
							<div class="form-group">
								<label for="input-name">Description</label>
								<textarea name="description" class="form-control" rows="3" placeholder="Description" >{{ old('name') }}</textarea>
								@if (!empty($errors->first('name')))
								<small id="name-error" class="form-text text-danger">{{ $errors->first('name') }}</small>
								@endif
							</div>
							<div class="form-group">
								<label for="input-type">Type</label>
								<select name="type" class="form-control" id="input-type">
									<option value="%" @if(old("type")=="%") selected @endif>%</option>
									<option value="$" @if(old("type")=="$") selected @endif>$</option>
								</select>
								@if (!empty($errors->first('type')))
								<small id="type-error" class="form-text text-danger">{{ $errors->first('type') }}</small>
								@endif
							</div>
							<div class="form-group">
								<label for="input-discount-amount">Discount Amount</label>
								<input  type="text" value="{{ old('discount_amount') }}" name="discount_amount" class="form-control" id="input-discount-amount" placeholder="Discount Amount" >
								@if (!empty($errors->first('discount_amount')))
								<small id="discount-amount-error" class="form-text text-danger">{{ $errors->first('discount_amount') }}</small>
								@endif
							</div>
							<div class="form-group">
								<label for="input-discount-unit">Store</label>
								<select name="store_id" class="form-control" id="input-phone-number">
								@foreach ($stores as $store)
									<option value="{{ $store->store_id }}" @if(old("store_id")==$store->store_id) selected @endif >{{$store->name}}</option>
								@endforeach
								</select>
								@if (!empty($errors->first('store_id')))
								<small id="discount-unit-error" class="form-text text-danger">{{ $errors->first('store_id') }}</small>
								@endif
							</div>
						</div>
						<!-- /.card-body -->

						<div class="card-footer">
						<button type="submit" class="btn btn-primary">Submit</button>
						</div>
					</form>
				</div>
			</div>
			<!-- /.col -->
		</div>
		<!-- /.row -->
	</div>
	<!-- /.container-fluid -->
</section>
@endsection