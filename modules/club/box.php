<?php

switch($A){
	case 'add':
		if($_SESSION['if_club_admin'] == 1){
			$sql = 'INSERT INTO uatme_oa_club_box (style_width, style_height, style_left, style_top, style_bgcolor) VALUES ("'.$_POST['styleWidth'].'", "'.$_POST['styleHeight'].'", "'.$_POST['styleLeft'].'", "'.$_POST['styleTop'].'", "'.$_POST['styleBgcolor'].'")';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '模块添加成功';
			}else{
				$httpstatus = 503;
				$error = '服务器忙，请稍候再试';
			}
		}else{
			$httpstatus = 503;
			$error = '您没有权限添加模块';
		}
		sendResponse($httpstatus, $error, $msg);
	break;
	case 'delete':
		if($_SESSION['if_club_admin'] == 1){
			$sql = 'DELETE FROM uatme_oa_club_box WHERE id="'.$_POST['id'].'"';
			$mysqli->query($sql);
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '模块删除成功';
			}else{
				$httpstatus = 503;
				$error = '服务器忙，请稍候再试';
			}
		}else{
			$httpstatus = 503;
			$error = '您没有权限删除模块';
		}
		sendResponse($httpstatus, $error, $msg);
	break;
	case 'edit':
		if($_SESSION['if_club_admin'] == 1){
			$assign['id'] = $_POST['id'];
			$sql = 'SELECT * FROM uatme_oa_club_box WHERE id="'.$_POST['id'].'"';
			$result = $mysqli->query($sql);
			if($result->num_rows == 1){
				while($array = $result->fetch_assoc()){
					foreach($array as $key=>$value){
						$assign[$key] = $value;
					}
				}
			}else{
				exit('没有符合条件的模块');
			}
			$smarty->assign($assign);
			$smarty->display('club/box.edit.html');
		}else{
			exit('没有权限编辑模块');
		}
	break;
	case 'save':
		if($_SESSION['if_club_admin'] == 1){
			$sql = 'UPDATE uatme_oa_club_box set title="'.$_POST['title'].'", style_height="'.$_POST['styleHeight'].'", style_width="'.$_POST['styleWidth'].'", style_left="'.$_POST['styleLeft'].'", style_top="'.$_POST['styleTop'].'", style_bgcolor="'.$_POST['styleBgcolor'].'" WHERE id="'.$_POST['id'].'"';
			if($mysqli->query($sql)){
				$httpstatus = 200;
				$msg = '模块保存成功';
			}else{
				$httpstatus = 503;
				$error = '服务器忙，请稍候再试';
			}
		}else{
			$httpstatus = 503;
			$error = '您没有权限保存编辑';
		}
		sendResponse($httpstatus, $error, $msg);
	break;
	case 'image.edit':
		if($_SESSION['if_club_admin'] == 1){
			$assign['id'] = $_POST['id'];
			$sql = 'SELECT * FROM uatme_oa_club_content WHERE box_id="'.$_POST['id'].'" OR (box_id NOT IN (SELECT id FROM uatme_oa_club_box))';
			$result = $mysqli->query($sql);
			if($result->num_rows > 0){
				while($array = $result->fetch_assoc()){
					$assign['image'][] = $array;
				}
			}else{
				exit('没有符合条件的照片');
			}
			$smarty->assign($assign);
			$smarty->display('club/image.edit.html');
		}else{
			exit('没有权限关联图片');
		}
	break;
	case 'image.save':
		if($_SESSION['if_club_admin'] == 1){
			$sql = 'UPDATE uatme_oa_club_content set box_id=0, ifthumbnail=0 WHERE box_id="'.$_POST['id'].'"';
			if(count($_POST['edit_image_checkbox'])>0){
				$mysqli->query($sql);
				$sql = 'UPDATE uatme_oa_club_content set ifthumbnail=1 WHERE id="'.$_POST['edit_image_ifthumbnail'].'"';
				$mysqli->query($sql);
				$_POST['edit_image_checkbox'][] = $_POST['edit_image_ifthumbnail'];
				$sql = 'UPDATE uatme_oa_club_content set box_id="'.$_POST['id'].'" WHERE id IN ('.implode(",", $_POST['edit_image_checkbox']).')';
				if($mysqli->query($sql)){
					$httpstatus = 200;
					$msg = '照片关联成功';
				}else{
					$httpstatus = 503;
					$error = '服务器忙，请稍候再试';
				}
			}else{
				if($mysqli->query($sql)){
					$httpstatus = 200;
					$msg = '已取消所有照片关联';
				}else{
					$httpstatus = 503;
					$error = '您没有权限保存关联';
				}
			}
		}else{
			$httpstatus = 503;
			$error = '您没有权限保存关联';
		}
		sendResponse($httpstatus, $error, $msg);
	break;
	case 'image.upload':
		if($_SESSION['if_club_admin'] == 1){
			$error = "";
			$msg = "";
			$fileElementName = 'fileToUpload';
			if(!empty($_FILES[$fileElementName]['error']))
			{
				switch($_FILES[$fileElementName]['error'])
				{

					case '1':
						$error = 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
						break;
					case '2':
						$error = 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
						break;
					case '3':
						$error = 'The uploaded file was only partially uploaded';
						break;
					case '4':
						$error = 'No file was uploaded.';
						break;

					case '6':
						$error = 'Missing a temporary folder';
						break;
					case '7':
						$error = 'Failed to write file to disk';
						break;
					case '8':
						$error = 'File upload stopped by extension';
						break;
					case '999':
					default:
						$error = 'No error code avaiable';
				}
			}elseif(empty($_FILES['fileToUpload']['tmp_name']) || $_FILES['fileToUpload']['tmp_name'] == 'none')
			{
				$error = 'No file was uploaded..';
			}else 
			{
					$msg .= " File Name: " . $_FILES['fileToUpload']['name'] . ", ";
					$msg .= " File Size: " . @filesize($_FILES['fileToUpload']['tmp_name']);
					$name_array = explode('.',$_FILES['fileToUpload']['name']);
					$extendName = '.' . $name_array[count($name_array)-1];
					$newName = $_SESSION['employee_id'] . date('Ymdhis') . $extendName;
					move_uploaded_file($_FILES['fileToUpload']['tmp_name'], DOCROOT . '/upload/club/' . $newName);
					$sql = 'INSERT INTO uatme_oa_club_content(title, detail, image) VALUES ("'.$_POST['image_name'].'", "'.$_POST['image_detail'].'", "upload/club/'.$newName.'")';
					$mysqli->query($sql);
					$msg = '照片上传成功！';
					//for security reason, we force to remove all uploaded file
					//@unlink($_FILES['fileToUpload']);		
			}		
		}else{
			$error = '您没有权限上传照片';
		}
		echo "{";
		echo				"error: '" . $error . "',\n";
		echo				"msg: '" . $msg . "'\n";
		echo "}";
	break;
	case 'slide.show':
		$sql = 'SELECT * FROM uatme_oa_club_content WHERE box_id="'.$_POST['id'].'" AND ifthumbnail=0';
		$result = $mysqli->query($sql);
		if($result->num_rows > 0){
			while($array = $result->fetch_assoc()){
				$array['id'] = ++$i;
				$assign['slide'][] = $array;
			}
		}
		$smarty->assign($assign);
		$smarty->display('club/slide.show.html');
	break;
}