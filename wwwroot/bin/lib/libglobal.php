<?
define('EWGVERSION',"8.0.0.1");

define('AJ_SYSTEM',0);
define('AJ_SYSCALL',1);
define('AJ_WINAJAX',2);
define('AJ_SHELL',3);
define('AJ_FLUX',4);
define('AJ_EVENT',5);
define('AJ_POST',6);
define('AJ_DIALOG',7);
define('AJ_LIST',' 0 1 2 3 4 5 6 7 ');
define('EWG_NOT_LOADED',false);
	
$MIME=EWG_NOT_LOADED; //DO NOT MODIFY!
$WGALLOWDIR = array();
$MODULES=array();
$WEBGUIhttpAuth=false;

$GLOBALSHORTCUT = array(
	'app'	=>	'/bin/app',
	'sys'	=>	'/bin/app/webgui',
	'dlg'	=>	'/bin/dlg',
	'grp'	=>	'/home',
	'home'	=>	'/home')
	;

function WGGetIni() {
	global $ini;
	return $ini;
	} 

function WGisHTTPAuth() {
	global $WEBGUIhttpAuth;
	return $WEBGUIhttpAuth ? true : false;
	}
 
function WGRequirePHP($PHPFILE,$APPVAR=array()) {
	global $CMD;
	global $OUTJSON;
	global $APPL;
	global $DBH;
	global $JSON;
	global $WEBGUI;	
	global $WGData;
	$WEBGUI['phpPath'] = pathinfo($PHPFILE,PATHINFO_DIRNAME).'/';
	require $PHPFILE;
	}  
    
function WGLoadMIME() {
	global $MIME;
	if (is_array($MIME)) return $MIME;
	
	$MIME = json_decode( 
		@file_get_contents($_SERVER['DOCUMENT_ROOT'].'/etc/mime.json') , true 
		) ;
		
	if (!is_array($MIME)) $MIME=array();
	return $MIME;
	}
	
function WGgetMimeName($ext,$d) {
	global $MIME;
	if (!is_array($MIME)) WGLoadMIME();
	$x= $d ? 'dir' : 'fil';
	$r = strtolower($ext);
	if ($r=='app' and $d) return array(
			'n' => 'Application',
			'i' => '*icon.png')
			;
			
	if (isset($MIME[$r][$x]['x'])) return $MIME[$r][$x]['x'];
	if (isset($MIME[$r]['x'])) return $MIME[$r]['x'];
	if (isset($MIME[$r]['r'])) return WGgetMimeName($MIME[$r]['r'],$d);
	if (isset($MIME['.def']['x'])) return $MIME['.def']['x'];
	return false;	
	}
	
function WGValidUser($usr) {
	return preg_match('/^[a-zA-Z0-9]{1,40}$/',$usr) >0;
	}	
	
function WGCreateGroup($name,$can=array(),$id=false) {
	global $DBH;
	
	if (!WGValidUser($name)) return false;
	
	$g = array(
		'name'	=>	$name	,
		'can'	=>	json_encode($can))
		;
		
	if ($id!==false) {
		$g['id']=$id;
		dbquery($DBH,"SELECT COUNT(*) as cx FROM `ewggroup` WHERE `id`='".intval($id)."' OR `name`='".dbenc($DBH,$name)."' LIMIT 1");
		} else {
		dbquery($DBH,"SELECT COUNT(*) as cx FROM `ewggroup` WHERE `name`='".dbenc($DBH,$name)."' LIMIT 1");
		}
	
	$db=dbget($DBH);
	if ($db['cx']>0) return false;
	
	dbinsert($DBH,'ewggroup',$g);
	return true;
	}	
	
