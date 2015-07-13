<?
$po = WGParseFile($APPL['file']['path']);
if (!$po['F']) return;
$st=@file_get_contents($po['f']);
$st=json_decode($st,true);
if (!is_array($st)) FatalError("Invalid stream info file");

$t=str_replace(array(".youtube\n","\n"),'',$APPL['file']['filename']."\n");
if (isset($st['t'])) $t=$st['t'];

Window($t,"/bin/mime/ico/youtube.png",580,360);
?><div style="width: 561px; height: 316px; margin: auto; padding-top: 4px;">
<iframe width="560" height="315" src="https://www.youtube.com/embed/<?=htmlspecialchars($st['v'],ENT_QUOTES); ?>" frameborder="0" allowfullscreen></iframe>
</div>
<?
endWin();
?>
