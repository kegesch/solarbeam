<h3>Jahresübersicht</h3>
<div id="yearlytable"></div>
<script>makeYearlyTable();</script>
<div style="border-bottom: 1px solid #ccc; height: 2px;"></div>
<h3>Jahresansicht</h3>
<?php
  $fgc = file_get_contents("http://".$_SERVER['SERVER_NAME'].dirname($_SERVER['PHP_SELF'])."/api.php?q=yl");
  $json = json_decode($fgc);
  foreach ($json->{'series'} as $year) {
    echo '<button class="btn btn-default" type="submit" onclick="getJSONFromUrl(\'api.php?q=yearall&y='.$year.'\', makeYearTable);">'.$year.'</button>';
  }
?>
<div id="yeartable"></div>
