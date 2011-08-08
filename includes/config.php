<?php 
// tu zawarte są różne zmienne i stałe


// adres strony
		define("_ADDRESS", "http://chmurli.dyndns.info/~chmurli/tracker_gps_gsm");
		//define("_ADDRESS", "http://localhost/~chmurli/tracker_gps_gsm");


// połączenie do bazy danych
		define("_HOST", "localhost");
		define("_DB_USER", "tracker");
		define("_DB_PASS", "tracker");
		define("_DB_NAME", "tracker_gps_gsm");
		define("_DB_TABLE", "tracker_gps_gsm_data");


// sekcja title dla odpowiednich stron
		define("_SITE_TITLE", "Open Tracker GPS-GSM - Bartosz Chmura");


// możliwe limity wyświetlanych pozycji na jednej stronie
		$limityMozliwe = array(10, 25, 50, 100, 200);
/* gdy jest duża ilości stron do wyświetlenia w przeglądzie tabelarycznym -> nie wyświetlamy wszystkich linków
 * tylko 1 i ostatni oraz zadaną ilośc na + i - od aktualnej strony, definiują ją ta zmienna
 */
		$stronyOdstep=4;



?>
