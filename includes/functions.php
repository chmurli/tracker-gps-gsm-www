<?php 
// funkcje


// standardowe łaczenie się z bazą mysql i wybieranie odpowiedniej bazy
function connectToMySql() {

		$db = mysql_connect(_HOST, _DB_USER, _DB_PASS);	

		if (!$db) {
			echo "Połączenie z bazą się nie powiodło!";		
			return false;
		}	
		
		//$kodowanie=mysql_query("SET NAMES 'utf8'");
		//$kodowanie;
		

		if (!@mysql_select_db(_DB_NAME)) {
			echo "Wybieranie bazy danych nie powiodło się!";		
			return false;
		}
        
}




?>
