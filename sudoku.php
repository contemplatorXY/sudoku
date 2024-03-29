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
 * 2021-05-17 2021-05-17 rozszerzenie: implementacja workflow i autentyfikacji kandydatow
 * Autorka: Maja Kruszelnicka
 *
 **/

 -->
<html lang="pl">
<head>
	<title>Sudoku</title>
	<meta http-equiv='cache-control' content='no-cache'>
	<meta http-equiv='expires' content='0'>
	<meta http-equiv='pragma' content='no-cache'>
	<script language="JavaScript" src="sudoku.js?rndstr=<? echo uniqid(); ?>" type="text/javascript"></script>
	<link rel="STYLESHEET" type="text/css" href="sudoku.css?rndstr=<? echo uniqid(); ?>">
	<meta http-equiv="Cache-Control" content="post-check=0">
	<meta http-equiv="Cache-Control" content="pre-check=0">
	<meta http-equiv="Content-Type" content="text/html; charset=Windows-1250"/>
</head>
<body>
<?php
$clientip = $_SERVER["REMOTE_ADDR"];
$remoteAgent = $_SERVER["HTTP_USER_AGENT"];
$zeigloesung = $_GET["zeigloesung"];
$res = trim($_POST["ergebnis"]);


switch($_SERVER['REQUEST_METHOD'])
{

	case 'GET':
		file_put_contents("./protokoll/clients."."txt",
				date ( DATE_RFC822 ).
				";".$clientip.
				";"."bez-stresu".
				";".$remoteAgent.
				"\r\n", FILE_APPEND);
		break;
	case 'POST':
		$wyniktext = "";
		$nickname=trim($_POST["nickname"]);
        $evaluate = trim($_POST["evaluate"]);
		$uniqueid = trim($_POST["uniqueid"]);
		$sudokunr = trim($_POST["sudokunr"]);

		if (strlen($nickname) > 0 && $evaluate == "True") {
			$isErgebnis = true;
			$ok=null;
			$restime = round(microtime(true)) - $_POST["beginn"];
           // echo "nick ".$nickname." uniqueid ".$uniqueid." evaluate ".$evaluate." wynik ".$res.;
			if ($res == "true") {
				$wyniktext = "rozwi&#261;zanie poprawne w czasie ".$restime." sekund.";
			} else {
				$wyniktext = "rozwi&#261;zanie niepoprawne w czasie ".$restime." sekund.";
			}

			setSpielEnde(
					date ( DATE_RFC822 ).
					";".$clientip.
					";".$nickname.
					";".$uniqueid.
					";"."bez-stresu".
					";".$sudokunr.
					";".$restime.
					";".$res.
					";".$remoteAgent,
					$nickname."_".$uniqueid);
			break;
		}


	default:
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

function setSpielEnde($wynik, $nick) {

	file_put_contents("./protokoll/wyniki".".txt", $wynik."\r\n", FILE_APPEND);
	file_put_contents("./protokoll/uniqueids".".txt", date ( DATE_RFC822 ).";".$nick."\r\n", FILE_APPEND);
}
?>

<div id=sudoku>
	<table border=0>
		<tr><td>
				<center><h1 class=sudoku>Sudoku</h1></center>
				<center><h3 class=sudoku>Eksperyment do pracy licencjackiej</h3></center>
				<center><h3 class=sudoku>sprobuj rozwi&#261;za&#263; sudoku tak szybko, jak potrafisz :)</h3></center>

				<? if ($evaluate == "True") {
					echo "Dzi&#281;kuje za udzia&#322; w eksperymencie ".$nickname.".\r\nTw&#243;j wynik: ".$wyniktext;
					mail ( "ggaida@hotmail.de" , $nickname." rozwiazal sudoku", $wyniktext );
				  }
				?>
			</td></tr>
		<tr><td><center>

					<script language="JavaScript">
						<!--

						var clockID = 0;
						var init = new Date();
						var start = init.getTime();



						function UpdateClock() {
							if(clockID) {
								clearTimeout(clockID);
								clockID  = 0;
							}

							var now = new Date ();
							var nowtime = now.getTime();
							var sec = Math.floor((nowtime - start) / 1000);
							var min = Math.floor((sec / 60));
							var std = Math.floor((min / 60));
							sec = sec % 60;
							min = min % 60;
							if (sec < 10) sec = "0" + sec;
							if (min < 10) min = "0" + min;
							if (std < 10) std = "0" + std;
							document.theClock.theTime.value = std + ":" + min + ":" + sec;

							clockID = setTimeout("UpdateClock()", 100);
						}

						function StartClock() {
							clockID = setTimeout("UpdateClock()", 100);
						}

						StartClock();

						//-->
					</script>

				</center></td></tr>
		<tr><td>
				<?

				function getSudoku ()
				{
					global $sudokunr;
					// Fast and simple solution for big files
					$ls = 164;
					$filename = "sudoku.txt";
					$size = filesize ($filename);
					$lines = $size / $ls;
					$rand = rand(0, $lines);
					$sudokunr = $rand;
					$handle = fopen ($filename, "r");
					$pos = $ls * ($rand -1);
					fseek ($handle, $pos, SEEK_SET);
					$contents = fread ($handle, $ls);
					fclose ($handle);
					return $contents;
				}

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
							echo "\r\n<input valign=middle type=text id=i" . $count . " name=i" . $count . " " . $data . " size=5 maxlength=5 class=cell onkeyup='fontsize(this, this.value)'>\r\n";
						} else {
							$data = " style='" . $border . "'";
							echo "\r\n<input valign=middle type=number id=i" . $count . " name=i" . $count . " " . $data . " size=5 maxlength=5 class=cell onkeyup='fontsize(this, this.value)'>\r\n";
						}

						echo "</div></td>";
						$count ++;
					}
					echo "</tr>";
				}
				echo "</table>";
				?>
			</td></tr><tr><td height=28 valign=bottom>
				<center><form method=post id="sudokuform" name="sudokuform">
						<nobr>
							<!--nobr><input type="submit" value="Inne Sudoku" style="font-size : 24px; width: 50%; height: 150px;"-->
							<input type="button" value="Sprawd&#378;" onclick="checkMySudoku()" style="font-size : 24px; width: 50%; height: 150px;"></nobr>
							<br><br><label style="font-size : 18px; width: 50%; height: 150px;">Tw&#243;j nick name - zapami&#281;taj koniecznie na drugi test:</label><br><br>
							<input type="text" border-radius="2px;" name="nickname" id="nickname" value=""  style="font-size : 18px; width: 50%; height: 50px;">
							<br><br><br><input type="submit" value="Gotowe" onclick="checkMySudoku2()" style="font-size : 24px; width: 50%; height: 150px;"><br><br>
							<!-- easter egg -->
						     <?
								if ($zeigloesung == "True") { ?>
									<input type="button" value="Poka&#380; rozwi&#261;zanie" onclick="solveMySudoku()">
							<? }
							?>
						    <input type="hidden" name="uniqueid" id="uniqueid" value="<? echo uniqid(); ?>" >
						    <input type="hidden" name="sudokunr" id="sudokunr" value="<? echo $sudokunr; ?>" >
							<input type="hidden" name="ergebnis" id="ergebnis" value="false" >
						    <input type="hidden" name="evaluate" id="evaluate" value="false" >
							<input type="hidden" name="beginn" id="beginn" value=<? echo "'".round(microtime(true))."'"; ?> >
					</form></center>
			</td></tr>
	</table>

	<table width=100% border=0 cellspacing=0 cellpadding=0>
		<tr>
			<td height=28 valign=top><a class=sudoku href=instrukcja.html>Instrukcja</a></td>
			<td align=right valign=bottom><a class=sudokumin href='http://ibgaida.de/sudoku'>Sudoku by Maja Kruszelnicka</a></td>
			<!-- td class=sudokumin >sudoku <? echo $sudokunr ?> </td-->
	</table>
	<!-- //
	I request you retain the full copyright notice below including the link to www.m-software.de.
	This not only gives respect to the amount of time given freely by the developers but also helps
	build interest, traffic and use of phpSudoku. If you cannot (for good reason) retain the full
	copyright we request you at least leave in place the phpSudoku by M-Software line, linked to
	www.m-software.de. Michael Jentsch: 2007
// -->
</div>

</body>
</html>
