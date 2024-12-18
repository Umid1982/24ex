function initKTDatatable(param,columns,detail,adds_search) {

	adds_search = adds_search || [];
	detail = detail || {};

	KTDatatable = function() {
		// Private functions

		// demo initializer
		var printTable = function() {

			var datatable = $('#kt_datatable').KTDatatable({
				// datasource definition
				data: {
					type: 'remote',
					source: {
						read: {
							url: ajaxurl + '=' + param,
						},
					},
					pageSize: 20, // display 20 records per page
					serverPaging: true,
					serverFiltering: true,
					serverSorting: true,
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
				sortable: true,

				pagination: true,

				search: {
					input: $('#kt_datatable_search_query'),
					key: 'generalSearch',
				},

				// columns definition
				columns: columns,

				// detail
				detail: detail,
			});

			if (adds_search.length > 0) for (var i in adds_search) {

				let now = adds_search[i];

				$(now.id).on('change', function() {

					datatable.search($(this).val().toLowerCase(), now.field);

				});

				$(now.id).selectpicker();

			}

		};

		return {
			// Public functions
			init: function() {
				printTable();
			},
		};
	}();


}

function resetPaysys(id) {

	$.post( ajaxurl+'=resetPaysys', 'id='+encodeURIComponent(id), function(data){

		if (data.result) {

			document.location.reload();			

		} else {

			showAlert(data.info);

		}

	},'jSON');	

}

function copyToClipboard(text) {

	var $temp = $("<input>");
	$("body").append($temp);
	$temp.val(text).select();
	document.execCommand("copy");
	$temp.remove();

	showAlert('Скопировано в буфер');
	
}

function showAlert(text,type) {

	//alert('123');

	type = type || 'primary';

	let block = '\
<div class="alert alert-custom alert-'+type+'" role="alert" style="display:none">\
    <div class="alert-icon"><i class="flaticon-warning"></i></div>\
    <div class="alert-text">'+text+'</div>\
    <div class="alert-close">\
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">\
            <span aria-hidden="true"><i class="ki ki-close"></i></span>\
        </button>\
    </div>\
</div>\
';

$('#all-alerts').prepend(block).ready(function(){ $('.alert').fadeIn(); });

//setTimeout(function(){ $('.alert').fadeOut(); },5000);

}

// PROMPTS ===================================================

var promptValue = '';
var promptBtn = false;

function showPrompt(text, btn, val) {

	val = val || '';

	promptBtn = btn;

	if (promptValue=='') {
		$('#promptModalValue').val(val);
		$('#promptModalText').html(text);
		$('#promptModal').modal('show');
		}

	return promptValue;

}

function sendPrompt() {

	promptValue = $('#promptModalValue').val();
	$('#'+promptBtn).trigger('click');
	$('#promptModal').modal('hide');

	promptValue = '';
	promptBtn = false;

}

// PROMPTS //////////////////////////////////////////////////////


// CONFIRMS ===================================================

var confirmStatus = false;
var confirmBtn = false;

function showConfirm(text, btn) {

	confirmBtn = btn;

	if (confirmStatus==false) {
		$('#confirmModalText').html(text);
		$('#confirmModal').modal('show');
		}

	return confirmStatus;

}

function sendConfirm() {

	confirmStatus = true;
	$('#'+confirmBtn).trigger('click');
	$('#confirmModal').modal('hide');

	confirmStatus = false
	confirmBtn = false;

}

// CONFIRMS //////////////////////////////////////////////////////

$.fn.datepicker.dates['ru'] = {
    days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота"],
    daysShort: ["Вскр", "Пон", "Втр", "Срд", "Чтв", "Птн", "Сбт"],
    daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб"],
    months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
    monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
    today: "Сегодня",
    clear: "Очистить",
    format: "yyyy-mm-dd",
    titleFormat: "MM yyyy", /* Leverages same syntax as 'format' */
    weekStart: 1
};