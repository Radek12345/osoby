<?php

// for final version
error_reporting(0);

if(!isset($_POST['imie']))
{
	header("Location: http://localhost/osoby");
	die();
}

try
{
	$nazwisko = ucfirst(mb_strtolower(str_replace(' ', '', $_POST['nazwisko']), "UTF-8"));
	
	if (!preg_match("/^[a-zA-ZóąęśłżźćńÓĄĘŚŁŻŹĆŃ]*$/", $nazwisko)) 
		throw new Exception("Nazwisko może składać się tylko z liter.");
	
	$imie = ucfirst(mb_strtolower(str_replace(' ', '', $_POST['imie']), "UTF-8"));
	
	if (!preg_match("/^[a-zA-ZóąęśłżźćńÓĄĘŚŁŻŹĆŃ]*$/", $imie)) 
		throw new Exception("Imię może składać się tylko z liter.");
	
	if($_POST['data_urodzenia'] <= date("Y-m-d", strtotime(date('Y-m-d').' -71 year')))
		throw new Exception("Osoba nie może mieć więcej niż 70 lat");
	
	if($_POST['data_urodzenia'] > date("Y-m-d", strtotime(date('Y-m-d').' -18 year')))
		throw new Exception("Osoba nie może mieć mniej niż 18 lat");
	
	require_once "db_conn.php";
	
	$stmt = $conn->prepare("UPDATE osoby SET nazwisko = :nazwisko, imie = :imie, id_miejscowosci = :id_miejscowosci, 
											  data_urodzenia = :data_urodzenia, id_oddzialy_firmy = :id_oddzialy_firmy WHERE id_osoby = :id_osoby"); 
											  
	$stmt->bindParam(':nazwisko', $nazwisko);
	$stmt->bindParam(':imie', $imie);
	$stmt->bindParam(':id_miejscowosci', $_POST['id_miejscowosci']);
	$stmt->bindParam(':data_urodzenia', $_POST['data_urodzenia']);
	$stmt->bindParam(':id_oddzialy_firmy', $_POST['id_oddzialy_firmy']);
	$stmt->bindParam(':id_osoby', $_POST['id_osoby']);
	$stmt->execute();
}
catch(PDOException $e)
{
	echo "Error: Wystąpił błąd podczas edycji, proszę spróbować jeszcze raz.";
	
	// show info for developer
	//echo "Error: ".$e;
}
catch(Exception $e)
{
	echo "Error: ".$e->getMessage();
}
?>