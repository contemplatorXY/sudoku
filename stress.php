<!--
/**
 *
 * Online Sudoku by Michael Jentsch
 *
 * Name: PHP Sudoku
 * Version: 1.0
 * Autor: Michael Jentsch <M.Jentsch@web.de>
 * Webseite: http://www.m-software.de/
 * Lizenz: LGPL 2.0
 * 2021-05-17 Erweiterung für Diplomarbeit und Durchführung eines psychologischen Tests mit 20 Probanden
 * Maja Kruszelnicka
 *
 **/

 -->
<html>
<head>
<title>Sudoku ze stresem</title>
<script language="JavaScript" src="sudoku.js" type="text/javascript"></script>
<link rel="STYLESHEET" type="text/css" href="sudoku.css">
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
p {
  text-align: center;
  font-size: 60px;
  margin-top: 0px;
  background-color: red;
}
</style>
</head>
<body>
<?
$ok = null;
$isErgebnis = false;
?>
<div id=sudoku>
<table border=0>
<tr><td>
<center><h1 class=sudoku><? echo $_POST["ergebnis"] != null ? "Dziekuje!" : "Witaj ".getUserName($_GET["uuid"]) ?></h1></center>
</td></tr>
<tr><td><center>
<?
switch($_SERVER['REQUEST_METHOD'])
{
case 'GET':
      $ok = getCredentials($_GET["uuid"]);
      if ($ok != null) { ?>
<p style="color:white" id="countdownclock"></p>
<? }
      break;
case 'POST':
      $wyniktext = "";
     // isStop = true;
      $isErgebnis = true;
      $ok=null;
      $restime = round(microtime(true)) - $_POST["beginn"];
      if ($_POST["ergebnis"] == "true") {
        $wyniktext = "rozwiazanie poprawne w czasie ".$restime." sekund";
      } else {
      	$wyniktext = "rozwiazanie niepoprawne w czasie ".$restime." sekund";
      }
	  echo "za udzial w eksperymencie ".getUserName($_POST["uuid"]).".\r\nTwoj wynik: ".$wyniktext;
	  setSpielEnde("Wynik:".date ( DATE_RFC822 )." "."czas: ".$restime." sekund"." wynik: ".$_POST["ergebnis"]);
      break;

default:
}

?>



<script language="JavaScript">
<!--

function countdown(value) {

// Set the date we're counting down to
var countDownDate = new Date().getTime() + (value*1000);

// Update the count down every 1 second
var x = setInterval(function() {

  // Get today's date and time
  var now = new Date().getTime();

  // Find the distance between now and the count down date
  var distance = countDownDate - now;

  // Time calculations for days, hours, minutes and seconds
  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);

  // Output the result in an element with id="demo"
  document.getElementById("countdownclock").innerHTML = hours + "h "
  + minutes + "m " + seconds + "s ";

  // If the count down is over, write some text
  if (distance < 0) {
    clearInterval(x);
   document.getElementById("countdownclock").innerHTML = "Czas sie skonczyl :-(";
   document.getElementById("showsolution").type = "button";
   alert("jesli chcesz zobaczyc rozwiazanie, kliknij na 'pokaz rozwiazanie'")


  }
}, 1000);

}


function StartClock() {

        countdown(18);
}

StartClock();

//-->
</script>

</center></td></tr>
<tr><td>
<?
function getSudoku ()
{
	// Fast and simple solution for big files
	$ls = 164;
	$filename = "sudoku.txt";
	$size = filesize ($filename);
	$lines = $size / $ls;
	$rand = rand(0, $lines);
	$handle = fopen ($filename, "r");
	$pos = $ls * $rand;
	fseek ($handle, $pos, SEEK_SET);
	$contents = fread ($handle, $ls);
	fclose ($handle);
	return $contents;
}

function getCredentials ($uuid)
{
	$filename = "credentials.txt";
	$handle = fopen ($filename, "r");
	$protokoll = null;

	while (!feof($handle)) {

       $line = trim(fgets($handle));
       //if (substr($line,0,1) != "#") {
       		$parts = explode(";",$line);
       		if ($uuid == $parts[0]) {
           		file_put_contents("./protokoll/"."stress-".$parts[2].".txt", "start:".date ( DATE_RFC822 )."\r\n", FILE_APPEND);
           		return $parts[0];
       		}
       	//}
    }
	return null;
}

