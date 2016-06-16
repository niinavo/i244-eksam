<?php
//rakenduse põhifail, mida me avame url-is

//algatame sessiooni
session_start();

if ( empty( $_SESSION['csrf_token'] ) ) {
	$_SESSION['csrf_token'] = bin2hex( openssl_random_pseudo_bytes(20) );
}

require('model.php');
require('controller.php');

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
	$result = false;
	if ( !empty( $_POST['csrf_token'] ) && $_POST['csrf_token'] == $_SESSION['csrf_token'] ) {
        switch ($_POST['action']) {
			case 'login':
		        $kasutajanimi = $_POST['kasutajanimi'];
			    $parool = $_POST['parool'];
			    $result = controller_login($kasutajanimi, $parool);
			    break;
		    case 'logout':
		        $result = controller_logout();
			    break;
			case 'register':
		        $kasutajanimi = $_POST['kasutajanimi'];
			    $parool = $_POST['parool'];
			    $result = controller_register($kasutajanimi, $parool);
			    break;
			case 'add': 
		        $nimetus = $_POST['nimetus'];
				$aeg = date ('Y-m-d H:i:s', strtotime($_POST['aeg']));
				$kohad = intval($_POST['kohad']);
		        $result = controller_add($nimetus, $aeg, $kohad);
		        break;
            case 'delete': 
		        $kinoseansi_id = intval($_POST['kinoseansi_id']);
		        $result = controller_delete($kinoseansi_id);
			    break;
	    }
    } else {
		message_add('Vigane päring, CSRF token ei vasta oodatule');
	}
	if (!$result) {
		message_add('Päring ebaõnnestus!');
	}
	header('Location: kino.php');
	exit;
	
}


if( !empty($_GET['view']) ) {
	switch($_GET['view']) {
		case 'register':
		    require 'view_register.php';
			break;
		case 'login':
		    require 'view_login.php';
			break;
		default:
		    header('Content-type: text/plain; charset=utf-8');
		    echo 'Tundmatu valik!';
			exit;
	}
} else {
	if( !controller_user() ) {
		header('Location: ' . $_SERVER['PHP_SELF'] . '?view=login');
		exit;
	}
	
	if ( empty($_GET['page']) ) {
		$page = 1;
	} else {
		$page = intval($_GET['page']);
	}
	
	if ($page < 1) {
		$page = 1;
	}
	
    require('view_kinoseansid.php');
}

mysqli_close($l);

?>
