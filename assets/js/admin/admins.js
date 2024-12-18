'use strict';
// Class definition

var KTDatatable;
initKTDatatable(
				'getAdmins',
				[
					{
						field: 'admin_id',
						title: 'ID',
						width: 50,
						textAlign: 'center',
					}, 
					{
						field: 'admin_login',
						title: 'Логин',
						sortable: 'asc',
					}, 
					{
						field: 'admin_type',
						title: 'Статус',
					}, 
					{
						field: 'admin_last_action',
						title: 'Последние действия',
					},
					{
						field: 'admin_lock',
						title: 'LOCK',
						template: function(row){
							return (row.admin_lock==1) ? '<i class="flaticon2-cross icon-nm" style="color:red"></i>' : '';
						}
					}, {
						field: 'actions',
						title: '',
						sortable: false,
						overflow: 'visible',
						autoHide: false,
						template: function(row){
							return '\
									<a href="javascript:editAdmin('+row.admin_id+');" class="btn btn-sm btn-clean btn-icon mr-2" title="Редактировать">\
		                            <span class="svg-icon svg-icon-md">\
		                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">\
		                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">\
		                                        <rect x="0" y="0" width="24" height="24"/>\
		                                        <path d="M8,17.9148182 L8,5.96685884 C8,5.56391781 8.16211443,5.17792052 8.44982609,4.89581508 L10.965708,2.42895648 C11.5426798,1.86322723 12.4640974,1.85620921 13.0496196,2.41308426 L15.5337377,4.77566479 C15.8314604,5.0588212 16,5.45170806 16,5.86258077 L16,17.9148182 C16,18.7432453 15.3284271,19.4148182 14.5,19.4148182 L9.5,19.4148182 C8.67157288,19.4148182 8,18.7432453 8,17.9148182 Z" fill="#000000" fill-rule="nonzero"\ transform="translate(12.000000, 10.707409) rotate(-135.000000) translate(-12.000000, -10.707409) "/>\
		                                        <rect fill="#000000" opacity="0.3" x="5" y="20" width="15" height="2" rx="1"/>\
		                                    </g>\
		                                </svg>\
		                            </span>\
		                        	</a>\
		                        	<a href="'+adminurl+'/?page=logs&account=admin_'+row.admin_id+'" class="btn btn-sm btn-clean btn-icon mr-2" title="Логи">\
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
				]
			);


// ADMINS FUNCTIONS ====
function editAdmin(admin_id) {

	var modal_id = '#editAdminModal';

	if (admin_id==0)
		{
		$(modal_id+' [name=admin_login]').val('NewAdmin');
		$(modal_id+' [name=admin_type]').val(1);
		$(modal_id+' [name=admin_id]').val(0);

		$('#deleteAdminBtn').hide();

		$(modal_id).modal('show');
		}
	else
		{
		$.post( ajaxurl+'=getAdminData', 'admin_id='+admin_id, function(data){

			if (data.result)
				{
				$(modal_id+' [name=admin_login]').val(data.admin_login);
				$(modal_id+' [name=admin_type]').val(data.admin_type);
				$(modal_id+' [name=admin_id]').val(data.admin_id);
				$(modal_id+' [name=admin_new_pass]').val('');

				$(modal_id+' [name=admin_lock').prop('checked',data.admin_lock==1 ? true : false);

				$('#deleteAdminBtn').show();

				$(modal_id).modal('show');
				}
			else
				{
				showAlert('Админ не найден');
				}


			},'jSON');
		}

}

function deleteAdmin() {

	if (!confirm('Точно удалить?')) return false;
	if (prompt('Введите `delete` чтобы подтвердить удаление')!='delete') return false;

	$.post( ajaxurl+'=deleteAdmin', 'admin_id='+$('#editAdminForm [name=admin_id]').val(), function(data){

		if (data.result) {
			$('#editAdminModal').modal('hide');
			$('#kt_datatable').KTDatatable().reload();
		} else {
			showAlert(data.info);
			}

		});
}

function saveAdmin() {

	$.post( ajaxurl+'=saveAdminData', $('#editAdminForm').serialize(), function(data){

		if (data.result) {

				$('#editAdminModal').modal('hide');
				$('#kt_datatable').KTDatatable().reload();

			} else {

				showAlert(data.info);
			} 

		},'jSON');

}




jQuery(document).ready(function() {
	KTDatatable.init();

	// default search
	let s = $('#kt_datatable_search_query').val();
	if (s!='') $('#kt_datatable_search_query').trigger($.Event("keyup", { keyCode: 13 }));
});