@extends('layouts.headless') 
@section('content')
<div class="container" style="max-width: 600px">
    <div class="modal-content">
        <div class="modal-body">
            <div class="container">
                <div class="py-5 text-center">
                    <h2>{{ $title }}</h2>
                </div>
                <form class="needs-validation checkout_form" novalidate id="promotion_form" method="POST" action="{{ route('promotion_submit') }}">
                    @csrf
                    <input type="hidden" name="discount_id" id="discount_id" value="{{ $promotion->discount_id ?? 0 }}">
                    <div class="form-row">
                        <div class="form-group col-md-8">
                            <label for="name">Name</label>
                            <input value="{{ $promotion->name ?? old('name') }}" type="text" class="form-control" id="name" name="name" placeholder="" />
                            @if (!empty($errors->first('name')))
                            <small id="name-error" class="form-text text-danger">{{ $errors->first('name') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-md-4">
                            <label for="name">Amount</label>
                            <div class="input-group my-group w-100"> 
                                <input value="{{ $promotion->discount_amount ?? old('discount_amount') }}" type="number" class="form-control text-right w-70" id="discount_amount" name="discount_amount" placeholder="" />
                                <select id="discount_unit" name="discount_unit" class=" form-control form-control-sm  w-30" data-live-search="true" title="" style="height: calc(2.19rem + 2px)">
                                    <?php
                                        $selected_unit =  $promotion->discount_unit ?? old('discount_unit');
                                        $selected = '';
                                        foreach ($discount_unit as $key =>$unit): 
                                            if($selected_unit == $unit){$selected = 'selected';}
                                    ?>
                                    <option value="{{ $unit }}" <?php echo $selected?>  >{{ $unit }}</option>
                                    <?php endforeach; ?>
                                </select> 
                            </div>
                            @if (!empty($errors->first('discount_amount')))
                            <small id="discount-amount-error" class="form-text text-danger">{{ $errors->first('discount_amount') }}</small>
                            @endif
                        </div>
                        <div class="form-group col-md-12">
                            <div class="form-check">
                                <input <?php $enabled = $promotion->enable ?? old('enable'); echo ($enabled == 'on') ? 'checked' : ''; ?> class="form-check-input"  type="checkbox" name="enable" id="enable" />
                                <label cass="form-check-label" for="enable">
                                    Enable
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary pull-right">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
