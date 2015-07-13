<?
// Version: WebGui8 Ver. 0.0.1
define('EWGINCLUDE',true);

require 'bin/lib/libbase.php';

if (isset($ini['auth']) and isset($ini['logon'])) {
	die("Bad auth + logon configuration");
	}

if ($_SERVER['REQUEST_METHOD']=='POST') {
	ob_start();
	require 'bin/lib/webgui.php';
	$STDIN=@fopen('php://input','rb');
	if ($STDIN===false) die('{"err":"Invalid Request"}');
	$JSON=fgets($STDIN,65535);
	$JSON=trim($JSON,"\t\r\n ");
	$JSON=json_decode($JSON,true);
	if (!is_array($JSON)) die('{"err":"Invalid Request"}');	
	$CMD=array();
	$OUTJSON=array();
	
	if (!isset($JSON['t']) or !is_numeric($JSON['t']) or strpos(AJ_LIST,' '.$JSON['t'].' ')===false) die('{"err":"Invalid Request type"}');
	if (is_array($ini['db']) && !$DBH) dbopen($DBH);
	WGSGarbage();
	$t0=WGSession();
	
	if (isset($ini['auth']) or isset($ini['logon']) and !$t0) {
		if ($JSON['t']!=AJ_SYSTEM) WGLockGui(3,"Session");
		}
	
	if (isset($JSON['url'])) $JSON['url'] = WGExpandUrl($JSON['url']);
	
	if ($JSON['t']==AJ_DIALOG) {
		$JSON['url']='bin/dlg/'.$JSON['url'].'.app';
		$WEBGUI['dialog'] = array(
			"dialog"=>	array(
				"by"	=>	$JSON['by']	,
				"datum"	=>	$JSON['datum'],
				"fun"	=>	$JSON['fun'],
				"par"	=>	$JSON['par'])
				,
			"winType"=>	WIN_DIALOG)
			;
		}
	
	$APPL = array(
		't'		=>	$JSON['t']	,
		'api'	=>	$JSON['api'] ? $JSON['api'] : null ,
		'url'	=>	$JSON['url'] ? $JSON['url'] : null ,
		'hdc'	=>	$JSON['hdc'] ? intval($JSON['hdc']) : 0,
		'app'	=>	$JSON['app'] ? $JSON['app'] : null 	,
		'act'	=>	$JSON['act'] ? $JSON['act'] : 'main',
		'apiFile'=>	false )
		;
	
	if ($APPL['url']) {
		$APPL['url']=rtrim($APPL['url'],'/');
		$APPL['file'] = WGParseURI($APPL['url']);
		if ($APPL['file']!==false and !WGisAllowed($APPL['file']['path'])) FatalError("Access denied");
		} else $APPL['file'] = null;
	
	if ($APPL['file']===false) FatalError("Invalid file name");
	if ($APPL['file']) WGProcMIME();
	
	if ($JSON['t']==AJ_SYSTEM) {
		$APPL['isRAW'] = true;
		$APPL['isAPI'] = 'bin/system';
		}
	
	if ($APPL['isAPI']) {
		if (!$APPL['api'] or $APPL['api']=='') FatalError("API name requested");
		if (!preg_match('/^[a-zA-Z0-9\_\-]{1,40}$/',$APPL['api'])) FatalError("Invalid API name");
		$f = $APPL['isAPI'].'/'. $APPL['api']. '.php';
		$f = WGParseFile($f);
		if (!$f) FatalError("Invalid API name");
		if (!$f['F']) FatalError("API file not found `".$f['f']."`");
		$APPL['bin'] = $f;
		WGRequirePHP($f['f']);
		}
		
	if ($APPL['executor']) {
		$f = WGParseFile($APPL['executor'],true);
		if (!$f) FatalError("Invalid executor file name");
		if (!$f['F'] and !$f['D']) FatalError("Executor not found `".print_r($APPL['executor'],true)."`");
		$APPL['bin'] = $f;
		if ($f['F']) { 
			if ($f['extension']=='php') {
				WGRequirePHP($f['f']);
				} else if ($f['extension']=='json') {
					$js=@file_get_contents($f);
					$js=json_decode($f,true);
					if (!is_array($js)) FatalError("Invalid executor json `".$APPL['executor']."`");
					Window($f['filename']);
					$WEBGUI['win']=array_merge($WEBGUI['win'],$js);
					$js=null;
					unset($js['hdc']);
					endWin(); 
					} else FatalError("Unknown executor type `".$APPL['executor']."`");
			} else if ($f['D']) execApp($f['w'],$JSON['t']);
		}
	if (is_array($ini['db']) && !$DBH) dbclose($DBH);			
	retGUI();
	}

