<?php
	
	// Kontrollib kas kasutaja on sisse logitud
	function controller_user() {
		if( empty($_SESSION['user']) ) {
			return false;
		}
		return $_SESSION['user'];
	}
	
	//Lisab uue kasutajakonto
	function controller_register($kasutajanimi, $parool) {
		if ($kasutajanimi == '' || $parool == '') {
			message_add('Kasutajanimi ja parooli lahtrid ei saa olla tühjad!');
			return false;
		}
		if ( model_user_add($kasutajanimi, $parool) ) {
			message_add('Teie konto on registreeritud!');
			return true;
		}
		message_add('Konto registreerimine ebaõnnestus, kasutajanimi võib olla juba võetud!');
		return false;
	}
	
	//Logib sisse kasutaja
	function controller_login ($kasutajanimi, $parool) {
		if ($kasutajanimi == '' || $parool == '') {
			message_add('Kasutajanimi ja parooli lahtrid ei saa olla tühjad!');
			return false;
		}
		$kasutaja_id = model_user_get($kasutajanimi, $parool);
		if (!$kasutaja_id) {
			message_add('Kasutajanimi või parool on vigane');
			return false;
		}
		session_regenerate_id();
		$_SESSION['user'] = $kasutaja_id;
		message_add('Tere tulemast, '.$kasutajanimi.'!');
		return $kasutaja_id;
	}
	
	//Logib välja kasutaja
	function controller_logout () {	
		if ( isset( $_COOKIE[session_name()] ) ) {
			setcookie( session_name(), '', time() - 42000, '/' );
		}	
		$_SESSION = array();	
		session_destroy();	
		message_add('Teie olete nüüd välja logitud');	
		return true;
		
	}
	
	
    function controller_add($nimetus, $aeg, $kohad) {
		
		$sysdate = date('Y-m-d H:i:s');
		if ( !controller_user() ) {
			message_add('Kasutaja peab olema sisselogitud!');
			return false;
		}
		// kontrollime, kas sisendväärtused on oodatud kujul või mitte
		if ($aeg == '' || $nimetus == '' || $kohad <= 0 || $aeg < $sysdate ) {
			message_add('Sisestatud andmed on vigased! Kõik vormi lahtrid peavad olema täidetud ja märkme tähtaeg ei tohi olla möödunud!');
			return false;
		}
		if ( model_add($nimetus, $aeg, $kohad) ) {
			message_add('Õnnestus lisada uus märge!');
			return true;
		}	
		message_add('Andmete lisamine ebaõnnestus!');
		return false;
	}
	
	function controller_delete($kinoseansi_id) {	
		if ( !controller_user() ) {
			message_add('Kasutaja peab olema sisselogitud!');
			return false;
		}		
		if ($kinoseansi_id <= 0) {
			message_add('Sisendandmed on vigased!');
			return false;
		}		
		if ( model_delete($kinoseansi_id) ) {
			message_add('Kustutati  märge nr. '.$kinoseansi_id);
			return true;
		}
		message_add('Märkme kustutamine ebaõnnestus!');
		return false;
	}
	
	//Tagastab kõik hetkel ootel olevad sõnumid
	function message_list() {
		if ( empty($_SESSION['messages']) ) {
			return array();
		}
		$messages = $_SESSION['messages'];
		$_SESSION['messages'] = array();
		return $messages;
	}
	
	//Lisab järjekorda uue sõnumi kasutajale kuvamiseks
	function message_add($message) {
		if ( empty($_SESSION['messages']) ) {
			$_SESSION['messages'] = array();
		}
		$_SESSION['messages'][] = $message;
	}
	