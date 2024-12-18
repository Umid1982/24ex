function image_upload_handler (blobInfo, success, failure, progress) {
  var xhr, formData;

  xhr = new XMLHttpRequest();
  xhr.withCredentials = false;
  xhr.open('POST', ajaxurl + '=tinyUploadImage');

  xhr.upload.onprogress = function (e) {
    progress(e.loaded / e.total * 100);
  };

  xhr.onload = function() {
    var json;

    if (xhr.status === 403) {
      failure('HTTP Error: ' + xhr.status, { remove: true });
      return;
    }

    if (xhr.status < 200 || xhr.status >= 300) {
      failure('HTTP Error: ' + xhr.status);
      return;
    }

    json = JSON.parse(xhr.responseText);

    if (!json || typeof json.location != 'string') {
      failure('Invalid JSON: ' + xhr.responseText);
      return;
    }

    success(json.location);
  };

  xhr.onerror = function () {
    failure('Image upload failed due to a XHR Transport error. Code: ' + xhr.status);
  };

  formData = new FormData();
  formData.append('file', blobInfo.blob(), blobInfo.filename());

  xhr.send(formData);
};

$(document).ready(function(){

	$('[name=n_dt_pub]').datetimepicker({
		format: 'DD.MM.YYYY HH:mm',
		locale: 'ru'
	});

	tinymce.init({
	    selector: '#n_body',
	    language: 'ru',
	    plugins: [
				    'advlist autolink lists link image charmap print preview anchor',
				    'searchreplace visualblocks code fullscreen',
				    'insertdatetime media table paste imagetools wordcount'
				  ],
		images_upload_handler: image_upload_handler
	    });

	$('#saveNewsBtn').click(function(){

		let id = $('[name=n_id]').val();
		let title = $('[name=n_title]').val();
		let lang  = $('[name=n_lang]').val();
		let status  = $('[name=n_status]').val();
		let dt  = $('[name=n_dt_pub]').val();
		let body  = tinymce.get('n_body').getContent();

		$.post( ajaxurl+'=saveNews', 'id='+encodeURIComponent(id)+
										'&title='+encodeURIComponent(title)+
										'&lang='+encodeURIComponent(lang)+
										'&status='+encodeURIComponent(status)+
										'&dt='+encodeURIComponent(dt)+
										'&body='+encodeURIComponent(body), function(data){


			if (data.result) {
				document.location.href = '/' + adminurl + '/?page=newsone&n_id=' + data.n_id + '&rand=' + Math.random();
			} else {
				showAlert(data.info);
			}

		},'jSON');

	});

	$('#delNews').click(function(){

		let id = $('[name=n_id]').val();
		if (!confirm('Вы уверены?')) return false;

		$.post( ajaxurl+'=delNews', 'id='+encodeURIComponent(id), function(data){

			if (data.result) {
				document.location.href = '/' + adminurl + '/?page=news';
			}

		},'jSON');

	});

});