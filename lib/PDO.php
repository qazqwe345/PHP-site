<?php
	class DatabaseAccessObject{
		private $mysql_address = "";
		private $mysql_username = "";
		private $mysql_password;
		private $mysql_database = "";
		private $link;
		private $last_sql = "";
		private $last_id = 0;
		private $last_num_rows = 0;
		private $error_message = "";
		
		public function __construct($mysql_address, $mysql_username, $mysql_password, $mysql_database){
			$this->mysql_address  = $mysql_address;
			$this->mysql_username = $mysql_username;
			$this->mysql_password = $mysql_password;
			$this->mysql_database = $mysql_database;
			
			try{
				$db = new PDO("mysql:host=".$this->mysql_address.";charset=utf8mb4;dbname=".$this->mysql_database, $this->mysql_username, $this->mysql_password);
				$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				$db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
				$this->link  = $db;
			}catch(PDOException $e){
				echo '<p class="bg-danger">'.$e->getMessage().'</p>';
				exit;
			}
		}
		
		public function __destruct(){
			$this->link = null;
		}
		public function execute($sql = null, $data_array=array()){
			try {
				if ($data_array){
					$stmt = $this->link->prepare($sql);
					$stmt->execute($data_array);
					return $stmt->fetchAll();
				}else{
					$stmt = $this->link->prepare($sql);
					$stmt->execute();
					return $stmt->fetchAll();
				}
			}catch (PDOException $e){
				$this->error_message = '<p class="bg-danger"'.$e->getMessage().'</p>';
				echo $this->error_message;
			}
		}
		
			public function query($table=null, $condition="1", $data_array = array(), $order_by="1", $fields="*", $limit=""){
			$this->last_sql = "SELECT {$fields} FROM {$table} WHERE {$condition} ORDER BY {$order_by} {$limit}";
			try{
				$stmt = $this->link->prepare($this->last_sql);
				$stmt->execute($data_array);
				return $stmt->fetchAll();
			} catch(PDOException $e){
				$this->error_message = '<p class="bg-danger">'.$e->getMessage().'</p>';
				echo $this->error_message;
			}

		}
		public function insert($table=null, $data_array=array()){
			if ($table==null || count($data_array)==0){ echo "Parameters not complete"; return false;}
			$tmp_col = array();
			$tmp_dat = array();
			
			foreach ($data_array as $key => $value){
				$tmp_col[] = $key;
				$tmp_dat[] = ":$key";
				$prepare_array[":".$key] = $value;
			} 
			$columns = join(",", $tmp_col);
			$data = join(",", $tmp_dat);

			$this->last_sql = "INSERT INTO ". $table."(".$columns. ") VALUES (".$data.")";
			echo $this->last_sql;
			$stmt = $this->link->prepare($this->last_sql);
			print_r($prepare_array);
			$stmt->execute($prepare_array);
			
			$this->last_id = $this->link->lastInsertId();
			return $this->last_id;
		}
		
		public function update($table = null, $data_array = null, $key_column = null, $id = null){
			if ($table==null || $id==null || $key_column==null || count($data_array)==0){ echo "invalid"; return false;}
			
			
			$setting_list = "";
			for ($xx = 0; $xx <count($data_array); $xx++ ){
				list($key, $value) = each($data_array);
				$setting_list .= $key . "=" . ":" . $key ;
				if ($xx != count($data_array) - 1)
					$setting_list .= ",";
			}
			$this->last_sql = "UPDATE " . $table . " SET " . $setting_list . " WHERE " . $key_column . " = " . ":" . $key_column;
			echo $this->last_sql;
			foreach ($data_array as $key => $value){
				$PDO_array[':'.$key] = $value;
			}
			$PDO_array[':'.$key_column] = $id;
			$stmt = $this->link->prepare($this->last_sql);
			$stmt->execute($PDO_array);

		}
		public function delete($table = null, $key_column = null, $id = null){
			if ($table==null || $id==null || $key_column==null){
				echo "parameter not complete";
				return false;
			}
			$this->last_sql = "DELETE FROM $table WHERE ".$key_column." = :".$key_column;
			$stmt = $this->link->prepare($this->last_sql);
			$stmt->execute(array(':'.$key_column => $id));
		}
		public function getLastSql(){
			return $this->last_sql;
		}
		public function setLastSql($last_sql){
			$this->last_sql = $last_sql;
		}
		public function getLastId(){
			if ($this->last_id)
				return $this->last_id;
			else
				echo "NO last id";
		}
		public function getErrorMessage(){
			return $this->error_message;
		}
	}


?>
