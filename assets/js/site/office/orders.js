var KTDatatable;
initKTDatatable(
				'getOrders',
				[
					{
						field: 'order_id',
						title: 'ID',
						class: 'align-top',
						width: 40,
					},
					{
						field: 'order_id_shop',
						title: 'ID в магазине',
						class: 'align-top',
						width: 120,
					},
					{
						field: 'm_num',
						title: 'Магазин',
						class: 'align-top',
						template: function(row){
							return row.m_title+' ('+row.m_num+')';
						}
					},
					{
						field: 'order_amount',
						title: 'Сумма / Комиссия',
						class: 'align-top',
						template: function(row){
							return row.order_amount + '$ / ' + row.order_com + '$';
						}
					},
					{
						field: 'order_desc',
						title: 'Описание',
						class: 'align-top',
						sortable: false,
					},
					{
						field: 'order_dt_create',
						title: 'Создан',
						class: 'align-top',
						width: 120,
						template: function(row){
							return row.order_dt_create_line;
						}
					},
					{
						field: 'order_status',
						title: 'Статус',
						class: 'align-top',
						template: function(row){
							return row.order_status_line;
						}
					},

				],
				{},
				[	
					{
						id: '#kt_datatable_m_num',
						field: 'm_num'
					},
				]
		);


jQuery(document).ready(function() {
	KTDatatable.init();
});
