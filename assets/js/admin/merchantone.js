function moderMerchant(id,type) {

	if (type==1) {
		if (!confirm('Вы уверены? Сайт будет ВКЛЮЧЕН и начнет работу!')) return false;
	} else if (type==0) {
		if (!confirm('Вы уверены? Сайт будет ОТКЛЮЧЕН и перестанет работать!')) return false;
	}

	$.post( ajaxurl + '=moderMerchant', 'id='+encodeURIComponent(id)+'&type='+encodeURIComponent(type), function(data){

		if (data.result) {

			document.location.href = '/' + adminurl + '/?page=merchantone&id=' + data.m_num + '&rand=' + Math.random();

		} else {

			showAlert(data.info);

		}

	},'jSON');


}

function saveMerchantPrc(id) {

	let prc = $('[name=m_prc]').val();

	$.post( ajaxurl + '=saveMerchantPrc', 'id='+encodeURIComponent(id)+'&prc='+encodeURIComponent(prc), function(data){

		if (data.result) {

			showAlert('Сохранено');

		} else {

			showAlert(data.info);

		}

		});


}