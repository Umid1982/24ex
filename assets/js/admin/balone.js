$(document).ready(function(){

    var balIcon = new KTImageInput('bal_icon_block');

    $('#delBalBtn').click(function(){

        if (!confirm('Точно удалить?')) return false;
        if (prompt('Введите `delete` чтобы подтвердить удаление')!='delete') return false;

        var bal_id = parseInt($('[name=bal_id]').val());
        if (bal_id==0) return false;

        $.post(ajaxurl+'=delBal', 'bal_id='+bal_id, function(data){

            if (data.result) {

                showAlert('Баланс удален');
                document.location.href = '/' + adminurl + '/?page=bals';

            } else {

                showAlert(data.info);

            }

        },'jSON');

    });

    $('#saveBalBtn').click(function(){

        var bal_id = parseInt($('[name=bal_id]').val());

        var formData = new FormData($('#balone_form')[0]);

        $.ajax({
            url: ajaxurl+'=saveBal',
            type: 'POST',
            dataType: 'jSON',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {

                if (data.result) {

                    //showAlert('Баланс сохранен');
                    document.location.href = '/' + adminurl + '/?page=balone&bal_id=' + data.bal_id + '&rand=' + Math.random();

                } else {

                    showAlert(data.info);

                }                   
            }
        });

    });


    $('[name="bal_payin_list[]"]').select2({
       placeholder: "Выберите доступные балансы",
      });

    $('[name="bal_payout_list[]"]').select2({
       placeholder: "Выберите доступные балансы",
      });


});