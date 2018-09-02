<?php

	class UserVelidator{
		private $error;

		public function isReady2Active($id, $active){
			$result = Database::get()->execute('SELECT username FROM members WHERE id = :id and active = :active', array(':id' => $id, ':active' => $active));
			if (isset($result[0]['username']) and !empty($result[0]['username'])){
				return true;
			}else{
				$this->error[] = 'Username provided is already in use.';
				return false;
			}
		}

		public static function isLogin($username){
			if ($username != ''){
				return true;
			}else{
				return false;
			}
		}

		public function loginVerification($username, $password){
			$result = Database::get()->execute('SELECT * FROM members WHERE active = "Yes" AND username = :username', array(':username' => $username));
			if(isset($result[0]['id']) and !empty($result[0]['id'])){
			
				$passwordObject = new Password();
				if ($password==$result[0]['password']){
					return true;
				}
			}
			$this->error[] = 'Wrong username or password or your account has not been activated.';
			return false;
		}

		public function getErrorArray(){
			return $this->error;
		}

		public function isPasswordMatch($password, $passwordConfirm){
			if ($password != $passwordConfirm){
				$this->error[] = 'Passwords do not match.';
				return false;
			}
			return true;
		}

		public function isUsernameDuplicate($username){
			$result = Database::get()->execute('SELECT * FROM members WHERE username = :username', array(':username' => $username));
			if (isset($result[0]['username']) and !empty($result[0]['username'])){
				$this->error[] = 'Username already exists.';
				return false;
			}
			return true;
		}
		public function isEmailDuplicate($email){
			$result = Database::get()->execute('SELECT * FROM members WHERE email = :email', array(':email' => $email));
			if (isset($result[0]['email']) and !empty($result[0]['email'])){
				$this->error[] = 'Email already be used.';
				return true;
			}
			return false;
		}

	}
		

?>
