<?php
	$file_name =  iconv('utf-8','big5', $_FILES['file']['name']);
	if ($_FILES['file']['error']>0){
		echo "Uploaded Failed";
		echo "Error: " . $_FILES['file']['error'];
		
	}else if(file_exists("file/".$file_name)){
		echo "File exists";
	}else{
		move_uploaded_file($_FILES['file']['tmp_name'],'file/'.$_FILES['file']['name']);
		echo "Succeeded<br />";
		echo "directory:"."<a href='file/".$_FILES['file']['name']."'>".$_FILES['file']['name']."</a>";
		echo "<br />";
		echo "size:".$_FILES['file']['size']."bytes";
		echo "<br />";
		echo "File Type:".$_FILES['file']['type'];
	}
?>
