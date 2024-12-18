<?php

class FeedbackModel extends Model
	{

	function vars()
		{
		if ($this->isAuth()) $this->all_vars['feedback_email'] = $_SESSION['coincash_user_email'];
		else $this->all_vars['feedback_email'] = '';
		}

	function ajax()
		{
		if ($_GET['ajax']=='sendFeedback')
			{
			$email = trim(htmlspecialchars(@$_POST['feedback_email']));
			$title = trim(htmlspecialchars(@$_POST['feedback_title']));
			$text  = trim(htmlspecialchars(@$_POST['feedback_text']));

			// проверяем данные
			if ($email=='' || $title=='' || $text=='')
				{
				$this->ajax_return['info'] = '[L:FEEDBACK_EMPTY_FIELDS]';
				}
			else if (!preg_match('#^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,})$#i',$email))
				{
				$this->ajax_return['info'] = '[L:REG_WRONG_EMAIL]';
				}
			else
				{
				$ins = [
						'user_id' => $this->userId(),
						'sup_msg_email' => $email,
						'sup_msg_title' => $title,
						'sup_msg_text' => $text,
						'sup_msg_dt' => time(),
						];
				$this->db->uni_insert('sup_msgs',$ins);

				$this->ajax_return['result'] = true;
				$this->ajax_return['info'] = '[L:FEEDBACK_SEND_OK]';
				}
			}		
		}

	}