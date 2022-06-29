@extends('layouts.headless') 
@section('content')
    <?php 
        $status = $status ?? '123';
        if($status == 'success'):
    ?>
        <script>
            //execute this script after adding new order
            window.parent.afterAddOrder();
        </script>
    <?php else:?>
<div class="container">
    <div class="modal-content">
        <div class="modal-body">
            <div class="container">
                <div class="py-5 text-center">
                    <!--<img class="d-block mx-auto mb-4" src="{{asset('media/logo_store.png')}}" alt="" style="object-fit: cover" width="270" height="135" />-->
                    <img class="d-block mx-auto mb-4 col-md-4 col-sm-12" src="{{asset('media/logo_store.png')}}" alt="" style="object-fit: cover"  />
                    <h2>Checkout form</h2>
                </div>
                <form class="needs-validation checkout_form" novalidate id="checkout_form" method="POST" action="{{ route('order_add') }}">
                    @csrf
                    <input type="hidden" name="supplier_id" id="supplier_id" value="<?php echo $supplier->supplier_id ?? 0 ?>" >
                    <div class="row">
                        <div class="col-md-5 order-md-2 mb-4">
                            <h4 class="d-flex justify-content-between align-items-center mb-3">
                                <span class="text-muted">Billing Information</span>
                            </h4>
                            <ul class="list-group mb-3">
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <label for="country" class="my-0 col-xl-4 col-sm-12 p-0">Amount</label>
                                    <div class="col-xl-8 col-sm-12 p-0">
                                        <input onchange="updateAmounts()" name="total_amount" type="number" class="form-control text-right  " id="total_amount" required placeholder="" value="{{ old('total_amount') ?? '' }}" required />
                                        @if (!empty($errors->first('total_amount')))
                                        <small id="total_amount-error" class="form-text text-danger">{{ $errors->first('total_amount') }}</small>
                                        @endif
                                    </div>
                                </li>
                                <li class="list-group-item d-flex justify-content-between bg-light">
                                    <div class="text-warning">
                                        <h6 class="my-0">Tax (<?php echo $supplier->default_tax ?? 0 ?>%)</h6>
                                    </div>
                                    <span class="text-warning" name="total_tax_span" id="total_tax_span">0$</span>
                                    <input type="hidden" name="total_tax" id="total_tax" value="{{ old('total_tax') }}" >
                                    <input type="hidden" name="default_tax" id="default_tax" value="<?php echo $supplier->default_tax ?? 0 ?>" >
                                </li>
                                <li class="list-group-item d-flex justify-content-between lh-condensed">
                                    <!--<label for="country" class="my-0">Promotion</label>-->
                                    <div class="w-100">
                                        <select name="promotion_select" value='{{ old('promotion_select') }}' onchange="updateAmounts()" disabled class="custom-select d-block w-100"  id="promotion_select" required>
                                            <option value="" data-discount-amount="0" data-discount-unit="0">-Select Promotion-</option>
                                            <?php 
                                            $selected_promotion = old('promotion_select') ?? 0;
                                            $selected = '';
                                            foreach ($promotion_data as $key =>$promotion): 
                                                if($selected_promotion == $promotion->discount_id){$selected = 'selected';}
                                                ?>
                                            <option <?php echo $selected?> value="{{$promotion->discount_id}}" data-discount-amount="{{$promotion->discount_amount}}" data-discount-unit="{{$promotion->discount_unit}}">
                                                {{ $promotion->name }} {{'-'.$promotion->discount_amount.$promotion->discount_unit}}
                                            </option>
                                            <?php endforeach?>
                                        </select>
                                        <div class="invalid-feedback">
                                            Please select a valid promotion.
                                        </div>
                                    </div>
                                </li>

                                <li class="list-group-item d-flex justify-content-between bg-light">
                                    <div class="text-success">
                                        <h6 class="my-0">Discounted</h6>
                                        <small id="promotion_name"></small>
                                    </div>
                                    <span class="text-success" name="total_discount_span" id="total_discount_span">0$</span>
                                    <input type="hidden" name="total_discount" id="total_discount" value="{{ old('total_discounted') }}" >
                                </li>                                
                                <li class="list-group-item d-flex justify-content-between">
                                    <span>Total</span>
                                    <strong id="paid"></strong>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-7 order-md-1">
                            <h4 class="mb-3">Customer Information</h4>
                            <input type="hidden" id="customer_id" name="customer_id"  value="{{ $customer->customer_id ?? 0 }}" />
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <label for="firstName">Name</label>
                                    <div class="input-group my-group w-100"> 
                                        <div class="col-md-6 col-sm-12 p-0">
                                            <input placeholder="First Name" name="first_name" type="text" class="form-control" required id="first_name" placeholder="" 
                                                   <?php echo (isset($customer->first_name)) ? 'readonly' : ''; ?>
                                                   value="{{ $customer->first_name ?? old('first_name') }}" required />
                                            @if (!empty($errors->first('first_name')))
                                            <small id="name-error" class="form-text text-danger">{{ $errors->first('first_name') }}</small>
                                            @endif
                                        </div>
                                        <div class="col-md-6 col-sm-12 p-0">
                                            <input placeholder="Last Name" name="last_name" type="text" class="form-control" required id="last_name" placeholder="" 
                                                   <?php echo (isset($customer->last_name)) ? 'readonly' : ''; ?>
                                                   value="{{ $customer->last_name ??  old('last_name') }}" required />
                                            @if (!empty($errors->first('last_name')))
                                            <small id="name-error" class="form-text text-danger">{{ $errors->first('last_name') }}</small>
                                            @endif
                                        </div>                                        
                                    </div>
                                </div>
                            </div>
                            @if (empty($customer))
                            <div class="mb-3">
                                <label for="email">Email </label>
                                <input name="email" type="email" class="form-control" id="email" placeholder="you@example.com" 
                                       <?php echo (isset($customer->email)) ? 'readonly' : ''; ?>
                                       value="{{ $customer->email ??  old('email') }}" />
                                @if (!empty($errors->first('email')))
                                <small id="email-error" class="form-text text-danger">{{ $errors->first('email') }}</small>
                                @endif
                            </div>

                            <div class="mb-3">
                                <label for="address">Phone Number</label>
                                <input name="phone_number" type="text" class="form-control" id="phone_number" placeholder="0000-000-000" 
                                       <?php echo (isset($customer->phone_number)) ? 'readonly' : ''; ?>
                                       value="{{ $customer->phone_number ??  old('phone_number') }}" required />
                                @if (!empty($errors->first('phone_number')))
                                <small id="phone_number-error" class="form-text text-danger">{{ $errors->first('phone_number') }}</small>
                                @endif
                            </div>
                            @endif
                            <hr class="mb-4" />
                        </div>
                    </div>
                    <button class="btn btn-primary btn-lg btn-block" type="submit">Continue to checkout</button>
                </form>
                <footer class="my-5 pt-5 text-muted text-center text-small"></footer>
            </div>
        </div>
    </div>
</div>
    <script>
        $(function(){                 
            updateAmounts();            
        });
    </script>
<?php endif;?>
@endsection
