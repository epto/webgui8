<?

define('POX_NORMAL',0);
define('POX_CENX',1);
define('POX_CENY',2);
define('POX_CENTER',3);
define('POX_RIGHT',4);
define('POX_BOTTOM',8);
define('POX_RB',12);

define('WIN_NORMAL',1);
define('WIN_NOSIZE',2);
define('WIN_POPUP',4);
define('WIN_DIALOG',8);

define('WS_NORMAL',0);
define('WS_MINIMZED',1);
define('WS_MAXIMIZED',2);

$WEBGUI=array();

function LIBDBERROR($num,$st) {
    @ob_get_clean();
    ob_start();
    echo "\nLIBDB: [ ".dechex($num)." ] $st\n" . mysql_error()."\n";
    debug_print_backtrace();
    die(json_encode(array("err" => ob_get_clean())));
  }
   
function WGSNew($url=false,$app=false) {
	if (!isset($_SESSION['WGS'])) {
		mt_srand(crc32(microtime().mt_rand(1000,9999999)));
		
		$_SESSION['WGS'] = array(
			'maxHdc'	=>	mt_rand(1,50000)<<1,
			'xorHdc'	=>	mt_rand(1,100000),
			'lst'		=>	array()	,
			'hdc'		=>	array()	)
			;
		}

	$idc = ++$_SESSION['WGS']['maxHdc'];
	$idc^=$_SESSION['WGS']['xorHdc'];
	if ($idc==0) $idc=1;
	
	$_SESSION['WGS']['idx'][$idc]=1;
	
	$_SESSION['WGS']['hdc']['h'.$idc] = array(
		'u'	=>	$url,
		'a'	=>	$app,
		's'	=>	false,
		'd'	=>	array())
		;
	
	return $idc;
	}  
  
 function WGSLoad($hdc,$url=false,$app=false) {
	 if (!isset($_SESSION['WGS'])) FatalError("WGSLoad: Session lost");
	 if (!isset($_SESSION['WGS']['hdc']['h'.$hdc])) FatalError("WGSLoad: Session lost on this application. `$hdc`");
	 if ($url!=false and $url!=$_SESSION['WGS']['hdc']['h'.$hdc]['u']) FatalError("WGSLoad: Invalid session handle $hdc for this URL.");
	 if ($app!=false and $app!=$_SESSION['WGS']['hdc']['h'.$hdc]['a']) FatalError("WGSLoad: Invalid session handle $hdc for this Application.");
	 $x = &$_SESSION['WGS']['hdc']['h'.$hdc]['d'];
	 return $x;
	 }
  
 function WGSSave($hdc,$data) {
	 if (!isset($_SESSION['WGS'])) FatalError("WGSSave: Session lost");
	 if (!isset($_SESSION['WGS']['hdc']['h'.$hdc])) FatalError("WGSSave: Session lost on this Application.");
	 $_SESSION['WGS']['hdc']['h'.$hdc]['d']=$data;
	 }
  
 function WGSDestroy($hdc) {
	if (isset($_SESSION['WGS'])) {
		unset($_SESSION['WGS']['hdc']['h'.$hdc]);
		unset($_SESSION['WGS']['idx'][$hdc]);
		}
	}
	
function WGSGarbage() {
	global $JSON;
	if (!isset($JSON['Clo']) or !isset($_SESSION['WGS'])) return;
	foreach($JSON['Clo'] as $c) WGSDestroy($c);
	}

function WGStatusBar($html,$height=false) {
	global $WEBGUI;
	if (!isset($WEBGUI['win'])) return false;
	$WEBGUI['win']['statusBar']=array();	
	if ($height) $WEBGUI['win']['statusBar']['h']=intval($height);
	$WEBGUI['win']['statusBar']['html']=$html;
	return true;
	}

function WGSetActivity($act,$hdc=false) {
	global $APPL;
	global $CMD;
	if (!$hdc) $hdc=$APPL['hdc'];
	$CMD[] = array(
		'api'	=>	'activity'	,
		'data'	=>	array(
			'hdc'	=>	$hdc,
			'act'	=>	$act)
			)
		;
	}

/*
 * 
 * name: Create a new window
 * @param title Title of window
 * @param icon Icon file name (web path)
 * @param w Window width.
 * @param h Window height.
 * @param newHDC Create a new window handle and don't use current window handle (open a new window).* 
 * 
 */
 
function WGSetTitle($t) {
	global $WEBGUI;
	if (isset($WEBGUI['win'])) $WEBGUI['win']['title']=$t; else return false;
	return true;
	}
 
function Window($title,$icon='',$w=320,$h=240,$newHdc=false) {
	global $WEBGUI;
	global $APPL;
	endWin();
		
	$WEBGUI['win'] = array(
		'title'	=>	$title	,
		'icon'	=>	array('i'=>$icon),
		'winType'=> WIN_NOSIZE,
		'w'		=>	$w,
		'h'		=>	$h)
		;
	
	if ($newHdc) {
		$hdc = newHDC();
		$WEBGUI['win']['hdc']=$hdc;
		} else {
		$hdc = $APPL['hdc'];	
		}
	}
	
function setWindowPox($pox,$x=false,$y=false,$w=false,$h=false) {
	global $WEBGUI;
	if (!isset($WEBGUI['win'])) return false;
	$WEBGUI['win']['pox'] = $pox;
	
	foreach(array(
			'x' => 0,
			'y' => 0,
			'w' => 320,
			'h' => 240) as $K => $V) {
		
		if ($$K===false) {
			if (!isset($WEBGUI['win'][$K])) $WEBGUI['win'][$K]=$V;
			} else $WEBGUI['win'][$K]=$$K;
		}
		
	return true;
	}	
	
function setWindowMenu($menuObj) {
	global $WEBGUI;
	if (!isset($WEBGUI['win']) or !is_array($menuObj)) return false;
	$WEBGUI['win']['menu']=$menuObj;
	return true;
	}
	
/*
 * Set the curren window as Dialog window if Dialog session is available
 * @return boolean, true if current window is now a dialog.
 */
function setWindowDialog() {
	global $WEBGUI;
	if (!isset($WEBGUI['win']) or !isset($WEBGUI['dialog'])) return false;
	foreach($WEBGUI['dialog'] as $k => $v) {
		$WEBGUI['win'][$k]=$v;
		}
	return true;
	}	
	
function getDialogValue() {
	global $JSON;
	return isset($JSON['val']) ? $JSON['val'] : null;
	}	

function getDialogTag() {
	global $JSON;
	return isset($JSON['tag']) ? $JSON['tag'] : null;
	}	
/*
 * Set all window application parameters.
 * @param hdc Window handel
 * @param app Application name
 * @param url Application URL
 * @return boolean, success.
 * 
 */
function setWindowContext($hdc,$app=false,$url=false) {
	global $WEBGUI;
	if (!isset($WEBGUI['win'])) return false;
	$WEBGUI['win']['hdc']=$hdc;
	if ($app) $WEBGUI['win']['app']=$app;
	if ($url) $WEBGUI['win']['url']=$url;
	return true;
	}
	
function setWindowCoord($name,$x,$y) {
	global $WEBGUI;
	if (!isset($WEBGUI['win'])) return false;
	if (!isset($WEBGUI['win']['coord'])) $WEBGUI['win']['coord']=array('x' => array(), 'y' => array());
	$WEBGUI['win']['coord']['x'][$name]=$x;
	$WEBGUI['win']['coord']['y'][$name]=$x;
	}

function endActivity() {
	global $WEBGUI;
	
	if (isset($WEBGUI['activity'])) {
		echo "</div>";
		unset($WEBGUI['activity']);
		}
	}

function Activity($name='main') {
	global $WEBGUI;
	endActivity();
	$WEBGUI['activity']=$name;
	echo '<div class="WGWActivity" data-wgid="'.htmlspecialchars($name,ENT_QUOTES).'">';
	}

function winJS($js) { 
	global $WEBGUI;
	if (isset($WEBGUI['win'])) {
		if (!isset($WEBGUI['win']['event'])) $WEBGUI['win']['event']=array();
		$WEBGUI['win']['event']['__']=$js;
		}
	}

function winEvent($name,$code) { winFunction($name,"WGWin,WGObj",$code); }

function winFunction($name,$fir,$code,$nadd=false) {
	global $WEBGUI;
	if (!isset($WEBGUI['win'])) FatalError("Can't declare function/event without a window");
	if (!isset($WEBGUI['win']['event'])) $WEBGUI['win']['event']=array();
	
	if (!$nadd) {
		$c='var FUN=this.WGWin.WGEvent; var WIN=this.WGWin; var DATA=this.WGWin.WGData;';
		$code="$c\n$code";
	}
	
	addFunction($WEBGUI['win']['event'],$name,$fir,$code);
	}

function winEventPoint($name,$point) {
	global $WEBGUI;
	if (!isset($WEBGUI['win'])) FatalError("Can't declare function/event without a window");
	if (!isset($WEBGUI['win']['event'])) $WEBGUI['win']['event']=array();
	addFunctionPointer($WEBGUI['win']['event'],$name,$point);
	}

function FatalError($st) {
	global $OUTJSON;
	global $APPL;
	global $CMD;
	global $WEBGUI;
	global $ini;
		
	$WEBGUI=array();
	unset($OUJSON['win']);
	
	if (@$ini['error']['debug'] or @$ini['error']['showid']) {
		$errorID = $APPL['app']."\n".parse_url($APPL['url'],PHP_URL_PATH)."\n$st";
		$errorID = base_convert(abs(crc32($errorID)),10,36).'-'.base_convert(abs(crc32(strtoupper($errorID))),10,36);
		}
		
	if (@$ini['error']['debug'] or @$ini['error']['log']) {
		@ob_get_clean();
		ob_start();
		if (@$ini['error']['args']) debug_print_backtrace(); else debug_print_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
		$dbg=@ob_get_clean();
		}
	
	if (@$ini['error']['log']) {
		$f = $_SERVER['DOCUMENT_ROOT'].'/var/log';
		if (!file_exists($f)) @mkdir($f);
		$f.= '/.error';
		if (!file_exists($f)) @mkdir($f);
		$f.='/'.$errorID.'.log';
		
		if (!file_exists($f)) {
			$t0=str_replace(array('<br>','<br />'),"\n",$dbg);
			$t0=str_replace("\n\n","\n",$t0);
			$t0=strip_tags($t0);
			$t0=html_entity_decode($t0,ENT_QUOTES);
			@file_put_contents($f,date('r')." Error: $st\n".$t0);
			$t0=null;
			}
		}
		
	if (@$ini['error']['showid']) $st="Error $errorID:\n".$st;
	if (@$ini['error']['debug']) $st=$st."\nDebug:\n".$dbg."\n\n\n";
	
	if ($APPL['isRAW']) die(json_encode(array('err'=>$st)));
		
	$OUTJSON['cmd'] = array(
			array(
				'api'	=>	'err',
				'data'	=>	$st	)
				)
			;
			
	if ($APPL['hdc']) $OUTJSON['cmd'][] = array(
		'api'	=>	'close',
		'data'	=>	$APPL['hdc'] )
		;
		
	$CMD=array();
	retGUI();
	}

