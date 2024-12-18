function sendPayIn() {

	$.post( ajaxurl+'=sendPayIn', $('#step1_form').serialize(), function(data){

		if (data.result) {

			document.location.reload();			

		} else {

			showAlert(data.info);

		}

	},'jSON');

}

function resetPaysys(id) {

	$.post( ajaxurl+'=resetPaysys', 'id='+encodeURIComponent(id), function(data){

		if (data.result) {

			document.location.reload();			

		} else {

			showAlert(data.info);

		}

	},'jSON');	

}

function setPayDone(id) {

	if (!confirm('Вы уверены что оплатили? Для подтверждения платежа потребуется некоторое время')) return false;

	$.post( ajaxurl+'=setPayDone', 'id='+encodeURIComponent(id), function(data){

		if (data.result) {

			document.location.reload();			

		} else {

			showAlert(data.info);

		}

	},'jSON');
}

function activateVoucher(id) {

	let code = $('[name=voucher_code]').val();
	if (code=='') return false;

	$.post( ajaxurl+'=activateVoucher', 'id='+encodeURIComponent(id)+'&code='+encodeURIComponent(code), function(data){

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