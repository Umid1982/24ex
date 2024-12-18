function saveMainSettings() {

	$.post(ajaxurl+'=saveMainSettings', $('#settings_form').serialize(), function(data){

		showAlert('Сохранено');

		});

}