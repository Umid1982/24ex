'use strict';
// Class definition

var KTDatatable = function() {
	// Private functions

	// demo initializer
	var printTable = function() {

		var datatable = $('#kt_datatable').KTDatatable({
			// datasource definition
			data: {
				type: 'remote',
				source: {
					read: {
						url: ajaxurl + '=getLangs',
					},
				},
				pageSize: 20, // display 20 records per page
				serverPaging: false,
				serverFiltering: true,
				serverSorting: false,
				saveState: false,
			},

			// layout definition
			layout: {
				scroll: false,
				footer: false,
			},


			translate: {
				records: {
					processing: 'Загрузка...',
					noRecords: 'Записей не найдено',
				},
				toolbar: {
					pagination: {
						items: {
							default: {
								first: 'Первая',
								prev: 'Пред.',
								next: 'След.',
								last: 'Последняя',
								more: 'Больше',
								input: 'Страница',
								select: 'На страницу',
								all: 'все',
							},
							info: 'Показаны {{start}} - {{end}} из {{total}}',
						},
					},
				},
			},

			// column sorting
			sortable: false,

			pagination: true,

			// columns definition
			columns: 
			[
				{
					field: 'key',
					title: 'Ключ',
					class: 'align-middle'
				},
				{
					field: 'val',
					title: 'Перевод',
				}
			],
		});

		$('#kt_datatable_search_lang').on('change', function() {
			datatable.search($(this).val().toLowerCase(), 'lang');
		});

		$('#kt_datatable_search_lang').selectpicker();
		
	};


	return {
		// Public functions
		init: function() {
			printTable();
		},
	};
}();

jQuery(document).ready(function() {
	KTDatatable.init();
});



function exportLang() {

	let lang = $('#kt_datatable_search_lang').val();
	let url = nowpage+'&export='+lang;
	window.location.href = url;

}

function importLang() {

	let lang = $('#kt_datatable_search_lang').val();
	let modal_id = '#importLangModal';

	$(modal_id+' [name=lang]').val(lang);
	$(modal_id).modal('show');

}

function goImportLang() {

	if (!confirm('Вы уверены? Сделайте бэкап (экспорт) перед импортом!')) return false;

	var modal_id = '#importLangModal';

	let import_file = $(modal_id+' [name=import_file]')[0].files[0];
	let lang = $(modal_id+' [name=lang]').val();
	let method = $(modal_id+' [name=method]').val();

	var formData = new FormData();
	formData.append('import_file', import_file);
	formData.append('lang', lang);
	formData.append('method', method);

	$.ajax({
	       url : ajaxurl+'=importLang',
	       type : 'POST',
	       data : formData,
	       processData: false,  // tell jQuery not to process the data
	       contentType: false,  // tell jQuery not to set contentType
	       success : function(data) {
	           
	       		if (data.result) {

	       			showAlert('Успешно импортировано');
	       			$('#kt_datatable').KTDatatable().reload();
	       			$(modal_id).modal('hide');

	       		} else {

	       			showAlert(data.info);

	       		}

	       $(modal_id+' [name=import_file]').val('');
	       $(modal_id+' [name=method]').val('add');

	       }
	});


}