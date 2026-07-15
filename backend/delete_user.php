<?php 
	
	include 'conn.php';
	
	$sql = $link->prepare("DELETE  FROM users WHERE user_id=?"); 

	$sql->bind_param("i", $_GET["id"]); 
	$sql->execute();
	$sql->close(); 
	$link->close();
	header('location: view_user.php');	
	
?>