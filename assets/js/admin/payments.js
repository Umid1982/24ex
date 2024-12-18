'use strict';
// Class definition

var KTDatatable;
initKTDatatable(
				'getPayments',
				[
				{
					field: 'delails',
					title: '',
					width: 30,
					class: 'align-middle'
				},
				{
					field: 'pay_id',
					title: 'ID',
					width: 30,
					class: 'align-top'
				}, 
				{
					field: 'user_id',
					title: 'Пользователь',
					class: 'align-top',
					template: function(row){
						let u_line = (!row.user_id) ? 'Обмен на сайте' : '<a href="/'+adminurl+'/?page=users&search='+row.user_email+'">'+row.user_email+' ('+row.user_id+')</a>';
						return u_line;
					}
				},  
				{
					field: 'pay_type',
					title: 'Тип',
					class: 'align-top',
					template: function(row){
						return row.pay_type_line
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
						let ret = '<span class="pay-status pay-status-'+row.pay_status+'">' + row.pay_status_line + '</span>';
						if (row.expired) ret += '<i style="color:gray">Время истекло</i>';
						return ret;
					}
				}
			],

			{
				title: 'Редактировать платеж',
				content: function(e) {

					var pay_id = $(e.currentTarget).parents('tr').find('td[data-field=pay_id]').attr('aria-label');
					$('[pay_details="'+pay_id+'"]').remove();

					$.post(ajaxurl+'=getPaymentDetails', 'pay_id='+pay_id, function(data){

						if (data.result)
							{
							let pd = data.pay_data;

							let details_block = '\
							\
							<div pay_details="'+pd.pay_id+'" class="row details-block">\
								<div class="col-md-3">\
									<p><b>КОММ. ПОЛЬЗОВАТЕЛЯ</b></p>\
									<p class="comm-block">'+pd.pay_comm_user+'</p>\
								</div>\
								<div class="col-md-3">\
									<p><b>КОММ. АДМИНА</b></p>\
									<textarea name="pay_comm_admin" class="comm-block">'+pd.pay_comm_admin+'</textarea>\
								</div>\
								<div class="col-md-3">\
									<p><b>ДАННЫЕ ПЛАТЕЖА</b></p>\
									<p class="comm-block">'+pd.pay_ps_data+'</p>\
								</div>\
								<div class="col-md-3">\
									<p><b>НОВЫЙ СТАТУС</b></p>';

							if (data.ava_statuses.length>0) {

								details_block += '<p><select name="pay_status" class="alt_select">';
								details_block += 	'<option value="">Не менять статус</option>';

								for (let i in data.ava_statuses) {

									let now = data.ava_statuses[i];
									details_block += '<option value="'+now.id+'">'+now.title+'</option>';

								}

								details_block += '</select></p>';
								

							} else {

								details_block += '<p>В текущем статусе нельзя изменить статус заявки</p>';

							}

							details_block += '\
									<button type="button" onClick="savePayDetails('+pd.pay_id+')" class="btn btn-primary">Сохранить</button>\
								</div>\
							</div>\
							';


							$(e.detailCell).append(details_block);
							}

					},'jSON');

					
				}
			},

			[	
				{
					id: '#kt_datatable_search_user',
					field: 'user_id'
				},
				{
					id: '#kt_datatable_search_status',
					field: 'status'
				},
				{
					id: '#kt_datatable_search_type',
					field: 'type'
				},
			]
		);


jQuery(document).ready(function() {
	KTDatatable.init();

});



function savePayDetails(pay_id) {

	var status = $('[pay_details="'+pay_id+'"] [name=pay_status]').val();

	if (status!='')
		{
		if (!confirm('Вы уверены что хотите сменить статус? Многие статусы нельзя будет сменить и произойдут изменения в балансе!')) return;
		}

	var adm_comm = $('[pay_details="'+pay_id+'"] [name=pay_comm_admin]').val();

	$.post(ajaxurl+'=savePayDetails', 'pay_id='+encodeURIComponent(pay_id)+
									'&status='+encodeURIComponent(status)+
									'&adm_comm='+encodeURIComponent(adm_comm),function(data){

										if (data.result) {

											$('#kt_datatable').KTDatatable().reload();

										} else {

											showAlert(data.info);
										}

									},'jSON');

}