<?php
setshowSolution($_GET["uuid"], "");


function setshowSolution($uuid. $prefix) {
  file_put_contents("./protokoll/".$prefix."-".$uuid.".txt", date ( DATE_RFC822 )." pokazal sobie rozwiazanie"."\r\n", FILE_APPEND);
  return;
}

?>