function WGCreateUser($name,$pass,$group,$can=array(),$data=array(),$id=false,$noHome=false) {
	global $DBH;
	
	if (!WGValidUser($name) or !WGValidUser($group)) return false;
	
	$g=dbfirst($DBH,'ewggroup','name',$group);
	if ($g===false) return false;
	
	$c = json_decode($g['can'],true);
	if (!is_array($c)) return false;
	$c=array_merge($c,$can);
	
	mt_srand(crc32(microtime().mt_rand(0,19999999)));
	$u = array(
		'name'	=>	$name,
		'group'	=>	$g['id']	,
		'can'	=>	json_encode($c),
		'data'	=>	json_encode($data),
		'session'=>	'[]',
		'rnd'	=>	'',
		'pass'	=>	'')
		;
	
	if ($id!==false) {
		$u['id']=$id;
		dbquery($DBH,"SELECT COUNT(*) as cx FROM `ewguser` WHERE `id`='".intval($id)."' OR `name`='".dbenc($DBH,$name)."' LIMIT 1");
		} else {
		dbquery($DBH,"SELECT COUNT(*) as cx FROM `ewguser` WHERE `name`='".dbenc($DBH,$name)."' LIMIT 1");
		}
	
	$db=dbget($DBH);
	if ($db['cx']>0) return false;
		
	for ($a=0;$a<16;$a++) $u['rnd'].=chr(mt_rand(33,126));
	$u['pass']=sha1($pass.$u['rnd']);
	dbinsert($DBH,'ewguser',$u);
	if ($noHome) return true;
	$t=$_SERVER['DOCUMENT_ROOT'].'/home/'.$name;
	if (@mkdir($t)===false) return false;
	if (@mkdir($t.'/.webgui')===false) return false;
	if ($c['home']) @mkdir($t.'/home.dir');
	$o=$t; //$_SERVER['DOCUMENT_ROOT'].'/home/'.$name;
	$f=$_SERVER['DOCUMENT_ROOT'].'/etc/.skel/all';
	$rs=array();
	if (file_exists($f)) WGXCopy($f,$o,$rs);
	$f=$_SERVER['DOCUMENT_ROOT'].'/etc/.skel/grp/'.$g['name'];
	if (file_exists($f)) WGXCopy($f,$o,$rs);
	if (count($rs)!=0) {
		$t0='';
		foreach($rs as $li) $t0.="Error {$li['o']}:\t{$li['f']}\n";
		@file_put_contents($o.'skel-error.log',$t0);
		$t0=null;
		}	
	$rs=null;
	return true;
	}

function WGDelThree($path,&$stdErr=array()) {
	if (trim($path,'/\\')=='') return false;
	if (strpos("/$path/","/../")!==false) return false;
	if (strpos("/$path/","/./")!==false) return false;
	
	$dh=@opendir($path);
	if ($dh===false) {
	        $stdErr[] = $path;
	        return false;
		}
		
	while($fi=readdir($dh)) {
		if ($fi=='.' or $fi=='..') continue;
		$tf = $path.'/'.$fi;
		if (is_dir($tf)) {
			WGDelThree($tf,$stdErr);
			continue;
			}
		
		$x = @unlink($tf);
		if ($x===false) $stdErr[] = $tf;
		}
		
	closedir($dh);
	$x = @rmdir($path);
	if ($x===false) $stdErr[] = $path;
	return count($stdErr)==0 ? true : false;
	}
	
function WGRemoveUser($name,$rmHome=true) {
	global $DBH; 
	if (!WGValidUser($name)) return false;
	$g=dbfirst($DBH,'ewguser','name',$name);
	if ($g===false) return false;
	$c=json_decode($g['can'],true);
	if (is_array($c) and $c['root']) return false;
	dbdelete($DBH,'ewguser',$g);
	if ($rmHome and $g['name']!='') return WGDelThree($_SERVER['DOCUMENT_ROOT'].'/home/'.$g['name']);
	return true;
	}	
	
