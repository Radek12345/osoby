<?php

// for final version
error_reporting(0);

try
{
	require_once "system/db_conn.php";
	
	$stmt = $conn->prepare("SELECT * FROM miejscowosci"); 
	$stmt->execute();
	
	$stmt2 = $conn->prepare("SELECT * FROM firmy"); 
	$stmt2->execute();
	
	$stmt3 = $conn->prepare("SELECT o.*, f.id_firmy FROM osoby AS o 
												INNER JOIN oddzialy_firmy AS f ON f.id_oddzialy_firmy = o.id_oddzialy_firmy 
												WHERE o.id_osoby = :id_osoby"); 
	
	$stmt3->bindParam(':id_osoby', $_GET['id_osoby']);
	$stmt3->execute();
	
	if($stmt3->rowCount() == 0)
	{
		header("Location: http://localhost/osoby");
		die();
	}
	else
	{
		$current_data = $stmt3->fetch(PDO::FETCH_ASSOC);
	}
	
	$stmt4 = $conn->prepare("SELECT id_firmy, id_oddzialy_firmy, oddzial_firmy FROM oddzialy_firmy WHERE id_firmy = :id_firmy");
	$stmt4->bindParam(':id_firmy', $current_data['id_firmy']);
	$stmt4->execute();
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
  <link rel="stylesheet" href="css/bootstrap-datetimepicker.min.css">
  <link rel="stylesheet" href="css/bootstrap-select.min.css">
  <script src="js/jquery.min.js"></script>
  <script src="js/moment.min.js"></script>
  <script src="js/moment-pl.js"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/bootstrap-datetimepicker.min.js"></script>
  <script src="js/bootstrap-select.min.js"></script>
   
  <script>
    $(document).ready(function(){
		
		$(".form-group").find("button").css("color", "#555");
			
		$(function () {
			$('#datetimepicker').datetimepicker({
				locale: 'pl',
				format: 'YYYY-MM-DD',
				viewDate: '<?php echo $current_data['data_urodzenia']; ?>'
			});
		});
		
		$("#firma").change(function() {
			
			var id_firmy = $('#firma').find(":selected").val();
			
			$.ajax({
				url : 'system/get_branches_for_company.php',
				data : 'id_firmy='+id_firmy,
				type: 'POST',
				success: function(data) {
					
					if(data.indexOf("Error:") != -1){
						alert(data);
						location.reload();
					}
					data = JSON.parse(data);
					var options;
					for (var i = 0; i < Object.keys(data.oddzialy_firmy).length; i++) {
						options += "<option value='"+data.oddzialy_firmy[i].id_oddzialy_firmy+"'>"+data.oddzialy_firmy[i].oddzial_firmy+"</option>";
					}
					$("#oddzial_firmy").html("").append(options).selectpicker('refresh');
				},
				error: function() 
				{ 
					alert("Wystąpił błąd, proszę spróbować jeszcze raz."); 
				} 
			});
		});
		
		$("#form_osoby").submit(function(e) {
			$.ajax({
				   type: "POST",
				   url: "system/edit_person.php",
				   data: $("#form_osoby").serialize(),
				   success: function(data)
				   {
					    if(data.indexOf("Error:") != -1)
							alert(data);
						else
							window.location.href = "home";
				   },
				   error: function() 
				   { 
						alert("Wystąpił błąd podczas edycji osoby, proszę spróbować jeszcze raz."); 
				   } 
			});
			e.preventDefault(); 
		});
	});
  </script>
</head>
<body>

<div class="container">
<?php	if(isset($db_error)) { echo "<p>".$db_error."</p>"; } else { ?>
	<h2 class="main-header">Edycja osoby</h2>
	<form id="form_osoby">
	<input type="hidden" name="id_osoby" value="<?php echo $_GET['id_osoby']; ?>">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="nazwisko">Nazwisko:</label>
				<input type="text" class="form-control" id="nazwisko" name="nazwisko" placeholder="Proszę wpisać nazwisko"
						   value="<?php echo $current_data['nazwisko']; ?>" required>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="imie">Imię:</label>         
				<input type="text" class="form-control" id="imie" name="imie" placeholder="Proszę wpisać imię"
						   value="<?php echo $current_data['imie']; ?>" required>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="id_miejscowosci">Miejscowość:</label> 
				<select class="selectpicker form-control option_color" id="id_miejscowosci" name="id_miejscowosci" 
				             title="Proszę wybrać miejscowość..." required>
					<?php 
					while($row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
						echo "<option value='".$row['id_miejscowosci']."'";
						
						if($row['id_miejscowosci'] == $current_data['id_miejscowosci'])
							echo " selected";
						
						echo ">".$row['miejscowosc']."</option>";
					}
					?>
				</select>
			</div>
		</div>
	</div>
    <div class="row">
        <div class="col-sm-6">
            <div class="form-group">
				<label for="datetimepicker">Data urodzenia:</label>
                <input onkeydown="return false" type="text" class="form-control" name="data_urodzenia" id="datetimepicker" 
						   placeholder="Proszę podać datę urodzenia" value="<?php echo $current_data['data_urodzenia']; ?>" required />
            </div>
        </div>
    </div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="firma">Firma:</label> 
				<select class="selectpicker form-control" id="firma" name="firma" title="Proszę wybrać firmę..." required>
					<?php 
					while($row = $stmt2->fetch(PDO::FETCH_ASSOC))
					{
						echo "<option value='".$row['id_firmy']."'";
						
						if($row['id_firmy'] == $current_data['id_firmy'])
							echo " selected";
						
						echo ">".$row['firma']."</option>";
					}
					?>
				</select>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="oddzial_firmy">Oddział firmy:</label> 
					<select class="selectpicker form-control" id="oddzial_firmy" name="id_oddzialy_firmy" title="Proszę wybrać oddział firmy..." required>
					<?php 
						while($row = $stmt4->fetch(PDO::FETCH_ASSOC)) 
						{ 
							echo "<option value='".$row['id_oddzialy_firmy']."'";
							
							if($row['id_oddzialy_firmy'] == $current_data['id_oddzialy_firmy'])
								echo " selected";
							
							echo ">".$row['oddzial_firmy']."</option>"; 
						} 
					?>
					</select>
			</div>
		</div>
	</div>
	<button type="submit" class="btn btn-success">Zapisz</button>
	<a href="home" class="btn btn-default">Anuluj</a>
	</form>
<?php } ?>
</div>
</body>
</html>

