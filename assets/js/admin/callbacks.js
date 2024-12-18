var KTDatatable;
initKTDatatable(
				'getCallbacks',
				[
					{
						field: 'cbl_id',
						title: 'ID',
						width: 40,
					},
					{
						field: 'user_line',
						title: 'Пользователь',
						sortable: false,
						template: function(row){
							return '<a href="/'+adminurl+'/?page=users&search='+row.user_email+'">'+row.user_email+' ('+row.user_id+')</a>';
						}
					},
					{
						field: 'm_num',
						title: 'Магазин',
						template: function(row){
							return '<a href="/'+adminurl+'/?page=merchantone&id='+row.m_num+'">'+row.m_title+' ('+row.m_num+')</a>';
						}
					},
					{
						field: 'order_id',
						title: 'Заказ',
						template: function(row){
							return row.order_id;
						}
					},
					{
						field: 'cbl_dt_create',
						title: 'Создан<br>Отправлен<br>Завершен',
						template: function(row){
							return row.cbl_dt_create_line + '<br>' + row.cbl_dt_last_send_line + '<br>' + row.cbl_dt_end_line;
						}
					},
					{
						field: 'cbl_status',
						title: 'Статус',
						width: 120,
						template: function(row){
							return row.cbl_status_line;
						}
					}
				],
				{
					title: 'Логи',
					content: function(e) {

						openCblLogs(e.data,e.detailCell);
						
					}
				}
		);


function openCblLogs(data,detailCell) {

	let block = '\
					<div class="col-xl-12">\
						<div class="card">\
							<div class="card-header h-auto">\
								<h3>Логи запросов к апи магазина</h3>\
							</div>\
							<div class="card-body h-auto">\
								<div cbl_logs="'+data.cbl_id+'" class="datatable datatable-bordered datatable-head-custom"></div>\
							</div>\
						</div>\
					</div>\
				\
				</div>\
				';

	$(detailCell).append(block).ready(function(){

			$('[cbl_logs="'+data.cbl_id+'"]').KTDatatable({
				data: {
					type: 'remote',
					source: {
						read: {
							url: ajaxurl + '=getCblLogs',
							params: {
								// custom query params
								query: {
									cbl_id: data.cbl_id,
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
						noRecords: 'Логов не найдено',
					},
				},

				sortable: false,
				pagination: true,

				// columns definition
				columns: [
					{
						field: 'send_dt',
						title: 'Дата',
						template: function(row){
							return row.send_dt_line;
							}
					}, {
						field: 'send_result',
						title: 'Результат',
						template: function(row) {
							return row.send_result_line;
						}
					}, {
						field: 'send_answer',
						title: 'Ответ апи',
						template: function(row) {
							return row.answer_line;
						}
					}
				]
			});

	});

}


jQuery(document).ready(function() {
	KTDatatable.init();
});