function WGLogonUser($name,$pass) {
	global $DBH;
	global $JSON;
	if (!WGValidUser($name)) return false;
	if (!isset($_SESSION['EWGRax']) and is_array($JSON) and isset($JSON['rax'])) $_SESSION['EWGRax']=$JSON['rax'];
	$u=dbfirst($DBH,'ewguser','name',$name);
	if ($u===false) return false;
	$c = sha1($pass.$u['rnd']);
	if ($c!=$u['pass']) return false;
	unset($u['rnd']);
	unset($u['pass']);
	$u['ir']='';
	for ($a=0;$a<16;$a++) $u['ir'].=chr(mt_rand(33,126));
	$u['ip']=md5($_SERVER['REMOTE_ADDR']."\n".$_SERVER['HTTP_USER_AGENT']."\n".$u['ir']);
	
	foreach(array('can','data','session') as $K) {
		$u[$K]=json_decode($u[$K],true);
		if (!is_array($u[$K])) FatalError("User profile corrupted!");
		}
		
	$_SESSION['EWGUser'] = $u;
	$g=dbfirst($DBH,'ewggroup','id',$u['group']);
	$_SESSION['EWGUser']['group'] = $g;
	return true;
	}
		
function WGPasswd($name,$pass,$newPass) {
	global $DBH;
	if (!WGValidUser($name)) return false;
	$u=dbfirst($DBH,'ewguser','name',$name);
	if ($u===false) return false;
	if ($pass!==false and $u['pass']!=sha1($pass.$u['rnd'])) return false;
	
	$c = sha1($pass.$u['rnd']);
	mt_srand(crc32($u['rnd'].microtime().mt_rand(0,1000000)));
	$u['rnd']='';
	for ($i=0;$i<16;$i++) $u['rnd'].=chr(mt_rand(33,126));
	$u['pass']=sha1($newPass.$u['rnd']);
	dbupdate($DBH,'ewguser',$u);
	return true;
	}

function WGExpandUrl($url) {
	global $GLOBALSHORTCUT;
	if (strlen($url)<3) return $url;
	if ($url[0]!='.') return $url;
	if (preg_match('/^\.([a-z]{1,5})\:(.*)$/', $url, $mat)==0) return $url;
	if (count($mat)!=3) return $url;
	if (!isset($GLOBALSHORTCUT[$mat[1]])) return $url;
	if ($mat[2]=='.') $mat[2]='';
	return $GLOBALSHORTCUT[$mat[1]].'/'.$mat[2];
	}
	
function WGLogout($msg=false,$but=false) {
	global $WEBGUIhttpAuth;
	global $OUTJSON;
	global $WEBGUI;
	global $ini;
	unset($WEBGUI['win']['data']);
	
	@session_start();
	unset($_SESSION['EWGUser']);
	if ($WEBGUIhttpAuth) $_SESSION=array();
	if (is_array($OUTJSON) and isset($OUTJSON['cmd'])) {
		if ($WEBGUIhttpAuth or isset($ini['logon'])) {
			$OUTJSON['cmd'][] = array(
				'api'	=>	'logout',
				'data'	=>	array(
					'msg'	=>	$msg,
					'but'	=>	$but)
					)
				;
				
			$_SESSION=array();
			}
		} else {
		$OUTJSON['cmd'][] = array(
				'api'	=>	'logon',
				'data'	=>	array()
					)
				;	
		}
	}	
	
function WGSession() {
	global $WGALLOWDIR;
	global $GLOBALSHORTCUT;
	
	@session_start();
	if (!isset($_SESSION['EWGUser'])) return false;
	$x=md5($_SERVER['REMOTE_ADDR']."\n".$_SERVER['HTTP_USER_AGENT']."\n".$_SESSION['EWGUser']['ir']);
	
	if ($_SESSION['EWGUser']['ip']!=$x) {
		$_SESSION=array('EWGSessionError' => "Session stolen: Your session may be stolen!\n The IP address of your client does not correspond to the session's ID");
		if (isset($_SESSION['EWGSessionError'])) WGLockGui(3,"Stolen");
		}
		
	$x='/home/_grp/'.$_SESSION['EWGUser']['group']['name'];
	$h='/home/'.$_SESSION['EWGUser']['name'];
	
	$GLOBALSHORTCUT['grp']=$x;
	$GLOBALSHORTCUT['home']=$h;
	WGAllowPath(array($x,$h));
	
	return true;
	}	
	
