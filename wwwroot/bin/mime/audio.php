<?
Window(
	str_replace(
		array(".audio\n","\n"),'',$APPL['file']['filename']."\n"),
		'/img/form.gif','320','100'
		)
	;

$WEBGUI['win']['app'] = 'WGMediaPlayer';

$x = pathinfo($APPL['file']['path'] ,PATHINFO_EXTENSION);
$ct=false;

if ($x=='radio') {
        $po = WGParseFile($APPL['file']['path']);
	if (!$po['F']) return;
	$st=@file_get_contents($po['f']);
	$st=json_decode($st,true);
	if (!is_array($st)) FatalError("Invalid stream info file");
	$APPL['file']['path']=$st['url'];
	$x=$st['type'];
	if(isset($st['title'])) $WEBGUI['win']['title']=$st['title'];
	}

if ($x=='mp3') $ct='audio/mpeg';
if ($x=='ogg') $ct='audio/ogg';

if ($ct===false) {
?><div style="text-align: center;">
<audio controls autoplay style="width: 94%; margin: 16px auto;">
  <source src="<?=htmlspecialchars($APPL['file']['path'],ENT_QUOTES); ?>.ogg" type="audio/ogg">
  <source src="<?=htmlspecialchars($APPL['file']['path'],ENT_QUOTES); ?>.mp3" type="audio/mpeg">
Your browser does not support the audio element.
</audio></div>
<?
} else {
?><div style="text-align: center;">
<audio controls autoplay style="width: 94%; margin: 16px auto;">
  <source src="<?=htmlspecialchars($APPL['file']['path'],ENT_QUOTES); ?>" type="<?=$ct ?>">
Your browser does not support the audio element.
</audio></div>
<?
}
endWin();
?>
