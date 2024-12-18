'use strict';
// Class definition

var now_user_data = {};

var rps_deps = [];
var rps_invs = [];

var KTDatatable;
initKTDatatable(
				'getUsers',
				[
					{
						field: 'user_id',
						title: '',
						width: 30,
						textAlign: 'center',
					}, 
					{
						field: 'show_user_id',
						title: 'ID',
						sortable: false,
						width: 30,
						textAlign: 'center',
						template: function(row) {
							return row.user_id;
						}
					}, 
					{
						field: 'user_email',
						title: 'Email',
						sortable: 'asc',
						width: 200,
					}, 
					{
						field: 'user_bal_num',
						title: 'Номер кошелька',
						sortable: 'asc',
						width: 200,
						template: function(row) {
							return '<span style="color:green">'+row.user_bal_num+'</span>';
						}
					},
					{
						field: 'user_lname',
						title: 'Имя / TG',
						template: function(row) {
							return row.user_lname + ' ' + row.user_fname + '<br>' + row.user_tg;
						}
					},
					{
						field: 'user_last_action',
						title: 'Последняя активность',
						template: function(row) {

							let ret = '';
							if (row.user_online) {
								ret += '<span class="label label-success label-dot mr-2"></span>\
										<span class="text-success">онлайн</span>';
							} else {
								ret += '<span class="label label-danger label-dot mr-2"></span>\
										<span class="text-danger"">'+row.user_last_action+' назад</span>';
							}

							ret += '<br>IP: '+row.user_last_ip+' <a href="javascript:lockIp(\''+row.user_last_ip+'\')">\
										<b class="flaticon-lock text-danger"></b></a>';
							return ret;
						}
					}, {
						field: 'actions',
						title: '',
						width: 30,
						sortable: false,
						autoHide: false,
						overflow: 'visible',
						template: function(row){
							return '\
		                        	<a href="'+adminurl+'/?page=logs&account=user_'+row.user_id+'" class="btn btn-sm btn-clean btn-icon mr-2" title="Логи">\
		                            <span class="svg-icon svg-icon-md">\
		                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
									    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
									        <rect x="0" y="0" width="24" height="24"/>\
									        <path d="M10,4 L21,4 C21.5522847,4 22,4.44771525 22,5 L22,7 C22,7.55228475 21.5522847,8 21,8 L10,8 C9.44771525,8 9,7.55228475 9,7 L9,5 C9,4.44771525 9.44771525,4 10,4 Z M10,10 L21,10 C21.5522847,10 22,10.4477153 22,11 L22,13 C22,13.5522847 21.5522847,14 21,14 L10,14 C9.44771525,14 9,13.5522847 9,13 L9,11 C9,10.4477153 9.44771525,10 10,10 Z M10,16 L21,16 C21.5522847,16 22,16.4477153 22,17 L22,19 C22,19.5522847 21.5522847,20 21,20 L10,20 C9.44771525,20 9,19.5522847 9,19 L9,17 C9,16.4477153 9.44771525,16 10,16 Z" fill="#000000"/>\
									        <rect fill="#000000" opacity="0.3" x="2" y="4" width="5" height="16" rx="1"/>\
									    </g>\
									</svg><!--end::Svg Icon--></span>\
		                        	</a>';
						}
					},
				],
				{
					title: 'Показать балансы',
					content: function(e) {

						openUserBlock(e.data,e.detailCell);
					}
				}
			);

