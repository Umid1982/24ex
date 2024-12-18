function saveUData() {

	var formData = new FormData($('#udata')[0]);

    $.ajax({
        url: ajaxurl+'=saveUData',
        type: 'POST',
        dataType: 'jSON',
        data: formData,
        async: false,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {

			showAlert(data.info);

		}

	},'jSON');

}

function setNewPass() {

	$.post(ajaxurl+'=setNewPass', $('#change_pass_form').serialize(), function(data){

		if (data.result) {
			document.location.href = "/" + nowlang + "/office/cabinet/#security";
			document.location.reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

function setPayPass() {

	$.post(ajaxurl+'=setPayPass', $('#pay_pass_form').serialize(), function(data){

		if (data.result) {
			document.location.href = "/" + nowlang + "/office/cabinet/#security";
			document.location.reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

function enable2Fa() {

	$.post(ajaxurl+'=enable2Fa', $('#google_2fa_form').serialize(), function(data){

		if (data.result) {
			document.location.href = "/" + nowlang + "/office/cabinet/#security";
			document.location.reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

function disable2Fa() {

	$.post(ajaxurl+'=disable2Fa', $('#google_2fa_form').serialize(), function(data){

		if (data.result) {
			document.location.href = "/" + nowlang + "/office/cabinet/#security";
			document.location.reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

function genUAPIkey() {

	$.post(ajaxurl+'=genUAPIkey', '', function(data){

		if (data.result) {
			document.location.href = "/" + nowlang + "/office/cabinet/#api";
			document.location.reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');

	return false;
}

function saveASS() {

	$.post(ajaxurl+'=saveASS', $('#ass_form').serialize(), function(data){

		showAlert('Сохранено');

		});

}

$(document).ready(function(){

	$('[name=birth_date]').datepicker({
		format: 'yyyy-mm-dd',
        language: 'ru-RU'
	});

	var hash = window.location.hash.substr(1);
	if (hash=='security') $('[data-toggle=tab]:eq(1)').click();
	else if (hash=='tg') $('[data-toggle=tab]:eq(2)').click();
	else if (hash=='api') $('[data-toggle=tab]:eq(3)').click();

	if (need_change_pass) {
		$('[data-toggle=tab]:eq(1)').click();
		$('[data-toggle=tab]').bind('click',function(){return false;});
		}

	var avatar = new KTImageInput('kt_image_4');

	avatar4.on('cancel', function(imageInput) {
		swal.fire({
			title: 'Аватар убран!',
			type: 'success',
			buttonsStyling: false,
			confirmButtonText: 'Отлично',
			confirmButtonClass: 'btn btn-primary font-weight-bold'
		});
	});

	avatar4.on('change', function(imageInput) {
		swal.fire({
			title: 'Аватар изменен!',
			type: 'success',
			buttonsStyling: false,
			confirmButtonText: 'Отлично',
			confirmButtonClass: 'btn btn-primary font-weight-bold'
		});
	});

	avatar4.on('remove', function(imageInput) {
		swal.fire({
			title: 'Аватар удален!',
			type: 'error',
			buttonsStyling: false,
			confirmButtonText: 'Ок',
			confirmButtonClass: 'btn btn-primary font-weight-bold'
		});
	});


});