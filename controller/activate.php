<?php

$data_array = array();
			$data_array['id'] = $route->getParameter(2);
			$data_array['active'] = $route->getParameter(3);
			print_r($data_array);

			$gump = new GUMP();
			$data_array = $gump->sanitize($data_array);
			$validation_rules_array = array(
				'id' => 'required|integer',
				'active' => 'required|exact_len,32'
			);
			$gump->validation_rules($validation_rules_array);
			
			$filter_rules_array = array(
				'id' => 'trim|sanitize_string',
				'active' => 'trim',
			);
			$gump->filter_rules($filter_rules_array);
			$validated_data = $gump->run($data_array);
			print_r($validated_data);

			if ($validated_data == false){
				$error = $gump->get_readable_errors(false);
				$msg->error("Your account could not be activated!");
				header('Location: login');
				exit;
			}else{
				foreach($validation_rules_array as $key=>$val){
					${$key} = $data_array[$key];
				}
				$userVelidator = new UserVelidator();
				if ($userVelidator->isReady2Active($id, $active)){
					$update_array['active'] = "Yes";
					Database::get()->update("members", $update_array, "id", $data_array['id']);
					$msg->success("Your account is now active.");
					header('Location: ../../login');
					exit;
				}else{
					$msg->error("Your account could not be activated.");
					header('Location: ../../home');
					exit;
				}
			}

?>


