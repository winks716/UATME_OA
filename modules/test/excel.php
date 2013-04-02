<?php
function downloadExcel($properties = array(), $data = array()){

	// The data should look like below format
	/*
	$properties = array(
		'filename'=>'excel',
		'creator'=>'UATME_OA',
		'lastmodifiedby'=>'UATME_OA',
		'title'=>'excel',
		'subject'=>'excel',
		'description'=>'excel',
		'keywords'=>'excel',
		'category'=>'excel'
	);
	$data = array(
		array(
			'title'=>'sheet1',
			'data'=>array(
				array('Hello','world!','Hello','world!'),
				array('Miscellaneous glyphs'),
				array('éàèùâêîôûëïüÿäöüç'),
				array('试试看中文好用不')
			)
		),
		array(
			'title'=>'工作表sheet2',
			'data'=>array(
				array('你好','world!','搞什么飞机','world!'),
				array('Miscellaneous glyphs'),
				array('éàèùâêîôûëïüÿäöüç'),
				array('试试看中文好用不')
			)
		)
	);
	*/
	
	// init excel column name
	$col1 = array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$col2 = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
	$col = array();
	foreach($col1 as $c1){
		foreach($col2 as $c2){
			$col[] = $c1.$c2;
		}
	}

	$objPHPExcel = new PHPExcel();

	// Set document properties
	$objPHPExcel->getProperties()->setCreator($properties['creator'])
								 ->setLastModifiedBy($properties['lastmodifiedby'])
								 ->setTitle($properties['title'])
								 ->setSubject($properties['subject'])
								 ->setDescription($properties['description'])
								 ->setKeywords($properties['keywords'])
								 ->setCategory($properties['category']);
	
	// go throw 3 level data array, level1 for sheet, level2 for row in one sheet, level3 for cell in one row
	// go throw sheet
	$i = 0;
	foreach($data as $sheet){
		// if worksheet more than 1, create the new one at last position
		if($i > 0){
			$objPHPExcel->createSheet();
		}
		
		// go throw every row in this sheet
		$j = 1;
		foreach($sheet['data'] as $row){
		
			// go throw every cell in this row
			$k = 0;
			foreach($row as $cell){
				$objPHPExcel->setActiveSheetIndex($i)->setCellValue($col[$k].$j,$cell);
				$k++;
			}
			$j++;
		}
		
		//set the sheet title
		$objPHPExcel->getActiveSheet()->setTitle($sheet['title']);
		$i++;
	}

	// Redirect output to a client’s web browser (Excel5)
	header('Content-Type: application/vnd.ms-excel');
	header('Content-Disposition: attachment;filename="'.$properties['filename'].'.xls"');
	header('Cache-Control: max-age=0');

	$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
	$objWriter->save('php://output');

	exit;
}

downloadExcel($properties, $data);