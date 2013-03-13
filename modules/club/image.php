<?php

if($_SESSION['if_club_admin'] == 1){
	switch($A){
		case 'club.image.setup':
			//get all boxes
			$sql = 'SELECT * FROM uatme_oa_club_box';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['box'][$array['id']] = $array;
				}
			}
			//get all images
			$sql = 'SELECT * FROM uatme_oa_club_content ORDER BY box_id ASC';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['image'][] = $array;
				}
			}
			$smarty->assign($assign);
			$smarty->display('club/image.setup.html');
			break;
		case 'save':
			
			break;
		case 'club.image.delete':
			$sql = 'SELECT image FROM uatme_oa_club_content WHERE id="'.$_POST['id'].'"';
			$result = $mysqli->query($sql);
			if($result->num_rows == 1){
				while($array = $result->fetch_assoc()){
					$extend = explode('.',$array['image']);
					$old_path = DOCROOT.'/'.$array['image'];
					$new_path = DOCROOT.'/upload/clubrecycle/'.Date('YmdHis').'.'.$extend[1];
					$sql = 'DELETE FROM uatme_oa_club_content WHERE id="'.$_POST['id'].'"';
					if($mysqli->query($sql)){
						rename($old_path, $new_path);
						$httpstatus = 200;
						$msg = '文档删除成功！';
					}
				}
			}else{
				$httpstatus = 500;
				$error = '反馈I1:服务器忙,请稍后再试';
			}
			sendResponse($httpstatus, $error, $msg);
			break;
	}
}else{
	exit('您无权访问此页面');
}