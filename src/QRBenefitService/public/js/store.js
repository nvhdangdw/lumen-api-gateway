function checkQRCOde(qr_code_data) {
    var customer_data;

    // check if data of QR code is JSON format
    try {
        customer_data = JSON.parse(qr_code_data);
    } catch (e) {
        errorToast("The provided QR code is not valid. Please try again!");
        return false;
    }
    
    $.ajax({
        type: "POST",
        dataType: "json",
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        url: window.location.origin + "/store/checkout/checkCustomer",
        data: { customer_data },
        success: function (data, xhr) {
            //open checkout modal
            checkOut(customer_data);
        },
        error: function (r) {
            console.log(r);
            errorToast("Customer not found. Please try again!");
        },
    });

    return;
}

function initQRCodeReader() {
    var resultContainer = document.getElementById("qr-reader-results");
    var lastResult,
        countResults = 0;
    function onScanSuccess(decodedText, decodedResult) {
        if (decodedText !== lastResult) {
            ++countResults;
            lastResult = decodedText;
            checkQRCOde("[" + decodedText + "]");
        }
    }

    var html5QrcodeScanner = new Html5QrcodeScanner("qr-reader", { fps: 10, qrbox: 250 });
    html5QrcodeScanner.render(onScanSuccess);
}

function IsJsonString(str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}

function errorToast(message = "") {
    if (message.lengh == 0) {
        message = "Error occured, please try again or contact Technical team.";
    }
    iziToast.error({
        position: "topCenter",
        message: message,
        zindex: 99999,
        onClosing: function () {},
    });
}

function successToast(message = "") {
    if (message.lengh == 0) {
        message = "Success.";
    }
    iziToast.success({
        position: "topCenter",
        message: message,
        timeout: 10000,
        zindex: 99999,
        onClosing: function () {},
    });
}

function checkOut(customer_data = []) {
    $("#qr_code_modal").iziModal("close");
    
    var customer_id = 0;
    
    $("#checkout_modal").iziModal("destroy");
    if(customer_data.length > 0){
        customer_id = customer_data[0].customer_id;
    }

    $("#checkout_modal").iziModal({
        headerColor: "#000",
        width: "90%",
        icon: "icon-settings_system_daydream",
        overlayClose: true,
        fullscreen: true,
        iframe: true,
        iframeHeight: document.documentElement.clientHeight,
        iframeURL: "/store/checkout/"+customer_id,
        //            openFullscreen: true,
        onOpening: function (modal) {
            modal.startLoading();
        },
        onOpened: function (modal) {
            modal.stopLoading();
        },
        onClosed: function(){
            reloadOrderTable();
        },
    });

    $("#checkout_modal").iziModal("open");
}

function afterAddOrder() {
    successToast("New Order added!");
    window.parent.$("#checkout_modal").iziModal("close");
}
function closePopUp(msg = '') {
    successToast(msg);
    window.parent.$(".iziModal").iziModal("close");
}

function updateAmounts() {
    $("#overlay").fadeIn();
    var form = $("#checkout_form");

    var total_amount = form.find("#total_amount").val();
    var promotion_select = $("#checkout_form").find("#promotion_select");
    var total_discount = form.find("#total_discount");
    var total_discount_span = form.find("#total_discount_span");
    var total_tax = form.find("#total_tax");
    var total_tax_span = form.find("#total_tax_span");
    var paid = form.find("#paid");

    // validate amount input
    if (isNaN(parseFloat(+total_amount)) || parseFloat(total_amount) <= 0 || total_amount.length == 0) {
        promotion_select.val("").attr("disabled", true);
        total_discount.val(0);
        total_discount_span.html(0);
        total_tax.val(0);
        total_tax_span.html(0);
        paid.html(0);
        $("#overlay").fadeOut();
        return false;
    }

    promotion_select.attr("disabled", false);

    //calculate tax
    var default_tax = form.find("#default_tax").val();
    var tax = parseFloat((total_amount * default_tax) / 100).toFixed(2);
    total_tax_span.html(tax+'$');
    total_tax.val(tax);

    //apply promotion
    var selected_option = promotion_select.find("option:selected");
    var discounted = 0;

    if (selected_option.val().length == 0 || selected_option.val() == 0) {
        total_discount_span.html(0 + "$");
        total_discount.val(0);
    }

    var discount_amount = selected_option.attr("data-discount-amount");
    var discount_unit = selected_option.attr("data-discount-unit");

    if (discount_unit == "%") {
        discounted = ( (parseFloat(tax) + parseFloat(total_amount) * discount_amount) / 100).toFixed(2);
    } else if (discount_unit == "$") {
        discounted = parseFloat(discount_amount).toFixed(2);
    }

    total_discount_span.html(discounted+'$');
    total_discount.val(discounted);

    //update total
    var paid = (parseFloat(total_amount) + parseFloat(tax) - parseFloat(discounted)).toFixed(2);
    $("#paid").html(paid+'$');

    $("#overlay").fadeOut();
}

function reloadOrderTable(){
    $("#overlay").fadeIn();
    table_order.ajax.reload();
    $("#overlay").fadeOut();
}

function reloadPromotionTable(){
    $("#overlay").fadeIn();
    table_promotion.ajax.reload();
    $("#overlay").fadeOut();
}

function loadPromotion(discount_id = 0){
    $("#promotion_modal").iziModal("destroy");

    $("#promotion_modal").iziModal({
        headerColor: "#000",
        width: "90%",
        
        icon: "icon-settings_system_daydream",
        overlayClose: true,
        fullscreen: true,
        iframe: true,
        iframeHeight: document.documentElement.clientHeight *2/3,
        iframeURL: "/store/promotion/showViewInfo/"+discount_id,
        //            openFullscreen: true,
        onOpening: function (modal) {
            modal.startLoading();
        },
        onOpened: function (modal) {
            modal.stopLoading();
        },
        onClosed: function(){
            reloadPromotionTable();
        },
    });

    $("#promotion_modal").iziModal("open");
}
