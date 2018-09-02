<?php
if (isset($_POST['submit'])){
				$gump = new GUMP();
				$_POST = $gump->sanitize($_POST);
				$validation_rules_array = array(
					'username' => 'required|alpha_numeric|max_len,20|min_len,3',
					'password' => 'required|max_len,20,|min_len,3'
				);
				$gump->validation_rules($validation_rules_array);
				$filter_rules_array = array(
					'username' => 'trim|sanitize_string',
					'password' => 'trim',
				);
				$gump->filter_rules($filter_rules_array);
				$validated_data = $gump->run($_POST);

				if ($validated_data == false){
					$error = $gump->get_readable_errors(false);
				}else{
					foreach($validation_rules_array as $key => $val){
						${$key} = $_POST[$key];
					}
					$userVelidator = new UserVelidator();
					$userVelidator->loginVerification($username, $password);
					$error = $userVelidator->getErrorArray();
					if(count($error) == 0){
						$condition = "username = :username";
						$data_array = array(":username" => $username);
						$result = Database::get()->query("members", $condition, $data_array);
						$_SESSION['id'] = $result[0]['id'];
						$_SESSION['username'] = $username;
						header('Location: home');
						exit;
					}
				}
				if (isset($error) AND count($error) >0){
					foreach($error as $e){
						$msg->error($e);
					}
					header('Location: ' . $_SERVER['HTTP_REFERER']) ;
					exit;
				}
			}else{
					header('Location: '.'home');
					exit;
				}
?>