function WGSessionSave($appl,$data) {
	@session_start();
	if (isset($_SESSION['EWGSessionError'])) FatalError($_SESSION['EWGSessionError']);
	if (!isset($_SESSION['EWGUser'])) return false;
	$db=dbfirst($DBH,'ewguser','id',$_SESSION['EWGUser']['id']);
	if ($db===false) return false;
	$dt=json_decode($db['session'],true);
	if (!is_array($dt)) $dt=array();
	if ($data===null) unset($dt[$appl]); else $dt=array_merge($dt,array($appl => $data));
	$db['session'] = json_encode($dt);
	dbupdate($DBH,'ewguser',$db);
	$_SESSION['EWGUser']['session']=$dt;
	return true;
	}
	
function WGSessionLoad($appl) {
	if (isset($_SESSION['EWGSessionError'])) FatalError($_SESSION['EWGSessionError']);
	if (!isset($_SESSION['EWGUser'])) return false;
	if (!isset($_SESSION['EWGUser']['session'][$appl])) return null;
	return $_SESSION['EWGUser']['session'][$appl];
	}
	
function WGSessionUser() {
	if (isset($_SESSION['EWGSessionError'])) FatalError($_SESSION['EWGSessionError']);
	if (!isset($_SESSION['EWGUser'])) return false;
	return $_SESSION['EWGUser'];
	}
	
function WGCan($stp) {
	if (isset($_SESSION['EWGSessionError'])) FatalError($_SESSION['EWGSessionError']);
	if (!isset($_SESSION['EWGUser'])) return false;
	$stp=explode(' ',$stp);
	$c=null;
	foreach($stp as $can) {
		if (!isset($_SESSION['EWGUser']['can'][$can]) or $_SESSION['EWGUser']['can'][$can]==false) return false;
		if (isset($_SESSION['EWGUser']['can'][$can])) {
			if ($c===null) $c=true;
			$c&=($_SESSION['EWGUser']['can'][$can]!=false);
			}
		}
	if ($c===null) return false;
	return $c;
	}	
		
function WGAllowPath($path) {
	global $WGALLOWPATHS;
	if (!is_array($path)) $path=array($path);
	
	foreach($path as $p) {
		$p=str_replace('\\','/',$p);
		while(strpos($p,'//')!==false) $p=str_replace('//','/',$p);
		$p=trim($p,'/');
		$p='/'.$p.'/';
		if (strpos($p,'/./')!==false or strpos($p,'/../')!==false) continue;
		$WGALLOWPATHS[]=$p;
		}
		
	$a=0;		
	$q=array();
	
	foreach($WGALLOWPATHS as $d) {
		$l=strlen($d);
		$id=str_pad($l,3,'0',STR_PAD_LEFT);
		$id.=str_pad($a,3,'0',STR_PAD_LEFT);
		$a++;
		$q[$id]=$d;
		}	
	krsort($q);
	$WGALLOWPATHS=array();
	foreach($q as $p) $WGALLOWPATHS[]=$p;		
	}	
	
function WGisAllowed($p) {
	global $WGALLOWPATHS;
	$path=str_replace('\\','/',$path);
	while(strpos($path,'//')!==false) $path=str_replace('//','/',$path);
	$p=trim($p,'/');
	$p='/'.$p.'/';
	foreach( $WGALLOWPATHS as $a) {
	        if (strpos($p,$a)===0) return true;
		}
	return false;
	}

function addFunction(&$obj,$member,$firm,$code) {
	$obj['_'.$member] = array(
		'p'	=>	$firm	,
		'f'	=>	$code	)
		;
	
	}

function addFunctionPointer(&$obj,$member,$pointer) {
	$obj['__'.$member] = $pointer;
	}

