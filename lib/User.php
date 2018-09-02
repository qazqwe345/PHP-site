<?php

class UserAction{
	function getResetToken($email){
		$data_array['resetComplete'] = 'No';
		$data_array['resetToken'] = md5(rand().time());
		Database::get()->update('members', $data_array, 'email', $email);
		$resetToken = $data_array['resetToken'];
		return $resetToken;
	}

	function sendResetEmail($resetToken, $email){
		$to = $email;
		$subject = "Password Reset";
		$body = "<p>Someone requested that tho password be reset.</p><p>If this was a mistake, just ignore this email nad nothing will happen.</p><p>To reset your password, visit the following address:<a href='http://192.168.137.234/reset/$resetToken'>reset/$resetToken</a></p>";

		$mail = new Mail(Config::MAIL_USER_NAME, Config::MAIL_USER_PASSWORD);
		$mail->setFrom(Config::MAIL_FROM, Config::MAIL_FROM_NAME);
		$mail->addAddress($to);
		$mail->subject($subject);
		$mail->body($body);
		$mail->send();
	
	}
	function redir2login(){
		$msg = new \Plasticbrain\FlashMessages\FlashMessages();
		$msg->success("Please check your inbox for a reset link.");
		header('Location: login');
		exit;
	}
}
