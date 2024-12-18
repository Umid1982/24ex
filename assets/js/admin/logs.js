'use strict';
// Class definition

var KTDatatable;
initKTDatatable(
				'getLogs&account='+now_acc,
				[
					{
						field: 'log_dt',
						title: 'Дата',
						class: 'align-top',
						width: 100,
						sortable: false,
						template: function(row) {
							return row.log_dt_line + '<br>' + row.log_time_line;
						}
					},
					{
						field: 'acc_line',
						title: 'Аккаунт',
						class: 'align-top',
						sortable: false,
					},
					{
						field: 'log_type_line',
						title: 'Действие',
						class: 'align-top',
						sortable: false,
						width: 200,
					}, 
					{
						field: 'logs_data',
						title: 'IP / Девайс / User-Agent',
						class: 'align-top',
						sortable: false,
						template: function(row){
							return '<span style="font-size:10px;">' + 
										row.log_ip + ' / ' + row.log_device + '<br>' + row.log_ua + 
									'</span>';
						}
					},
				],
				{},
				[	
					{
						id: '#kt_datatable_log_type',
						field: 'log_type'
					}
				]
		);


jQuery(document).ready(function() {
	KTDatatable.init();

	// default search
	//let s = $('#kt_datatable_search_query').val();
	//if (s!='') $('#kt_datatable_search_query').trigger($.Event("keyup", { keyCode: 13 }));

	$('[name=account]').autocomplete({
		source: ajaxurl+'=accSearch',
      	minLength: 0,
      	select: function( event, ui ) {
      		selectAcc(ui.item.id);
      	}
	});

});

function selectAcc(acc) {
	document.location.href = '/'+adminurl+'/?page=logs&account='+acc;
}