function WGLockGui($flg=3,$msg=false) {
	global $OUTJSON;
	
	$api = array(
		'api'	=>	'lockGui',
		'data'	=>	array(
			'flg'	=>	$flg	,
			'msg'	=>	$msg	)
			)
		;
		
	if ($flg) {
		$x = array('cmd' => array( $api	) );
		@ob_get_clean();
		header("Content-Type: application/json",true);
		exit(json_encode($x));
		}
		
	$OUTJSON['cmd'][] = $api;
	}

function WGError($st) {
	global $CMD;
		
	$cmd[] = array(
			array(
				'api'	=>	'err',
				'data'	=>	$st	)
				)
			;
	}

function WGLoadDLLEx($lpFile) {
	global $OUTJSON;
	$OUTJSON['cmd'][] = array(
		'api'	=>	'loadDLL',
		'data'	=>	$lpFile)
		;
	}
	
function loadNamedDLL($lpFile,$name=null) {
	global $OUTJSON;
	$OUTJSON['cmd'][] = array(
		'api'	=>	'loadNamedDLL',
		'data'	=>	array(
			'f'	=>	$lpFile,
			'n'	=>	$name)
			)
		;
	
	}
	
function WGUnloadDLL($mod) {
	global $OUTJSON;
	$OUTJSON['cmd'][] = array(
		'api'	=>	'unloadDLL',
		'data'	=>	$mod)
		;
	}
	
function WGLoadDLL($lpFile) {
	global $OUTJSON;
	
	$dl = WGParseFile("/bin/dll/$lpFile");
	if (!$dl['F'] and !$dl['D']) FatalError("Can't load Module DLL `$lpFile`");
	
	if ($dl['D']) {
		$f = $dl['w'].'/dll.js';
		if (file_exists($_SERVER['DOCUMENT_ROOT'].$f)) WGLoadDLLEx($f);
		$f = $dl['w'].'/dll.css';
		if (file_exists($_SERVER['DOCUMENT_ROOT'].$f)) WGLoadDLLEx($f);
		$f = $dl['f'].'/dll.php';
		if (file_exists($f)) require_once $f;
		} 
		
	if ($dl['F']) {
		if ($dl['extension']=='css' or $dl['extension']=='js') WGLoadDLLEx($dl['w']);
		if ($dl['extension']=='php') require_once $dl['f'];
		}
	}
	
function WGChangeTheme($thm) {
	global $OUTJSON;
	
	$dl = WGParseFile("/img/$thm");
	$rs=true;
	if (!$dl['D']) {
		$thm='default';
		$dl = WGParseFile("/img/$thm");
		$rs=false;
		}
	
	if (!$dl['D']) return false;
	$_SESSION['EWGTheme']=$thm;
	
	WGUnloadDLL('theme');
	
	$f = $dl['w'].'/script.js';
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$f)) loadNamedDLL($f,'theme');
	$f = $dl['w'].'/style.css';
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$f)) loadNamedDLL($f,'theme');
	
	$f = '/var/custom.css';
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$f)) loadNamedDLL($f,'custom');
		
	$dp=@file_get_contents($_SERVER['DOCUMENT_ROOT'].'/etc/procset.json');
	$dp=json_decode($dp,true);
	if (!is_array($dp)) $dp=array();
	
	$f = $dl['f'].'/procset.json';
	if (file_exists($f)) {
		$js=@file_get_contents($f);
		$js=json_decode($js,true);
		if (!is_array($js)) FatalError("Invalid ProcSet for theme `$thm`");
		foreach($js as $k => $v) $dp[$k]=$v;
		}
		
	WGProcSet($dp);
	$dp=null;
	WGTheme($thm);
	
	$OUTJSON['cmd'][] = array(
		'api'	=>	'redraw',
		'data'	=>	true)
		;
	
	return $rs;
	}
	
function WGAddCustomControl($lpName) {
	global $OUTJSON;
	$dl = WGParseFile("/bin/wgx/$lpName");
	if ($dl===false) FatalError("Invalid custom control WGX `$lpName`");
	if (!$db['D']) return false;
	$f=$dl['f'].'/wgx.json';
	if (!file_exists($f)) return false;
	$js = @file_get_contents($f);
	$js = json_decode($js,true);
	if (!is_array($js)) FatalError("Invalid WGX custom control `$lpName`");
	if (isset($js['dll'])) {
		foreach($js['dll'] as &$d) {
			$f = "/bin/wgx/$lpName/$d";
			if (!file_exists($_SERVER['DOCUMENT_ROOT'].$f)) FatalError("WGX: `$lpName` Can't load `$d`");
			$d=$f;
			}
		}
		
	$OUTJSON['cmd'][] = array(
		'api'	=>	'wgx',
		'data'	=>	$js)
		;
	}
	
function WGProcSet($procSet) {
	global $OUTJSON;
	$OUTJSON['cmd'][] = array(
		'api'	=>	'procSet',
		'data'	=>	$procSet)
		;
	}

function WGTheme($lpStr) {
	global $OUTJSON;
	$OUTJSON['cmd'][] = array(
		'api'	=>	'theme',
		'data'	=>	$lpStr)
		;
	}
	
function WGCreateStartBar($menu) {
	global $OUTJSON;
	$OUTJSON['cmd'][] = array(
		'api'	=>	'startBar',
		'data'	=>	$menu)
		;
	}	

function WGCloseWin($hdc=false) {
	global $CMD;
	global $WEBGUI;
	global $APPL;
	
	if (!$hdc) {
		if (!isset($WEBGUI['hdc'])) $hdc=@$APPL['hdc']; else $hdc=$WEBGUI['win']['hdc'];
		}
		
	$CMD[] = array(
		'api'	=>	'close',
		'data'	=>	$hdc )
		;
	}

function WGMsgBox($text,$title,$buttons,$ico=false,$onButton=false,$class=false) {
	global $CMD;
	global $APPL;
	global $WEBGUI;
	if (isset($WEBGUI['win']['hdc'])) $hdc=$WEBGUI['win']['hdc']; else $hdc = $APPL['hdc'];
		
	$x = array(
		'win'	=>	$hdc,
		'text'	=>	$text,
		'title'	=>	$title,
		'buttons'=>	$buttons,
		'ico'	=>	$ico)
		;
		
	if ($class) $x['class']=$class;		
	if ($onButton) $x['onButton']=$onButton;
	$CMD[] = array(
		'api'	=>	'msgbox',
		'data'	=>	$x )
		;
	}

function WGGetVars() { 
	global $WEBGUI;
	return isset( $WEBGUI['rvar'] ) ? $WEBGUI['rvar'] : null;
	}

function WGVar($key,$def=null) {
	global $WEBGUI;
	return isset( $WEBGUI['rvar'][$key] ) ? $WEBGUI['rvar'][$key] : $def;
	}

function WGSL($key,$def=null) {
	global $WEBGUI;
	list($key,$m)=explode('_',$key.'_');
	
	$rs = ( $WEBGUI['rvar'][$key] ) ? $WEBGUI['rvar'][$key] : $def;
	if ($m=='') return $rs;
	if ($m=='c') return addcslashes($rs,"\t\r\n\\\"'`&<>");
	if ($m=='ch') return addcslashes(htmlspecialchars($rs,ENT_QUOTES),"\t\r\n\\\"'`&<>");
	if ($m=='hc') return htmlspecialchars(addcslashes($rs,"\t\r\n\\\"'`&<>"),ENT_QUOTES);
	if ($m=='n') return htmlspecialchars($rs,ENT_QUOTES);
	FatalError("Invalid WGSL encoding `$m`");
	}

function WGAppPath($fsys=false) {
	global $WEBGUI;
	if (!isset( $WEBGUI['rvar'] )) return false;
	return ($fsys ? $_SERVER['DOCUMENT_ROOT'] : '' ) .$WEBGUI['rvar']['APPPATH'];
	}
	
function WGNotify($text) { WGToast($text,false,-2,2); }	
	
function WGToast($text,$t=false,$x0=0,$y0=0 ) {
	global $CMD;
	$CMD[] = array(
		'api'	=>	'toast',
		'data'	=>	array(
			'text'	=>	$text,
			'x0'	=>	$x0,
			'y0'	=>	$y0,
			't'	=>	$t)
			)
		;
	}

function noWindow() {
	global $WEBGUI;
	unset($WEBGUI['win']);
	unset($WEBGUI['activity']);
	ob_get_clean();
	ob_start();
	}

