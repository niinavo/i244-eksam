<?php 
    $host = 'localhost';
    $user = 'test';
    $pass = 't3st3r123';
    $db = 'test';

    $l = mysqli_connect($host, $user, $pass, $db);
    mysqli_query($l, "SET NAMES 'utf8'") or
            die('Viga: andmebaasi charsetti ei saanud seada!');
    
	//Laeb andmebaasist kirjed valitud lehekülje kohta, tagastab need massiivina, mida sorteeritakse märkme tähtaja järgi kasvavas järjekorras
    function model_load($page) {
	    global $l;
		$max = 10;
		$start = ($page - 1) * $max;
		$query = 'SELECT Kinoseansi_id, Kinoseansi_nimetus, Algusaeg, Kohti_kokku as vabu_kohti 
		FROM 10153400___kinoseansid  
		GROUP BY Kinoseansi_id 
		ORDER BY Algusaeg ASC LIMIT ?,?';
		$stmt = mysqli_prepare($l, $query);
		if ( mysqli_error($l) ) {
			echo mysqli_error($l);
			exit;
		}
		mysqli_stmt_bind_param($stmt, 'ii', $start, $max);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $kinoseansi_id, $nimetus, $aeg, $kohad);
		$rows = array();
		while (mysqli_stmt_fetch($stmt)) {
			$rows[] = array(
			    'kinoseansi_id' => $kinoseansi_id, 
				'nimetus' => $nimetus, 
				'aeg' => $aeg,
				'kohad' => $kohad
			);
		}	
		mysqli_stmt_close($stmt);	
		return $rows;
	}
	
	function model_add($nimetus, $aeg, $kohad) {
		global $l;	
		$query = 'INSERT INTO 10153400___kinoseansid (Kinoseansi_nimetus, Algusaeg, Kohti_kokku) 
		VALUES (?, ?, ?)';
		$stmt = mysqli_prepare($l, $query);
		if ( mysqli_error($l) ) {
			echo mysqli_error($l);
			exit;
		}
		mysqli_stmt_bind_param($stmt, 'ssi', $nimetus, $aeg, $kohad);
		mysqli_stmt_execute($stmt);
		$kinoseansi_id = mysqli_stmt_insert_id($stmt);
		mysqli_stmt_close($stmt);
		return $kinoseansi_id;
	}
	
	function model_delete($kinoseansi_id) {
		global $l;
		$query = 'DELETE FROM 10153400___kinoseansid 
		WHERE Kinoseansi_id = ? LIMIT 1';
		$stmt = mysqli_prepare($l, $query);
		if ( mysqli_error($l) ) {
			echo mysqli_error($l);
			exit;
		}
		mysqli_stmt_bind_param($stmt, 'i', $kinoseansi_id);
		mysqli_stmt_execute($stmt);
		$deleted = mysqli_stmt_affected_rows($stmt);
		mysqli_stmt_close($stmt);
		return $deleted;
	}
	
	//Lisab andmebaasi kasutajad uue kasutaja. Õnnestub vaid juhul kui sellist kasutajat veel pole.
	function model_user_add($kasutajanimi, $parool) {
		global $l;
		$hash = password_hash($parool, PASSWORD_DEFAULT);
		$query = 'INSERT INTO 10153400___kasutajad (Kasutajanimi, Parool) 
		VALUES (?,?)';
		$stmt = mysqli_prepare($l, $query);
		if ( mysqli_error($l) ) {
			echo mysqli_error($l);
			exit;
		}
		mysqli_stmt_bind_param($stmt, 'ss', $kasutajanimi, $hash);
		mysqli_stmt_execute($stmt);
		$kasutaja_id = mysqli_stmt_insert_id($stmt);
		mysqli_stmt_close($stmt);
		return $kasutaja_id;
	}
	
	// Tagastab andmebaasist kasutajad kasutaja_id, kelle kasutajanimi ja parool klapivad sisendiga.
	function model_user_get($kasutajanimi, $parool) {
		global $l;
		$query = 'SELECT Kasutaja_id, Parool 
		FROM 10153400___kasutajad 
		WHERE Kasutajanimi=? LIMIT 1';
		$stmt = mysqli_prepare($l, $query);
		if ( mysqli_error($l) ) {
			echo mysqli_error($l);
			exit;
		}
		mysqli_stmt_bind_param($stmt, 's', $kasutajanimi);
		mysqli_stmt_execute($stmt);
		mysqli_stmt_bind_result($stmt, $kasutaja_id, $hash);
		mysqli_stmt_fetch($stmt);
		mysqli_stmt_close($stmt);
		if ( password_verify($parool, $hash) ) {
			return $kasutaja_id;
		} else {
			return false;
		}
	}