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
				
		$("#oddzial_firmy").prop("disabled", true).selectpicker('refresh');
		
		$(function () {
			$('#datetimepicker').datetimepicker({
				locale: 'pl',
				format: 'YYYY-MM-DD',
				viewDate: '<?php echo date("Y-m-d", strtotime(date("Y-m-d")." -18 year")); ?>'
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
					$("#oddzial_firmy").html("").append(options).prop("disabled", false).selectpicker('refresh')
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
				   url: "system/insert_person.php",
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
						alert("Wystąpił błąd podczas dodawania osoby, proszę spróbować jeszcze raz."); 
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
	<h2 class="main-header">Dodawanie osoby</h2>
	<form id="form_osoby">
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="nazwisko">Nazwisko:</label>
				<input type="text" class="form-control" id="nazwisko" name="nazwisko" placeholder="Proszę wpisać nazwisko" required>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="imie">Imię:</label>         
				<input type="text" class="form-control" id="imie" name="imie" placeholder="Proszę wpisać imię" required>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<label for="id_miejscowosci">Miejscowość:</label> 
				<select class="selectpicker form-control" id="id_miejscowosci" name="id_miejscowosci" title="Proszę wybrać miejscowość..." required>
					<?php 
					while($row = $stmt->fetch(PDO::FETCH_ASSOC))
					{
						echo "<option value='".$row['id_miejscowosci']."'>".$row['miejscowosc']."</option>";
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
                    <input id="datetimepicker" onkeydown="return false" type="text" class="form-control" name="data_urodzenia" 
					            placeholder="Proszę podać datę urodzenia" required />
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
						echo "<option value='".$row['id_firmy']."'>".$row['firma']."</option>";
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
