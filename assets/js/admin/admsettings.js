function saveASS() {

	$.post(ajaxurl+'=saveASS', $('#ass_form').serialize(), function(data){

		showAlert('Сохранено');

		});

}