function filterFile($st,$canRoot=false) {
        $st=str_replace(array('\\','/../','/.','..','//'),chr(255),$st);
        if (!preg_match('/^[a-zA-z0-9\/\.\-\_\@]+$/',$st)) return false;
        if (!$canRoot and @$st[0]=='/') return false;
        if (strlen($st)==0) return false;
        return $st;
	}

function WGParseFile($fl,$etc=false) {
	$fl = filterFile($fl,true);
	if ($fl===false) return false;
	
	if ($fl[0]!='/') $fl='/'.$fl;
	
	$q=array(
		'w'	=>	$fl	,
		'f'	=>	$_SERVER['DOCUMENT_ROOT'].$fl )
		;
	
	$q['id'] = is_dir($q['f']);
	$q['if'] = is_file($q['f']);
	$q['ie'] = file_exists($q['f']);
	$q['F'] = ($q['ie'] && $q['if']) ? true : false;
	$q['D'] = ($q['ie'] && $q['id']) ? true : false;

	if ($etc) {
		$x=pathinfo($fl);
		foreach($x as $k => $v) $q[$k]=$v;
		}

	return $q;	
	}

function EWGGetFonts() {
	global $FONTS;
	if (is_array($FONTS)) return $FONTS;
	
	$f=$_SERVER['DOCUMENT_ROOT'].'/sys/font';
	$mt=filemtime($f);
	$json = @file_get_contents($_SERVER['DOCUMENT_ROOT'].'/var/cache/font.json');
	$json = @json_decode($json,true);
	if (is_array($json) and $json['m']==$mt) {
		$FONTS=$json;
		return $FONTS;		
		} else $FONTS=array();
		
	return $FONTS;
	}
    
function WGThreeList($path,$isFull=false,$filterExt=false,&$rs=array()) {
	$path=rtrim($path,'/');
	$fsPath=WGSubPath($_SERVER['DOCUMENT_ROOT'],$path);
	
	if (!is_array($rs)) $rs=array();
	if ($filterExt) {
		if (!is_array($filterExt)) {
			$filterExt=str_replace(array(' ',',','.'),'.',$filterExt);
			$filterExt=trim($filterExt,'.');
			$filterExt=explode('.',$filterExt);
			}
		}

	if ($filterExt) $flt = '.'.implode('.',$filterExt).'.';
		
	$dh=@opendir($fsPath);
	if ($dh===false) return false;
	while($fi=readdir($dh)) {
		if ($fi[0]=='.') continue;
		$tf = $fsPath.'/'.$fi;
		$wf = $path.'/'.$fi;
		$d=is_dir($tf);
		$f=is_file($tf);
		if (!$d and !$f) continue;
		if ($d) {
			WGThreeList($wf,$isFull,$filterExt,$rs);
			continue;
			}
			
		if ($filterExt and stripos($flt,'.'.pathinfo($fi,PATHINFO_EXTENSION).'.')===false) continue;
		$x = $isFull ? $tf : $wf;
		$rs[] = $x;
		}
		
	closedir($dh);
	return $rs;
	}
          
