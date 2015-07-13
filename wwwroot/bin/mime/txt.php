<?
Window($APPL['file']['filename'],'/bin/mime/ico/txt.gif','565','300');
$po = WGParseFile($APPL['file']['path']);
if (!$po['F']) return;
$txt = @file_get_contents($po['f']);
$txt = mb_convert_encoding($txt,'UTF-8','UTF-8,ISO-8859-1');
$txt=explode("\n",$txt);
foreach($txt as &$li) $li=rtrim($li,"\t\r ");
$txt=implode("\n",$txt);
$txt = str_replace("\r\n","\n",$txt);
$txt = wordwrap($txt,80,"\n",false);
$txt = explode("\n",$txt);
foreach($txt as &$li) $li=rtrim($li,"\t\r ");
$txt=implode("\n",$txt);
$txt = htmlspecialchars($txt,ENT_QUOTES);
?><div style="height: 100%; overflow-x: hidden; overflow-y: scrollbar;">
<pre style="font-size: 11px; margin: auto;"><?=$txt ?></pre>
</div>
<? endWin(); ?>
