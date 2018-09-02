<?php
		

	if (isset($_POST['submit']) AND isset($_POST['email'])){
		$email = $_POST['email'];
		$postVelidator = new PostVelidator();
		$userVelidator = new UserVelidator();
		$userAction = new UserAction();
		$log = new Log();

		if($postVelidator->isValidEmail($email)){
			if($userVelidator->isEmailDuplicate($email)){
				try{
					$resetToken = $userAction->getResetToken($email);
					$userAction->sendResetEmail($resetToken, $email);
					$userAction->redir2login();
				}catch(PDOException $e){
					$error[] = $e->getMessage();
					$log->error(__FILE__, json_encode($error));
				}
			}else{
				$log->warning(__FILE__, 'WRONG EMAIL: '.$email);
				sleep(rand(1,2));
				$userAction->redir2login();
				exit;
			}
		}else{
			header('Location: '.$_SERVER['HTTP_REFERER']);
		}
	}else{
		header('Location: home');
		exit;
	}
			
?>