function endWin($action=false) {
	global $OUTJSON;
	global $WEBGUI;
	global $APPL;
	global $JSON;
	
	if ($action===false) $action=$JSON['t'];
		
	endActivity();
		
	if (!isset($WEBGUI['win'])) return false;
	$WEBGUI['win']['html'] = @ob_get_clean();
	if (isset($WEBGUI['rvar'])) $WEBGUI['win']['html']=WGReplacer($WEBGUI['win']['html'],$WEBGUI['rvar']);
		
	ob_start();
	
	if (!$WEBGUI['win']['hdc'] and $APPL['hdc']) $WEBGUI['win']['hdc'] = $APPL['hdc'];
	if (!$WEBGUI['win']['name'] and $APPL['app']) $WEBGUI['win']['name']= $APPL['app'];
	if (!$WEBGUI['win']['url'] and $APPL['url']) $WEBGUI['win']['url']= $APPL['url'];
				
	if (isset($WEBGUI['win']['data'])) {
		WGSSave($APPL['hdc'],$WEBGUI['win']['data']);
		}

	if ($action!=AJ_SHELL and $action!= AJ_DIALOG) {
		$OUTJSON['cmd'][] = array(
			'api'	=>	'reply'	,
			'data'	=>	$WEBGUI['win']	)
			;		
		} else {
		$OUTJSON['cmd'][] = array(
			'api'	=>	'open'	,
			'data'	=>	$WEBGUI['win']	)
			;		
		}
				
	
	unset($WEBGUI['win']);
		
	return true;
	}

function WGMnuFunction($js) {
	return array(
		'p'	=>	'',
		'f'	=>	$js)
		;
	}
	
function WGMnuPoint($fun) {
	return array(
		'l'	=>	$fun)
		;
	}


function WGNewMenu() { return array(); }				

function WGAddMnuItem(&$to,$title=false,$link=false,$icon=false,$mtxt=false) {
	$o = array();
	if ($title) $o['t']=$title;
	if ($link) {
		if (is_array($link)) {
			if (isset($link['l'])) $o['_']=$link['l']; else $o['_i']=$link; 
			} else $o['i']=$link;
		}
	if ($icon) $o['i']=$icon;
	if ($mtxt) $o['l']=$mtxt;
	$to[]=$o;
	}
	
function WGAddMnuSub(&$to,$title,$mnu,$icon=false) {
	$o=array(
		't'	=>	$title,
		'l'	=>	$mnu)
		;
	if ($icon) $o['i']=$icon;
	$to[]=$o;
	}
				

function retGUI() {
	global $CMD;
	global $OUTJSON;
	global $DBH;
	global $STDIN;
	global $APPL;
	global $ini;
	if (!isset($OUTJSON['cmd'])) $OUTJSON['cmd']=array();
	
	endWin();
	
	if (count($CMD)) {
		foreach($CMD as $a) $OUTJSON['cmd'][]= $a;
		}
			
	header("Content-Type: application/json; charset=UTF-8",true);
	header("X-Sid: ".session_id());
	fclose($STDIN);
	if (isset($ini['db']))dbclose($DBH);
	
	exit(json_encode($OUTJSON));
	}

function WGProcMIME() {
	global $APPL;
		
	$MIME=WGLoadMIME();

	if (!$APPL['file']) FatalError("File name requested");
	$ext = strtolower($APPL['file']['extension']);

	if ($ext=='app') {
		$fi = WGParseFile($APPL['file']['path']);
		if ($fi===false or !$fi['D']) FatalError("Invalid application");
		$APPL['bin'] = $fi;
		$APPL['executor'] = $fi['w'];
		return;
		}


	$f=null;
	if (isset($MIME[$ext])) $f = $MIME[$ext];

	if (isset($f['r'])) {
		if (!isset($MIME[$f['r']])) FatalError("Invalid MIME redirector");
		$f=$MIME[$f['r']];
		}
		
	if ($f==null and isset($MIME['.def'])) $f=$MIME['.def'];
	if ($f==null) FatalError("Unsupported file type $ext");

	if (isset($f['dir']) or isset($f['fil'])) {
		$fi = WGParseFile($APPL['file']['path']);
		if ($fi===false) FatalError("Invalid file name");
		$APPL['bin'] = $fi;
		} else {
		if (isset($f['db'])) {
			$f=$f['db'];
			} else FatalError("Entry type not supported");	
		}

	if ($APPL['bin']['F']) {
			if (!isset($f['fil'])) FatalError("File type not supported");
			$f = $f['fil'];
			}
	if ($APPL['bin']['D']) {
			if (!isset($f['dir'])) FatalError("Directory type not supported");
			$f = $f['dir'];
		}

	$APPL['executor'] = "/bin/mime/".$f;	
	if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/'.$APPL['excutor'])) FatalError("Exceutor not found `$f`");
	}

function WGParseEJSTag($ejs) {
	
	$tags = array(
		'function'	=>	array(
			'm'	=>	'/^\\@function\\s([a-zA-Z0-9\\_\\-]{1,40})\\s\\(([a-zA-Z0-9\\_\\-\\,\\s]{1,64})\\)$/',
			'j'	=>	true,
			'f'	=>	'_WG_EJS_Tag_Function'
			),
		
		'event'		=>	array(
			'm'	=>	'/^\\@event\\s([a-zA-Z0-9\\-\\_]{1,40})$/',
			'j'	=>	true,
			'f'	=>	'_EG_EJS_Tag_Event'
			),
		
		'rebound'	=>	array(
			'm'	=>	'/^\\@rebound\\s([a-zA-Z0-9\\-\\_]{1,40})\\s\\=\\>\\s([a-zA-Z0-9\\-\\_]{1,40})$/',
			'j'	=>	false,
			'f'	=>	'_EJ_EJS_Tag_Rebound'
			),
			
		'evepoint'	=>	array(
			'm'	=>	'/^\\@evepoint\\s([a-zA-Z0-9\\-\\_]{1,40})\\s([a-zA-Z0-9\\-\\_]{1,40})$/',
			'j'	=>	false,
			'f'	=> 	'_EG_EJS_Tag_EvePoint'
			),
			
		'menu'	=>	array(
			'm'	=>	'/^\\@menu\\s([a-zA-Z]{1,8})$|^\\@menu$/',
			'j'	=>	true,
			'f'	=>	'_EG_EJS_Tag_Menu'
			),
			
		'menuj'	=>	array(
			'm'	=>	false,
			'j'	=>	true,
			'f'	=>	'_EG_EJS_Tag_MenuJ'
			)
		) ;
		
	$j=count($ejs);
	$st=false;
	for($i=0;$i<$j;$i++) {
		$li=trim($ejs[$i],"\t\r\n ");
		
		list($a,$b)=explode(' ',"$li ",2);
		if ($a=='') continue;
		if ($a[0]!='@') continue;
		$tag=substr($a,1); 
		if (!isset($tags[$tag])) continue;
	
		if ($tags[$tag]['m']) {
			if (preg_match($tags[$tag]['m'],$li,$par)==0) FatalError("EJS: Line ".($i+1)." Bad tag `@$tag`\n$li\n".print_r($par,true));
			} else $par=array();
		if ($tags[$tag]['j']==false) {
			$tags[$tag]['f']($par,$js);
			continue;
			}
		$st=true;
		$js='';
		$i++;
			
		for(;$i<$j;$i++) {
			$li=trim($ejs[$i],"\t\r\n ");
			if ($li=='@end') {
				$st=false;
				$tags[$tag]['f']($par,$js);
				break;
				}
			$js.=$li."\n";
			}
		}
	
	if ($st) FatalError("EJS: Line $i tag `$tag` not closed");
}
	
function _WG_EJS_Tag_Function($par,$js) { winFunction($par[1],$par[2],$js); }

function _EG_EJS_Tag_Event($par,$js) { winEvent($par[1],$js); }

function _EG_EJS_Tag_EvePoint($par,$js) { winEventPoint($par[1],$par[2]); }

function _EJ_EJS_Tag_Rebound($par,$js) { winFunction($par[1],"WGWin,WGObj","this.WGWin.WGEvent.".$par[2]."(this.WGWin,WGObj);",true); }

function _EG_EJS_Tag_MenuJ($par,$js) { 
	$js=json_decode($js,true);
	if (!is_array($js)) FatalError("Invalid menu");
	setWindowMenu($js);
	}

function _EG_EJS_Tag_Menu($par,$js) { 
	global $APPL;
	global $WEBGUI;
	if (!isset($WEBGUI['win']) ) return false;
		
	$mnu = WGParseMenu($js,array(
		'PATH'	=>	$APPL['dirname'].'/',
		'FILE'	=>	$APPL['path'],
		'HDC'	=>	isset($WEBGUI['win']['hdc']) ? $WEBGUI['win']['hdc'] : 0)
		) ;
		
	$par=strtoupper($par);
	
	if ($par=='ICOSX') {
		$WEBGUI['win']['icoSx']=$mnu;
		return true;
		}
	if ($par=='ICODX') {
		$WEBGUI['win']['icoDx']=$mnu;
		return true;
		}
	
	$WEBGUI['win']['menu']=$mnu;
	return true;
	}

function WGParseMenu($mnu,$var) {
	$sp = array(
		'ax'	=>	0,
		'cx'	=>	count($mnu),
		'in'	=>	0,
		'par'	=>	$var	)
		;
		
	return _WGParseMenu($mnu,$sp);
	}

