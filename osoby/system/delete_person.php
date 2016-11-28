<?php

// for final version
error_reporting(0);

if(!isset($_POST['id_osoby']))
{
	header("Location: http://localhost/osoby");
	die();
}

try
{
	require_once "db_conn.php";
	
	$stmt = $conn->prepare("DELETE FROM osoby WHERE id_osoby = :id_osoby"); 
	$stmt->bindParam(':id_osoby', $_POST['id_osoby']);
	$stmt->execute();
}
catch(PDOException $e)
{
	echo "Error: Wystapił błąd podczas usuwania osoby, proszę spróbować jeszcze raz.";
	
	// show info for developer
	//echo $e;
}
?>