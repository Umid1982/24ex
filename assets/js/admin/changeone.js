$(document).ready(function(){

    $('#saveChBtn').click(function(){

        var bal_id = parseInt($('[name=bal_id]').val());

        var formData = new FormData($('#changeone_form')[0]);

        $.ajax({
            url: ajaxurl+'=saveCh',
            type: 'POST',
            dataType: 'jSON',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {

                if (data.result) {

                    showAlert('Сохранено');

                } else {

                    showAlert(data.info);

                }                   
            }
        });

    });

    $('#ch_change_plus').click(function(){
        changeChValue('plus');
        });

    $('#ch_change_minus').click(function(){
        changeChValue('minus');
        });

    $('[name="ch_in_list[]"]').select2({
       placeholder: "Выберите доступные направления обмена",
      });

    $('[name="ch_out_list[]"]').select2({
       placeholder: "Выберите доступные направления обмена",
      });


});

function changeChValue(type) {

    if (prompt('Вы уверены что хотите изменить остаток? Введите `'+type+'` для подтверждения')!=type) return false;

    let value = $('#ch_value_change').val();
    let bal_id = $('[name=bal_id]').val();
    
    $.post(ajaxurl+'=changeChValue', 'bal_id='+encodeURIComponent(bal_id)+
                                    '&type='+encodeURIComponent(type)+
                                    '&value='+encodeURIComponent(value), function(data){

        showAlert(data.info);

        if (data.result) {

            $('#now_ch_value').html(data.new_val);

        }

    },'jSON');


}