function EWGCacheFont() {
	global $FONTS;
	
	$f=$_SERVER['DOCUMENT_ROOT'].'/sys/font';
	$mt=filemtime($f);
	$json = @file_get_contents($_SERVER['DOCUMENT_ROOT'].'/var/cache/font.json');
	$json = @json_decode($json,true);
	if (is_array($json) and $json['m']==$mt) {
		$FONTS=$json;
		return $FONTS;		
		}
	
	$dh=opendir($f);
	
	$font = array();
	$phpFont=array();
	$captchaFont=array();
	
	$list = WGThreeList('sys/font',false,'ttf,woff,fon,svg,eot');
	foreach($list as $file) {
		$face = pathinfo($file,PATHINFO_FILENAME);
		
		$face=str_replace(array(' ','_','.'),'-',$face);
		while(strpos('--',$face)!==false) $face=str_replace('--','-',$face);
		
		$face=trim($face,'-');
		if (preg_match('/^[a-zA-Z0-9\_]{1,40}$/',$face)==0) continue;
		
		$ext = pathinfo($file,PATHINFO_EXTENSION);
		$ext=strtolower($ext);
				
		if ($ext=='ttf') {
			$phpFont[$face]=$file;
			if (strpos($file,'/font/captcha/')!==false) $captchaFont[$face]=$file;
			
			if (strpos($file,'/font/_captcha/')!==false) {
				$captchaFont[$face]=$file;
				continue;
				}
			}
		
		if (!isset($font[$face])) $font[$face]=array();
		
		$font[$face][] = $file;
		
		}
	$css='';
	$json=array(
		'm'		=>	filemtime($f)	,
		'font'	=>	array()			,
		'php'	=>	array()			,
		'captcha'=> array()			)
		;
	
	foreach($font as $k=>$v) $json['font'][] = array( 
		'face'	=>	$k,
		'file'	=>	$v)
		;
	
	foreach($phpFont as $k => $v) $json['php'][] = array(
		'face'	=>	$k,
		'file'	=>	$v)
		;
	
	foreach($captchaFont as $k => $v) $json['captcha'][] = array(
		'face'	=>	$k,
		'file'	=>	$v)
		;
	
	$t0=json_encode($json, JSON_PRETTY_PRINT);
	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/var/cache/font.json',$t0);
	$t0=null;
	$FONTS=$json;
	
	$ty=array(
		'eot'	=>	'embedded-opentype',
		'ttf'	=>	'truetype'	)
		;
	
	foreach($font as $k => $v) {
		$css.= "@font-face {\n\t";
		$css.= "font-family: '".htmlspecialchars($k,ENT_QUOTES)."';\n\t";
		$css.= "src: ";
		$m=count($v)-1;
		foreach($v as $a => $l) {
			$x=pathinfo($l,PATHINFO_EXTENSION);
			if (isset($ty[$x])) $x=$ty[$x];
			$css.="url(/".htmlspecialchars($l,ENT_QUOTES).") format('".htmlspecialchars($x,ENT_QUOTES)."')";
			if ($a!=$m) $css.=",\n\t\t"; else $css.=";\n\t";
			}
		$css.="}\n\n";
		}

	file_put_contents($_SERVER['DOCUMENT_ROOT'].'/var/cache/font.css',$css);
	$css=null;
	return $FONTS;		
	}   
	
function WGParseURI($st) {
	$u = parse_url($st);
	if (isset($u['scheme'])) return false;
	if (!isset($u['path'])) return false;
	$p = pathinfo($u['path']);
	$qq=array();
	if ($u['query']) parse_str($u['query'],$qq);
	$q=array('url' => $st);
	$q=array_merge($q,$u);
	$q=array_merge($q,$p);
	$q['par']=$qq;
	return $q;
	}
/*
[url] => /sys/piccolo.pup?q=0.8&f=5#lol
[path] => /sys/piccolo.pup
[query] => q=0.8&f=5
[fragment] => lol
[dirname] => /sys
[basename] => piccolo.pup
[extension] => pup
[filename] => piccolo
[par] => Array
    (
        [q] => 0.8
        [f] => 5
    )

*/

