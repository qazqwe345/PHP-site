<?php
		if (isset($_POST['submit'])){
			$gump = new GUMP();
			$_POST = $gump->sanitize($_POST);
			$validation_rules_array = array(
				'resetToken' => 'required',
				'password'   => 'required|max_len,20|min_len,3',
				'passwordConfirm' => 'required'
			);
			$gump->validation_rules($validation_rules_array);
			$filter_rules_array = array(
				'resetToken' => 'trim',
				'password'   => 'trim',
				'passwordConfirm' => 'trim'
			);
			$gump->filter_rules($filter_rules_array);

			$validated_data = $gump->run($_POST);

			if ($validated_data == false){
				$error = $gump->get_readable_errors(false);
				foreach($error as $e){
					$msg->error($e);
				}
				header("Location: ".$_SERVER['HTTP_REFERER']);
				exit;
			}else{
				foreach($validation_rules_array as $key => $val){
					${$key} = $validated_data[$key];

				}
				$table = 'members';
				$condition = 'resetToken = :resetToken';
				$order_by = '1';
				$fields = 'resetToken, resetComplete';
				$data_array[':resetToken'] = $resetToken;
				$result = Database::get()->query($table, $condition, $data_array, $order_by, $fields);
				print_r($result);
				
				if (!isset($result[0]['resetToken']) OR empty($result[0]['resetToken'])){
					$msg->error('reset:'.$resetToken);
					$msg->error('Invalid token provided, please use the link provided to reset email.');
					$msg->error($resetToken);
					//header('Location: login');
					exit;
				}else if (isset($result[0]['resetComplete']) AND $result[0]['resetComplete'] == 'Yes'){
					$msg->info('Your password has already been changed!');
					header('Location: login');
					exit;
				}
			}
			
		
	
				$userVelidator = new UserVelidator();
				$userVelidator->isPasswordMatch($password, $passwordConfirm);
				$error = $userVelidator->getErrorArray();
				
			if (count($error) == 0){
				$passwordObject = new Password();
				$hashedpassword = $passwordObject->password_hash($password, PASSWORD_BCRYPT);
				try{
					$data_array = array();
					$table = 'members';
					$data_array['password'] = $password;
					$data_array['resetComplete'] = 'Yes';
					$key = "resetToken";
					$id = $resetToken;
					Database::get()->update($table, $data_array, $key, $id);
						
					
					$msg->success('Password changed, you may now login in');
					header('Location: ../login?action=resetAccount');
					exit;
				}catch(PDOException $e){
					$error[] = $e->getMessage();
				}
			}
			if (isset($error) AND count($error) > 0){
				foreach($error as $e){
					$msg->error($e);
				}
				header('Location: '.$_SERVER['HTTP_REFERER']);
				exit;
			}
		}else{
			header('Location: home');
			exit;
		}

?>
