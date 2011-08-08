<?php



	// sprawdź ile maksymalnie pozycji ma być wyświetlanych na jednej stronie
	if (isset($_GET['pozycje_limit'])) 
		$pozycje_limit=(int)($_GET['pozycje_limit']);
	else $pozycje_limit=$limityMozliwe[0];


	// sprawdź od której pozycji zacząć wyświetlać
	if (isset($_GET['pozycje_start'])) 
		$pozycje_start=(int)($_GET['pozycje_start']-1);
	else $pozycje_start=0;


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




<!-- zmiana ustawień; KALENDARZ -->
<form method="get" action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>">

	<table>
	<tr>
		<td><b>od:</b></td>
		<td><script type="text/javascript">DateInput('data1', true, 'YYYY-MM-DD' <?php if($data1) echo ', \''.$data1.'\''; ?>)</script></td>
		<td><input name="godzina1" type="text" size="5" maxlength="5" value="<?php if($godzina1) echo $godzina1; else echo '06:00'; ?>" /></td>
	</tr>
	<tr>
		<td><b>do:</b></td>
		<td><script type="text/javascript">DateInput('data2', true, 'YYYY-MM-DD' <?php if($data2) echo ', \''.$data2.'\''; ?>)</script></td>
		<td><input name="godzina2" type="text" size="5" maxlength="5" value="<?php if($godzina2) echo $godzina2; else echo '23:59'; ?>" /></td>
	</tr>
	</table>

	<input type="hidden" name="show" value="view_table" />
	<input type="submit" value="zmień ustawienia" />
</form>





