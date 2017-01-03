<script type="text/javascript" src="../charts.js"></script>
<h3>Letzten 7 Tage</h3>
<canvas id="lastWeekChart"> </canvas>
<div style="border-bottom: 1px solid #ccc; height: 2px;"></div>
<h3>Monatsübersicht</h3>
<p style="margin-top: 15px;">
  <?php
  $res = mysqli_query($con, "SELECT DISTINCT(YEAR(`time`)) as year FROM leistung WHERE DATE_FORMAT(`time`, '%m-%d') = '01-01' OR DATE_FORMAT(`time`, '%m-%d') = '12-31' ORDER BY `time` ASC;");
  while($row = mysqli_fetch_array($res)) {
    echo '<button class="btn btn-default" type="submit" onclick="getJSONFromUrl(\'api.php?q=year&y='.$row['year'].'\', updateYears);">'.$row['year'].'</button>';
  }
  mysqli_close($con);
  ?>
  <button class="btn btn-default" type="submit" onclick="updateYearsChartAll()">Alle</button>
</p>
<canvas id="yearsChart"> </canvas>
<div style="border-bottom: 1px solid #ccc; height: 2px; margin: 10px 0px;"></div>
<h3>Jahresübersicht</h3>
<canvas id="yearlyChart"> </canvas>