function _WGParseMenu($mnu,&$sp=false) {
	if (!is_array($mnu)) {
		$mnu=trim($mnu,"\t\r\n ");
		$mnu=explode("\n",$mnu);
		}	
		
	if ($sp===false) $sp = array(
		'ax'	=>	0,
		'cx'	=>	count($mnu),
		'in'	=>	0,
		'par'	=>	array()	)
		;
	
	$out=array();
	$sp['in']++;
	$sp['cx']=count($mnu);
	
	for (;$sp['ax']<$sp['cx'];$sp['ax']++) {
		$li=trim($mnu[$sp['ax']],"\t\r\n ");
		$li=str_replace(array("\t","\r"),' ',$li);
		$li=trim($li,' ');
		if ($li=='') continue;
		while(strpos($li,'  ')!==false) $li=str_replace('  ',' ',$li);
		if (preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $li, $par)==0) FatalError("Invalid menu entry: Near menu line ".(1+$sp['ax'])+"\n"+$li);
			
		$par=$par[0];
		/*
		 * -
		 * "text" img link
		 * "text" link
		 * "text" {
		 * "text" img {
		 * }
		 * 
		 * */
		$c = count($par);
		foreach($par as &$p) {
			$l=strlen($p);
			if ($l>1) {
				if ($p[0]=='"' and $p[$l-1]=='"') {
					$p=substr($p,1);
					$p=substr($p,0,strlen($p)-1);
					}
				}
			$p=stripcslashes($p);
			if (strpos($p,'%')!==false) {
				foreach($sp['par'] as $k => $v) $p=str_replace("%$k%",$v,$p);
				}
			}
			
		if ($c==3) {
			$x = array(
				't'	=>	$par[0],
				'l'	=>	$par[2],
				'i'	=>	$par[1])
				;
			
			if ($x['l']=='{') {
				$sp['ax']++;
				$x['l'] = _WGParseMenu($mnu,$sp); 
				}
			
			$out[]=$x;
			continue;
			}
		
		if ($c==2) {
			$x = array(
				't'	=>	$par[0],
				'l'	=>	$par[1])
				;
				
			if ($x['l']=='{') {
				$sp['ax']++;
				$x['l'] = _WGParseMenu($mnu,$sp); 
				}
			
			$out[] = $x;
			continue;
			}
		
		if ($c==1) {
			if ($par[0]=='-') {
				$out[] = array();
				continue;
				}
				
			if ($par[0]=='}') {
				$sp['in']--;
				if ($sp['in']<0) FatalError("Too many menu close: Near menu line ".(1+$sp['ax'])+"\n"+$li);
				return $out;
				}
			}
				 
		if (count($li)!=3) FatalError("Invalid menu entry: Near menu line ".(1+$sp['ax']));
		
		}
		
	$sp['in']--;
	if ($sp['in']<0) FatalError("Too many menu close: Near menu line ".(1+$sp['ax'])+"\n"+$li);
	return $out;
	}

function raiseGuiEvent($name,$par=null) {
	global $CMD;
	
	$x=array(
		'name'	=>	$name)
		;
		
	if ($par) $x['data']=$par;
	
	$CMD[] = array(
		'api'	=>	'event',
		'data'	=>	$x)
		;
	}
	
function raiseEvent($name,$par=null,$hdc=false) {
	global $APPL;
	global $CMD;
	global $WINGUI;
	if (!$hdc and isset($WINGUI['win']['hdc'])) $hdc=$WINGUI['win']['hdc'];
	if (!$hdc) $hdc=$APPL['hdc'];
	
	$x=array(
		'hdc'	=>	$hdc,
		'name'	=>	$name)
		;
		
	if ($par) $x['par']=$par;
	
	$CMD[] = array(
		'api'	=>	'doevent',
		'data'	=>	$x)
		;
	}
	
function WGCss($id,$cssObj) {
	global $OUTJSON;
	
	$cssObj['id']=$id;
	
	$OUTJSON['cmd'][] = array(
		'api'	=>	'css',
		'data'	=>	$cssObj)
		;
	
	}
	
function WGSetDesktop($data) {
	global $OUTJSON;
	$OUTJSON['cmd'][] = array(
		'api'	=>	'desktop',
		'data'	=>	$data)
		;
	}	
	
function WGAddWidget($name,$html) {
	global $OUTJSON;
	$OUTJSON['cmd'][] = array(
		'api'	=>	'widget',
		'data'	=>	array(
			'name'	=>	$name,
			'html'	=>	$html)
			)
		;
	}
	
function setHTML($id,$html) {
	global $APPL;
	global $CMD;
	global $WINGUI;
	if (!$hdc and isset($WINGUI['win']['hdc'])) $hdc=$WINGUI['win']['hdc'];
	if (!$hdc) $hdc=$APPL['hdc'];
	
	$x=array(
		'hdc'	=>	$hdc,
		'id'	=>	$id,
		'html'	=>	$html)
		;
		
	$CMD[] = array(
		'api'	=>	'setHTML',
		'data'	=>	$x)
		;
	}	
	
function setCaption($name,$capt,$multi=false) {
	global $APPL;
	global $CMD;
	global $WINGUI;
	if (!$hdc and isset($WINGUI['win']['hdc'])) $hdc=$WINGUI['win']['hdc'];
	if (!$hdc) $hdc=$APPL['hdc'];
	
	$x=array(
		'hdc'	=>	$hdc,
		'name'	=>	$name,
		'par'	=>	$capt,
		'mul'	=>	$multi ? true:false)
		;
		
	$CMD[] = array(
		'api'	=>	'setcap',
		'data'	=>	$x)
		;
	}	

function WGIconObj($url,$ico,$text,$back=false,$sub=false) {
	
	$x= array(
		'i'	=>	$ico,
		'l'	=>	$url,
		't'	=>	$text)
		;
	
	if ($back) $x['b']=$back;
	if ($sub) $x['sub']=$sub;
	return $x;
	}
	
function WGIconSubObj($ico,$url) {
	return array(
		'i'	=>	$ico,
		'l'	=>	$url)
		;
	}
		
function WGNewForm($name,$action,$actOk=false,$actErr=false,$connect=true,$formid=false) {
	global $WEBGUI;
	global $APPL;
	
	if ($formid===false) {
		if (isset($WEBGUI['win'])) $hdc= $WEBGUI['win']['hdc']; else if (isset($APPL['hdc'])) $hdc=$APPL['hdc'];
		} else {
		$hdc=$formid;
		}
	
	return array(
		'html'	=>	$action == AJ_SHELL or $action == AJ_DIALOG,
		'formid'=>	"W{$hdc}/{$name}/{$action}/",
		'name'	=>	$name	,
		'connect'=>	$connect,
		'actok'	=>	$actOk,
		'acterr'=>	$actErr,
		'obj'	=>	array()	)
		;
	}
	
function WGFormParser($X,$action,$OnlyForm=false) { //da rivedere
	$X = str_replace("\t",' ',$X);
	$X = str_replace("\0",'',$X);
	$X = str_replace("\r",'',$X);
	while(strpos($X,'  ')!==false) $X=str_replace('  ',' ',$X);
	$X = trim($X,"\n ");
	$X=explode("\n",$X);
	$cur=false;
	$frm=array();          
	foreach($X as $li) {
		$li=trim($li,' ');
		$l=strlen($li);
		
		if ($l>0) {
		        $lc = $li[$l-1];
			if ($lc==';') { //fix
				$li[$l-1]="\n";
				$li.="zero = 0\0";
				}
				
			if ($lc=='{') {
			        list($cur)=explode(' ',$li,2);
			        $frm[$cur]='';
			        continue;
				}
			if ($lc=='}') {
				$cur=false;
				continue;
				}	
			
			if ($cur===false) FatalError("Unattended token in form `".$li."`");
			$frm[$cur].=$li."\n";
			}
		}
		
	foreach($frm as &$li) {
		$li=explode("\0",$li);
		
		foreach($li as &$l2) {
		        $l2=trim($l2,"\n ");
		        $l2=explode("\n",$l2);
		        foreach($l2 as &$l3) {
				$l3=trim($l3,' ');
				if ($l3=='') continue;
				if (preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $l3, $par)==0) FatalError("Form syntax error `$l3`");
				$par=$par[0];
				
				foreach($par as &$p) {
					$l=strlen($p);
					if ($l>1) {
						if ($p[0]=='"' and $p[$l-1]=='"') {
							$p=substr($p,1);
							$p=substr($p,0,strlen($p)-1);
							}
						}
					$p=stripcslashes($p);
					}
				$l3=$par;
				
				}
			}
		}
	
	$par=null;			
	foreach($frm as $K => $X) {
	        if ($OnlyForm && $K!=$OnlyForm) continue;
	        $O=WGNewForm($K,$action); 
	        if (count($X)==0) FatalError("No parameter on form `$K`");
	      	        
	        foreach($X as $rc => $rule) {
						$cr=count($rule);
				if ($cr==0) continue;
				$l=count($rule[0]);
		        if ($l==1 and $rule[0]=='') continue;
				if ($l<3) FatalError("Bad form rule on form `$K`");
				
				if ($l==4 and $rule[0]=='?') $p=1; else $p=0;
				$par=false;
				
			if ($cr>1) {
				$par=array();
				for ($ax=1;$ax<$cr;$ax++) {
				        $ll=count($rule[$ax]);
				        			        
				        if ($ll<2 or $ll>3 or ($ll==3 and $rule[$ax][1]!='=')) FatalError("Invalid rule in form `$K`\nLine: ".($ax+1)." from start.");
				        if ($ll==2) {
				        	$x=$rule[$ax][0];
				        	$x=trim(strtolower($x),' ');
						$par[$x]=$rule[$ax][1];
						} else {
							
						$x=$rule[$ax][0];
				        $x=trim(strtolower($x),' ');
						$par[$x]=$rule[$ax][2];
						}
					}
				} else $par=array();
			if ($rule[0][$p][0]=='?') {
			        $par['req']=false;
			        $rule[0][$p][0]=substr($rule[0][$p][0],1);
				} else $par['req']=true;
				
			if ($rule[0][$p]=='str') $rule[0][$p][0]='string';
			if ($rule[0][$p]=='boolean') $rule[0][$p][0]='bool';
			if ($rule[0][$p]=='txt') $rule[0][$p][0]='text';
			if ($rule[0][$p]=='&') $rule[0][$p][0]='int';
			if ($rule[0][$p]=='$') $rule[0][$p][0]='string';
			if ($rule[0][$p]=='#') $rule[0][$p][0]='float';
			if ($rule[0][$p]=='!') $rule[0][$p][0]='bool';
			
			if (isset($par['function'])) {
				$x0 = $par['function'];
				if (!function_exists($x0)) FatalError("Can't call unknown function `$x0`");
				$x1 = $x0($par);
				if ($x1===false) continue;
				if (is_array($x1)) {
					$par=$x1;
					$par['function']=$x0;
					}
								
				}
			WGAddToForm($O,$rule[0][$p],$rule[0][$p+1],$rule[0][$p+2],@$rule[0][$p+3],$par);
			}
		$frm[$K] = $O;
				
		}	
	
	if ($OnlyForm) return $frm[$OnlyForm]; 
	
	return $frm;
	}
	
