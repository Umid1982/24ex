var from_rate = 1;
var to_rate = 1;

var from_id = 0;
var to_id = 0;

$(document).ready(function(){

	$('#props').hide();

	if (Object.keys(change_json).length>0) for (let i in change_json) {

		let now = change_json[i];
		let block = '<a class="one_ch_from" bal_id="'+now.bal_id+'" onClick="showExchange('+now.bal_id+')">['+now.bal_name+'] '+now.bal_title+'</a>';

		$('#change_list_from').append(block);

	}

	// по хэшу
	var hash = window.location.hash.substr(1);
	var temp = hash.match(new RegExp('(.+?)\-(.+)'));
	
	if (temp!=null)
		{
		let from_auto = getBalId(temp[1]);
		let to_auto = getBalId(temp[2]);

		showExchange(from_auto);
		showProps(from_auto,to_auto);
		}
});

function showExchange(bal_id) {

	from_id = 0;
	to_id = 0;

	$('.one_ch_to').remove();
	$('.one_ch_from').removeClass('hover').filter('[bal_id='+bal_id+']').addClass('hover');

	$('#props').hide();

	let now = change_json[bal_id];

	var list_exch = [];
	if (now.ch_in_list!='') {
		list_exch = (now.ch_in_list).split(',');
		}

	for (let i in change_json) {

		if (i==bal_id) continue;

		let one = change_json[i];

		var can_exch = true; //false;
/*
		if (one.ch_in_list!='') {
			let temp = (one.ch_in_list).split(',');
			for (let j in temp) {
				if (temp[j]==bal_id) {
					can_exch = true;
					break;
					}
				}
			}
		else {
			can_exch = true;
			}
*/

		if (can_exch) {
			if (list_exch.length==0 || list_exch.includes(i)) can_exch = true;
			else can_exch = false;
			}

		if (can_exch) {

			let rate = now.bal_rate / one.bal_rate;

			let block = '<a class="one_ch_to" bal_id="'+one.bal_id+'" onClick="showProps('+bal_id+','+one.bal_id+')">['+one.bal_name+'] '+one.bal_title+' <br> '+rate+'</a>';
			$('#change_list_to').append(block);

			}

		}

	}

function showProps(from,to) {

	$('.one_ch_to').removeClass('hover').filter('[bal_id='+to+']').addClass('hover');

	let root = $('#props');

	from_id = from;
	from_rate = change_json[from].bal_rate;
	$(root).find('#from_name').html(change_json[from].bal_name);
	$(root).find('#from_min').html(change_json[from].ch_in_min);
	$(root).find('#from_max').html(change_json[from].ch_in_max);
	$(root).find('#from_val').val(0);
	$(root).find('#from_ub_value').html(change_json[from].ub_value + ' ' + change_json[from].bal_name);

	to_id = to;
	to_rate = change_json[to].bal_rate;
	$(root).find('#to_name').html(change_json[to].bal_name);
	$(root).find('#to_min').html(change_json[to].ch_in_min);
	$(root).find('#to_max').html(change_json[to].ch_in_max);
	$(root).find('#to_ch_value').html(change_json[to].ch_value);
	$(root).find('#to_val').val(0);
	$(root).find('#to_ub_value').html(change_json[to].ub_value + ' ' + change_json[to].bal_name);

	$(root).show();
}

function goChange() {

	let val = $('#props #from_val').val();
	let pp = $('#props #pay_pass').val();

	$.post(ajaxurl+'=goChangeIn', 'from='+encodeURIComponent(from_id)+
								'&to='+encodeURIComponent(to_id)+
								'&pp='+encodeURIComponent(pp)+
								'&val='+encodeURIComponent(val), function(data) {

								if (data.result) {

										document.location.href = "/" + nowlang + "/payment/changein?pay=" + data.pay_id;

									} else {

										showAlert(data.info);

									}

								});

}

function calcVals(type) {

	if (type=='from') {

		var from = parseFloat($('#from_val').val());
		var to = from_rate * from / to_rate;

		$('#to_val').val(to);

	} else {

		var to = parseFloat($('#to_val').val());
		var from = to_rate * to / from_rate;

		$('#from_val').val(from);

	}


	let from_com = from * change_json[from_id].ch_in_com / 100;
	$('#from_com').html(from_com);

}


// step 2
function cancelChange() {

	if (!confirm('Вы уверены?')) return false;

	let pay_id = $('#step2_form [name=pay_id]').val();

	$.post(ajaxurl+'=cancelChange','pay_id='+encodeURIComponent(pay_id),function(data){

		if (data.result) {

			document.location.href = "/" + nowlang + "/payment/changein";

		} else {

			showAlert(data.info);

		}

	},'jSON');

}


function confirmChange() {

	if (!confirm('Вы уверены?')) return false;

	let pay_id = $('#step2_form [name=pay_id]').val();

	$.post(ajaxurl+'=confirmChange','pay_id='+encodeURIComponent(pay_id),function(data){

		if (data.result) {

			document.location.reload();

		} else {

			showAlert(data.info);

		}

	},'jSON');

}

function getBalId(name) {

	if (Object.keys(change_json).length>0) {

		for (let i in change_json) {

			let now = change_json[i];
			if (now.bal_name==name) return i;

		}

	}

	return 0;

}