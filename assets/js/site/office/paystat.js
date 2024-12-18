var KTDatatable;
initKTDatatable(
				'getPSs',
				[
				{
					field: 'pay_id',
					title: 'ID',
					class: 'align-top',
					width: 30,
					template: function(row){
						return '<a href="/'+nowlang+'/payment/'+row.typeurl+'?pay='+row.pay_id+'">'+row.pay_id+'</a>';
					}
				}, 
				{
					field: 'pay_type',
					title: 'Тип',
					class: 'align-top',
					width: 150,
					template: function(row){
						return row.pay_type_line;
					}
				}, 
				{
					field: 'pay_value',
					title: 'Сумма',
					class: 'align-top',
					template: function(row){
						return row.pay_value + ' ' + row.bal_name;
					}
				},
				{
					field: 'pay_dt',
					title: 'Дата',
					class: 'align-top',
					template: function(row){
						return row.pay_dt_line;
					}
				},
				{
					field: 'pay_status',
					title: 'Статус',
					class: 'align-top',
					template: function(row){
						let ret = '<span class="pay-status-'+row.pay_status+'">'+row.pay_status_line+'</span>';
						if (row.expired) ret += ' | <span style="color:gray">Время истекло</span>';
						else if (row.pay_status==0) ret += ' | <a href="javascript:rejectPay('+row.pay_id+')">Отменить</a>';
						return ret;
					}
				}
			]
		);


jQuery(document).ready(function() {
	KTDatatable.init();
});

// ====================================================================================

function saveUserComm(pay_id) {
	var comm = $('[pay_id='+pay_id+'] textarea[name=user_comm]').val();
	$.post(ajaxurl+'=saveUserComm', 'pay_id='+encodeURIComponent(pay_id)+'&comm='+encodeURIComponent(comm), function(data){
		if (data.result) {
			showAlert('Сохранено');
		} else {
			showAlert(data.info);
		}
	},'jSON');
}

function rejectPay(pay_id) {
	if (!confirm('Вы уверены?')) return false;
	$.post(ajaxurl+'=cancelPay', 'pay_id='+encodeURIComponent(pay_id), function(data){
		if (data.result) {
			showAlert('Платеж отменен');
			$('#kt_datatable').KTDatatable().reload();
		} else {
			showAlert(data.info);
		}
	},'jSON');
}