function WGFormActivity($form) {
	echo '<div class="WGWActivity" data-wgid="'.htmlspecialchars($form['name'],ENT_QUOTES).'">';
	$act=$form['actok'] ? $form['actok'] : $form['name'];
	$act=htmlspecialchars($act,ENT_QUOTES);
	echo "\n".'<form method="POST" action="#" name="'.$act.'" data-wgname="'.$act.'" data-wgform="'.htmlspecialchars($form['name'],ENT_QUOTES).'"';
	if ($form['acterr']) echo ' data-wgerrorto="'.htmlspecialchars($form['acterr'],ENT_QUOTES).'"';
	echo'>'."\n";
	echo "<table class=\"EWGForm\">\n";
	
	foreach($form['obj'] as $li) {
		if ($li['hid']) continue;
		if ($li['s']) {
			echo "<tr><td colspan=\"2\" class=\"".htmlspecialchars($li['s'],ENT_QUOTES)."\">".$li['h']."</td></tr>\n";
			} else {
			echo "<tr><td>".htmlspecialchars($li['c'],ENT_QUOTES).":</td><td>".$li['h']."</td></tr>\n";
			}
		
		}
		
	foreach($form['obj'] as $li) {
		if (!$li['hid']) continue;
		echo $li['h'];
		}
		
	echo "</table>";
	echo "</form></div>\n";
	}


function WGFormAddType($T) {
	global $MODULES;
	
	if (!isset($MODULES['form'])) $MODULES['form']=array();
	if (!function_exists("EWGMOD_FORM_{$T}_OnCheck")) return false;
	if (!function_exists("EWGMOD_FORM_{$T}_OnCreate")) return false;
	$MODULES['form'][$T] = true;
	
	return true;
	}

function WGDestroyCaptcha($obj) {
	if (!isset($obj['captchas'])) return;
	foreach($obj['captchas'] as $id) {
		unset($_SESSION['EWGCaptcha'][$id]);
		}
	}

function WGFormCheck(&$obj,&$data) {
	global $MODULES;
	
	foreach($obj['obj'] as $li) {
		if (!isset($li['n'])) continue;
		$K = $li['n'];
		$V = @$data[$K];
		$T = $li['t'];
		
		if ($MODULES['form'][$T]) {
			$f="EWGMOD_FORM_{$T}_OnCheck";
			if ($f($li,$data[$K])) return $K;
			continue;
			}
				
		if (!$li['f'] and (!isset($data[$li['n']]) or $data[$li['n']]=='')) return $K;
		
		if ($li['f'] and $V=='') continue;
		
		if ($li['par']['lmin']) {
			if (strlen($V)<$li['par']['lmin']) return $K;
			}
				
		if ($li['par']['lmax']) {
			if (strlen($V)>$li['par']['lmax']) return $K;
			}
						
		if ($li['reg']) {
			if (preg_match('/'.$li['reg'].'/',$V)==0) return $K;
			}
		
		if ($T=='int') {
			if (!is_numeric($V) or preg_match('/^[0-9]{1,16}$/',$V)==0) return $K;
			if (isset($li['par']['min']) and $V<$li['par']['min']) return $K;
			if (isset($li['par']['max']) and $V>$li['par']['max']) return $K;
			$data[$K]=intval($V);
			}
			
		if ($T=='float') {
			if (!is_numeric($V)) return $K;
			if (isset($li['par']['min']) and $V<$li['par']['min']) return $K;
			if (isset($li['par']['max']) and $V>$li['par']['max']) return $K;
			$data[$K]=floatval($V);
			}
		
		if ($T=='image') {
			$V = WGParseFile($V,true);
			if ($V===false or !WGisAllowed($V['w'])) return $K;
			if (!$V['F'] or $V['D']) return $K;
			if (isset($li['par']['path'])) {
				$t0=explode(',',$li['par']['path']);
				$t2=array();
				foreach($t0 as $t1) {
					$t1=trim($t1,'/ ');
					if ($t1=='') continue;
					$t2[]="/$t1/";
					}
				$t2="\n".implode("\n",$t2)."\n";
				if (strpos($t2,"\n{$V['dirname']}/\n")===false) return $K;
				}
				
			if (isset($li['par']['type'])) {
				$t0=explode(',',$li['par']['type']);
				$t2=array();
				foreach($t0 as $t1) {
					$t1=trim($t1,'. ');
					if ($t1=='') continue;
					$t2[]=$t1;
					}
				$t2=".".implode(".",$t2).".";
				if (strpos($t2,".{$V['extension']}.")===false) return $K;
				}
				
			$data[$K] = $V['w'];
			}
			
		if ($T=='enum') {
			$v=array();
			foreach($li['par'] as $k => $v) {
				if (strpos($k,'.')===false) continue;
				list($a,$b)=explode('.',$k,2);
				$v[$b]=true;
				}
				
			if (!$v[$V]) return $K;
			}
			
		if ($T=='time') {
			list($a,$b)=explode(':',$V.':',2);
			$a=intval($a);
			$b=intval($b);
			if ($a<0 or $a>23 or $b<0 or $b>59) return $K;
			$data[$K] = ($a*60)+$b;
			}
			
		if ($T=='date') {
			$z=explode('/',$V.'//');
			$d=@$z[$li['par']['ord'][0]];
			$m=@$z[$li['par']['ord'][1]];
			$y=@$z[$li['par']['ord'][2]];
			if (!checkdate($m,$d,$y)) return $K;
			$data[$K] = gmmktime(0,0,0,$m,$d,$y);
			}
			
		if ($T=='mail') {
			$x=filter_var($V, FILTER_SANITIZE_EMAIL);
			if ($x===false) return $K;
			$data[$K]=$x;
			}
			
		if ($T=='url') {
			$x = filter_var($V,	FILTER_VALIDATE_URL );
			if ($x===false) return $K;
			}
			
		if ($T=='color') {
			if (preg_match('/^\#[0-9a-fA-F]{6}$/',$V)==0) return $K;
			}
		
		if ($T=='captcha') {
			$capid= $li['cap'];
			if (!isset($_SESSION['EWGCaptcha'][$capid]) or $_SESSION['EWGCaptcha'][$capid]=='') FatalError($li['par']['onused'] ? $li['par']['onused'] : "The captcha code/session is arleady used.");
			$code = $_SESSION['EWGCaptcha'][$capid];
			if ($code=='' or $code!=strtolower($V)) return $K;
			if (isset($obj['captchas'])) $obj['captchas']=array();
			$obj['captchas'][] = $capid; 
			}
			
		if ($T=='font') {
			if (!is_array($V)) return $K;
			if (!isset($V['fontFamily']) or preg_match('/^[^\,\-\_\.\s]{1}[a-zA-Z0-9\s\_\-\.\,]{1,40}[^\,\-\_\.\s]{1}$/',$V['fontFamily'])==0) return $K;
			if (!isset($V['fontSize']) or preg_match('/^[1-9]{1}[0-9]{0,3}(px|pt)$/',$V['fontSize'])==0) return $K;
			if (!isset($V['fontWeight']) or preg_match('/^[a-zA-Z0-9]{1,16}$/',$V['fontWeight'])==0) return $K;
			}
			
		}
	return false;
	}
	
