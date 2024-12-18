function lockIp(ip) {

	if (ip=='') return false;
	if (ip=='UNKNOWN') return false;
	if (!confirm('Вы уверены что хотите заблокировать IP адрес '+ip+'?')) return false;

	$.post(ajaxurl+'=lockIp', 'ip='+encodeURIComponent(ip), function(data){

		if (data.result) {
			showAlert('IP адрес заблокирован');
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

function showNewAlerts() {

	$.post(ajaxurl+'=getNewAlerts','from='+nowtm, function(data){

		if (data.result) {

			if (data.alerts.length>0) {

				for (var i in data.alerts) {

					showAlert(data.alerts[i]);

				}
			}

		nowtm = data.nowtm;

		}

	});

}


function showAllUsers() {

	$('#user_search').val('');
	$('#kt_datatable').KTDatatable().search('','user_id');
	
}


function showAllMerchants() {

	$('#merchant_search').val('');
	$('#kt_datatable').KTDatatable().search('','m_num');
	
}

$(document).ready(function(){

	setInterval(showNewAlerts,10000);

	$('#user_search').autocomplete({
		source: ajaxurl+'=userSearchAutocomplete',
      	minLength: 0,
      	select: function( event, ui ) {
      		//$('#kt_datatable_search_user').val(ui.item.raw_id);
      		$('#kt_datatable').KTDatatable().search(ui.item.raw_id,'user_id');
      	}
	});

	$('#merchant_search').autocomplete({
		source: ajaxurl+'=merchantSearchAutocomplete',
      	minLength: 0,
      	select: function( event, ui ) {
      		//$('#kt_datatable_search_user').val(ui.item.raw_id);
      		$('#kt_datatable').KTDatatable().search(ui.item.raw_id,'m_num');
      	}
	});
	
});