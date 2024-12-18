var KTDatatable;
initKTDatatable(
				'getUBs',
				[
				{
					field: 'bal_id',
					title: 'Валюта',
					class: 'align-top',
					template: function(row){
						let ret = '<img src="'+row.bal_icon+'" style="width:25px" /> ';
						if (row.ub_lock==1 || row.bal_status_active==0) ret += '<i class="fas fa-lock"></i> '; 
						ret += row.bal_title;
						return ret;
					}
				}, 
				/*{
					field: 'ub_num',
					title: 'Номер кошелька',
					class: 'align-top'
				},*/
				{
					field: 'ub_value',
					title: 'Баланс',
					class: 'align-top',
					template: function(row){
						return row.bal_name + ' ' + row.ub_value;
					}
				},
				{
					field: 'actions',
					title: '',
					class: 'align-top text-right',
					autoHide: false,
					sortable: false,
					template: function(row){
						let ret = '';
						if (row.can_voucher) ret += '<a class="ml-2" id="newVoucherBtn'+row.ub_id+'" href="#" onClick="return newVoucher('+row.ub_id+',\''+row.bal_name+'\')" title="Создать ваучер">'+
							'<img src="/assets/media/svg/icons/Shopping/Ticket.svg"/></a>';
						if (row.can_transfer) ret += '<a class="ml-2" id="newTransferBtn'+row.ub_id+'" href="#" onClick="return newTransfer('+row.ub_id+',\''+row.bal_name+'\')" title="Перевести">'+
							'<img src="/assets/media/svg/icons/Navigation/Arrow-from-left.svg"/></a>';
						if (row.can_payin) ret += '<a class="ml-2" id="newPayInBtn'+row.ub_id+'" href="#" onClick="return newPayIn('+row.ub_id+',\''+row.bal_name+'\')" title="Пополнить">'+
							'<img src="/assets/media/svg/icons/Navigation/Arrow-to-bottom.svg"/></a>';
						if (row.can_payout) ret += '<a class="ml-2" id="newPayOutBtn'+row.ub_id+'" href="#" onClick="return newPayOut('+row.ub_id+',\''+row.bal_name+'\')" title="Вывести">'+
							'<img src="/assets/media/svg/icons/Navigation/Arrow-from-bottom.svg"/></a>';
						return ret;
					}
				},
			]
		);


jQuery(document).ready(function() {
	KTDatatable.init();
});

// ====================================================================================


function addBal(id) {

	if (id==0) return false;

	$.post( ajaxurl+'=addBal' , 'id='+id, function(data){

		if (data.result) {

			$('#balForAdd').val(0).find('[value='+id+']').remove();
			$('#kt_datatable').KTDatatable().reload();

		} else {

			showAlert(data.info);

		}

	},'jSON');

}

function delBal(id) {

	if (id==0) return false;

	$.post( ajaxurl+'=delBal' , 'id='+id, function(data){

		if (data.result) {

			$('#kt_datatable').KTDatatable().reload();

		} else {

			showAlert(data.info);

		}

	},'jSON');

}

function newPayIn(id,curr) {

	var val = showPrompt('Сумма пополнения, '+curr, 'newPayInBtn'+id);
	if (val=='' || val==null || val==undefined) return false;

	$.post( ajaxurl+'=sendPayIn', 'ub_id='+encodeURIComponent(id)+'&val='+encodeURIComponent(val), function(data){

		if (data.result) {

			document.location.href = data.pay_link;
			
		} else {
			
			showAlert(data.info);
		}

	},'jSON');

	return false;


}

function newPayOut(id,curr) {

	var val = showPrompt('Сумма вывода, '+curr, 'newPayOutBtn'+id);
	if (val=='' || val==null || val==undefined) return false;

	$.post( ajaxurl+'=sendPayOut', 'ub_id='+encodeURIComponent(id)+'&val='+encodeURIComponent(val), function(data){

		if (data.result) {

			document.location.href = data.pay_link;
			
		} else {
			
			showAlert(data.info);
		}

	},'jSON');

	return false;


}

function newVoucher(id,curr='') {

	var val = showPrompt('Создать ваучер на сумму, '+curr, 'newVoucherBtn'+id);
	if (val=='' || val==null || val==undefined) return false;

	$.post( ajaxurl+'=newVoucher', 'ub_id='+encodeURIComponent(id)+'&val='+encodeURIComponent(val), function(data){

		if (data.result) {

			document.location.href = "/" + nowlang + "/office/vouchers";
			
		} else {
			
			showAlert(data.info);
		}

	},'jSON');

	return false;

}

function newTransfer(id,curr) {

	var val = showPrompt('Перевести другому клиенту сумму, '+curr, 'newTransferBtn'+id);
	if (val=='' || val==null || val==undefined) return false;

	$.post( ajaxurl+'=newTransfer', 'ub_id='+encodeURIComponent(id)+'&val='+encodeURIComponent(val), function(data){

		if (data.result) {

			document.location.href = data.pay_link;
			
		} else {
			
			showAlert(data.info);
		}

	},'jSON');

	return false;

}