function WGAddToForm(&$obj,$type,$name,$caption,$value=null,$par=false) {
	global $ini;
	global $MODULES;
	global $DBH;
	$formTemplate=array();
	if (!is_array($par)) $par=array();
	$x=WGLang();
	$x=$_SERVER['DOCUMENT_ROOT'].'/etc/form/tpl-'.$x.'.json';
	if ($x!='_D_' and !file_exists($x)) $x=$_SERVER['DOCUMENT_ROOT'].'/etc/form/tpl-_D_.json';
	
	if ($par['dbquery'] and $par['val'] and $par['text']) {
		dbquery($DBH,$par['dbquery']);
		while($db=dbget($DBH)) {
			$par['v.'. @$db[ $par['val'] ] ] = @$db[ $par['text'] ];
			}
		}	
	
	if (file_exists($x)) {
		$formTemplate = @file_get_contents($x);
		if ($formTemplate!==false) $formTemplate=json_decode($formTemplate,true);
		if (!is_array($formTemplate)) $formTemplate=array();
		}
	
	if (isset($par['tpl'])) {
		if ($par['tpl']!='no') {
			if (isset($formTemplate[$par['tpl']])) $par=array_merge($formTemplate[$par['tpl']],$par);
			}
		} else {
			$x=strtoupper($type);
			if (isset($formTemplate[$x])) $par=array_merge($formTemplate[$x],$par);
		}
	
	if (isset($MODULES['form'][$type])) {
		$x = array(
			't'	=>	$type,
			'c'	=>	$caption,
			'n'	=>	$name,
			'r'	=>	$par['reg'] ? $par['reg'] : false,
			'f'	=>	$par['req']!=false,
			'par'=>	$par,
			'v'	=>	$value |=null ? $value : "",
			'h'	=> '')
			;
		
		$f = "EWGMOD_FORM_{$type}_OnCreate";
		$x = $f($x,$obj['html']);
		if ($x) $obj['obj'][]= $x;
		return;		
		}
	
	if ($obj['connect']===false) {
		$connect=false;
		} else {
			$connect=$obj['name'].'.'.$name;
		}
	
	$html=$obj['html'];
		
	$tv=' string text int float bool font color url image enum time date mail password send title info hidden hcolor captcha ';
	$dlg=' font color image ';
		
	if (strpos($type."\n",".dlg\n")===false) {
		$type=str_replace(' ','',$type);
		if (strpos($tv," $type ")===false) FatalError("Unknown form element type type: `$type` in `$caption`");
		$isDialog= strpos($dlg," $type ")!==false;
		} else {
		$isDialog=true;	
		}
	
	if ($type=='enum' and !is_array($par)) FatalError("Invalid enum in `$caption`");
	
	
	
	$hCls='EWGInputT'.htmlspecialchars($type,ENT_QUOTES); 
			
	if (strpos(' string int float url time date mail password hidden hcolor '," $type ")!==false) { //bool non setta a 0 
			
		$t='text';
		if (strpos(' hcolor '," $type ")!==false) $t=substr($type,1);
		if ($type=='password') $t='password';
		if ($type=='hidden') $t='hidden';
		if ($type=='bool') $t='checkbox';
		$h='<input type="'.$t.'" name="'.htmlspecialchars($name,ENT_QUOTES).'" class="'.$hCls.'" value="'.htmlspecialchars($value!==null ? $value : "",ENT_QUOTES).'" ';
		//
		if (isset($par['err'])) $h.='data-wgerr="'.htmlspecialchars($par['err'],ENT_QUOTES).'" ';
		if (isset($par['lmin'])) $h.='data-wglmin="'.htmlspecialchars($par['lmin'],ENT_QUOTES).'" ';
		if (isset($par['lmax'])) $h.='data-wglmax="'.htmlspecialchars($par['lmax'],ENT_QUOTES).'" ';
		if ($connect) $h.='data-wgconnect="'.htmlspecialchars($connect,ENT_QUOTES).'" ';
		if (@$par['fac']==0) $h.='data-wgreq="1" ';
		
		if ($type=='int') @$par['reg']='^[0-9]{1,16}$';
		if ($type=='float') @$par['reg']='^[0-9]{1,10}[\.]{1}[0-9]{1,8}|[0-9]{1,8}$';
		
		if ($type=='date') {
			$dm = isset($ini['webgui']['datemode']) ? $ini['webgui']['datemode'] : false;
			if (isset($par['mode'])) $dm=$par['mode'];
			if ($dm===false) $dm=='dmy';
			$dm = strtolower(trim($dm,' '));
			$dm=str_replace(array(' ','/','-','\''),'',$dm);
			if (strlen($dm)!=3) $dm='mdy';
			$t0=array();
			$t1=array();
			$t2=array();
			$jm = strlen($dm);
			for ($jj=0;$jj<$jm;$jj++) {
					$ch0 = $dm[$jj];
					if ($ch0=='d') {
					$t0[] = "DD";
					$t1[] = '([1-9]{1}|[012]{1}[0-9]{1}|3[01]{1})';	
					$t2[] = 0;
					}
					
				if ($ch0=='m') {
					$t0[] = "MM";
					$t1[] = '([1-9]{1}|0[1-9]{1}|1[012]{1})';
					$t2[] = 1;
					}
					
				if ($ch0=='y') {
					$t0[] = "YYYY";
					$t1[] = '([0-9]{4})';
					$t2[] = 2;
					}
				}
			
			@$par['reg']='^'.implode('\/',$t1).'$';
			$par['ord'] = $t2;
			if (!isset($par['placeholder'])) $par['placeholder']=implode('/',$t0);
			}
			
		if ($type=='time') {
			@$par['reg']='^([0-2]{0,1}[0-9]{1})\:([0-5]{0,1}[0-9]{1})$';
			if (!isset($par['placeholder'])) $par['placeholder']='HH:MM';
			}
		if ($type=='mail') @$par['reg']='^[^\"\&\!\'\`\?\^\#\+\@\s]{1,40}\@[a-zA-Z0-9\_\-\.]{1,40}\.[a-zA-Z]{2,6}$';
		if ($type=='url') @$par['reg']='^http[s]{0,1}\:\/\/[0-9a-zA-Z\_\-\.]{1,80}\.[a-zA-Z]{1,16}';
		
		if (isset($par['placeholder'])) $h.='placeholder="'.htmlspecialchars($par['placeholder'],ENT_QUOTES).'" ';			
		
		if (isset($par['reg'])) {
			$h.='data-wgreg="'.htmlspecialchars(trim($par['reg'],"\t\r\n "),ENT_QUOTES).'" ';
			}
		
		$h=trim($h,' ').'>';	
			
		if (isset($par['phpreg'])) $par['reg']=$par['phpreg'];
				
		$obj['obj'][]=array(
			'hid'=>	$type=='hidden',
			't'	=>	$type,
			'c'	=>	$caption,
			'n'	=>	$name,
			'r'	=>	$par['reg'] ? $par['reg'] : false,
			'f'	=>	$par['req']!=false,
			'par'=>	$par,
			'v'	=>	$value |=null ? $value : "",
			'h'	=> $html ? $h : false)
			;
		}
		
	if ($type=='enum') {
		if ($html) {
			$h='<select name="'.htmlspecialchars($name,ENT_QUOTES).'" class="'.$hCls.'"';
			if ($connect) $h.=' data-wgconnect="'.htmlspecialchars($connect,ENT_QUOTES).'"';
			$h.='>';
			foreach($par as $k => $v) {
				if (strpos($k,'.')===false) continue;
				list($fuffa,$k)=explode('.',$k,2);
				$h.='<option value="'.htmlspecialchars($k,ENT_QUOTES).'"'.( $k === $value ? ' default ': '').'>'.htmlspecialchars($v,ENT_QUOTES)."</option>\n";
				}
			$h.='</select>';
		} else $h=false;
		$obj['obj'][]=array(
			'c'	=>	$caption,
			'n'	=>	$name,
			'r'	=>	$par['reg'] ? $par['reg'] : false,
			'f'	=>	$par['req']!=false,
			'v'	=>	$value |=null ? $value : "",
			'h'	=> $h)
			;
		}
	
	if ($type=='captcha') {
		$capid=md5($obj['formID'].'/'.$name);
				
		if ($html) {
			$alfa="QWERTYUIPADYFHTLMNVX234679234679234679";
			$ecx=strlen($alfa)-1;
			$code='';
			for ($a=0;$a<6;$a++) $code.=$alfa[mt_rand(0,$ecx)];
			$code=strtolower($code);
			if (!is_array($_SESSION['EWGCaptcha'])) $_SESSION['EWGCaptcha']=array();
			$_SESSION['EWGCaptcha'][$capid]=$code;
			}
			
		if ($html) {
			$h='<div class="EWGCaptchaCont">';
			if ($par['placeholder']) $h.='<div class="EWGCaptchaInfo">'.nl2br(htmlspecialchars($par['placeholder'],ENT_QUOTES),false).'</div>';
			$h.='<img class="EWGCaptcha" data-wgspec="imgclr" src="/bin/php/captcha.php?id='.$capid.'&t='.crc32(microtime()).'"';
			if ($par['placeholder']) $h.=' title="'.htmlspecialchars($par['imgtext'],ENT_QUOTES).'"';
			$h.='>';
			$h.='<input class="EWGCaptchaInput" type="text" name="'.htmlspecialchars($name,ENT_QUOTES).'" class="'.$hCls.'"';
			$h.=' value="" data-wgsha1="'.sha1($code.$capid).'" data-wgsalt="'.$capid.'"';
			if ($connect) $h.=' data-wgconnect="'.htmlspecialchars($connect,ENT_QUOTES).'"';
			if ($par['err']) $h.=' data-wgerr="'.htmlspecialchars($par['err'],ENT_QUOTES).'"';
			$h.='></div>';
			} else {
			$h=false;
			}
			
		$obj['obj'][]=array(
			't'	=>	'captcha',
			'c'	=>	$caption,
			'n'	=>	$name,
			'cap'=> $capid,
			'ver'=>	$code,
			'h'	=> $h)
			;
		}
		
	if ($type=='text') {
		if ($html) {
			$h='<div class="EWGFormText">'.htmlspecialchars($caption,ENT_QUOTES).'</div>';
			$h.='<textarea name="'.htmlspecialchars($name,ENT_QUOTES).'" class="'.$hCls.'"';
			if ($par['height']) $h.=' style="height: '.intval($par['height']).'px;"';
			
			if (isset($par['err'])) $h.='data-wgerr="'.htmlspecialchars($par['err'],ENT_QUOTES).'" ';
			if (isset($par['lmin'])) $h.='data-wglmin="'.htmlspecialchars($par['lmin'],ENT_QUOTES).'" ';
			if (isset($par['lmax'])) $h.='data-wglmax="'.htmlspecialchars($par['lmax'],ENT_QUOTES).'" ';
			
			///if ($connect) $h.=' data-wgconnect="'.htmlspecialchars($connect,ENT_QUOTES).'"';
			//NON SUPPORTA WGCONNECT
			$h=trim($h,' ').'>';
			$h.=nl2br(htmlspecialchars($value!==null ? $value : "",ENT_QUOTES),false);
			$h.='</textarea>';
		} else $h=false;
		$obj['obj'][]=array(
			'c'	=>	' ',
			'n'	=>	$name,
			's'	=>	'EWGFormTextTD',
			'r'	=>	$par['reg'] ? $par['reg'] : false,
			'f'	=>	$par['req']!=false,
			'v'	=>	$value |=null ? $value : "",
			'h'	=> $h)
			;
		}
	
	if ($type=='info') {
		if ($html) {
			$h='<div class="EWGFormINFO">'.nl2br(htmlspecialchars($caption,ENT_QUOTES),false).'</div>';
			} else $h=false;
		$obj['obj'][]=array(
			'c'	=>	' ',
			's'	=>	'EWGFormINFOTD',
			'h'	=> $h)
			;
		}
	
	if ($type=='title') {
		if ($html) {
			$h='<div class="EWGFormTITLE">'.nl2br(htmlspecialchars($caption,ENT_QUOTES),false).'</div>';
			} else $h=false;
		$obj['obj'][]=array(
			'c'	=>	' ',
			's'	=>	'EWGFormTITLETD',
			'h'	=> $h)
			;
		}
	
	if ($type=='send') {
		if ($html) {
			$x= $caption!='' ? $caption : $value;
			if ($x=='') $ok='OK';
			$h='<input type="submit" class="EWGFormSend" value="'.htmlspecialchars($caption!='' ? $caption : $value,ENT_QUOTES).'" name="'.htmlspecialchars($name,ENT_QUOTES).'">';
			} else $h=false;
	
		$obj['obj'][]=array(
			'c'	=>	'',
			'n'	=>	$name,
			'r'	=>	$par['reg'] ? $par['reg'] : false,
			'f'	=>	$par['req']!=false,
			's'	=>	'EWGFormSendTD',
			'v'	=>	$value |=null ? $value : "",
			'h'	=> $h)
			;
							
		if (isset($par['action'])) $obj['actok'] = $par['action']; 
		if (isset($par['error'])) $obj['acterr'] = $par['error']; 
		}
		
	if ($isDialog!==false) {
		if ($html) {
		
			$h='<div data-wgdialog="'.htmlspecialchars($type,ENT_QUOTES).'" data-wgname="'.htmlspecialchars($name,ENT_QUOTES).'" data-wgdata="'.htmlspecialchars($connect,ENT_QUOTES).'"';
			if ($value!==null) {
				if (is_array($value)) {
					$h.=' data-wgvaluej="'.htmlspecialchars(json_encode($value),ENT_QUOTES).'"';
					} else {
					$h.=' data-wgvalue="'.htmlspecialchars($value,ENT_QUOTES).'"';	
					}
				}
			if (is_array($par)) {
				foreach($par as $k => $v) {
					if (strpos($k,'wg')===0 and preg_match('/^[a-z]{2,40}$/',$k)!=0) {
						$h.=" data-$k=\"".htmlspecialchars($v,ENT_QUOTES)."\"";
						}
					}
				}
		
		$h=trim($h,' ');
		$h.='></div>';
		
		} else $h=false;
		$obj['obj'][]=array(
			'c'	=>	$caption,
			'n'	=>	$name,
			'v'	=>	$value |=null ? $value : "",
			'f'	=>	$par['req']!=false,
			'h'	=> $h)
			;
		}
	}