function WGReplacer($txt,$arr,$Sx='<%<',$Dx='>%>') {
	$sxl=strlen($Sx);
	$dxl=strlen($Dx);
	$o=array();
	$j = strlen($txt);
	for ($i=0;$i<$j;$i++) {
		$p = strpos($txt,$Sx);
		if ($p!==false) {
		        $o[] = array(0,substr($txt,0,$p));
		        $txt = substr($txt,$p+$sxl);
		        $f = strpos($txt,$Dx);
		        if ($f!==false) {
			        $t = substr($txt,0,$f);
			        list($a,$b)=explode('_',$t.'_');
			        $txt=substr($txt,$f+$dxl);
			        $o[] = array(1,$a,$b);
				}
			} else break; 
		}
	if (strlen($txt)>0) $o[] = array(0,$txt);
	$txt='';
	foreach($o as $t) {
		if ($t[0]==0) $txt.=$t[1]; else {
		        $v = $arr[$t[1]] ? $arr[$t[1]] : " —„ –ˆ{$t[1]} –ˆ – ";
			if ($t[2]=='c') $v=addcslashes($v,"\t\r\n\\\"`'/<>&");
			if ($t[2]=='h') $v=htmlspecialchars($v,ENT_QUOTES);
			if ($t[2]=='ch') $v=htmlspecialchars(addcslashes($v,"\t\r\n\\\"`'/"),ENT_QUOTES);
			$txt.=$v;
			}
		}
	$txt=str_replace($Sx,'&lt; À&lt;',$txt);
	$txt=str_replace($Dx,'&gt;  &gt;',$txt);
	return $txt;
	}
	
function WGSubPath($a,$b) {
	$a=str_replace('\\','/',$a);
	$b=str_replace('\\','/',$b);
	$a=rtrim($a,'/');
	$b=ltrim($b,'/');
	return $a.'/'.$b;
	}	
	
function WGHTTPExit($stat,$cod=404) {//swp
	header("HTTP/1.1 $cod $stat",true,$cod);
	header("Content-Type: text/plain; charset=UTF-8",true,$cod);
	header("Expires: ".gmdate('r',0),true,$cod);
	header("Pragma: no-cache",true,$cod);
	exit($stat);
	}	
	
function WGHTTPRedirection() {
	$fileo=isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : @$_SERVER['REQUEST_URI'];
	if ($fileo=='') WGHTTPExit("Access Denied",403);

	if ((strpos($fileo,'/.')===0 or $fileo[0]=='.') and strpos($fileo,':')>3) {
		$fileo = WGExpandUrl($fileo);
		}

	$file=WGParseFile($fileo,true);
		
	if ($file===false) WGHTTPExit("File not found",404);
	if (!WGisAllowed($file['w'])) WGHTTPExit("Access Denied",403);
	
	if ($file['D']) {
		if ($file['extension']=='dir' or $file['extension']=='app') {
			header("Content-Type: text/html; charset=UTF-8",true,200);
			return $fileo;
			}
		
		WGHTTPExit("File not found",404);
		}

	$mx=trim(strtolower($file['extension']),' ');
	$mx=str_replace(' ','',$mx);
	
	if ($file['name'][0]=='.') WGHTTPExit("File not found",404);

	if (strpos(' ini conf ejs phpe htaccess htpasswd php json css js app dlg html '," $mx ")!==false) WGHTTPExit("Access Denied",403);
	$mime = @json_decode( @file_get_contents($_SERVER['DOCUMENT_ROOT'].'/etc/mimehome.json') , true);
	if (!is_array($mime)) $mime=array();
	if (isset($mime['_ALLOWURL_'])) {
		if (strpos(' '.$mime['_ALLOWURL_'].' '," $mx ")!==false) {
			header("Content-Type: text/html; charset=UTF-8",true,200);
			return $fileo;
			}
		}
	
	if (!isset($mime[$mx])) WGHTTPExit("Unknown file mime type",403);
	$m=$mime[$mx];

	$noCache=false;
	if (isset($mime['_NOCACHE_'])) {
		if (strpos(' '.$mime['_NOCACHE_'].' '," $mx ")!==false) $noCache=true;
		}
	
	$mt = filemtime($file['f']);
	$sz = filesize($file['f']);
	$T=md5($mt.$sz.$file['f']);
	$D=date('r',$mt);
	$A=true;
		
	if ($noCache) {
		header("Expires: ".gmdate('r',0),true,200);
		header("Pragma: no-cache",true);
		$A=true;
		$S=200;
		$ST='Ok';
		} else {
		
		if (trim(@$_SERVER['HTTP_IF_NONE_MATCH'],'"')==$T or @$_SERVER['HTTP_IF_MODIFIED_SINCE']==$D) {
				$A=false;
				$S=304;
				$ST='Not modified';
			} else {
				$A=true;
				$S=200;
				$ST='Ok';
				}
		
		header("Last-Modified: ".$D,true);
		header("Etag: \"".$T."\"",true);
		header("Expires: ".date('r',time()+86400),true);
		header("Cache-Control: private, max-age=86400",true);
		header_remove('pragma');
		}
	
	header("HTTP/1.1 $S $ST",true,$S);
	header("Content-Type: $m",true,$S);
	header("Content-Length: $sz",true);
	
	@ob_get_clean();
	ob_implicit_flush(true);
	if ($A and @$_SERVER['REDIRECT_REQUEST_METHOD']!='HEAD') @readfile($file['f']);
	exit;
	}