/// GET

$startFile=false;

if (@$_SERVER['REQUEST_URI']=='/logout.php' or @$_SERVER['REDIRECT_URL']=='/logout.php') {
			header("HTTP/1.1 200 Ok",true,200);
			header("Content-Type: text/plain; charset=UTF-8",true,200);
			@session_destroy();
			exit("Logout");
			}

if (
	(isset($_SERVER['REDIRECT_STATUS']) and ( $_SERVER['REDIRECT_STATUS'] == 403 or $_SERVER['REDIRECT_STATUS'] == 404 )) and 
	(
		(isset($_SERVER['REQUEST_URI']) and $_SERVER['REQUEST_URI']!='') or
		(isset($_SERVER['REDIRECT_URL']) and $_SERVER['REDIRECT_URL']!='')
		)
	) {
				
		WGSession();
		
		$startFile=WGHTTPRedirection();
		if ($lockIntUri) {
			header("Location: /",true);
			exit("Access denied by HTTP policy.");
			}
	}
	
if (!$lockIntUri or isset($ini['logon'])) $_SESSION=array();

header("Content-Type: text/html; charset=UTF-8",true,200);

EWGCacheFont();
if ($DBH) dbclose($DBH);

?><!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title><?=htmlspecialchars(@$ini['webgui']['title'],ENT_QUOTES); ?></title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?
	if (@$ini['webgui']['cookie']) echo "<script src=\"/sys/cookie.js\"></script>\n";
	?>
	<link rel="stylesheet" href="/sys/win.css">
	<? 
	if (isset($ini['logon'])) echo "<link rel=\"stylesheet\" href=\"/img/logonui.css\">\n";
	if (file_exists("var/custom-logonui.css")) echo "<link rel=\"stylesheet\" href=\"/var/custom-logonui.css\">\n";
	?>
	<script src="/sys/win.js"></script>
	<? 
	
	if ($ini['logon'] and !WGSession()) {
		$startFile=false;
		?>
		<script>
		WGlob.logonUI=true;
		WGProcSet.logonUI = <?=json_encode($ini['logon']); ?>;
		</script>
		<?
		}
	
	if ($startFile) { ?>
		<script>
		WGSetStartFile('<?=htmlspecialchars(addcslashes($startFile,"\t\r\n\\\"'`<>&\0"),ENT_QUOTES)?>');
		</script>
		<?
		}	
		
	$dh=@opendir($_SERVER['DOCUMENT_ROOT'].'/var/cache');
	if ($dh) {
		while($fi=readdir($dh)) {
			$fi='/var/cache/'.$fi;
			if ($fi[0]=='.' or is_dir($_SERVER['DOCUMENT_ROOT'].$fi)) continue;
			$ext=pathinfo($fi,PATHINFO_EXTENSION);
			if ($ext=='css') echo "<link rel=\"stylesheet\" href=\"".htmlspecialchars($fi,ENT_QUOTES)."\">\n";
			if ($ext=='js') echo "<script src=\"".htmlspecialchars($fi,ENT_QUOTES)."\"></script>\n";
			}
		closedir($dh);
		}
	?>
	
	<!-- 
	EPTO WebGui V 8.0
	(C) 2015 EPTO (A)
	-->
</head>
<body onload="Init()"><noscript>This site requires Javascript to be enabled.</noscript></body>
</html>
