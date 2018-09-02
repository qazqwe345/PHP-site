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
			
			$this->link = mysqli_connect($this->mysql_address, $this->mysql_username, $this->mysql_password);
			
			if (mysqli_connect_errno()){
				$this->error_message = "Failed to connect to MySQL: " . mysqli_connect_error();
				echo $this->error_message;
				return false;
			}
			echo "connect succeeded</br>";
			mysqli_query($this->link, "SET NAMES utf8");
			mysqli_query($this->link, "SET CHARCTER_SET_database = utf8");
			mysqli_query($this->link, "SET CHARCTER_SET_CLIENT = utf8");
			mysqli_query($this->link, "SET CHARACTER_SET_RESULTS = utf8");
			
			if (!(bool)mysqli_query($this->link, "USE ".$this->mysql_database))$this->error_message = 'Database '.$this->mysql_database.' does not exists!'; echo $this->error_message;
		}
		
		public function __destruct(){
			mysqli_close($this->link);
		}
		public function execute($sql = null){
			if ($sql===null) return false;
			$this->last_sql = str_ireplace("DROP","",$sql);
			$result_set = array();
			$result = mysqli_query($this->link, $this->last_sql);

			if ($result){
				for ($xx = 0; $xx < @mysqli_num_rows($result); $xx++){
					$result_set[$xx] = mysqli_fetch_assoc($result);
				}
				return $result_set;
			}else{
				echo "Error, no results";
				return;
			}
		}
		public function query($table=null, $condition="1", $order_by="1", $fields="*", $limit=""){
			$sql = "SELECT $field FROM $table WHERE $condition ORDER BY $order_by $limit";
			return $this->execute($sql);
		}
		public function insert($table=null, $data_array=array()){
			if ($table==null || count($data_array)==0){ echo "Parameters not complete"; return false;}
			$tmp_col = array();
			$tmp_dat = array();
			
			foreach ($data_array as $key => $value){
				$value = mysqli_real_escape_string($this->link, $value);
				$tmp_col[] = $key;
				$tmp_dat[] = "'$value'";
			} 
			$columns = join(",", $tmp_col);
			$data = join(",", $tmp_dat);

			$this->last_sql = "INSERT INTO ". $table."(".$columns. ") VALUES (".$data.")";
			echo $this->last_sql;
			mysqli_query($this->link, $this->last_sql);
			
			$this->last_id = mysqli_insert_id($this->link);
			return $this->last_id;
		}
		
		public function update($table = null, $data_array = null, $key_column = null, $id = null){
			if ($table==null || $id==null || $key_column==null || count($data_array)==0){ echo "invalid"; return false;}
			
			$id = mysqli_real_escape_string($this->link, $id);
			
			$setting_list = "";
			for ($xx = 0; $xx <count($data_array); $xx++ ){
				list($key, $value) = each($data_array);
				$value = mysqli_real_escape_string($this->link, $value);
				$setting_list .= $key . "=" . "\"" . $value . "\"";
				if ($xx != count($data_array) - 1)
					$setting_list .= ",";
			}
			$this->last_sql = "UPDATE " . $table . " SET " . $setting_list . " WHERE " . $key_column . " = " . "\"" . $id . "\"";
			$result = mysqli_query($this->link, $this->last_sql);

			if ($result){
				return $result;
			}else{
				echo "update failed.";
				return;
			}
		}
		public function delete($table = null, $key_column = null, $id = null){
			if ($table==null || $id==null || $key_column==null){
				echo "parameter not complete";
				return false;
			}
			return $this->execute("DELETE FROM $table WHERE ". $key_column ." = "."\"" . $id ."\"");
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