function WGWinCss($webFile,$rvar=array()) {
	global $WEBGUI;
	global $OUTJSON;
	global $JSON;
	
	$webDir=pathinfo($webFile,PATHINFO_DIRNAME);
	$webDir='/'.trim($webDir,'/').'/';
	
	$f = $_SERVER['DOCUMENT_ROOT'].'/'.$webFile;
	$t = $webFile;
	$t = crc32($t);
	if ($t<0) $t=strtoupper(base_convert(-$t,10,36)); else $t=base_convert($t,10,36); 
	$t = "EWGC".$t;
	$t0 = "\n".implode("\n",@$JSON['css'])."\n";
	if (strpos($t0,"\n$t\n")===false or @$WEBGUI['forceCss']!=false) {
		$css = @file_get_contents($f);
		if ($css===false) FatalError("Can't load application's CSS");
		$css=WGReplacer($css,$rvar);
		
		$css=str_replace(array("\t","\r","\n"),' ',$css);
		$css=trim($css,' ');
		$css=str_replace('{',' {',$css);
		while(strpos($css,'  ')!==false) $css=str_replace('  ',' ',$css);
		$css=str_replace('{',"{\n",$css);
		$css=str_replace('}',"}\n",$css);
		$css=explode("\n",$css);
		foreach($css as &$li) {
			$li=trim($li," ");
			if (strpos($li,'{')!==false) {
				$li=str_replace('{',' {',$li);
				while(strpos($li,'  ')!==false) $li=str_replace('  ',' ',$li);
				$li=str_ireplace('body {','.WGWActivity {',$li);
				$li='.'.$t.' '.$li;
				} else {
				if (stripos($li,"URL(./")!==false) {
					$li=str_ireplace("URL(./","URL($webDir",$li);
					}
				$li=str_replace("%PATH%",$webDir,$li);	
				}
			}
					
		$css=implode("\n",$css);
		$OUTJSON['cmd'][] = array(
			'api'	=>	'wincss',
			'data'	=>	array(
				'id'	=>	$t,
				'css'	=>	$css)
				)
			;
		}
		
	if (isset($WEBGUI['win'])) $WEBGUI['win']['winCss']=$t;
	
	return $t;
	}
	
function WGTokenLine($li) {
	$li=trim($li,"\t\r\n ");
        if (preg_match_all('/"(?:\\\\.|[^\\\\"])*"|\S+/', $li, $par)==0) return false;
	$par=$par[0];
	$c = count($par);
	foreach($par as &$p) {
		$l=strlen($p);
		if ($l>1) {
			if ($p[0]=='"' and $p[$l-1]=='"') {
				$p=substr($p,1);
				$p=substr($p,0,strlen($p)-1);
				}
			}
		$p=stripcslashes($p);
		}
	return $par;
	}	
	
function EWGLoadProfile() {
	global $OUTJSON;
	global $CMD;
	WGDetectLang(true);
	
	$usr = WGSessionUser();
	if ($usr===false) {
			$user=false;
			$group=false;
		} else {
			$user=$usr['name'];
			$group=$usr['group']['name'] ? $usr['group']['name'] : false;
			if ($group===false) $group='web';
		}
	
	$t0 = EWGLoadProfileFile('start.json',$user,$group);
	if (is_array($t0)) {
		$OUTJSON['cmd'][] = array('api'=>'startBar','data'=>$t0);
		} else {
		$OUTJSON['cmd'][] = array('api'=>'startBar','data'=>array());
		}
	
	$t0 = EWGLoadProfileFile('desktop.json',$user,$group);
	if (!is_array($t0)) $t0=array();
	if ($user!==false and file_exists($_SERVER['DOCUMENT_ROOT']."/home/$user/home.dir")) {
			$t0[] = array(
				"b"	=>	"/img/folder.png",
				"i"	=>	"/img/user_documents.png",
				"l"	=>	"/home/$user/home.dir",
				"t"	=>	"Home")
				;
			}
			
	$OUTJSON['cmd'][] = array('api'=>'desktop','data'=>$t0);
	
	$OUTJSON['cmd'][] = array(
		'api'	=>	'logon',
		'data'	=>	array(
			'user'	=>	$user,
			'group'	=>	$group,
			'can'	=>	$usr['can'] ? $usr['can'] : false )
			)
		;
	
	WGUnloadDLL('custom-user');	
	$f="/var/user/{$user}-custom.css";
	if (file_exists($_SERVER['DOCUMENT_ROOT'].$f)) loadNamedDLL($f,'custom-user');
		
	}
	
function EWGHomeDir($user) { return "/home/$user"; }	
function EWGGroupDir($group) { return '/etc/_grp/'.$group; }		

function EWGListKeyOrder($arr) {
	ksort($arr);
	$o=array();
	foreach($arr as $v) $o[]=$v;
	return $o;
	}

function EWGLoadProfileFile($file,$user,$group) {
	
	if ($user===false) {
		$p=array(
			$_SERVER['DOCUMENT_ROOT'].'/etc')
			;
			
		} else {
		if (!WGValidUser($user)) return false;
		if (!WGValidUser($group)) return false;
		$p=array(
			$_SERVER['DOCUMENT_ROOT'].'/etc',
			$_SERVER['DOCUMENT_ROOT'].'/etc/_grp/_all',
			$_SERVER['DOCUMENT_ROOT'].'/etc/_grp/'.$group,
			$_SERVER['DOCUMENT_ROOT'].'/home/'.$user.'/.webgui')
			;
		}
	
	$t0=array();
	foreach($p as $f) {
		$f=$f.'/'.$file;
		if (file_exists($f)) {
			$t1=@file_get_contents($f);	
			if ($t1!==false) {
				$t1=@json_decode($t1,true); 
				if (is_array($t1)) $t0=array_merge($t0,$t1);
				}
			}
		}
	if (!count($t0)) return false;
	$rs=array();
	foreach($t0 as $db) {
		$r=true;
		if (isset($db['can'])) {
			$r=WGCan($db['can']);
			}
			
		if (isset($db['group'])) {
			if ($group===false or $user===false) {
				$r=false;
				} else {
				$r = isset($db['group'][$group]) ? $db['group'][$group] : true;
				}
			}
		if ($r) $rs[] = $db;
		}
		
	return EWGListKeyOrder($rs);
	}
	
function WGAutoResizeWin() {
	global $WEBGUI;
	if (!isset($WEBGUI['win'])) return false;
	$WEBGUI['win']['autoResize']=true;
	return true;
	}	
	
function WGDetectLang($forceReload) {
	global $ini;
	
	if (
		!$forceReload and 
		isset($_SESSION['WGLang']) and 
		$_SESSION['WGLang']!='' and 
		$_SESSION['WGLang']!='_D_'
		) return;
		
	if (isset($ini['webgui']['lang'])) {
		$defLang = strtolower(trim($ini['webgui']['lang'],' '));
		if (!preg_match('/^[a-z]{2}\-[a-z]{2}$|^[a-z]{2}$/',$defLang)==0) $defLang='en';
		} else $defLang='en';
	
	$t0 = @$_SERVER['HTTP_ACCEPT_LANGUAGE'];
	$curLang=false;
	if ($t0!='') {
		$t0=explode(',',$t0);
		$langList=array();
		foreach($t0 as $t1) {
			$t1=trim($t1,' ');
			$t1=strtolower($t1);
			list($t1)=explode(';',$t1,2);
			if (preg_match('/^[a-z]{2}\-[a-z]{2}$/',$t1)!=0) $langList[] =$t1;
			if (preg_match('/^[a-z]{2}$/',$t1)!=0) $langList[] =$t1;
			}
			
		foreach($langList as $t1) {
			if ($defLang && $defLang==$t1) $t1='_D_';
			if (!file_exists($_SERVER['DOCUMENT_ROOT'].'/etc/lang/'.$t1.'.json')) continue;
			$curLang=$t1;
			break;
			}	
		}

	if (isset($JSON['data']['lang']) and !$curLang) {
		$t1=strtolower($JSON['data']['lang']);
		if (preg_match('/^[a-z]{2}\-[a-z]{2}$|^[a-z]{2}$/',$t1)!=0) $curLang = $t1;
		}

	if ($curLang) WGSetLang($curLang);
	}	
	
