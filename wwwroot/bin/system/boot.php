<?
if (!defined("EWGINCLUDE")) exit;

$ini = WGGetIni();

WGDetectLang(true);

$OUTJSON['cmd'][] = array(
	"api"	=>	"boot"	,
	"data"	=>	array(
		"theme"	=>	"WGTemeDefault"	)
		)
	;

WGChangeTheme($ini['webgui']['theme'] ? $ini['webgui']['theme'] : 'default');

EWGLoadProfile();	

if (isset($ini['webgui']['onload'])) {
	$OUTJSON['cmd'][] = array(
		'api'	=>	'shell',
		'data'	=>	$ini['webgui']['onload'])
		;
	}

$doAutoExec=true;
if (@$JSON['data']['start']) {
	$x=ltrim($JSON['data']['start'],'/');
	$mx = pathinfo($x,PATHINFO_EXTENSION);
	$x="/".$x;
	$mx=strtolower($mx);
	$mx=str_replace(' ','',$mx);
	
	$mime = @json_decode( @file_get_contents($_SERVER['DOCUMENT_ROOT'].'/etc/mimehome.json') , true);
	if (!is_array($mime)) $mime=array();
	if (isset($mime['_ALLOWURL_'])) {
		if (strpos(' '.$mime['_ALLOWURL_'].' '," $mx ")!==false) {
			$OUTJSON['cmd'][] = array(
				'api'	=>	'shell',
				'data'	=>	$x)
				;
			
			$doAutoExec=false;
			} 
		}
	}
	
if ($doAutoExec && isset($ini['webgui']['autoexec'])) {
	$OUTJSON['cmd'][] = array(
		'api'	=>	'shell',
		'data'	=>	$ini['webgui']['autoexec'])
		;
	}	
	
?>