<?php

	connectToMySql();

	// ilość wszystkich rekordów w bazie
	$rowsAll=countRowsAll();


	// jeżeli wszystkie wartości podane to wyświetlamy wg. kalendarza
	if($data1 && $data2 && $godzina1 && $godzina2)
		$kalendarz=1;
	else
		$kalendarz=0;


	// wspólna część zapytania
	$query='SELECT * FROM '._DB_TABLE.' ';


	// tworzymy resztę zapytania zależnie od tego co wyświetlić
	if($kalendarz) {
		
		$query2='WHERE date >= \''.$data1.' '.$godzina1.'\' AND date <= \''.$data2.' '.$godzina2.'\' ';
		// znajdź ilość rekordów spełniających warunki
		$rowsFounded=countRows($query2);
	} else {

		$query2='';
		// nie ma warunków, wyświetlamy więc wszystkie rekordy (po np. 100 na stronę)
		$rowsFounded=$rowsAll;
	}
	
	
	// złącz ostateczne zapytanie
	$query.=$query2;
	$query.='ORDER BY id DESC LIMIT '.($pozycje_start*$pozycje_limit).' , '.$pozycje_limit;


	$result=mysql_query($query);
	if (!$result) {
		echo 'Błąd. Połączenie nie powiodło się!';
		exit;
	}
		


	// jeżeli brak wyników to odpowiedni komunikat
	if (!$rowsFounded) { 
		
		echo '<h2>BRAK DANYCH DO WYŚWIETLENIA <br />';
		
		// jeżeli nie ma żadnych danych w bazie
		if (!$rowsAll)
			echo 'BAZA DANYCH JEST PUSTA</h2>';
		// dane w bazie są, trzeba zmienić zapytanie
		else {
			echo 'ZMIEŃ USTAWIENIA</h2>';
			echo '<h3>wszystkich pozycjiw bazie: '.$rowsAll.'</h3>';
		}
	}
	// są rekordy do wyświetlenia
	else {

		// jeżeli korzystaliśmy z kalendarza to wyświetl krótkie info
		if($kalendarz){
			echo '
				<p class="center">
				znalezionych pozycji: <strong>'.$rowsFounded.' z '.$rowsAll.'</strong><br />
				od <strong>'.$data1.' '.$godzina1.'</strong> do <strong>'.$data2.' '.$godzina2.'</strong><br />
				<br />
				<a href="'.htmlentities($_SERVER['PHP_SELF']).'?show=view_table" class="link1">
				przejdź do wyświetlanie wszystkich pozycji</a>
				<br /><br />
				</p>
			';
		} else {
			echo '
				<p class="center">wyświetlanie wszystkich pozycji <strong>('.$rowsAll.')</strong><br /><br /></p>
			';
		}
	
		echo '
			<div id="przegladaj">
			<table>
				<tr>
					<th title="liczba porządkowa">lp.</th>
					<th title="numer ID w tabeli">id</th>
					<th title="znacznik daty (date)">data</th>
					<th title="szerokość geograficzna (latitude)">szer. geo.</th>
					<th title="długość geograficzna (longitude)">dł. geo.</th>
					<th title="wysokość w m. n.p.m. (altitude)">wysokość</th>
					<th title="prędkość w km/h (speed)">prędkość</th>
					<th title="ilość użytych satelitów (satellites)">satelity</th>
					<th title="precyzja dla trzech wpsółrzędnych">pdop</th>
					<th title="tryb odbieranych danych, D=DGPS">tryb</th>
				</tr>
		';
				
						
							
		// wyświetl pozycje
		for ($i=1; $row=mysql_fetch_array($result); ++$i) {
				
			echo '
				<tr>
					<td class="nr">'.$i.'</td>
					<td class="id">'.$row['id'].'</td>
					<td class="date">'.$row['date'].'</td>
					<td class="latitude">'.$row['latitude'].'</td>
					<td class="longitude">'.$row['longitude'].'</td>
					<td class="altitude">'.$row['altitude'].'</td>
					<td class="speed">'.$row['speed'].'</td>
					<td class="satellites">'.$row['satellites'].'</td>
					<td class="pdop">'.$row['pdop'].'</td>
					<td class="mode">'.$row['mode'].'</td>
				</tr>			
			';		
		} // koniec pętli for
		
		echo '</table></div>';
		
		
		
		// wspólny początek dla przesyłania GET'em 
		$get1='?show=view_table&pozycje_limit='.$pozycje_limit;
		if($kalendarz){
			$get1.='&data1='.$data1.'&godzina1='.$godzina1.'&data2='.$data2.'&godzina2='.$godzina2;
		}
		
		echo '	<div class="przewijaj_tabele">
				<p class="center">
		';


		// jeżeli mamy więcej znalezionych rekordów niż może się zmieścic na 1 stronie
		if($rowsFounded > $pozycje_limit){
			
			$iloscStron=(int)($rowsAll/$pozycje_limit)+1;
			
			
			/* jeżeli stron jest dużo nie wysztkie zostaną wyświetlone jako linki
			 * tutaj dajemy warunek żeby zawsze wyświetlał link do począteku (strona 1)
			 */
			if($pozycje_start>=$stronyOdstep+1)
				echo '<a href="'.htmlentities($_SERVER['PHP_SELF']).''.$get1.'&pozycje_start=1" class="link3">1</a>';
			if($pozycje_start>=$stronyOdstep+2)
				echo '<strong> &nbsp;...&nbsp; </strong>';
			
			
			
			/* "przewijanie" na dalsze strony tabeli
			 * wyświetl linki tylko do stron o zadanym odstępie na plus i na minus
			 */
			for(	$i = ($pozycje_start>$stronyOdstep)? ($pozycje_start-$stronyOdstep+1) : 1; 
					($i <= $pozycje_start+$stronyOdstep+1) && ($i <= $iloscStron); 
					++$i) 
			{
				
				// jeżeli to nie jest aktualna strona
				if($i != $pozycje_start+1) {		
					// reszta GET'a
					$get2='&pozycje_start='.$i;
					echo '<a href="'.htmlentities($_SERVER['PHP_SELF']).''.$get1.''.$get2.'" class="link3">'.$i.'</a>';
				} else
					// aktualna strona, więc bez odnośnika
					echo '<strong> '.$i.' </strong>';
			}
			
			
			/* tutaj dajemy warunek żeby zawsze wyświetlał link do końca (ostatnia strona)
			 */
			if($pozycje_start<=$iloscStron-$stronyOdstep-3)
				echo '<strong> &nbsp;...&nbsp; </strong>';
			if($pozycje_start<=$iloscStron-$stronyOdstep-2)
				echo '<a href="'.htmlentities($_SERVER['PHP_SELF']).''.$get1.'&pozycje_start='.$iloscStron.'" class="link3">'.$iloscStron.'</a>';
			
			echo '<br/ ><br />';
			
			
		}
		

		// zmiana ilości wyświetlanych pozycji na stronie
		echo 'wyświetlaj po:';		
		for ($i=0, $cnt=count($limityMozliwe); $i < $cnt ; ++$i) 
		{
			if($limityMozliwe[$i] == $pozycje_limit)
				echo '<strong>&nbsp;&nbsp;'.$limityMozliwe[$i].'&nbsp;&nbsp;</strong>';
			else	
				echo '	<a href="'.htmlentities($_SERVER['PHP_SELF']).''.$get1.'&pozycje_start=1&pozycje_limit='.$limityMozliwe[$i].'" class="link3">'
						.$limityMozliwe[$i].'</a>
				';
		}


		echo '</p></div>';	// div .przewijaj_tabele

		
	}


?>






