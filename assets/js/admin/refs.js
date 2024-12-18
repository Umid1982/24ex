'use strict';
// Class definition

var KTDatatable;
initKTDatatable(
				'getRefPlans',
				[
					{
						field: 'rp_id',
						title: 'ID',
						width: 30,
						class: 'align-center',
					},  
					{
						field: 'rp_title',
						title: 'Название',
						class: 'align-top',
					},  
					{
						field: 'rp_prcs',
						title: 'Проценты',
						class: 'align-top',
						sortable: false,
						template: function(row){
							return row.prcs_line;
						}
					}, 
					{
						field: 'rp_type',
						title: 'Тип плана',
						class: 'align-top',
						template: function(row){
							return row.type_line;
						}
					}
				],
				{
				title: 'Редактировать план',
				content: function(e) {

					$('[plan_details="'+e.data.rp_id+'"]').remove();

					$.post(ajaxurl+'=getRefPlanDetails', 'rp_id='+e.data.rp_id, function(data){

						if (data.result)
							{
							let rp_data = data.rp_data;

							let details_block = '\
							\
							<div plan_details="'+rp_data.rp_id+'" class="details-block">\
								<form id="plan_details_form_'+rp_data.rp_id+'" class="row">\
									<input type="hidden" name="rp_id" value="'+rp_data.rp_id+'" />\
									<div class="col-lg-4">\
										<label>Название плана</label>\
										<input type="text" class="form-control" name="rp_title" value="'+rp_data.rp_title+'" />\
									</div>\
									<div class="col-lg-4">\
										<label>Тип плана</label>\
										<select name="rp_type" class="form-control">\
											<option value="">- выберите тип плана -</option>\
											<option value="0" '+(rp_data.rp_type==0 ? 'selected' : '')+' >Пополнение</option>\
											<option value="1" '+(rp_data.rp_type==1 ? 'selected' : '')+' >Инвестиции</option>\
										</select>\
									</div>\
									<div class="col-lg-4">\
										<div class="form-group">\
									    	<label>Уровни: <b id="levels_cc_'+rp_data.rp_id+'">'+rp_data.levels_cc+'</b></label>\
									    	<div></div>\
									    	<input type="range" class="custom-range" min="1" max="7" value="'+rp_data.levels_cc+
									    		'" onInput="planLevels('+rp_data.rp_id+')" name="levels" />\
										</div>\
									</div>\
									<div class="col-lg-12 ">\
										<label>Проценты по уровням</label>\
										<div class="form-inline">\
										';
									
								for (let i=1;i<=7;i++) {
									let prc = (typeof(rp_data.prcs[i])=='undefined') ? '' : rp_data.prcs[i];
									details_block += '<input class="form-control" style="width:14%" type="text" name="prcs['+i+']" value="'+prc+'" />';
									}

								details_block += '\
										</div>\
									</div>\
									<div class="col-lg-12">\
										<br>\
										<button type="button" onCLick="saveRefPlan('+rp_data.rp_id+')" class="btn btn-primary">Сохранить</button>\
										&nbsp;\
										<button type="button" onCLick="delRefPlan('+rp_data.rp_id+')" class="btn btn-danger">Удалить</button>\
									</div>\
								</form>\
							</div>\
							';

							$(e.detailCell).append(details_block).ready(function(){ planLevels(rp_data.rp_id); });
							}

					},'jSON');

					
				}
			},
				[
					{
						id: '#kt_datatable_search_type',
						field: 'type',
					}
				]
			);

jQuery(document).ready(function() {
	KTDatatable.init();
});

function newRefPlan() {

	$.post(ajaxurl+'=newRefPlan','',function(data){

		if (data.result) {

			$('#kt_datatable').KTDatatable().reload();

		};

	},'jSON');

}

function planLevels(id) {

	let val = parseInt($('[plan_details='+id+'] [name=levels]').val());
	$('#levels_cc_'+id).html(val);

	for(let i=1;i<=val;i++) 	{ $('[plan_details='+id+'] [name="prcs['+i+']"]').show(); }
	for(let i=val+1;i<=7;i++) 	{ $('[plan_details='+id+'] [name="prcs['+i+']"]').hide(); }

}

function saveRefPlan(id) {

	$.post(ajaxurl+'=saveRefPlan', $('#plan_details_form_'+id).serialize(), function(data){

		if (data.result) {

			$('#kt_datatable').KTDatatable().reload();

		} else {

			showAlert(data.info);

		}

	},'jSON');	

}

function delRefPlan(id) {

	if (!confirm('Точно удалить?')) return false;

	$.post(ajaxurl+'=delRefPlan', 'id='+encodeURIComponent(id), function(data){

		if (data.result) {

			$('#kt_datatable').KTDatatable().reload();

		} else {

			showAlert(data.info);

		}

	},'jSON');		

}