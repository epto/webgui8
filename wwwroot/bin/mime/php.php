<?
Window($APPL['file']['filename'],'/img/form.gif','630','400');
$po = WGParseFile($APPL['file']['path']);
if (!$po['F']) return;
include $po['f'];
endWin(); 
?>
