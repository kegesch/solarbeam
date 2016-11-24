<?php
$json_arr = array();
if(!array_key_exists('q', $_GET)) {
	$json_arr['error'] = 'no param';
	echo json_encode($json_arr);
	exit();
}
$q = $_GET['q'];

$con = mysqli_connect('localhost', 'root', '', 'stromzaehler');
if(mysqli_connect_errno()) {
	$json_arr['error'] = mysql_error();
	echo json_encode($json_arr);
	exit;
}

switch($q) {
	case 'yearly':
		$data = array();
		$preyear_value = 0;
		$res = mysqli_query($con, "SELECT DATE_FORMAT(`time`, '%Y') as `year`, leistung as zaehlerstand, `offset` FROM leistung, zaehler WHERE (DATE_FORMAT(`time`, '%m-%d') = '12-31' OR DATE_FORMAT(`time`, '%Y-%m-%d') = DATE_FORMAT(subdate(now(),1), '%Y-%m-%d')) AND zaehlerid = ID ORDER BY `time` ASC;");
		while($row = mysqli_fetch_array($res)) {
			$db_year = $row['year'];
			$value = $row['zaehlerstand'] - $preyear_value + $row['offset'];
			//echo "Zaehlerstand: ".$row['zaehlerstand']."-".$preyear_value."+".$row['offset']."=".$value."<br/>";
			$data['labels'][] = $db_year;
			$data['series'][] = round($value);
			$preyear_value = $row['zaehlerstand']+$row['offset'];
		}
		echo json_encode($data);
		exit;
	break;
	case 'yearlybezug':
		$data = array();
		$preyear_value = 0;
		$res = mysqli_query($con, "SELECT DATE_FORMAT(`time`, '%Y') as `year`, leistung as zaehlerstand, `offset` FROM leistung_bezug, zaehler WHERE (DATE_FORMAT(`time`, '%m-%d') = '12-31' OR DATE_FORMAT(`time`, '%Y-%m-%d') = DATE_FORMAT(subdate(now(),1), '%Y-%m-%d')) AND zaehlerid = ID ORDER BY `time` ASC;");
		while($row = mysqli_fetch_array($res)) {
			$db_year = $row['year'];
			$value = $row['zaehlerstand'] - $preyear_value + $row['offset'];
			//echo "Zaehlerstand: ".$row['zaehlerstand']."-".$preyear_value."+".$row['offset']."=".$value."<br/>";
			$data['labels'][] = $db_year;
			$data['series'][] = round($value);
			$preyear_value = $row['zaehlerstand']+$row['offset'];
		}
		echo json_encode($data);
		exit();
	break;
	case 'year': 
		if(!array_key_exists('y', $_GET)) {
			
			$years = array();
			$res = mysqli_query($con, "SELECT DISTINCT(YEAR(`time`)) as `year` FROM leistung ORDER by `time` ASC;");
			while($row = mysqli_fetch_array($res)) $years[] = $row['year'];
			$datas = array();
			$value_lastmonth = 0;
			foreach ($years as $year) {
				$data = array();
				for($i = 1; $i < 13; $i++) {
					$sql = "SELECT DATE_FORMAT(`time`, '%M') as month, leistung, `offset` FROM `leistung`, `zaehler` WHERE DATE_FORMAT(`time`, '%Y-%m') = '".$year."-".($i<10 ? "0".$i : $i)."' AND zaehlerid = ID ORDER BY `time` DESC LIMIT 1;";
					$res = mysqli_query($con, $sql);
					$row = mysqli_fetch_array($res);
					if(count($row) == 0) continue;
					$month = $row['month'];
					$value = $row['leistung'] + $row['offset'] - $value_lastmonth;
					$value_lastmonth = $row['leistung'] + $row['offset'];
					$data['labels'][] = $month;
					$data['series'][] = round($value);
				}
					$datas[] = array('year' => $year, 'data' => $data);
			}
				echo json_encode($datas);
				exit;
			
		}
		$year = $_GET['y'];
		if($year < 2008 || $year > date('Y')) {
			$json_arr['error'] = 'year too small or big';
			break;
		}
		
		$res = mysqli_query($con, "SELECT DATE_FORMAT(`time`, '%M') as `month`, leistung, `offset` FROM `leistung`, `zaehler` WHERE DATE_FORMAT(`time`, '%Y-%m-%d') = LAST_DAY('". ($year-1) ."-12-01') AND zaehlerid = ID ORDER BY `time` DESC LIMIT 1; ");
		$row = mysqli_fetch_array($res);

		$value_lastmonth = $row['leistung'] + $row['offset'];
		
		$data = array();
		for($i = 1; $i < 13; $i++) {
			$sql = "SELECT DATE_FORMAT(`time`, '%M') as month, leistung, `offset` FROM `leistung`, `zaehler` WHERE DATE_FORMAT(`time`, '%Y-%m') = '".$year."-".($i<10 ? "0".$i : $i)."' AND zaehlerid = ID ORDER BY `time` DESC LIMIT 1;";
			$res = mysqli_query($con, $sql);
			$row = mysqli_fetch_array($res);
			if(count($row) == 0) continue;
			$month = $row['month'];
			$value = $row['leistung'] + $row['offset'] - $value_lastmonth;
			$value_lastmonth = $row['leistung'] + $row['offset'];
			$data['labels'][] = $month;
			$data['series'][] = round($value);
		}
		echo json_encode($data);
		exit;
	break;
	case 'c':
		$res = mysqli_query($con, "SELECT * FROM momentane_leistung");
		$data = array();
		while($row = mysqli_fetch_array($res)) {
			$data[] = array("zaehlerid" => $row[0], "time" => $row[1], "leistung" => $row[2]);
		}
		echo json_encode($data);
		exit;
	break;
	case 'w':
		$data = array();
		$res = mysqli_query($con, "SELECT DATE_FORMAT(`time`, '%W') as `weekday`, leistung, `offset` FROM leistung, zaehler WHERE zaehlerid = ID ORDER BY `time` DESC LIMIT 8;");
		$revdata = array();
		while($row = mysqli_fetch_array($res)) {
			$revdata[] = array('weekday' => $row['weekday'], 'leistung' => $row['leistung'], 'offset' => $row['offset']);
		}
		$revdata = array_reverse($revdata);
		$value_before = 0; 
		$firstday = true;
		foreach($revdata as $row) {
			$weekday = $row['weekday'];
			$value = $row['leistung'] - $value_before + $row['offset'];
			$value_before = $row['leistung'] + $row['offset'];
			if($firstday) {
				$firstday = false;
				continue;
			}
			//echo "vb " . $value_before . " + val " . $value . " + wd " . $weekday . "<br/>";
			//data fill
			$data['labels'][] = $weekday;
			$data['series'][] = $value;
		}
		echo json_encode($data);
		exit;
	break;
	default: 
		$json_arr['error'] = 'wrong parameter';
}

if($json_arr['error'] != null) {
	echo json_encode($json_arr);
	exit;
}

mysqli_close($con);
exit();
?>