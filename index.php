<?php
include 'cours.php';
include 'style.php';
$listSalle = array();

array_push($listSalle, new salle('TD4','2375'));
array_push($listSalle, new salle('P22','2358'));
array_push($listSalle, new salle('C03','2334'));
array_push($listSalle, new salle('C05','2354'));
array_push($listSalle, new salle('TD1','2361'));
array_push($listSalle, new salle('C08','2356'));
array_push($listSalle, new salle('P20','2357'));

array_push($listSalle, new salle('I01','2379'));
array_push($listSalle, new salle('I02','2378'));
array_push($listSalle, new salle('I03','6299'));
array_push($listSalle, new salle('P26','2398'));
array_push($listSalle, new salle('TD2','2377'));
array_push($listSalle, new salle('TD3','2362'));

array_push($listSalle, new salle('C06','2391'));
array_push($listSalle, new salle('C07','1610'));
array_push($listSalle, new salle('P05b','2394'));

$list = array();

if (htmlspecialchars($_GET["date"])){
	$date = $_GET["date"].' '.$_GET["time"];
}else{
	$date = date("Y-m-d H:i");
}
	echo '</br><h3>Disponibilté pour le '.$date.'</h3></br></br></br>';


for ($s = 0; $s < sizeof($listSalle); $s++) {

	$salle = array();

	$handle = fopen("http://edt.univ-lemans.fr/jsp/custom/modules/plannings/anonymous_cal.jsp?resources=".$listSalle[$s]->id."&projectId=1&calType=ical&nbWeeks=1", "r");

	if ($handle) {
		while (($line = fgets($handle)) !== false) {
		if(substr( $line, 0, 7 ) === "DTSTART"){
			if(intval(substr($line,17,2)+1) < 10){
			$salle[sizeof($salle)-1]->start= substr($line,8,4)."-".substr($line,12,2)."-".substr($line,14,2)." 0".intval(substr($line,17,2)+1).":".substr($line,19,2);
			}else{
			$salle[sizeof($salle)-1]->start= substr($line,8,4)."-".substr($line,12,2)."-".substr($line,14,2)." ".intval(substr($line,17,2)+1).":".substr($line,19,2);
			}
			//echo ' s-> '.$salle[sizeof($salle)-1]->start.'</br>';
		}
		if(substr( $line, 0, 5) === "DTEND"){
			if(intval(substr($line,15,2)+1) < 10){
			$salle[sizeof($salle)-1]->end= substr($line,6,4)."-".substr($line,10,2)."-".substr($line,12,2)." 0".intval(substr($line,15,2)+1).":".substr($line,17,2);
			}else{
			$salle[sizeof($salle)-1]->end= substr($line,6,4)."-".substr($line,10,2)."-".substr($line,12,2)." ".intval(substr($line,15,2)+1).":".substr($line,17,2);
			}
			//echo ' e-> '.$salle[sizeof($salle)-1]->end.'</br>';
			//$salle[sizeof($salle)-1]->end= substr($line,6,4)."-".substr($line,10,2)."-".substr($line,12,2)." ".substr($line,15,2).":".substr($line,17,2) ;
		}
		if(substr( $line, 0, 7 ) === "SUMMARY"){
			$salle[sizeof($salle)-1]->name= str_replace("'", "", substr( $line, 8, strlen($line)) );
		}
		if(substr( $line, 0, 12 ) === "BEGIN:VEVENT"){
			//echo 'New event for '.$listSalle[$s].'</br>';
			array_push($salle,new cours());
		}
		if(substr( $line, 0, 11 ) === "DESCRIPTION"){
			$tab = explode ( "\\n" , str_replace("\\n\\n", " ", substr( $line, 12, strripos($line, "(Exporte")-12	 )));
			
			if(substr($tab[0],0,1) === "\s"){
				$tab[0] = substr($tab[0],1,sizeof($tab[0]));
			}
			$salle[sizeof($salle)-1]->group=  $tab[0];
			$salle[sizeof($salle)-1]->teacher=  $tab[1];
		}

		}//while

	    fclose($handle);
	}

	//usort($salle, "cmp");

	array_push($list,$salle);

}

$listSalleDispo = array();

	for ($i = 0; $i < sizeof($listSalle); $i++) {
		if(inClass($list[$i], $date) == 0){
			array_push($listSalleDispo, new salleDispo($listSalle[$i], nextClass($list[$i],$date )));
		}else{
			//echo 'la '.$listSalle[$i].' n\'est libre</br>';
		}
	}
	
	usort($listSalleDispo, "cmpSD");

	for ($i = 0; $i < sizeof($listSalleDispo); $i++) {
			echo $listSalleDispo[$i].'</br>';
	}

	echo '</br></br></br></br>'.sizeof($listSalleDispo).' salles dispo sur '.sizeof($listSalle);

echo '</br></br><form action="index.php" method="get">
        <input type="date" name="date" value="'.explode(' ',$date)[0].'">
        <input type="time" name="time" value="'.explode(' ',$date)[1].'">
        <p><input type="submit" value="OK"></p>
        </form>';
/*
echo'<div class="footer"><p>pour specifier une date ajouter `?date=AAAA-MM-DD hh:mm`</p></div></body>';
*/

	//echo '</br> pour specifier une date ajouter `?date=AAAA-MM-DD hh:mm`';
?>
