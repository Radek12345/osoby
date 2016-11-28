<?php

// for final version
error_reporting(0);

if(!isset($_POST['id_firmy']))
{
	header("Location: http://localhost/osoby");
	die();
}


try
{
	require_once "db_conn.php";
	
	$json = array();
	
	$stmt = $conn->prepare("SELECT id_oddzialy_firmy, oddzial_firmy FROM oddzialy_firmy WHERE id_firmy = :id_firmy");
	$stmt->bindParam(':id_firmy', $_POST['id_firmy']);
	$stmt->execute();
	
	$oddzialy_firmy = array();
	
	while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	{
		$oddzialy_firmy[] = array('id_oddzialy_firmy'=>$row['id_oddzialy_firmy'], 'oddzial_firmy'=>$row['oddzial_firmy']);
	}
	
	$json['oddzialy_firmy'] = $oddzialy_firmy;
	echo json_encode($json, JSON_UNESCAPED_UNICODE);
}
catch(PDOException $e)
{
	$db_error = "Error: Wystąpił błąd, prosimy spróbować jeszcze raz.";
	
	// show info for developer
	//echo $e;
}
?>