function _WGHTTPRequestAuth($realm) {
	header('WWW-Authenticate: Basic realm="'.$realm.'"',true,401);
    header('HTTP/1.0 401 Unauthorized');
    if ($_SERVER['REQUEST_METHOD']=='POST') die('{"err":"Access denied"}');
    die('Access denied');
	}

function WGHTTPAuth() {
	global $ini;
	global $DBH;
	$realm = @$_SESSION['HTTP_REALM'];
	
	if ($realm=='') {
		mt_srand(crc32(microtime().mt_rand(1,99999999)));
		$a=time();
		$a^=$a>>1;
		$a=crc32(pack('V',$a));
		$a^=$a>>1;
		$a&=0x7FFFFFFFF;
		$b = mt_rand(1679616,60466175);
				
		$realm=@$ini['auth']['realm'] ? $ini['auth']['realm'] : '';
		$realm.=' ('.base_convert($a,10,36) .' - '.base_convert($b,10,36).')';
		$realm=trim($realm.' ');
		$_SESSION['HTTP_REALM']=$realm;
		}
	
	if (!WGSession()) {
		$ok=false;
		$no=(@$_SESSION['HTTP_RETRY'] > 5 ? true : false);
		
		if (isset($_SERVER['PHP_AUTH_USER']) and WGValidUser($_SERVER['PHP_AUTH_USER'])) {
			$t=str_replace(array("\t",' '),',', ( @$ini['auth']['nouser'] ? $ini['auth']['nouser'] : 'root'));
			$t=trim($t,',');
			$t=explode(',',$t);
			foreach($t as $u) {
				$u=trim($u," ");
				if ($u=='') continue;
				if ($u==$_SERVER['PHP_AUTH_USER']) {
					$no=true;
					break;
					}
				}
			
			if (!$no) {
				$ok = WGLogonUser($_SERVER['PHP_AUTH_USER'],@$_SERVER['PHP_AUTH_PW']);
				}
			}

		if (!$ok) @$_SESSION['HTTP_RETRY']++;
		if (!$ok or !WGSession()) _WGHTTPRequestAuth($realm);
		}
	
	}
	
function WGXCopy($src,$dest,&$stdErr=array()) {
	$dh=@opendir($src);
	if ($dh===false) {
		$stdErr[] = array('f'=>$src,'o'=>'open');
		return false;
		}
		
	if (file_exists($dest)==false and @mkdir($dest)==false) {
		$stdErr[] = array('f'=>$dest,'o'=>'mkdir');
		return false;
		}
		
	while($fi = readdir($dh)) {
		if ($fi=='.' or $fi=='..') continue;
		$i = $src.'/'.$fi;
		$o = $dest.'/'.$fi;
		if (is_dir($i)) {
			WGXcopy($i,$o,$stdErr);
			continue;
			}
			
		if (!copy($i,$o)) {
			$stdErr[] = array('f'=>$o,'o'=>'copy');
			return false;
			}
		}
		
	closedir($dh);
	return count($stdErr)==0 ? true : $stdErr;
	}	
	
?>
