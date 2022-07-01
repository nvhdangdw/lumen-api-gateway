@extends('store.store_template') @section('content')

        <main role="main" class="col-md-9 ml-sm-auto col-lg-10 pt-3 px-4">
<!--            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                <h2>Orders</h2>
                <div class="btn-toolbar mb-2 mb-md-0">
                    <div class="btn-group mr-2">
                        <a href="#" data-toggle="modal" data-target="#qr_code_modal" class="btn btn-sm btn-info">New Order</a>
                        <a href="javascript:void(0)" id="new-order-btn" class="btn btn-sm btn-info">New Order</a>
                    </div>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table table-hover table-row-border table-order-column" cellspacing="0" width="100%" id="table_order">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th class="text-center">Total Amount</th>
                            <th class="text-center">Tax</th>
                            <th class="text-center">Discounted</th>
                            <th class="text-center">Paid</th>
                            <th class="text-center">Created at</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>-->
            <h2>Promotions</h2>
            <div class="table-responsive">
                <table class="table table-hover table-bordered" cellspacing="0" width="100%" id="table_promotion">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Promotion Type</th>
                            <th>Amount</th>
                            <th class="text-center">Applied</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($promotion_data as $key =>
                        $promotion): ?>
                        <tr>
                            <td>{{ $promotion->discount_id }}</td>
                            <td>{{ $promotion->name }}</td>
                            <td>{{ $promotion->type }}</td>
                            <td>{{ $promotion->discount_amount . $promotion->discount_unit }}</td>
                            <td class="text-right">{{ $promotion->applied }}$</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </main>

        <div id="qr_code_modal" data-izimodal-title="Scan your QR code" class="modal">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <!--            <div class="modal-header">
                <h5 class="modal-title">Customer from QR Code</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>-->
                    <div class="modal-body">
                        <div id="qr-reader"></div>
                        <div id="qr-reader-results"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="checkOut()" class="btn btn-primary">Input Manually</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="checkout_modal" data-izimodal-title="Checkout" class="iziModal"></div>

        <script>
            var table_order;

            $(function () {
                $("#new-order-btn").on("click", function () {
                    scanQRCode();
                });

//                table_order = $("#table_order").DataTable({
//                    //            responsive: true,
//                    aaSorting: [[6, "desc"]],
//                    columnDefs: [
//                        {
//                            aTargets: [2, 3, 4, 5],
//                            className: "text-right",
//                        },
//                        {
//                            aTargets: [6],
//                            className: "text-center",
//                        },
//                        {
//                            aTargets: [3],
//                            className: "text-warning",
//                        },
//                        {
//                            aTargets: [4],
//                            className: "text-success",
//                        },
//                    ],
//                    bLengthChange: false,
//                    //            'lengthMenu': [
//                    //                [10, 25, 50, 100, 250, 500, 9999],
//                    //                [10, 25, 50, 100, 250, 500, "All"]
//                    //              ],
//                    serverMethod: "POST",
//                    ajax: {
//                        url: "store/order/getOrderList",
//                        headers: {
//                            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
//                        },
//                        data: function (data) {
//                            data.supplier_id = $("#supplier_id").val();
//                        },
//                    },
//                    columns: [
//                        {
//                            data: "order_id",
//                        },
//                        {
//                            data: "customer",
//                        },
//                        {
//                            data: "total_amount",
//                        },
//                        {
//                            data: "total_tax",
//                            className: "text-warning text-right",
//                        },
//                        {
//                            data: "total_discount",
//                            className: "text-success text-right",
//                        },
//                        {
//                            data: "paid",
//                            className: "font-weight-bold text-right",
//                        },
//                        {
//                            data: "created_at",
//                        },
//                    ],
//                });
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
            }

            var temp = 1;
            var temp2 = 1;
        </script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/css/iziModal.min.css" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/izimodal/1.5.1/js/iziModal.min.js"></script>
@endsection
