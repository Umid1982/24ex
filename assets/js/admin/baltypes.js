// BALTYPES ===
function editBalType(id) {

	var modal_id = '#editBalTypeModal';

	if (id==0)
		{
		$(modal_id+' [name=bal_type_title]').val('Новый тип баланса');
		$(modal_id+' [name=bal_type_id]').val(0);

		$('#deleteBalTypeBtn').hide();

		$(modal_id).modal('show');
		}
	else
		{
		$.post( ajaxurl+'=getBalTypeData', 'id='+id, function(data){

			if (data.result)
				{
				$(modal_id+' [name=bal_type_title]').val(data.bal_type_title);
				$(modal_id+' [name=bal_type_id]').val(data.bal_type_id);

				$('#deleteBalTypeBtn').show();

				$(modal_id).modal('show');
				}
			else
				{
				showAlert('Тип баланса не найден');
				}


			},'jSON');
		}

}

function deleteBalType() {

	if (!confirm('Точно удалить?')) return false;
	if (prompt('Введите `delete` чтобы подтвердить удаление')!='delete') return false;

	$.post( ajaxurl+'=deleteBalType', 'id='+$('#editBalTypeForm [name=bal_type_id]').val(), function(data){

		if (data.result) {
			document.location.reload();
		} else {
			showAlert(data.info);
			}

		});
}

function saveBalType() {

	$.post( ajaxurl+'=saveBalTypeData', $('#editBalTypeForm').serialize(), function(data){

		if (data.result) {

				$('#editBalTypeModal').modal('hide');

				if (!data.is_new)
					{
					let sel = '#balTypesTable tr[bal_type_id='+data.bal_type_id+']';
					$(sel).find('td:eq(1)').html(data.bal_type_title);
					}
				else
					{
					document.location.reload();
					}

			} else {

				showAlert(data.info);
			} 

		},'jSON');

}