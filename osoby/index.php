<?php

// for final version
error_reporting(0);

try
{
	require_once "system/db_conn.php";
	
	$stmt = $conn->prepare("SELECT o.id_osoby, o.nazwisko, o.imie, o.data_urodzenia, m.miejscowosc, f.firma, of.oddzial_firmy 
												FROM osoby AS o 
												LEFT JOIN miejscowosci AS m ON o.id_miejscowosci = m.id_miejscowosci 
												LEFT JOIN oddzialy_firmy AS of ON o.id_oddzialy_firmy = of.id_oddzialy_firmy
												LEFT JOIN firmy AS f ON of.id_firmy = f.id_firmy"); 
	
	$stmt->execute();
}
catch(PDOException $e)
{
	$db_error = "System jest chwilowo niedostępny. Prosimy spóbować za klika minut.";
	
	// show info for developer
	//echo $e;
}

$conn = NULL;

?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <title>Osoby</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="css/bootstrap.min.css">
  <link rel="stylesheet" href="css/style.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/bootstrap.min.js"></script>
  
  <script>
  
	$(document).ready(function(){
		$(document).on("click", ".btn-delete", function () {
			 var id_osoby = $(this).data('id');
			 $("#id_osoby").val(id_osoby);
		});
		
		$("#delete_form").submit(function(e) {
			$.ajax({
				   type: "POST",
				   url: "system/delete_person.php",
				   data: $("#delete_form").serialize(),
				   success: function(data)
				   {
						if(data.indexOf("Error:") != -1)
							alert(data);
						else
							location.reload(); 
				   },
				   error: function() 
				   { 
						alert("Wystąpił błąd podczas usuwania osoby, proszę spróbować jeszcze raz."); 
				   } 
			});
			e.preventDefault(); 
		});
	});
	
  </script>
</head>
<body>

<div class="container-fluid">
  <?php if(!isset($db_error)) { ?>
  <h2 class="main-header">Osoby</h2>          
  <a class="btn btn-default" href="dodawanie-osoby">Nowy</a>
  <?php if($stmt->rowCount() > 0) { ?>
  <table class="table table-bordered table_with_margin">
    <thead>
      <tr>
        <th>Nazwisko</th>
        <th>Imię</th>
        <th>Miejscowość</th>
		<th>Wiek</th>
		<th>Płeć</th>
		<th>Firma</th>
		<th>Oddział firmy</th>
		<th>Edycja</th>
		<th>Usuwanie</th>
      </tr>
    </thead>
    <tbody>
	<?php
	$today = new DateTime();
	while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
	?>
      <tr>
        <td><?php echo $row['nazwisko']; ?></td>
        <td><?php echo $row['imie']; ?></td>
        <td><?php echo $row['miejscowosc']; ?></td>
        <td><?php $date_of_birth = new DateTime($row['data_urodzenia']); $years = $date_of_birth->diff($today); echo $years->y; ?></td>
        <td><?php if(substr($row['imie'], -1) == 'a') echo "Kobieta"; else echo "Mężczyzna"; ?></td>
        <td><?php echo $row['firma']; ?></td>
        <td><?php echo $row['oddzial_firmy']; ?></td>
		<td><a class="btn btn-default btn-xs" href="edycja-osoby?id_osoby=<?php echo $row['id_osoby']; ?>">Edytuj</a></td>
		<td>
			<button type="button" class="btn btn-danger btn-xs btn-delete" data-toggle="modal" data-target="#modal_delete" 
						  data-id="<?php echo $row['id_osoby']; ?>">Usuń
			</button>
	    </td>
      </tr>
    <?php } ?> 
    </tbody>
  </table>
  <?php } else { ?>
	<p class="table_with_margin">W bazie nie istnieją żadne osoby. Kliknij przycisk nowy, aby dodać jakąś.</p>
  <?php } ?>
  <?php } else { 
	echo "<p>".$db_error."</p>";
  } ?>
 
<!-- Modal -->
	<div class="modal fade" id="modal_delete" role="dialog">
		<div class="modal-dialog">

		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">Usuwanie</h4>
			</div>
			<div class="modal-body">
			  <h4 class="text-center">Czy na pewno chcesz usunąć tę osobę?</h4>
			</div>
			<div class="modal-footer">
				<form id="delete_form">
					<input type="hidden" id="id_osoby" name="id_osoby">
					<button type="submit" class="btn btn-danger">Tak</button>
					<button type="button" class="btn btn-default" data-dismiss="modal">Nie</button>
				</form>
			</div>
		  </div>
		  
		</div>
	</div> 
  
</div>

</body>
</html>