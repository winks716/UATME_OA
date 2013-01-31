<?php

//search box
$sql = 'SELECT * FROM uatme_oa_club_box';
$result = $mysqli->query($sql);
if($result->num_rows > 0){
	while($array = $result->fetch_assoc()){
		$sql_image = 'SELECT image FROM uatme_oa_club_content WHERE box_id="'.$array['id'].'" AND ifthumbnail=1 ORDER BY id DESC LIMIT 1';
		$result_image = $mysqli->query($sql_image);
		if($result_image->num_rows == 1){
			while($array_image = $result_image->fetch_assoc()){
				$array['style_bgimage'] = $array_image['image'];
			}
		}
		$assign['box'][] = $array;
	}
}

$smarty->assign($assign);
$smarty->display('club/index.html');