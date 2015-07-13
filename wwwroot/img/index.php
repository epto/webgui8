<?

function WGHTTPExit($stat,$cod=404) {
	header("HTTP/1.1 $cod $stat",true,$cod);
	header("Content-Type: text/plain; charset=UTF-8",true,$cod);
	header("Expires: ".gmdate('r',0),true,$cod);
	header("Pragma: no-cache",true,$cod);
	exit($stat);
	}

$MIME=array(
	"ico"	=> "image/x-icon",
	"bmp"	=> "image/bmp",
	"css"	=> "text/css",
	"jpeg"	=> "image/jpeg",
	"gif"	=> "image/gif",
	"jpg"	=> "image/jpeg",
	"png"	=> "image/png",
	"svg"	=> "image/svg+xml",
	"wmf"	=> "application/x-msmetafile",
	"webp"	=> "image/webp",
	"ttf"	=> "application/x-font-ttf", 
	"woff"	=> "application/x-font-woff",
	"eot"	=> "application/vnd.ms-fontobject",
	"otf"	=> "application/font-otf",
	"js"	=> "text/javascript")
	;

@session_start();
$fileo=isset($_SERVER['REDIRECT_URL']) ? $_SERVER['REDIRECT_URL'] : @$_SERVER['REQUEST_URI'];
if ($fileo=='') WGHTTPExit("File not found");
if (strpos($fileo,'/./')!==false or strpos($fileo,'/../')!==false) WGHTTPExit("File not found");
$path=pathinfo($fileo,PATHINFO_DIRNAME);
if (strpos($path,'.')!==false) WGHTTPExit("File not found");
$basename = pathinfo($fileo,PATHINFO_BASENAME);
$isMIME = strpos('/'.$path.'/','/mime/')!==false ? true : false;

$tema=@$_SESSION['EWGTheme'];
if ($tema=='' and preg_match('/^[a-zA-Z0-9\_\-]{1,40}$/',$tema)==0) $tema='default';

$ext=strtolower(pathinfo($fileo,PATHINFO_EXTENSION));
if (!isset($MIME[$ext])) WGHTTPExit("File not found");

$lst=array();
if ($isMIME) {
	$lst[] = rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/img/'.$tema.'/mime/'.$basename; 
	$lst[] = rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/bin/mime/ico/'.$basename; 
	} else {
	$lst[] = rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/img/'.$tema.'/'.$basename; 
	if ($tema!='default') $lst[] = rtrim($_SERVER['DOCUMENT_ROOT'],'/').'/img/default/'.$basename;
	}

foreach($lst as $prova) {
	if (!file_exists($prova)) continue;
	
	$mt = filemtime($prova);
	$sz = filesize($prova);
	$T=md5($mt.$sz.$prova.$tema);
	$D=date('r',$mt);
	$A=true;
	$S=200;
	$ST='Ok';
	
	if (trim(@$_SERVER['HTTP_IF_NONE_MATCH'],'"')==$T and @$_SERVER['HTTP_IF_MODIFIED_SINCE']==$D) {
		$A=false;
		$S=304;
		$ST='Not modified';
	} else {
		$A=true;
		$S=200;
		$ST='Ok';
		}
		
	header("HTTP/1.1 $S $ST",true,$S);
	header("Last-Modified: ".$D,true);
	header("Etag: \"".$T."\"",true);
	header("Expires: ".date('r',time()+86400),true);
	header("Cache-Control: private, max-age=86400",true);
	header_remove('pragma');
	header("Content-Type: $m",true,$S);
	header("Content-Length: $sz",true);
	ob_implicit_flush(true);
	if ($A and @$_SERVER['REDIRECT_REQUEST_METHOD']!='HEAD') @readfile($prova);
	exit;	
	}	
WGHTTPExit("File not found");
?>
