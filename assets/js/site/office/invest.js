var KTDatatable;
initKTDatatable(
				'getUIs',
				[
				{
					field: 'ui_id',
					title: 'ID',
					class: 'align-top',
					width: 30,
				},
				{
					field: 'plan_name',
					title: 'Название',
					class: 'align-top',
					sortable: false,
				},
				{
					field: 'ui_value_now',
					title: 'Вложено / Начислено',
					class: 'align-top',
					template: function(row){
						return row.ui_value_start + ' / ' + row.ui_value_now + ' ' + row.bal_name;
					}
				},
				{
					field: 'plan_proc',
					title: 'Ставка',
					class: 'align-top',
					sortable: false,
					template: function(row){
						return row.plan_proc + '%';
					}
				},
				{
					field: 'ui_dt_start',
					title: 'Открыт',
					class: 'align-top',
					template: function(row){
						return row.ui_dt_start_line;
					}
				},
				{
					field: 'ui_status',
					title: 'Статус',
					class: 'align-top',
					template: function(row){
						return row.ui_status_line;
					}
				},
				{
					field: 'actions',
					title: '',
					class: 'align-top text-right',
					sortable: false,
					template: function(row){
						let ret = '';
						if (row.can_append) ret += '<a href="#" id="appendInvestBtn'+row.ui_id+'" onClick="return appendInvest('+row.ui_id+',\''+(row.plan_max-row.ui_value_start)+'\',\''+row.bal_name+'\')" title="Дополнить"><img src="assets/media/svg/icons/Navigation/Plus.svg"/></a>';
						if (row.can_unfreeze) ret += '<a href="#" id="unFreezeUIBtn'+row.ui_id+'" onClick="return unFreezeUI('+row.ui_id+')" title="Разморозить"><img src="/assets/media/svg/icons/Navigation/Arrow-from-bottom.svg"/></a>';
						return ret;
					}
				},
			]
		);


jQuery(document).ready(function() {
	KTDatatable.init();
});

// ====================================================================================

function newInvest() {

	var plan_id = parseInt($('#newInvestPlanId').val());
	if (plan_id==0 || plan_id==undefined || plan_id==null) return false;

	var value = $('#newInvestVal').val();

	$.post( ajaxurl + '=newInvest', 'plan_id=' + encodeURIComponent(plan_id) + '&value=' + encodeURIComponent(value), function(data){

		if (data.result) {

			$('#newInvestPlanId').val(0)
			$('#newInvestVal').val('');
			$('#kt_datatable').KTDatatable().reload();

		} else {

			showAlert(data.info);

		}

	},'jSON');


	return false;

}

function unFreezeUI(ui_id) {

	if (!showConfirm('Вы уверены?','unFreezeUIBtn'+ui_id)) return false;

	$.post( ajaxurl + '=unFreezeUI', 'ui_id='+encodeURIComponent(ui_id), function(data){

		if (data.result) {

			$('#kt_datatable').KTDatatable().reload();

		} else {

			showAlert(data.info);

		}

	},'jSON');

	return false;

}

function appendInvest(ui_id, min, name) {

	var value = showPrompt('Введите сумму дополнения к вкладу, '+name, 'appendInvestBtn'+ui_id, min);
	if (value=='') return false;

	$.post( ajaxurl + '=appendInvest', 'ui_id=' + encodeURIComponent(ui_id) + '&value=' + encodeURIComponent(value), function(data){

		if (data.result) {

			$('#kt_datatable').KTDatatable().reload();

		} else {

			showAlert(data.info);

		}

	},'jSON');


	return false;

}