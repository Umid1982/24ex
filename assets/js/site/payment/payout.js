function sendPayOut() {

	$.post( ajaxurl+'=sendPayOut', $('#step1_form').serialize(), function(data){

		if (data.result) {

			document.location.reload();			

		} else {

			showAlert(data.info);

		}

	},'jSON');

}

function setPayProps() {

	$.post( ajaxurl+'=sendPayProps', $('#step2_form').serialize(), function(data){

		if (data.result) {

			document.location.reload();			

		} else {

			showAlert(data.info);

		}

	},'jSON');

}

$(document).ready(function(){

	$('[name=paysys_id]:eq(0)').prop('checked',true);

});