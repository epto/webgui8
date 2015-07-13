<?
	
$MIME=WGLoadMIME();

$po = WGParseFile($APPL['file']['path']);
if (!$po['D']) return;
$dh=opendir($po['f']);
if (!$dh) return;

Window($APPL['file']['filename'],'/img/form.gif','600','400');
$ls=array();
$ax=0;
$ld=array();  
 
$canUP=rtrim($APPL['file']['path'],'/');
$canUP=explode('/',$canUP);
$cx=count($canUP);

if ($cx>1) {
	$canUP[$cx-1]='';
	$canUP=implode('/',$canUP);
	$canUP=rtrim($canUP,'/');
	if (preg_match('/\\.dir$/',$canUP)==0) $canUP=false;
	} else $canUP=false;
     
if ($canUP) {
	$ld['0000']=array(
	    'i'	=>	'<img src="/bin/mime/ico/dir.gif">',
		'f'	=>	'..',
	    's'	=>	'-',
		'd'	=>	'Parent dir.',
		'ex'=>	" ondblclick=\"WGTargetSelf(this,'".htmlspecialchars($canUP.'/'.$fi,ENT_QUOTES)."');\" ontouchstart=\"WGTargetSelf(this,'".htmlspecialchars($canUP.'/'.$fi,ENT_QUOTES)."');\"")
		;	
	}     
         
$fSpec=array();
$t0 = $po['f'].'/.dirInfo';
if (file_exists($t0)) {
	$t0=@file_get_contents($t0);
	$lfi='';
	if ($t0!==false) {
		$t0=explode("\n",$t0);
		foreach($t0 as $li) {
			$li=trim($li,"\t\r\n ");
			if ($li=='') continue;
			$li=WGTokenLine($li);
			if (count($li)<2) continue;
			$cm=strtolower($li[0]);
			$cf=$li[1];
			if ($cf=='.') $cf=$lfi; else $lfi=$cf;
			if ($cm=='t' or $cm=='title') {
				$WEBGUI['win']['title'] = $li[1];
			        continue;
				}
				
			if ($cm=='wi' or $cm=='win-icon') {
				$WEBGUI['win']['ico'] = $li[1];
			        continue;
				}
				
			if ($cf=='*') $cf=0; else $cf=crc32($cf);
			if (!isset($fSpec[$cf])) $fSpec[$cf]=array();
			if ($cm=='d' or $cm=='download') $fSpec[$cf]['download']=true;
			if ($cm=='i' or $cm=='icon') $fSpec[$cf]['i'] ="" . @$li[2];
			}
	        
		}
	}	 
	           
while($fi = readdir($dh)) {
	if ($fi[0]=='.') continue;
	$f = $po['f'].'/'.$fi;
	$p=pathinfo($f);
	$id=$p['filename'].$ax;
	$ax++;
	$of=$f;
	$dd=is_dir($of);
	$t0=crc32($fi);
	if (isset($fSpec[0]) and !$dd and !$fSpec[$t0]) {
	        $cSpec=array('download' => true);
		} else { 
		$cSpec = $fSpec[$t0] ? $fSpec[$t0] : array();
		} 
	
	if ($dd) {
		$ex=" ondblclick=\"WGTargetSelf(this,'".htmlspecialchars($po['w'].'/'.$fi,ENT_QUOTES)."');\" ontouchstart=\"WGTargetSelf(this,'".htmlspecialchars($po['w'].'/'.$fi,ENT_QUOTES)."');\"";
		} else {
		$ex=" ondblclick=\"shellExecute('".htmlspecialchars($po['w'].'/'.$fi,ENT_QUOTES)."');\" ontouchstart=\"shellExecute('".htmlspecialchars($po['w'].'/'.$fi,ENT_QUOTES)."');\"";
		}

	$a = array(
	    'i'	=>	'<img src="/bin/mime/ico/exec.gif">',
		'f'	=>	htmlspecialchars($fi,ENT_QUOTES),
	    's'	=>	@filesize($of),
		'd'	=>	'-')
		;
		
	if ($a['s']===false or $dd) $a['s']='-'; else {
		if ($a['s']<1024) $a['s'].=' Bytes'; else if ($a['s']<1048577) $a['s'] = round($a['s']/1024,2).' KB'; else $a['s']=round( $a['s']/1048576 , 2).' MB';
		}
	   	
	$x = WGgetMimeName($cSpec['download'] ? 'down' : $p['extension'],$dd);
	if ($x) {
		$a['d'] = htmlspecialchars($x['n'],ENT_QUOTES);
		if ($x['i'][0]=='*') {
			$x['i']= $po['w'].'/'.$fi.'/'.substr($x['i'],1);
			if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$x['i'])) $x['i']='exec.gif';
			}
			
		if ($x['i'][0]!='/') $x['i']='/bin/mime/ico/'.$x['i'];
		
		$a['i'] = '<img src="'.htmlspecialchars($x['i'],ENT_QUOTES).'">';
		if ($x['download']) {
		        $a['j']=$po['w'].'/'.$fi;
		        $ex='';
			}
		}
	
	if ($p['extension']!='app' and !isset($MIME[$p['extension']]) and !$cSpec['download']) {
		$a['d']='(Not exec.)';
		$ex='';
		$a['f']=htmlspecialchars($fi,ENT_QUOTES);
		$a['i']='<img src="/bin/mime/ico/noexec.gif">';
		} 
	
	if ($cSpec['i']) {
	        $a['i'] = '<img src="'.htmlspecialchars($cSpec['i'],ENT_QUOTES).'">';
		}
	
	$a['ex']=$ex;
	if ($dd) $ld[$id]=$a; else $ls[$id]=$a;
	}
	
closedir($dh);

ksort($ls);
ksort($ld);
?>
<table class="WGTableList" style="width: 100%; margin: 0px;">
<?
foreach($ld as $a) {
	echo '<tr onclick="WGSetDivFocus(this);">';
	$x=$a['ex'];
	unset($a['ex']);
	unset($a['j']);
 	foreach($a as $v) echo '<td'.$x.'>'.$v.'</td>' ;
 	echo '</tr>';
	}

foreach($ls as $a) {
	echo '<tr onclick="WGSetDivFocus(this);">';
	$x=$a['ex'];
	unset($a['ex']);
	if (@$a['j']) {
		foreach(array('i','f') as $K) {
	        	$v=$a[$K];
			$v='<a href="'.htmlspecialchars($a['j'],ENT_QUOTES).'" target="_blank">'.$v.'</a>';
			$a[$K]=$v;
	        	}
		}
	unset($a['j']);
	foreach($a as $v) echo '<td'.$x.'>'.$v.'</td>' ;
 	echo '</tr>';
	}
	
?></table><?
endWin();
?>
