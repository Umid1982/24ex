var KTDatatable;
initKTDatatable(
				'getUVs',
				[
				{
					field: 'voucher_code',
					title: 'Код',
					class: 'align-top',
				}, 
				{
					field: 'voucher_value',
					title: 'Сумма',
					class: 'align-top',
					template: function(row){
						return row.voucher_value + ' ' + row.bal_name;
					}
				},
				{
					field: 'voucher_status',
					title: 'Статус',
					class: 'align-top',
					template: function(row){
						return row.voucher_status_line;
					}
				}
			]
		);


jQuery(document).ready(function() {
	KTDatatable.init();
});