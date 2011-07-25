<?php

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


<!-- zmiana ustawień -->  
<form method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">
	ilość wyświetlanych <b>ostatnich</b> pozycji: 
	<input name="pozycje" type="text" size="15" value="<?php if($pozycje) echo $pozycje; else echo "100"; ?>" />	
	&nbsp;&nbsp;	
	<input type="hidden" name="show" value="view_table" />
    <input type="submit" value="zmień ustawienia" />
</form>	



<!-- zmiana ustawień KALENDARZ -->
<form method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">

	<table>
	<tr>
		<td><b>od:</b></td>
		<td><script>DateInput('data1', true, 'YYYY-MM-DD' <?php if($data1) echo ", '$data1'"; ?>)</script></td>
		<td><input name="godzina1" type="text" size="5" maxlength="5" value="<?php if($godzina1) echo $godzina1; else echo "06:00"; ?>" /></td>
	</tr>
	<tr>
		<td><b>do:</b></td>
		<td><script>DateInput('data2', true, 'YYYY-MM-DD' <?php if($data2) echo ", '$data2'"; ?>)</script></td>
		<td><input name="godzina2" type="text" size="5" maxlength="5" value="<?php if($godzina2) echo $godzina2; else echo "23:59"; ?>" /></td>
	</tr>
	</table>

	<input type="hidden" name="show" value="view_table" />
	<input type="submit" value="zmień ustawienia" />
</form>





<?php
	connectToMySql();


	// ilość wszystkich rekordów w bazie
	$result=mysql_query("SELECT * FROM ". _DB_TABLE." ORDER BY id DESC");
	if (!$result) {
		echo "Błąd. Połączenie nie powiodło się!";
		exit;
	}
	$rowsAll=mysql_num_rows($result);



	
	if($data1 && $data2 && $godzina1 && $godzina2)	
		$result=mysql_query("SELECT * FROM ". _DB_TABLE." WHERE date >= '$data1 $godzina1' AND date <= '$data2 $godzina2' ORDER BY id DESC");
	else
		$result=mysql_query("SELECT * FROM ". _DB_TABLE." ORDER BY id DESC LIMIT 0 , $pozycje");
		

	if (!$result) {
		echo "Błąd. Połączenie nie powiodło się!";
		exit;
	}
		
	$rowsFounded=mysql_num_rows($result);

	// jeżeli brak wyników to odpowiedni komunikat
	if (!$rowsFounded) { 
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
	else {		
		echo "
			<h3>wyświetlanych pozycji: $rowsFounded z $rowsAll</h3>
			<div id=\"przegladaj\">
			<table>
				<tr>
					<th title=\"liczba porządkowa (nr.)\">lp.</th>
					<th title=\"znacznik daty (date)\">data</th>
					<th title=\"szerokość geograficzna (latitude)\">szer. geo.</th>
					<th title=\"długość geograficzna (longitude)\"> dł. geo.</th>
					<th title=\"wysokość w m. n.p.m. (altitude)\">wysokość</th>
					<th title=\"prędkość w km/h (speed)\">prędkość</th>
					<th title=\"ilość użytych satelitów (satellites)\">satelity</th>
					<th title=\"precyzja dla trzech wpsółrzędnych\">pdop</th>
					<th title=\"tryb odbieranych danych, D=DGPS\">tryb</th>
				</tr>
		";
				
						
							
		// wyświetl n ostatnich
		for ($i=1; $row=mysql_fetch_array($result); $i++) {
				
			echo "
				<tr>
					<td class=\"nr\">$i</td>
					<td class=\"date\">".$row['date']."</td>
					<td class=\"latitude\">".$row['latitude']."</td>
					<td class=\"longitude\">".$row['longitude']."</td>
					<td class=\"altitude\">".$row['altitude']."</td>
					<td class=\"speed\">".$row['speed']."</td>
					<td class=\"satellites\">".$row['satellites']."</td>
					<td class=\"pdop\">".$row['pdop']."</td>
					<td class=\"mode\">".$row['mode']."</td>
				</tr>			
			";		
		} // koniec pętli for
		
		echo "</table></div>";
		
		}

?>






