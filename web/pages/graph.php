<script type="text/javascript" src="charts.js"></script>
<h3>Letzten 7 Tage</h3>
<canvas id="lastWeekChart"> </canvas>
<div style="border-bottom: 1px solid #ccc; height: 2px;"></div>
<h3>Monatsübersicht</h3>
<p style="margin-top: 15px;">
  <?php
    $fgc = file_get_contents("http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF'])."/api.php?q=yl");
    $json = json_decode($fgc);
    foreach ($json->{'series'} as $year) {
      echo '<button class="btn btn-default" type="submit" onclick="getJSONFromUrl(\'api.php?q=year&y='.$year.'\', updateYears);">'.$year.'</button>';
    } 
  ?>
  <button class="btn btn-default" type="submit" onclick="updateYearsChartAll()">Alle</button>
</p>
<canvas id="yearsChart"> </canvas>
<div style="border-bottom: 1px solid #ccc; height: 2px; margin: 10px 0px;"></div>
<h3>Jahresübersicht</h3>
<canvas id="yearlyChart"> </canvas>

