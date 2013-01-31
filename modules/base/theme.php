<?php
switch($A){
	case 'switch':
		$_SESSION['themeName'] = $_POST['themeName'];
		header('Location: index.php');
	break;
	default:
	
	break;
}