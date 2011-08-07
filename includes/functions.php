<?php 	// funkcje


// funkcja obliczająca czas generowania strony
function getmicrotime(){
	list($usec, $sec) = explode(" ",microtime());
	return ((float)$usec + (float)$sec);
}
// rozpoczynamy obliczanie, będzie lekkie przekładanie
$time_start = getmicrotime();




// standardowe łaczenie się z bazą mysql i wybieranie odpowiedniej bazy
function connectToMySql() {

	$db = mysql_pconnect(_HOST, _DB_USER, _DB_PASS);	

	if (!$db) {
		echo 'Połączenie z bazą się nie powiodło!';		
		return false;
	}	
		
	//$kodowanie=mysql_query("SET NAMES 'utf8'");
	//$kodowanie;
		

	if (!@mysql_select_db(_DB_NAME)) {
		echo 'Wybieranie bazy danych nie powiodło się!';		
		return false;
	}     
}

//zwraca ilość wszystkich rekordów w bazie
function countRowsAll(){
	
	$query='SELECT Count( id ) AS rowsNumber FROM '._DB_TABLE;
	$result=mysql_query($query);
	if (!$result) {
		echo 'Błąd. Połączenie nie powiodło się!';
		exit;
	}

	$row=mysql_fetch_array($result);
	return $row['rowsNumber'];
}


//zwraca ilość rekordów spełniających warunek
function countRows($query2){
	
	$query='SELECT Count( id ) AS rowsNumber FROM '._DB_TABLE.' '.$query2;
	$result=mysql_query($query);
	if (!$result) {
		echo 'Błąd. Połączenie nie powiodło się!';
		exit;
	}

	$row=mysql_fetch_array($result);
	return $row['rowsNumber'];
}










?>
