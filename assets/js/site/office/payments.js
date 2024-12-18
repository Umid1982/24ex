function sendPayOut() {

	$.post( ajaxurl+'=sendPayOut', $('#pay_form').serialize(), function(data){

		if (data.result) {
			document.location.href = "/" + nowlang + "/office/paystat";
		} else {
			showAlert(data.info);
		}

	},'jSON');

}