<?
//write to txt 
$file = fopen("catch.txt", "a");
fwrite($file, print_r($_REQUEST,true));
fclose($file);
?>