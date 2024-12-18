
// ====================================================================================


function saveMerchant() {

	if (!showConfirm('Вы уверены?','saveMerchantBtn')) return false;

	$.post( ajaxurl + '=saveMerchant', $('#m_data').serialize(), function(data){

		if (data.result) {

			if (data.is_new) {

				document.location.href = '/'+nowlang+'/office/merchantone/?id='+data.m_num;

			} else {

				showAlert('Сохранено');

			}

		} else {

			showAlert(data.info);

		}

	},'jSON');

	return false;

}

function checkMerchant(id) {

	if (!showConfirm('Вы уверены?','checkMerchantBtn')) return false;

	$('#errors_block').html(' ... проверяем ... ');

	$.post( ajaxurl + '=checkMerchant', 'id='+encodeURIComponent(id), function(data){

		if (data.result) {

			document.location.href = '/'+nowlang+'/office/merchantone/?id='+data.m_num+'#check';
			document.location.reload();

		} else {

			showAlert('Обработчик не прошел проверку');

			let block = 'Обработчик не прошел проверку, список ошибок<br><br>\
						<i class="flaticon2-'+(data.errors.sign ? 'check-mark text-success' : 'cross text-danger')+'"></i> &nbsp;&nbsp;&nbsp; Ответ на неверную подпись<br>\
						<i class="flaticon2-'+(data.errors.shop ? 'check-mark text-success' : 'cross text-danger')+'"></i> &nbsp;&nbsp;&nbsp; Ответ на неверный ID магазина<br>\
						<i class="flaticon2-'+(data.errors.orderid_wrong ? 'check-mark text-success' : 'cross text-danger')+'"></i> &nbsp;&nbsp;&nbsp; Ответ на несуществующий ID заказа<br>\
						<i class="flaticon2-'+(data.errors.orderid_fail ? 'check-mark text-success' : 'cross text-danger')+'"></i> &nbsp;&nbsp;&nbsp; Ответ на заказ `fail`<br>\
						<i class="flaticon2-'+(data.errors.orderid_success ? 'check-mark text-success' : 'cross text-danger')+'"></i> &nbsp;&nbsp;&nbsp; Ответ на заказ `test`<br>\
						';

			$('#errors_block').html(block);


		}

	});

}

function moderMerchant(id) {

	if (!showConfirm('Вы уверены, что сайт полностью готов?','moderMerchantBtn')) return false;

	$.post( ajaxurl + '=moderMerchant', 'id='+encodeURIComponent(id), function(data){

		if (data.result) {

			document.location.href = '/'+nowlang+'/office/merchantone/?id='+data.m_num+'#check';
			document.location.reload();

		} else {

			showAlert(data.info);

		}

	});

}

function pssMerchant(id) {

	if (!showConfirm('Вы уверены, что хоиите изменить принимаемые валюты?','pssMerchantBtn')) return false;

	$.post( ajaxurl + '=pssMerchant', $('#merch_pss').serialize(), function(data){

		if (data.result) {

			showAlert('Сохранено');

		} else {

			showAlert(data.info);

		}

	});

}

$(document).ready(function(){

	var hash = window.location.hash.substr(1);
	if (hash=='api') $('[data-toggle=tab]:eq(1)').click();
	else if (hash=='check') $('[data-toggle=tab]:eq(2)').click();
	else if (hash=='money') $('[data-toggle=tab]:eq(3)').click();

});