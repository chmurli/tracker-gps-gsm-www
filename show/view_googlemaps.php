<?php

	// sprawdź czy wyświetlać markery przy każdym punkcie
	// domyślnie tylko przy ostatniej pozycji jest wyświetlany
	if (isset($_GET['markery']) && ($_GET['markery']==1)) 
		$markery=1;
	else $markery=0;


	// sprawdź ile ostatnich pozycji wyświetlić, domyślnie 100
	if (isset($_GET['pozycje'])) 
		$pozycje=(int)$_GET['pozycje'];
	else $pozycje=100;


	// sprawdź czy została podana data początkowa
	if (isset($_GET['data1'])) 
		$data1=addslashes($_GET['data1']);
	else $data1='';

	// sprawdź czy została podana data końcowa
	if (isset($_GET['data2'])) 
		$data2=addslashes($_GET['data2']);
	else $data2='';

	// sprawdź czy została podana godzina początkowa
	if (isset($_GET['godzina1'])) 
		$godzina1=addslashes($_GET['godzina1']);
	else $godzina1='';

	// sprawdź czy została podana godzina końcowa
	if (isset($_GET['godzina2'])) 
		$godzina2=addslashes($_GET['godzina2']);
	else $godzina2='';

?>


<script type="text/javascript">  
<!--
	var mapa; 										// obiekt globalny
	var dymek = new google.maps.InfoWindow(); 		// zmienna globalna


		// *******************************************
		// ustawienia ikon - start
		// wspólne cechy ikon
		var rozmiar = new google.maps.Size(20,34);
		var rozmiar_cien = new google.maps.Size(37,34);
		var punkt_startowy = new google.maps.Point(0,0);
		//var punkt_zaczepienia = new google.maps.Point(16,16);
		var punkt_zaczepienia = new google.maps.Point(10,34);
		 
		// ikonki
		var ikona1 = new google.maps.MarkerImage("http://www.google.com/intl/en_ALL/mapfiles/dd-start.png", rozmiar, punkt_startowy, punkt_zaczepienia);
		var ikona2 = new google.maps.MarkerImage("http://www.google.com/intl/en_ALL/mapfiles/marker.png", rozmiar, punkt_startowy, punkt_zaczepienia);
		var cien1 = new google.maps.MarkerImage("http://www.google.com/intl/en_ALL/mapfiles/shadow50.png", rozmiar_cien, punkt_startowy, punkt_zaczepienia);
		var cien2 = new google.maps.MarkerImage("http://www.google.com/intl/en_ALL/mapfiles/shadow50.png", rozmiar_cien, punkt_startowy, punkt_zaczepienia);


		// ustawienia ikon - koniec
		// *******************************************



	function dodajMarker(lat,lng,txt,ikona,cien)
	{
		// tworzymy marker
		var opcjeMarkera =   
		{  
			position: new google.maps.LatLng(lat,lng),
			icon: ikona, 
			shadow: cien,
			map: mapa
		}  
		var marker = new google.maps.Marker(opcjeMarkera);
		marker.txt=txt;
			
		google.maps.event.addListener(marker,"click",function()
		{
			dymek.setContent(marker.txt);
			dymek.open(mapa,marker);
		});
		return marker;
	}


	


