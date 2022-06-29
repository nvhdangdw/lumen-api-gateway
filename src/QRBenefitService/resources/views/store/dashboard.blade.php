@extends('store.store_template') @section('content')

        <main role="main" class="col-md-10 col-lg-10 pt-3 px-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2>Transactions</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <!--<a href="javascript:void(0)" id="new-order-btn" class="btn btn-sm btn-info">New Order</a>-->
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <div class="row">
                    <div class="col-xl-4 col-md-8 col-12">
                        <div class="form-group form-inline">
                                <label for="customer_name" class="mr-2">Customer</label>
                                <div class="input-group my-group w-100"> 
                                <input placeholder="First Name" type="text" class="form-control form-control-sm flex-fill w-50" name="customer_first_name" id="customer_first_name" autocomplete="off">
                                <input placeholder="Last Name" type="text" class="form-control form-control-sm flex-fill w-50" name="customer_last_name" id="customer_last_name" autocomplete="off">
                                </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-12">
                        <div class="form-group form-inline">
                                <label for="paid" class="mr-2">Total Amount</label>
                                <div class="input-group my-group w-100"> 
                                    <select id="total_amount_operator" class="group-select-sm form-control form-control-sm  w-20" data-live-search="true" title="Please select a lunch ...">
                                        <option value=">=" selected>>=</option>
                                        <option value="<="><=</option>
                                    </select> 
                                    <input type="number" class="form-control form-control-sm text-right" name="total_amount" id="total_amount" value="0.00" placeholder="0.00">
                                    <span class="input-group-btn"></span>
                                </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-md-8 col-12">
                        <div class="form-group form-inline">
                                <label for="paid" class="mr-2">Date</label>
                                <div class="input-group my-group w-100"> 
                                    <select id="created_at_operator" class=" group-select-sm form-control form-control-sm  w-20" data-live-search="true" title="Please select a lunch ...">
                                        <option selected value="">-Select-</option>
                                        <option value=">=">FROM</option>
                                        <option value="<=">TO</option>
                                    </select> 
                                    <input type="date" class="form-control form-control-sm " name="created_at" id="created_at" placeholder="Test">
                                    <span class="input-group-btn"></span>
                                </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-md-4 col-12">
<!--                        <div class="form-group form-inline">
                            <label for="paid" class="mr-2"></label>
                        </div>-->
                        <div class="btn-group mr-2 text-center w-100">
                            <button href="javascript:reloadOrderTable();" id="" class="btn btn-md btn-secondary col-xl-6 col-md-12 col-6 ">Search</button>
                            <div class="btn-group w-100">
                                <button class="btn btn-info dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                  Checkout
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                  <a class="dropdown-item" href="javascript:scanQRCode()">Scan QR Code</a>
                                  <a class="dropdown-item" href="javascript:checkOut()">New Customer</a>
                                </div>
                              </div>
                        </div>
                    </div>
            </div>
                <table class="table table-hover table-row-border table-order-column" cellspacing="0" width="100%" id="table_order">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th class="text-center">Total Amount</th>
                            <th class="text-center">Tax</th>
                            <th class="text-center">Discounted</th>
                            <th class="text-center">Paid</th>
                            <th class="text-center">Created At</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </main>

        <div id="qr_code_modal" data-izimodal-title="Scan your QR code" class="modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <div id="qr-reader"></div>
                        <div id="qr-reader-results"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="checkOut()" class="btn btn-primary">New Customer</button>
                        <!--<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>-->
                    </div>
                </div>
            </div>
        </div>

        <div id="checkout_modal" data-izimodal-title="Checkout" class="iziModal"></div>

        <script>
            var table_order;

            $(function () {
                
                //set date filter back to empty when operator is empty
                $("#created_at_operator").on("change", function () {
                    if($(this).val().length == 0){
                        $('#created_at').val('');
                    }
                });

                table_order = $("#table_order").DataTable({
                    //            responsive: true,
                    aaSorting: [[6, "desc"]], // order by created at
                    bFilter: false,
                    columnDefs: [
                        {
                            aTargets: [2, 3, 4, 5],
                            className: "text-right",
                        },
                        {
                            aTargets: [6],
                            className: "text-center",
                        },
                        {
                            aTargets: [3],
                            className: "text-warning",
                        },
                        {
                            aTargets: [4],
                            className: "text-success",
                        },
                    ],
                    bLengthChange: false,
                    //            'lengthMenu': [
                    //                [10, 25, 50, 100, 250, 500, 9999],
                    //                [10, 25, 50, 100, 250, 500, "All"]
                    //              ],
                    serverMethod: "POST",
                    ajax: {
                        url: "store/order/getOrderList",
                        headers: {
                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                        },
                        data: function (data){
                            data.suppier = {
                                column : "supplier.supplier_id",
                                operator : '=',
                                value : $("#supplier_id").val()  
                            };
                            data.first_name = {
                                column : "customer.first_name",
                                operator : 'LIKE',
                                value : $("#customer_first_name").val()  
                            };
                            data.last_name = {
                                column : "customer.last_name",
                                operator : 'LIKE',
                                value : $("#customer_last_name").val()  
                            };
                            data.total_amount = {
                                column : "order.total_amount",
                                operator : $("#total_amount_operator").val()  ,
                                value : $("#total_amount").val()  
                            };
                            data.created_at = {
                                column : "order.created_at" ,
                                operator : $("#created_at_operator").val()  ,
                                value : $("#created_at").val()  
                            };
                        return data;
                        }
                    },
                    columns: [
                        {
                            data: "order_id",
                        },
                        {
                            data: "customer",
                        },
                        {
                            data: "total_amount",
                        },
                        {
                            data: "total_tax",
                            className: "text-warning text-right",
                        },
                        {
                            data: "total_discount",
                            className: "text-success text-right",
                        },
                        {
                            data: "paid",
                            className: "font-weight-bold text-right",
                        },
                        {
                            data: "created_at",
                        },
                    ],
                });
            });

            function scanQRCode() {
                $("#qr_code_modal").iziModal("destroy");

                $("#qr_code_modal").iziModal({
                    headerColor: "#000",
                    //            width: "40%",
                    icon: "icon-settings_system_daydream",
                    overlayClose: true,
                    fullscreen: true,
                    //            openFullscreen: true,
                    onOpening: function (modal) {
                        modal.startLoading();

                        modal.stopLoading();
                    },
                });
                $("#qr_code_modal").iziModal("open");

                initQRCodeReader();
                $('#qr-reader__dashboard_section_csr button').click();
            }

            var temp = 1;
            var temp2 = 1;
        </script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.min.js"></script>
@endsection
