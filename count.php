<?php
	$file_name = "total_count.txt";
	$file = file($file_name);
	$open = fopen($file_name,"w+");
	
	fwrite($open,$file[0]+1);
	fclose($open);
	
	echo "total visitors:";
	echo $file[0]+1;
?>
