<?php
if (isset($_POST['submit'])){
				$gump = new GUMP();
				$_POST = $gump->sanitize($_POST);

				$validation_rules_array = array(
					'username'	=> 'required|alpha_numeric|max_len,20|min_len,8',
					'email'		=> 'required|valid_email',
					'password'	=> 'required|max_len,20|min_len,8',
					'passwordConfirm' => 'required'
				);
				$gump->validation_rules($validation_rules_array);
				$filter_rules_array = array(
					'username' => 'trim|sanitize_string',
					'email'    => 'trim|sanitize_email',
					'password' => 'trim',
					'passwordConfirm' => 'trim'
				);
				$gump->filter_rules($filter_rules_array);
				
				$validated_data = $gump->run($_POST);

				if ($validated_data === false){
					$error = $gump->get_readable_errors(false);
				}else{
					foreach($validation_rules_array as $key => $val){
						${$key} = $_POST[$key];
					}
					$userVelidator = new UserVelidator();
					$userVelidator->isPasswordMatch($password, $passwordConfirm);
					$userVelidator->isUsernameDuplicate($username);
					$userVelidator->isEmailDuplicate($email);
					$error = $userVelidator->getErrorArray();
				}
				if(count($error) == 0){
					$passwordObject = new Password();
					$hashedpassword = $passwordObject->password_hash($password, PASSWORD_BCRYPT);
					$activation = md5(uniqid(rand(),true));

					try{
						$data_array = array(
							'username' => $username,
							'password' => $password,
							'email' => $email,
							'active' => $activation
						);
						Database::get()->insert("members", $data_array);
						//header('Location: '.'register');
						$id = Database::get()->getLastId();
						
						if (isset($id) AND is_numeric($id)){
							$subject = "Registration Confirmation";
							$body = "<p>Thank you for registering at demo site.</p><p>To activate your account, please click on this link: <a href='192.168.137.173/activate/$id/$activation'>192.168.137.173/activate/$id/$activation</a></p><Regards Site Admin</p>>";
							$mail = new Mail(Config::MAIL_USER_NAME, Config::MAIL_USER_PASSWORD);
							$mail->setFrom(Config::MAIL_FROM, Config::MAIL_FROM_NAME);
							$mail->addAddress($email);
							$mail->subject($subject);
							$mail->body($body);
							if ($mail->send()){
								$msg->success('Registration successful, please check your email to activate your account.');
							}else{
								$msg->error('Sorry, unable to send Email.');
							}
							header('Location: '.'register');
							exit;
						}else{
							$error[] = 'Registration Error Occur on Database.';
						}
					
					}catch(PDOException $e){
							$error[] = $e->getMessage();
						}
				}
				if (isset($error) AND count($error) > 0){
					foreach( $error as $e ){
						$msg->error($e);
					}
					header('Location: ' . $_SERVER['HTTP_REFERER']);
					exit;
				}
			}else{
				header('Location: home');
				exit;
			}
?>
			
			
