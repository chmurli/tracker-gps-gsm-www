<?php // dołączanie	

	error_reporting(E_ALL);

	echo '<?xml version="1.0" encoding="UTF-8"?'.">\n";
	include("includes/config.php"); 		// stałe konfiguracyjne
	include("includes/functions.php"); 	// funkcje
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" 
"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<!-- copyright © 2011 Bartosz "chmurli" Chmura -->

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="pl">
<head>

	<title><?php echo _SITE_TITLE; // pobiera z pliku config.php ?></title>
		<link rel="shortcut icon" href="/const_img/favicon.ico" type="image/x-icon" />

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<meta http-equiv="reply-to" content="chmurli [at] gmail.com" />
	<meta name="author" content="Bartosz Chmura"/>
	<meta name="content-language" content="pl" />
	
	<link rel="stylesheet" type="text/css" href="style.css" />
	<script type="text/javascript" src="calendarDateInput.js"></script>
	<script src="http://maps.google.com/maps/api/js?sensor=false" type="text/javascript"></script>   
	
</head>
<body onload="mapaStart()">



<div id="logo">
	Open Tracker GPS-GSM
</div>


<div id="header">
	<ul>
		<li>
			<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?show=start" class="link2">
			&nbsp;&nbsp;&nbsp;strona główna&nbsp;&nbsp;&nbsp;</a>
		</li>
		<li>
			<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?show=view_googlemaps" class="link2">
			&nbsp;&nbsp;&nbsp;oglądaj w Google Maps&nbsp;&nbsp;&nbsp;</a>
		</li>
		<li>
			<a href="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>?show=view_table" class="link2">
			&nbsp;&nbsp;&nbsp;przeglądaj tabelarycznie&nbsp;&nbsp;&nbsp;</a>
		</li>
	</ul>
</div>



<?php // skrypt na wyświetlanie odpowiedniej strony w części głównej z pomocą include()


	if (isset($_GET['show'])) 
		$show=$_GET['show']; 
	else $show='';

	// dozwolone strony do wyświetlenia
	$allowed = array('start', 'view_googlemaps', 'view_table');

	if (in_array($show, $allowed)) { 
		include("show/".$show.".php");
	} 
	else { 
		// jeżeli wartość niepoprawna (możliwa próba włamania)
		// wyświetl stronę startową
		include("show/start.php");
	}




?>







<div id="footer">
	&copy; Copyright by Bartosz Chmura 2011. Wszelkie prawa zastrzeżone<br />


	<a href="http://validator.w3.org/check?uri=referer">
	<img src="const_img/valid-xhtml-1.1.gif" alt="valid xhtml 1.1" height="15" width="80" /></a>

	<a href="http://jigsaw.w3.org/css-validator/check/referer">
	<img src="const_img/valid-css.gif" alt="valid css" height="15" width="80" /></a>

	<a href="http://www.php.net/">
	<img src="const_img/powered-php.gif" alt="powered php" height="15" width="80" /></a>

	<a href="http://www.mysql.com/">
	<img src="const_img/powered-mysql.gif" alt="powered mysql" height="15" width="80" /></a>

	<br />

	
</div>








</body>
</html>
