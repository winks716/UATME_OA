<?php
switch($A){
	case 'upload':
		if($_SESSION['if_document_admin'] == 1 || $_SESSION['if_tech_admin']==1){
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
					move_uploaded_file($_FILES['fileToUpload']['tmp_name'], DOCROOT . '/upload/document/' . $newName);
					$sql = 'INSERT INTO uatme_oa_document_document(name, path, type_id) VALUES ("'.$_POST['document_name'].'", "upload/document/'.$newName.'", "'.$_POST['document_type_id'].'")';
					$mysqli->query($sql);
					$msg = '文档上传成功！';
					//for security reason, we force to remove all uploaded file
					//@unlink($_FILES['fileToUpload']);		
			}		
		}else{
			$error = '您没有权限上传文档';
		}
	break;
	case 'delete':
		if($_SESSION['if_document_admin'] == 1 || $_SESSION['if_tech_admin']==1){
			$sql = 'SELECT path FROM uatme_oa_document_document WHERE id="'.$_POST['id'].'"';
			$result = $mysqli->query($sql);
			if($result->num_rows == 1){
				while($array = $result->fetch_assoc()){
					$file_path = DOCROOT.'/'.$array['path'];
					$sql = 'DELETE FROM uatme_oa_document_document WHERE id="'.$_POST['id'].'"';
					if($mysqli->query($sql)){
						@unlink($file_path);
						$msg = '文档删除成功！';
					}
				}
			}
		}else{
			$error = '您没有权限删除文档';
		}
	break;
}
echo "{";
echo				"error: '" . $error . "',\n";
echo				"msg: '" . $msg . "'\n";
echo "}";