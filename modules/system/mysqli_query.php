<?php 

			$sql = 'SELECT id,name,email FROM uatme_oa_system_employee WHERE email LIKE "%@highsource.com.cn"';
			$result = $mysqli->query($sql);
			while($array = $result->fetch_assoc()){
				$sql = 'UPDATE uatme_oa_system_employee SET email="'.$array['name'].'@highsource.com" WHERE id="'.$array['id'].'"';
				$mysqli->query($sql);
				echo $array['email'],'<br/>';
			}