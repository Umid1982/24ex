<form id="feedback">
	<p>ФОРМА ОБРАТНОЙ СВЯЗИ</p>
	<p>Email<br><input type="email" name="feedback_email" value="<?=@$var_feedback_email?>" /></p>
	<p>Заголовок<br><input type="text" name="feedback_title" value="" /></p>
	<p>Текст<br><textarea name="feedback_text" cols="100" rows="10"></textarea></p>
	<p><button type="button" onClick="sendFeedback()">Отправить</button></p>
</form>