function sendFeedback() {

	$.post( ajaxurl+'=sendFeedback', $('#feedback').serialize(), function(data){

		if (data.result) {

			showAlert('Сообщение отправлено!');
			$('#feedback input, #feedback textarea').val('');

		} else {

			showAlert(data.info);

		}

	},'jSON')

}