function getUserName ($uuid)
{
	$filename = "credentials.txt";
	$handle = fopen ($filename, "r");
	while (!feof($handle)) {

       $line = trim(fgets($handle));
       $parts = explode(";",$line);
       if ($uuid == $parts[0]) {
           return $parts[1];
       }
    }
	return null;
}
function getUserName2 ($uuid)
{
	$filename = "credentials.txt";
	$handle = fopen ($filename, "r");
	while (!feof($handle)) {

       $line = trim(fgets($handle));
       $parts = explode(";",$line);
       if ($uuid == $parts[0]) {
           return $parts[2];
       }
    }
	return null;
}

function setSpielEnde($wynik) {
  file_put_contents("./protokoll/"."stress-".getUserName2($_POST["uuid"]).".txt", $wynik."\r\n", FILE_APPEND);
  return;
}




if ($ok != null) {
	$sudokustr = getSudoku ();
	$sudoku    = explode(";", $sudokustr);

	echo "<table cellspacing=0 cellpadding=1 border=0 bgcolor=#000000>";
	$count = 0;
	for ($x = 0; $x < 9; $x++)
	{
		echo "<tr>";
		for ($y = 0; $y < 9; $y++)
		{
			echo "<td><div class=cell>";
			$data = "";
			if ($y == 2 || $y == 5)
			{
				$border = "border-right:2px solid #000000;";
			} else {
				$border = "";
			}
			if ($x == 2 || $x == 5)
			{
				$border .= "border-bottom:2px solid #000000;";
			}
		// if (strlen ($sudoku[$count]) > 0 && $sudoku[$count] != " ")
			if (intval($sudoku[$count]) > 0 )
			{
				$data = "value='" . $sudoku[$count] . "' readonly style='background:#DDDDDD; " . $border . "'";
			} else {
				$data = " style='" . $border . "'";
			}
			echo "\r\n<input valign=middle type=text id=i" . $count . " name=i" . $count . " " . $data . " size=5 maxlength=5 class=cell onkeyup='fontsize(this, this.value)'>\r\n";
			echo "</div></td>";
			$count ++;
		}
		echo "</tr>";
	}
	echo "</table>";
 } else {

     echo "\r\n";
     if ($isErgebnis == false) {
     	echo "nie jestes upowazniona/y do czytania tej strony";
     }

 }



if ($ok != null) {
?>
</td></tr><tr><td height=28 valign=bottom>
<center>
<form method=post>
<nobr>
<input type="button" value="Sprawdz" onclick="checkMySudoku()" style="font-size : 24px; width: 50%; height: 150px;">
<input type="submit" value="Gotowe" onclick="checkMySudoku2();isStop=true;" style="font-size : 24px; width: 50%; height: 150px;"></nobr>
<input type="hidden" name="uuid" id="uuid" value=<? echo "'".$_GET["uuid"]."'"; ?> >
<input type="hidden" name="ergebnis" id="ergebnis" value="" >
<input type="hidden" name="beginn" id="beginn" value=<? echo "'".round(microtime(true))."'"; ?> >
<input type="hidden" name="ende" id="ende" value=<? echo "'".$_GET["uuid"]."'"; ?> >
<input type="hidden" id="showsolution" value="Pokaz rozwiazanie" onclick="solveMySudoku()" style="font-size : 24px; width: 50%; height: 150px;">
</form></center>
</td></tr>

</table>
<table width=100% border=0 cellspacing=0 cellpadding=0>
<tr><td height=28 valign=top><a class=sudoku target=_blank href=instrukcja.html>Instrukcja</a></td><td align=right valign=bottom>
<tr><td height=28 valign=top><a class=sudoku target=_blank href=https://docs.google.com/forms/d/e/1FAIpQLSd_xqJuy5Bo2q8JtluW9VdGqNTzVkYE5G9cm7uG6kcHc8ikxA/viewform>Ankieta</a></td><td align=right valign=bottom>

<a class=sudokumin href='#'>Sudoku by IBG</a></td>
</table>
</div>
<? } ?>
</body>
</html>
