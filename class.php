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
			$this->mysql_address = $mysql_address;
			$this->mysql_username = $mysql_username;
			$this->mysql_password = $mysql_password;
			$this->mysql_database = $mysql_database;
			
			$this->link
		}
	}
