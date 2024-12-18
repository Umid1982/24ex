'use strict';
// Class definition

var KTDatatable;
initKTDatatable(
				'getPaystat',
				[
					{
						field: 'ps_id',
						title: 'ID',
						width: 30,
						class: 'align-top',
					},  
					{
						field: 'user_line',
						title: 'Пользователь',
						class: 'align-top',
						sortable: false,
						template: function(row){
							return '<a href="/'+adminurl+'/?page=users&search='+row.user_email+'">'+row.user_email+' ('+row.user_id+')</a>';
						}
					},
					{
						field: 'ps_value',
						title: 'Сумма',
						class: 'align-top',
						template: function(row){
							let cl = (row.ps_type==0) ? 'text-success' : 'text-danger';
							let plim = (row.ps_type==0) ? '+' : '-';
							return row.bal_name + ' <span class="'+cl+'">'+plim+row.ps_value+'</span>';
						}
					}, 
					{
						field: 'ps_reason',
						title: 'Причина',
						class: 'align-top',
						template: function(row){
							return row.reason_line;
						}
					},
					{
						field: 'ps_param',
						title: 'ID',
						width: 50,
						class: 'align-top'
					},
					{
						field: 'ps_dt',
						title: 'Дата',
						class: 'align-top',
						template: function(row){
							return row.ps_dt_line;
						}
					}
				],
				{},
				[
					{
						id: '#kt_datatable_search_user',
						field: 'user_id',
					}
				]
			);

jQuery(document).ready(function() {
	KTDatatable.init();

	// default search
	//let s = $('#kt_datatable_search_query').val();
	//if (s!='') $('#kt_datatable_search_query').trigger($.Event("keyup", { keyCode: 13 }));
});