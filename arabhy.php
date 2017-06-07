<!DOCTYPE html>
<html>
	<head>
		<title>ARABHY</title>
		<style>
			table, th, td {
			    border: 1px solid black;
			    border-collapse: collapse;
			}
			th, td {
			    padding: 5px;
			}
			th {
			    text-align: left;
			}
		</style>
		
		<?php
			include 'util.php';
			$pastWeather    = getPastWeather($_POST["city"], $_POST["country"], 5      );			
			$currentWeather = getWeather    ($_POST["city"], $_POST["country"], "today");
			$postWeather    = getPostWeather($_POST["city"], $_POST["country"], 5      );
		?>
	</head>
	
	<body>

		<h1>ARABHY : ARAchide Bilan HYdrique</h1>
		
		<p>Précipitations en mm pour la ville de <?php echo $_POST["city"]; ?>, <?php echo $_POST["country"]; ?> ...</p>
		
		<table style="width:100%">
			<tr>
				<th>Date</th>
				<?php 
					foreach($pastWeather as $w){
						echo '<td>' . $w['Date'] . '</td>';
					}
					echo '<td>' . $currentWeather['Date'] . '</td>';
					foreach($postWeather as $w){
						echo '<td>' . $w['Date'] . '</td>';
					}
				?>
			</tr>
			<tr>
				<th>Précipitation</th>
				<?php 
					foreach($pastWeather as $w){
						echo '<td>' . $w['Precip'] . ' mm </td>';
					}
					echo '<td>' . $currentWeather['Precip'] . ' mm </td>';
					foreach($postWeather as $w){
						echo '<td>' . $w['Precip'] . ' mm </td>';
					}
				?>
			</tr>
		</table><br />
		
		<p>Détermination de la pluie de semis pour la ville de <?php echo $_POST["city"]; ?>, <?php echo $_POST["country"]; ?> ...</p>

	</body>
</html>