function WGSetLang($lang) {
	global $WEBGUI;
	global $OUTJSON;
	global $ini;
		
	$lang=strtolower($lang);
	if (preg_match('/^[a-z]{2}\\-[a-z]{2}$|^[a-z]{2}$|^\_d\_$/',$lang)==0) return false;
	if ($lang=='_d_') $lang='_D_';
	
	if (isset($ini['webgui']['lang'])) {
		$defLang = strtolower(trim($ini['webgui']['lang'],' '));
		if (!preg_match('/^[a-z]{2}\-[a-z]{2}$|^[a-z]{2}$/',$defLang)==0) $defLang='en';
		} else $defLang='en';
	
	if ($lang==$defLang) $lang='_D_';
	
	$_SESSION['WGLang']=$lang;
	$WEBGUI['lang']=$lang;
	
	$jsLang=array();
	$lst=array($lang);
	
	if (strlen($lang)>3) {
			list($a)=explode('-',$lang,2);
			$lst[] = $a;
			}
	
	if ($lang!='_D_') $lst[] = '_D_';
	
	foreach($lst as $k) {
		$rsLang = $_SERVER['DOCUMENT_ROOT'].'/etc/lang/'.$k.'.json';
		if (file_exists($rsLang)) {
			$jsLang = @file_get_contents($rsLang);
			if ($jsLang) $jsLang = json_decode($jsLang,true);
			if (!is_array($jsLang)) $jsLang=array();
			break;
			}
		}

	$OUTJSON['cmd'][] = array(
		'api'	=>	'setlang'	,
		'data'	=>	array(
					'id'	=>	$lang,
					'js'	=>	$jsLang	)		
					)
		;
		
	return true;
	}	
	
function WGKillAll($app=false) {
	global $OUTJSON;
	$OUTJSON['cmd'][] = array(
		'api'	=>	'killall',
		'data'	=>	$app )
		;
	}	
	
function EWGLogout($msg=false,$but=false) {
	global $ini;
	
	if (WGisHTTPAuth()) {
		noWindow();
		WGKillAll();
		WGLogout($msg,$but);
		} else {
		WGKillAll();
		WGLogout($msg,$but);
		if (!isset($ini['logon'])) EWGLoadProfile();
		}
				
	}	
	
function EWGLogon($login,$pass) {
	$x = WGLogonUser($login,$pass);
	if ($x===false) return false;
	$x = WGSession();
	if (!$x) return false;
	WGKillAll();
	$usr = WGSessionUser();
	EWGLoadProfile();
	return $usr;
	}	
	
function WGLang() { return $_SESSION['WGLang'] ? $_SESSION['WGLang'] : '_D_'; }	
	
function WGShellExecute($url) {
	global $CMD;
	$CMD[] = array(
		'api'	=>	'shell',
		'data'	=>	$url	)
		;
	}	
	
function execApp($ex,$action=AJ_SHELL) {
	global $CMD;
	global $OUTJSON;
	global $APPL;
	global $DBH;
	global $JSON;
	global $WEBGUI;	
	global $WGData;
	
	if (!is_array($ex)) $ex = WGParseFile($ex);
	if ($ex===false) FatalError("Invalid file");
	if (!$ex['D']) FatalError("E5 Invalid application");
	
	$lang=$_SESSION['WGLang'] ? $_SESSION['WGLang'] : '_D_';
		
	$rvar = array(
		'FILE'	=> $ex['w'],
		'PATH'	=> pathinfo($ex['w'],PATHINFO_DIRNAME),
		'URL'	=> @$JSON['url'])
		;
	
	if ($ex['D']) $rvar['APPPATH']=rtrim($ex['w'],'/').'/'; else $rvar['APPPATH']=rtrim($rvar['PATH'],'/').'/';
	
	$mf = json_decode( @file_get_contents($ex['f'].'/manifest.json') , true);
	if (!is_array($mf)) FatalError("Invalid Manifest");
	
	$x = $ex['f'].'/lang';
	if (file_exists($x) and is_dir($x)) {
		list($y)=explode('-',$lang,2);
		foreach(array($lang,$y,'_D_') as $kf) {
			$y = $x . '/'. $kf.'.json';
			
			if (file_exists($y)) {
				$x = @file_get_contents($y);
				$x = json_decode($x,true);
				if (!is_array($x)) FatalError("Can't load lang `$kf`");
				$rvar=array_merge($rvar,$x);
				$x=null;
				break;
				}
			}
		}
	
	$WEBGUI['rvar']=$rvar;
	
	$x = $ex['f'].'/'.$mf['ico'];
	if (file_exists($x)) $x=$ex['w'].'/'.$mf['ico']; else $x=null;
	
	//--form

	endWin();
	
	Window(
		$mf['title'], 
		$x, 
		$mf['w'] ? $mf['w'] : 320 ,
		$mf['h'] ? $mf['h'] : 240 )
		;
	
	if (@$mf['autoResize']) WGAutoResizeWin();
	
	if (isset($mf['winType'])) {
		if (is_numeric($mf['winType'])) $WEBGUI['win']['winType']=$mf['winType']; else {
			if ($mf['winType']=='nosize') $WEBGUI['win']['winType'] = WIN_NOSIZE;
			if ($mf['winType']=='dialog') $WEBGUI['win']['winType'] = WIN_DIALOG;
			if ($mf['winType']=='normal') $WEBGUI['win']['winType'] = WIN_NORMAL;
			if ($mf['winType']=='popup') $WEBGUI['win']['winType'] = WIN_POPUP;
			}
		}
		
	if (isset($mf['pox'])) {
			if (!is_numeric($mf['pox'])) {
				if ($mf['pox']=='center') 
					$mf['pox']=POX_CENTER; 
					else if ($mf['pox']=='rev') 
					$mf['pox']=POX_RB;
					else $mf['pox']=POX_NORMAL;
				}
			setWindowPox(
			$mf['pox'],
			isset($mf['x']) ? $mf['x'] : false,
			isset($mf['y']) ? $mf['y'] : false)
			;
		}
	
	if ($action == AJ_SHELL or $action == AJ_DIALOG) {
		$hdc=WGSNew($ex['w'], isset($mf['name']) ? $mf['name'] : false);
		$APPL['hdc']=$hdc;
		$APPL['asNew']=true;
		$APPL['url']=$ex['w']; 
		} else $hdc=$APPL['hdc'];
		
	setWindowContext($hdc,$mf['name'] ? $mf['name'] : false,$ex['w']);
		
	$WEBGUI['win']['data'] = WGSLoad($hdc,$ex['w'],isset($mf['name']) ? $mf['name'] : false);

	if (is_array($rvar) and ($action==AJ_SHELL or $action==AJ_DIALOG)) {
		if (isset($rvar['win']) and is_array($rvar['win'])) $WEBGUI['win']['winLang'] = $rvar['win'];
		if (file_exists($ex['f'].'/style.css')) WGWinCss($ex['w'].'/style.css',$rvar);
		if (isset($rvar['_title'])) $WEBGUI['win']['title'] = $rvar['_title'];
		}
			
	$f = $ex['f'].'/event.php';
	if (file_exists($f)) {
		WGRequirePHP($f,$rvar);
		
		$fn=false;
		
		if ($action==AJ_POST) {
			$fn='form_'.@$JSON['frm'].'_post';
			if (!function_exists($fn)) $fn='form_'.$APPL['act'].'_post'; 
			$WEBGUI['win']['data'][$APPL['act']]=$JSON['par'];
			}
			
		if ($action==AJ_WINAJAX) $fn='ajax_'.$APPL['api'];
		if ($action==AJ_EVENT) $fn='event_'.$APPL['api'];
		if (($action==AJ_SHELL || $action==AJ_DIALOG) && $APPL['act']!='') $fn='load_'.$APPL['act'];
		if (($action==AJ_SHELL || $action==AJ_DIALOG) && $APPL['act']=='') $fn='winMain';
		
		if ($fn and function_exists($fn)) {
			if (isset($WEBGUI['form']) and isset($JSON['frm']) and $JSON['frm']!='') {
				if (!isset($WEBGUI['form'][ $JSON['frm'] ])) FatalError("Unknown form `".$JSON['frm']."`");
				$formData=$JSON['par'];
				$err = WGFormCheck($WEBGUI['form'][ $JSON['frm'] ], $formData);
				if ($err) {
					foreach($WEBGUI['form'][ $JSON['frm']]['obj'] as $t0) {
						if ($t0['n']==$err) {
							FatalError( isset($t0['par']['err']) ? $t0['par']['err'] : "Invalid value for `".$t0['c']."`");
							}
						}
					FatalError("Invalid value for `$err`");
					}
				WGDestroyCaptcha($WEBGUI['form'][ $JSON['frm'] ]);
				} else {
					$formData=@$JSON['par']; 
				}
			$pr=$fn(@$JSON['par'],$formData);
			
			if ($action==AJ_POST and $pr) $OUTJSON['postReply']=$pr;
			if ($action==AJ_WINAJAX and $pr) $OUTJSON['ajaxReturn'] = $pr;
			}
				
		}
	
	$formFile=$ex['f'].'/form.conf';
	if (file_exists($formFile)) {
		$X = @file_get_contents($formFile);
		if ($X===false) FatalError("Can't read form.conf");
		$X = WGReplacer($X,$rvar);
		$WEBGUI['form'] = WGFormParser($X,$action);
		$X=null;
		$formFile=true;
		} else $formFile=false;
		
	if ($action == AJ_SHELL || $action == AJ_DIALOG) {
		
		$f = $ex['f'].'/app.js';
		if (file_exists($f)) winJS( @file_get_contents($f) );
	
		$f = $ex['f'].'/data.json';
		if (file_exists($f)) {
			$WEBGUI['win']['data'] = json_decode(@file_get_contents($f),true);
			if (!is_array($WEBGUI['win']['data'])) FatalError("Invalid `data.json` structure/syntax");
			}
			
		$f = $ex['f'].'/layout.php';
		if (file_exists($f)) WGRequirePHP($f,$rvar);
		
		if (isset($WEBGUI['form'])) {
			foreach($WEBGUI['form'] as $X) WGFormActivity($X);
			$X=null;
			}
			
		$f = $ex['f'].'/app.ejs';
		if (file_exists($f)) {
			$f = @file_get_contents($f);
			if ($f===false) FatalError("Can't load EJS");
			$f = WGReplacer($f,$rvar);
			$f=explode("\n",$f);
			WGParseEJSTag($f);
			} else {
			$f = $ex['f'].'/event.json';	
			if (file_exists($f)) {
				$js=@file_get_contents($f);
				$js=json_decode($js,true);
				if (!is_array($js)) FatalError("Can't load application.\nBad JSON sctructure in event.json");
				$WEBGUI['win']['event']=$js;
				$js=null;
				}
			}
		
		if (function_exists('layout_create')) layout_create();
		}	
		
	endWin();
	}

$DBERROR=LIBDBERROR;
?>