<?php

	connectToMySql();


	// ilość wszystkich rekordów w bazie
	$result=mysql_query('SELECT * FROM '._DB_TABLE.' ORDER BY id DESC');
	if (!$result) {
		echo 'Błąd. Połączenie nie powiodło się!';
		exit;
	}
	$rowsAll=mysql_num_rows($result);


	if($data1 && $data2 && $godzina1 && $godzina2)	
		$result=mysql_query('SELECT * FROM '._DB_TABLE.' WHERE date >= \''.$data1.' '.$godzina1.'\' AND date <= \''.$data2.' '.$godzina2.'\' ORDER BY id DESC');
	else
		$result=mysql_query('SELECT * FROM '._DB_TABLE.' ORDER BY id DESC LIMIT 0 , '.$pozycje);

		
	if (!$result) {
		echo 'Błąd. Połączenie nie powiodło się!';
		exit;
	}
		
	$rowsFounded=mysql_num_rows($result);

	// jeżeli są jakies dane do wyświetlenia
	if ($rowsFounded) { 
	

		$row=mysql_fetch_array($result);


		// start mapy
		echo '
			function mapaStart() 
			{ 
				// punkt startowy, centruj na ostatniej pozycję
				var wspolrzedne = new google.maps.LatLng('.$row['latitude'].','.$row['longitude'].');
				var opcjeMapy = {
					zoom: 18,
					center: wspolrzedne,
					mapTypeId: google.maps.MapTypeId.SATELLITE,
					scaleControl: true	// kontrolka skali
			};
			mapa = new google.maps.Map(document.getElementById("mapka"), opcjeMapy);
		';


		// wyświetl marker z ostatnią pozycją (zawsze)
		echo '
			var marker1 = dodajMarker('.$row['latitude'].','.$row['longitude'].',\'\
				<p class="ostatniaPozycja">Ostatnia pozycja</p><br />\
				<strong>data: </strong>'.$row['date'].'<br />\
				<strong>prędkość: </strong>'.$row['speed'].' km/h<br />\
				<strong>wysokość: </strong>'.$row['altitude'].' m. n.p.m.<br />\
				<strong>satelity: </strong>'.$row['satellites'].'<br />\
				<strong>tryb: </strong>'.$row['mode'].'<br />\
				<strong>pdop: </strong>'.$row['pdop'].'<br />\
				<strong>szer. geo.: </strong>'.$row['latitude'].'<br />\
				<strong>dł. geo.: </strong>'.$row['longitude'].'<br />\
				\',ikona1);
		';
		
		// tablica z punktami dla polilinii, dodajemy pierwszy element
		echo '
			var poliliniaPunkty = [new google.maps.LatLng('.$row['latitude'].','.$row['longitude'].')];	
		';


		
		for ($i=2,$j=1; $row=mysql_fetch_array($result); $i++,$j++) {
				
			// jeżeli opcja zaznaczona to wyświetl markery wszystkich pozycji
			if($markery) {
				echo '
					var marker'.$i.' = dodajMarker('.$row['latitude'].','.$row['longitude'].',\'\
						<strong>pozycja: '.$i.'</strong><br />\
						<strong>data: </strong>'.$row['date'].'<br />\
						<strong>prędkość: </strong>'.$row['speed'].' km/h<br />\
						<strong>wysokość: </strong>'.$row['altitude'].' m. n.p.m.<br />\
						<strong>satelity: </strong>'.$row['satellites'].'<br />\
						<strong>tryb: </strong>'.$row['mode'].'<br />\
						<strong>pdop: </strong>'.$row['pdop'].'<br />\
						<strong>szer. geo.: </strong>'.$row['latitude'].'<br />\
						<strong>dł. geo.: </strong>'.$row['longitude'].'<br />\
						\',ikona2);
				';	
			}
			
			// dodaj kolejne elementy do tablicy punktów do polilinii
			echo '
				poliliniaPunkty['.$j.'] = new google.maps.LatLng('.$row['latitude'].','.$row['longitude'].');	
			';
			
		} // koniec pętli for
		
	
	}

?>
        

		var polilinia = new google.maps.Polyline({
			map: 			mapa,
			path: 			poliliniaPunkty,
			strokeColor:	'#D90000',
			strokeWeight:	3
		});


		// domyślnie włączony dymek z ostatnio pozycją na starcie
		google.maps.event.trigger(marker1,'click');


	 
	}
-->
</script>




<!-- zmiana ustawień wyświetlania mapy -->  
<form method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
	ilość wyświetlanych <b>ostatnich</b> pozycji:
	<input name="pozycje" type="text" size="15" value="<?php if($pozycje) echo $pozycje; else echo '100'; ?>" />
	&nbsp;&nbsp;
	<input type="checkbox" name="markery" value="1" <?php if($markery) echo 'checked="checked"'; ?> /> 
	pokazuj markery
	&nbsp;&nbsp;	
	<input type="hidden" name="show" value="view_googlemaps" />
    <input type="submit" value="zmień ustawienia" />
</form>	



<!-- zmiana ustawień KALENDARZ -->
<form method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">

	<table>
	<tr>
		<td><b>od:</b></td>
		<td><script>DateInput('data1', true, 'YYYY-MM-DD' <?php if($data1) echo ', \''.$data1.'\''; ?>)</script></td>
		<td><input name="godzina1" type="text" size="5" maxlength="5" value="<?php if($godzina1) echo $godzina1; else echo "06:00"; ?>" /></td>
	</tr>
	<tr>
		<td><b>do:</b></td>
		<td><script>DateInput('data2', true, 'YYYY-MM-DD' <?php if($data2) echo ', \''.$data2.'\''; ?>)</script></td>
		<td><input name="godzina2" type="text" size="5" maxlength="5" value="<?php if($godzina2) echo $godzina2; else echo "23:59"; ?>" /></td>
	</tr>
	</table>
	<input type="checkbox" name="markery" value="1" <?php if($markery) echo "checked=\"checked\""; ?> /> 
	pokazuj markery
	&nbsp;&nbsp;	

	<input type="hidden" name="show" value="view_googlemaps" />
	<input type="submit" value="zmień ustawienia" />
</form>




<?php

	// jeżeli brak wyników to odpowiedni komunikat
	if(!$rowsFounded) {
		echo "<h2>BRAK DANYCH DO WYŚWIETLENIA <br />";
		// jeżeli nie ma żadnych danych w bazie
		if ($rowsAll==0)
			echo "BAZA DANYCH JEST PUSTA</h2>";
		// dane w bazie są, trzeba zmienić zapytanie
		else {
			echo "ZMIEŃ USTAWIENIA</h2>";
			echo "<h3>wszystkich rekordów w bazie: $rowsAll</h3>";
		}
	}
?>

<div id="mapka" class="googlemaps">  
<!-- tu będzie mapa -->  
</div>









