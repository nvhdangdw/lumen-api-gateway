/* ------------------------------------------------------------------------------
 *
 *  # Custom JS code
 *
 *  Place here all your custom js. Make sure it's loaded after app.js
 * ---------------------------------------------------------------------------- */
var showPreloader = function () {
	$(".preloader").css("display", "flex");
};
var hidePreloader = function () {
	$(".preloader").css("display", "none");
};


var notifyModalError = function (message) {
	$("#modal-error").iziModal({
		headerColor: "#d43838",
		width: 700,
		timeout: 50000,
		pauseOnHover: true,
		timeoutProgressbar: true,
		attached: "bottom",
	});
	$("#modal-error .iziModal-content").html(message);
	$("#modal-error").iziModal("open");
};
var notifyModalWarning = function (message) {
	$("#modal-warning").iziModal({
		headerColor: "#ff9800",
		width: 700,
		timeout: 50000,
		pauseOnHover: true,
		timeoutProgressbar: true,
		attached: "bottom",
	});
	$("#modal-warning .iziModal-content").html(message);
	$("#modal-warning").iziModal("open");
};

var notifyModalInfo = function (message) {
	$("#modal-info").iziModal({
		headerColor: "#00617a",
		width: 700,
		timeout: 50000,
		pauseOnHover: true,
		timeoutProgressbar: true,
		attached: "bottom",
	});
	$("#modal-info .iziModal-content").html(message);
	$("#modal-info").iziModal("open");
};
var notifyError = function (message) {
	iziToast.error({
		id: "success",
		title: "Notice",
		message: message,
		position: "topCenter",
		timeout: 40000,
	});
};

var notifyWarning = function (message) {
	iziToast.warning({
		id: "warning",
		title: "Notice",
		message: message,
		position: "topCenter",
		timeout: 40000,
	});
};

var notifySuccess = function (message) {
	iziToast.success({
		id: "success",
		title: "Notice",
		message: message,
		position: "topCenter",
		timeout: 40000,
	});
};

var uri = new URI();