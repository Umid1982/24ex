'use strict';
// Class definition

var now_user_data = {};

var rps_deps = [];
var rps_invs = [];

var KTDatatable;
initKTDatatable(
				'getUsersInvest',
				[
					{
						field: 'ui_id',
						title: 'ID',
						width: 30,
						textAlign: 'center',
					}, 
					{
						field: 'user_id',
						title: 'Пользователь',
						width: 200,
						template: function(row){
							return '[' + row.user_id + '] ' + row.user_email;
						}
					},
					{
						field: 'plan_id',
						title: 'План / Ставка',
						template: function(row){
							return row.plan_name + ' / ' + row.plan_proc + '%';
						}
					},
					{
						field: 'ui_value_now',
						title: 'Вложение /<br> Начислено',
						template: function(row){
							return row.ui_value_start + ' / ' + row.ui_value_now + ' ' + row.bal_name;
						}
					},
					{
						field: 'ui_dt_start',
						title: 'Создан',
						template: function(row){
							return row.ui_dt_start_line;
						}
					},
					{
						field: 'ui_dt_last_calc',
						title: 'Последнее начисление',
						template: function(row){
							return row.ui_dt_last_calc_line;
						}
					},
					{
						field: 'ui_status',
						title: 'Статус',
						template: function(row){
							return row.ui_status_line;
						}
					}

				],
				{
					title: 'Подробно',
					content: function(e) {

						var data = e.data;

						let block = '\
									<div class="row" style="padding:10px;">\
										<div class="col-xl-6">\
											<div class="card">\
												<div class="card-header h-auto">\
													<h3>Данные депозита</h3>\
												</div>\
												<div class="card-body h-auto">\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">ID:</span>\
														<span class="text-hover-primary">'+data.ui_id+'</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">План:</span>\
														<span class="text-hover-primary">'+data.plan_name+'</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">Процент:</span>\
														<span class="text-hover-primary">'+data.plan_proc+'%</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">План:</span>\
														<span class="text-hover-primary">'+data.plan_name+'</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">Начисления каждые:</span>\
														<span class="text-hover-primary">'+data.plan_time+' ч.</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">Максимум:</span>\
														<span class="text-hover-primary">'+data.plan_max_time+' ч.</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">Создан:</span>\
														<span class="text-hover-primary">'+data.ui_dt_start_line+'</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">Дата окончания:</span>\
														<span class="text-hover-primary">'+data.ui_dt_end_line+'</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">Сумма вначале:</span>\
														<span class="text-hover-primary">'+data.ui_value_start+' '+data.bal_name+'</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">Сумма сейчас:</span>\
														<span class="text-hover-primary">'+data.ui_value_now+' '+data.bal_name+'</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">Последнее начисление:</span>\
														<span class="text-hover-primary">'+data.ui_dt_last_calc_line+'</span>\
													</div>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
														<span class="font-weight-bold mr-2">Статус:</span>\
														<span class="text-hover-primary">'+data.ui_status_line+'</span>\
													</div>\
													<hr>\
													<div class="d-flex align-items-center justify-content-between mb-2">\
					'+(data.can_on  ? '<button type="button" onClick="uiNewStatus('+data.ui_id+',0)" class="btn btn-success">Включить начисления</button>' : '')+'\
					'+(data.can_off ? '<button type="button" onClick="uiNewStatus('+data.ui_id+',2)" class="btn btn-danger">Отключить начисления</button>' : '')+'\
					'+(data.can_pay ? '<button type="button" onClick="uiNewStatus('+data.ui_id+',3)" class="btn btn-info">Выплатить на баланс и закрыть</button>' : '')+'\
													</div>\
													\
												</div>\
											</div>\
										</div>\
										<div class="col-xl-6">\
											<div class="card">\
												<div class="card-header h-auto">\
													<h3>История депозита</h3>\
												</div>\
												<div class="card-body h-auto">\
													<div il_block="'+data.ui_id+'" class="datatable datatable-bordered datatable-head-custom"></div>\
												</div>\
											</div>\
										</div>\
									</div>\
									';
						
						$(e.detailCell).append(block).ready(function(){

							$('[il_block="'+data.ui_id+'"]').KTDatatable({

								data: {
									type: 'remote',
									source: {
										read: {
											url: ajaxurl + '=getIls',
											params: {
												// custom query params
												query: {
													ui_id: data.ui_id,
												},
											},
										},
									},
									pageSize: 20,
									serverPaging: true,
									serverFiltering: true,
									serverSorting: true,
									saveState: false,
								},

								// layout definition
								layout: {
									scroll: true,
									footer: false,
								},

								translate: {
									records: {
										processing: 'Загрузка...',
										noRecords: 'Записей не найдено',
									},
								},

								sortable: false,
								pagination: true,

								// columns definition
								columns: [
									{
										field: 'il_dt',
										title: 'Дата',
										template: function(row){
											return row.il_dt_line;
											}
									}, {
										field: 'il_type',
										title: 'Действие',
										template: function(row){
											return row.il_type_line;
											}
									}, {
										field: 'il_val',
										title: 'Сумма',
										template: function(row){
											let ret = '';
											if (row.il_type>=2 && row.il_type<=4) ret = '<span style="color:red">- '+row.il_val+' '+data.bal_name+'</span>';
											else ret = '<span style="color:green">+ '+row.il_val+' '+data.bal_name+'</span>';
											return ret;
											}
									},
								]

							});

						});
						
					}
				},
				[
					{
						id: '#kt_datatable_search_user',
						field: 'user_id'
					},
					{
						id: '#kt_datatable_search_status',
						field: 'ui_status'
					}
				]
			);

jQuery(document).ready(function() {
	KTDatatable.init();
});


function uiNewStatus(ui_id,status) {

	if (!confirm('Вы уверены, что хотите изменить статус?')) return false;

	$.post(ajaxurl+'=uiNewStatus', 'ui_id='+encodeURIComponent(ui_id)+'&status='+encodeURIComponent(status),function(data){

		if (data.result) {
			$('#kt_datatable').KTDatatable().reload();
		} else {
			showAlert(data.info);
		}

	},'jSON');

}