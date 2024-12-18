'use strict';
// Class definition

var KTDatatable;
initKTDatatable(
				'getVouchers',
				[
				{
					field: 'voucher_id',
					title: 'ID',
					width: 30,
					class: 'align-top'
				},     
				{
					field: 'voucher_code',
					title: 'Код',
					class: 'align-top',
					sortable: false,
				}, 
				{
					field: 'user_id',
					title: 'Пользователь',
					class: 'align-top',
					template: function(row){
						return row.user_id==null ? '- создан админом -' : '<a href="/'+adminurl+'/?page=users&search='+row.user_email+'">'+row.user_email+' ('+row.user_id+')</a>';
					}
				},   
				{
					field: 'voucher_value',
					title: 'Сумма остатка',
					class: 'align-top',
					template: function(row){
						return row.voucher_value + ' ' + row.bal_name;
					}
				},  
				{
					field: 'voucher_dt_create',
					title: 'Создан',
					class: 'align-top',
					template: function(row){
						return row.voucher_dt_create_line;
					}
				},   
				{
					field: 'voucher_dt_activate',
					title: 'Последняя активация',
					class: 'align-top',
					template: function(row){
						return row.voucher_dt_activate_line;
					}
				},  
				{
					field: 'voucher_status',
					title: 'Статус',
					width: 100,
					class: 'align-top',
					template: function(row) {

						let ret = '';
						if (row.voucher_status==0) {
							ret += '<span class="text-success">активен</span>&nbsp;&nbsp;\
									<a href="javascript:lockVoucher('+row.voucher_id+',true)"><i class="fas fa-lock"></i></a>';
						} else {
							ret += '<span class="text-danger"">заморожен</span>&nbsp;&nbsp;\
									<a href="javascript:lockVoucher('+row.voucher_id+',false)"><i class="fas fa-lock-open"></i></a>';
						}
					return ret;
					}
				}
			],

			{},

			[	
				{
					id: '#kt_datatable_search_user',
					field: 'user_id'
				},
				{
					id: '#kt_datatable_search_status',
					field: 'status'
				},
			]
		);


jQuery(document).ready(function() {
	KTDatatable.init();
});

function newVShow() {

	$('#editVoucherModal [name=value]').val('');
	$('#editVoucherModal [name=bal]').val('');

	$('#editVoucherModal').modal('show');

}

function newVoucher() {

	$.post(ajaxurl+'=newVoucher',$('#editVoucherForm').serialize(),function(data){

		if (data.result) {

			$('#editVoucherModal').modal('hide');
			$('#kt_datatable').KTDatatable().reload();

		} else {

			showAlert(data.info);

		}

	});

}

function lockVoucher(id,lock) {

	var status = lock ? 1 : 0;

	$.post(ajaxurl+'=lockVoucher', 'id='+encodeURIComponent(id)+'&status='+encodeURIComponent(status),function(data){

		if (data.result) {

			$('#kt_datatable').KTDatatable().reload();

		} else {

			showAlert(data.info);

		}

	});

}

