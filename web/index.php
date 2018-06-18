<!DOCTYPE html>
<?php require_once "config.php" ?>
<html lang="de">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
	
		<link href="https://fonts.googleapis.com/css?family=Leckerli+One|Roboto" rel="stylesheet">
		<link rel="stylesheet" href="style.css" />
		<link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
		
		
		<link rel="apple-touch-icon" sizes="180x180" href="favicons/apple-touch-icon.png">
		<link rel="icon" type="image/png" href="favicons/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="favicons/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="favicons/manifest.json">
		<link rel="mask-icon" href="favicons/safari-pinned-tab.svg" color="#5bbad5">
		<link rel="shortcut icon" href="favicons/favicon.ico">
		<meta name="msapplication-config" content="favicons/browserconfig.xml">
		<meta name="theme-color" content="#ffffff">
		
		<script type="text/javascript" src="ajax.js"></script>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
		<script type="text/javascript" src="Chart.min.js"></script>
                <script type="text/javascript" src="tables.js"></script>
		<title>SOLAR#Beam</title>
	</head>
        <body>
                <?php
                  $con = mysqli_connect(MYSQL_HOST, MYSQL_LOGIN, MYSQL_PASS, 'stromzaehler');
                  //$res = mysqli_query("SELECT `time` FROM leistung_bezug WHERE ")             
                ?>   
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
                  <?php
                    $page = "graph";
                    if(array_key_exists("page", $_GET)) {
                      $page = $_GET['page'];
                    }
                    if(!file_exists("pages/".$page.".php")) {
                      $page = "404";
                    }
                  ?>
                  <ul class="nav nav-tabs">
                      <li role="presentation" class="<?php echo ($page == "graph" ? "active" : "") ?>"><a href="?page=graph">Graph</a></li>
                      <li role="presentation" class="<?php echo ($page == "table" ? "active" : "") ?>"><a href="?page=table">Tabelle</a></li>
                      <li role="presentation" class="<?php echo ($page == "counter" ? "active" : "") ?>"><a href="?page=counter">Zähler</a></li>
                  </ul>
                  <br/>
                  <div class="panel panel-default">
                    <div class="panel-heading">
                      <h3 class="panel-title">Momentane Leistung</h3>
                    </div>
                    <div class="panel-body">
                       <div class="momentaneLeistung">
		        <b>Bezug:</b> <span id="bezug"></span>W 
		        <b>Lieferung:</b> <span id="lieferung"></span>W
                      </div>
                    </div>
                  </div>
                  <?php
                    include "pages/".$page.".php";
                  ?>
			
		
		</div>
	
	</body>
</html>
