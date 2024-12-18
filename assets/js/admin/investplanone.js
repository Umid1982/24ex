$(document).ready(function(){

    $('#delPlan').click(function(){

        if (!confirm('Точно удалить?')) return false;
        if (prompt('Введите `delete` чтобы подтвердить удаление')!='delete') return false;

        var plan_id = parseInt($('[name=plan_id]').val());
        if (plan_id==0) return false;

        $.post(ajaxurl+'=delPlan', 'id='+plan_id, function(data){

            if (data.result) {

                showAlert('План удален');
                document.location.href = '/' + adminurl + '/?page=investplans';

            } else {

                showAlert(data.info);

            }

        },'jSON');

    });

    $('#savePlan').click(function(){

        var formData = new FormData($('#planone_form')[0]);

        $.ajax({
            url: ajaxurl+'=savePlan',
            type: 'POST',
            dataType: 'jSON',
            data: formData,
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            success: function (data) {

                if (data.result) {

                    document.location.href = '/' + adminurl + '/?page=investplanone&plan_id=' + data.plan_id + '&rand=' + Math.random();

                } else {

                    showAlert(data.info);

                }                   
            }
        });

    });

});