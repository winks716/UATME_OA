<?php 

			$sql = 'SELECT * FROM uatme_oa_hr_leave_apply';
			$result = $mysqli->query($sql);
			while($array = $result->fetch_assoc()){
				$start = explode(' ', $array['start']);
				$end = explode(' ', $array['end']);
				$startdate = explode('-', $start[0]);
				$starttime = explode(':', $start[1]);
				$enddate = explode('-', $end[0]);
				$endtime = explode(':', $end[1]);

				$startsecond = mktime($starttime[0],$starttime[1],$starttime[2],$startdate[1],$startdate[2],$startdate[0]);
				$endsecond = mktime($endtime[0],$endtime[1],$endtime[2],$enddate[1],$enddate[2],$enddate[0]);
				
				$second = $endsecond-$startsecond;
				echo $second,'秒';
				$minute = $second/60;
				echo $minute,'分';
				$hour = $minute/60;
				echo $hour,'小时';
				$day = $hour/8;
				echo $day,'天';
			}