function openUserBlock(data,detailCell) {

	let block = '\
				<div user_block="'+data.user_id+'" class="row mt-0 mt-lg-8">\
				\
					<div class="col-xl-6">\
						<form user_id="'+data.user_id+'">\
						<input type="hidden" name="user_id" value="'+data.user_id+'" />\
						<div class="card">\
							<div class="card-header h-auto">\
								<h3>Данные пользователя</h3>\
							</div>\
							<div class="card-body h-auto">\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">ID:</span>\
									<span class="text-muted text-hover-primary">'+data.user_id+'</span>\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">ФИО:</span>\
									<span class="text-muted text-hover-primary">'+data.user_lname+' '+data.user_fname+' '+data.user_sname+'</span>\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">Дата рождения:</span>\
									<span class="text-muted text-hover-primary">'+data.user_birth_date+'</span>\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">Город рождения:</span>\
									<span class="text-muted text-hover-primary">'+data.user_birth_city+'</span>\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">Адрес:</span>\
									<span class="text-muted text-hover-primary">'+data.user_address+'</span>\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">Индекс:</span>\
									<span class="text-muted text-hover-primary">'+data.user_postcode+'</span>\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">Зарегистрирован:</span>\
									<span class="text-muted text-hover-primary">'+data.user_dt_reg+'</span>\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2" id="paycode_block">\
									'+(data.user_payhash=='' ? 
										'Платежный пароль не установлен' : 
										'Платежный пароль установлен <button type="button" class="btn btn-danger btn-sm" onClick="resetPaycode('+data.user_id+')">сбросить</button>')+'\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2" id="2fa_block">\
									'+(data.user_2fa==0 ? 
										'Двухфакторная авторизация выключена' : 
										'Двухфакторная авторизация включена <button type="button" class="btn btn-danger btn-sm" onClick="reset2FA('+data.user_id+')">сбросить</button>')+'\
								</div>\
								<hr>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">Блокировка пользователя:</span>\
									<span class="text-muted text-hover-primary">\
										<div class="col-3">\
											<span class="switch  switch-outline switch-icon switch-danger switch-sm">\
												<label>\
												<input type="checkbox" '+(data.user_lock==1 ? 'checked="checked"' : '')+' name="user_lock" />\
												<span></span>\
												</label>\
											</span>\
										</div>\
									</span>\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">Время блокировки до:</span>\
									<input type="date" name="user_lock_date" class="form-control" style="width:150px !important" value="'+data.user_lock_date+'" />\
								</div>\
								<div class="mb-2 text-center">\
									Не заполняйтя время для бессрочной блокировки\
								</div>\
								<hr>\
								<h3>Заблокировать действия по API</h3>';

	let rules = {
				'myBals' 		: 'Мои балансы',
				'balsCanAdd' 	: 'Какие балансы можно добавить',
				'balAdd' 		: 'Добавить баланс',
				'transfer' 		: 'Переводы другим участникам',
				'payIn' 		: 'Пополнение',
				'payOut' 		: 'Мои балансы',
				'newVoucher' 	: 'Создание ваучера',
				'change' 		: 'Обмен',
				'payInfo' 		: 'Информация о платеже',
				};
	for (let rule_name in rules)
		{
		let rule_title = rules[rule_name];
		block += '\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">'+rule_title+':</span>\
									<span class="text-muted text-hover-primary">\
										<div class="col-3">\
											<span class="switch switch-outline switch-danger switch-sm">\
												<label>\
												<input type="checkbox" '+(data.user_api_rules[rule_name]!==undefined ? 'checked="checked"' : '')+' name="user_api_rules['+rule_name+']" />\
												<span></span>\
												</label>\
											</span>\
										</div>\
									</span>\
								</div>\
				';
		}


	block +=	'				<hr>\
								<h3>Персональные реферальные планы</h3>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">Пополнение:</span>\
									<span class="text-muted text-hover-primary">\
										<div class="col-12">\
											<select name="deposit_plan" class="alt_select">\
												<option value="">- не выбран -</option>\
											</select>\
										</div>\
									</span>\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">Инвест пополнение:</span>\
									<span class="text-muted text-hover-primary">\
										<div class="col-12">\
											<select name="invest_plan_depo" class="alt_select">\
												<option value="">- не выбран -</option>\
											</select>\
										</div>\
									</span>\
								</div>\
								<div class="d-flex align-items-center justify-content-between mb-2">\
									<span class="font-weight-bold mr-2">Инвест %:</span>\
									<span class="text-muted text-hover-primary">\
										<div class="col-12">\
											<select name="invest_plan_proc" class="alt_select">\
												<option value="">- не выбран -</option>\
											</select>\
										</div>\
									</span>\
								</div>\
							</div>\
							<div class="card-footer h-auto">\
								<button onClick="saveUser('+data.user_id+')" type="button" class="btn btn-primary">Сохранить изменеия</button>   \
							</div>\
						</div>\
						</form>\
					</div>\
				\
					<div class="col-xl-6">\
						<div class="card">\
							<div class="card-header h-auto">\
								<h3>Балансы пользователя</h3>\
							</div>\
							<div class="card-body h-auto">\
								<div user_bals="'+data.user_id+'" class="datatable datatable-bordered datatable-head-custom"></div>\
							</div>\
						</div>\
					</div>\
				\
				</div>\
				';

	$(detailCell).append(block).ready(function(){

			$('[user_bals="'+data.user_id+'"]').KTDatatable({
				data: {
					type: 'remote',
					source: {
						read: {
							url: ajaxurl + '=getUbs',
							params: {
								// custom query params
								query: {
									user_id: data.user_id,
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
						noRecords: 'Кошельков не найдено',
					},
				},

				sortable: false,
				pagination: true,

				// columns definition
				columns: [
					{
						field: 'bal_title',
						title: 'Название',
						template: function(row){
							return row.bal_title;
							}
					}, {
						field: 'ub_value',
						title: 'Баланс',
						template: function(row) {
							return '<!--code>' + row.ub_num + '</code><br-->' + row.ub_value + ' ' + row.bal_name;
						}
					}, {
						field: 'ub_lock',
						title: 'Блок',
						width: 50,
						template: function(row){
							return '<span class="switch  switch-outline switch-icon switch-danger switch-sm">\
												<label>\
												<input type="checkbox" '+((row.ub_lock==1) ? 'checked="checked"' : '')+' onChange="lockUB(\''+row.ub_id+'\',this);" />\
												<span></span>\
												</label>\
											</span>';
						}
					}
				]
			});


	if (rps_deps.length > 0) {

		for (let i in rps_deps) {

			let sel = data.user_deposit_plan == rps_deps[i].rp_id ? 'selected' : '';
			let block = '<option value="'+rps_deps[i].rp_id+'" '+sel+' >'+rps_deps[i].rp_title+' ('+rps_deps[i].prcs_line+')</option>';
			$('[user_block="'+data.user_id+'"] [name=deposit_plan]').append(block);

			};

		};


	if (rps_invs.length > 0) {

		for (let i in rps_invs) {

			let sel1 = data.user_invest_plan_depo == rps_invs[i].rp_id ? 'selected' : '';
			let block1 = '<option value="'+rps_invs[i].rp_id+'" '+sel1+' >'+rps_invs[i].rp_title+' ('+rps_invs[i].prcs_line+')</option>';
			$('[user_block="'+data.user_id+'"] [name=invest_plan_depo]').append(block1);

			let sel2 = data.user_invest_plan_proc == rps_invs[i].rp_id ? 'selected' : '';
			let block2 = '<option value="'+rps_invs[i].rp_id+'" '+sel2+' >'+rps_invs[i].rp_title+' ('+rps_invs[i].prcs_line+')</option>';
			$('[user_block="'+data.user_id+'"] [name=invest_plan_proc]').append(block2);			

			};

		};


	});

}

jQuery(document).ready(function() {
	KTDatatable.init();

	// default search
	let s = $('#kt_datatable_search_query').val();
	if (s!='') $('#kt_datatable_search_query').trigger($.Event("keyup", { keyCode: 13 }));
});





function saveUser(user_id) {

	$.post(ajaxurl+'=saveUser', $('form[user_id='+user_id+']').serialize(), function(data){

		if (data.result) {
			showAlert('Сохранено');
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

function resetPaycode(user_id) {

	if (!confirm('Вы уверены?')) return false;
	if (prompt('Введите `reset` для подтверждения')!='reset') return false;

	$.post(ajaxurl+'=resetPaycode', 'user_id='+encodeURIComponent(user_id), function(data){

		if (data.result) {
			showAlert('Платежный пароль сброшен');
			$('#paycode_block').html('Платежный пароль не установлен');
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

function reset2FA(user_id) {

	if (!confirm('Вы уверены?')) return false;
	if (prompt('Введите `reset` для подтверждения')!='reset') return false;

	$.post(ajaxurl+'=reset2FA', 'user_id='+encodeURIComponent(user_id), function(data){

		if (data.result) {
			showAlert('Двухфакторная авторизация сброшена');
			$('#2fa_block').html('Двухфакторная авторизация выключена');
		} else {
			showAlert(data.info);
		}

	},'jSON');

}

function lockUB(ub_id,now) {

	let lock = $(now).prop('checked') ? 1 : 0;

	$.post(ajaxurl+'=lockUB', 'ub_id='+encodeURIComponent(ub_id)+'&lock='+lock, function(data){

			if (data.result) {
				if (data.lock==1) {
					showAlert('Баланс пользователя заблокирован');
				} else {
					showAlert('Баланс пользователя разблокирован');
				}
			} else {
				showAlert(data.info);
			}

		},'jSON');	

}

$(document).ready(function(){

	$.post(ajaxurl+'=getAllRefPlans', '', function(data){

		if (data.result) {

			for (let i in data.refplans) {

				if (data.refplans[i].rp_type==0) 		rps_deps.push(data.refplans[i]);
				else if (data.refplans[i].rp_type==1) 	rps_invs.push(data.refplans[i]);

				}

			}
		

		},'jSON');

});
