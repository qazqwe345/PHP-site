<?php

require "vendor/autoload.php";

$servername = "localhost";
$username = "harry";
$password = "qqq111";
$dbs = "shop";

//$DAO = new DatabaseAccessObject($servername, $username, $password, $dbs);
$DAO = Database::get();


$table = "hero";
$data_array['hero_name'] = "Van";
$data_array['hero_hp'] = 199;
$data_array['hero_mp'] = 69;
$DAO->insert($table, $data_array);
$hero_id = $DAO->getLastId();

$result_set = $DAO->execute("SELECT * FROM hero");
foreach ($result_set as $key=>$value){
	foreach($value as $root=>$name){
		echo "$root=>$name"."</br>";
	}
}

//$DAO->delete($table, "hero_name", "Van");

$data_array['hero_name'] = 'Van ATM';
$key_column = "id";
$id = $DAO->getLastId();
echo "id:".$id;
$DAO->update($table, $data_array, $key_column, $id);
echo $DAO->getLastSql();

echo "Connected successfully";
?>
