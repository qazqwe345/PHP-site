<?php
	require 'PDO.php';
	class Database{
		private static $instance;
		private function __construct(){
			
		}
		private static function getInstance(){
			if (!isset(self::$instance)){
				self::$instance = new DatabaseAccessObject(
					MySQL::ADDRESS,
					MySQL::USERNAME,
					MySQL::PASSWORD,
					MySQL::DATABASE
				);
			}
		}

		public static function get(){
			self::getInstance();
			if (isset(self::$instance)){
				return self::$instance;
			}else{
				return NULL;
			}
		}

		public static function unlinkDAO(){
			if (isset(self::$instance)){
				self::$instance = null; // execute destructor automatically
			}
		}
	}

