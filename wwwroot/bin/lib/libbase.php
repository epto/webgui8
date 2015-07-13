<?
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
$DBH=false;
$ini = @parse_ini_file($_SERVER['DOCUMENT_ROOT'].'/etc/server.php',true);
@session_start();

require_once $_SERVER['DOCUMENT_ROOT'].'/bin/lib/libdb.php';
require_once $_SERVER['DOCUMENT_ROOT'].'/bin/lib/libglobal.php';

$lockIntUri=false;

if (isset($ini['auth'])) {
	dbopen($DBH);
	WGHTTPAuth();
	$lockIntUri=true;
	$WEBGUIhttpAuth=true;
	} else {
	$WEBGUIhttpAuth=false;	
	}
	
WGAllowPath(
	array(
		"/usr"				,
		"/bin/mime/ico"		,
		"/bin/theme"		,
		"/bin/dlg"			,
		"/bin/wgx"			,
		"/bin/app/webgui"	,
		"/bin/app/usr"		)
		)
	;
	
if (is_array($ini['allowdir'])) WGAllowPath($ini['allowdir']);

?>