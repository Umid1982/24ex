// step 1
function sendTransferData() {

	$.post(ajaxurl+'=sendTransferData', $('#step1_form').serialize(), function(data){

		if (data.result) {
			document.location.reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

// step 2
function resetTransferProps(pay_id) {

	$.post(ajaxurl+'=resetTransferProps', 'pay_id='+encodeURIComponent(pay_id), function(data){

		if (data.result) {
			document.location.reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

function confirmTransfer(pay_id) {

	$.post(ajaxurl+'=confirmTransfer', 'pay_id='+encodeURIComponent(pay_id), function(data){

		if (data.result) {
			document.location.reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');
	
}