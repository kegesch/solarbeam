<!DOCTYPE html>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	
		<link href="https://fonts.googleapis.com/css?family=Leckerli+One|Roboto" rel="stylesheet">
		<link rel="stylesheet" href="style.css" />
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		
		<script type="text/javascript" src="ajax.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script type="text/javascript" src="Chart.min.js"></script>
		<script type="text/javascript" src="charts.js"></script>
		<title>SOLAR#Beam</title>
	</head>
	<body>
		<script>
			function checkMomentaneLeistung() {
				getJSONFromUrl('api.php?q=c', function(obj) {
					document.getElementById("bezug").innerHTML=obj[1].leistung;
					document.getElementById("lieferung").innerHTML=obj[0].leistung;					
				});					
			}
		
			setInterval(checkMomentaneLeistung, 5000);
			checkMomentaneLeistung();
		</script>
		<div id="logo">
			Solar Beam
		</div>
		<div id="content">
			<div class="momentaneLeistung">
				<b>Bezug:</b> <span id="bezug"></span>W 
				<b>Lieferung:</b> <span id="lieferung"></span>W
			</div>
			<br/>
			<div style="border-bottom: 1px solid #ccc; height: 2px;"></div>
			<h3>Jahresübersicht</h3>
			<canvas id="yearlyChart" width="400" height="150"> </canvas>
			<div style="border-bottom: 1px solid #ccc; height: 2px;"></div>
			<h3>Monatsübersicht</h3>
			<p style="margin-top: 15px;">
				<?php
					
				$con = mysqli_connect('localhost', 'root', '', 'stromzaehler');
				$res = mysqli_query($con, "SELECT DISTINCT(YEAR(`time`)) as year FROM leistung WHERE DATE_FORMAT(`time`, '%m-%d') = '01-01' OR DATE_FORMAT(`time`, '%m-%d') = '12-31' ORDER BY `time` ASC;");
				while($row = mysqli_fetch_array($res)) {
					echo '<button class="btn btn-default" type="submit" onclick="getJSONFromUrl(\'api.php?q=year&y='.$row['year'].'\', updateYears);">'.$row['year'].'</button>';
				}
				mysqli_close($con);
				?>
				<button class="btn btn-primary" type="submit" onclick="updateYearsChartAll()">Alle</button>
			</p>
			
			<canvas id="yearsChart" width="400" height="150"> </canvas>
			<div style="border-bottom: 1px solid #ccc; height: 2px; margin: 10px 0px;"></div>
			<h3>Letzten 7 Tage</h3>
			<canvas id="lastWeekChart" width="400" height="150"> </canvas>
		</div>
	
	</body>
</html>