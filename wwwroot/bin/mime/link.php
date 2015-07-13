<?
$po = WGParseFile($APPL['file']['path']);
if (!$po['D'] and !$po['F']) return;
if (!WGisAllowed($po['w'])) FatalError("Access denied");

$js=@file_get_contents($po['f']);

if ($js===false) FatalError("Invalid Link");
$js=trim($js,"\t\r\n ");
if ($js=='') FatalError("Invalid Link");

$CMD[] = array(
	'api'	=>	'shell',
	'data'	=>	$js)
	; 	 
?>
