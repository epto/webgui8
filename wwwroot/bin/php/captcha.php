<?
@session_start();
require $_SERVER['DOCUMENT_ROOT'].'/bin/lib/libbase.php';
require $_SERVER['DOCUMENT_ROOT'].'/bin/lib/webgui.php';

$Capid=@$_GET['id'];
if( preg_match('/^[a-zA-Z0-9]{1,40}$/',$Capid)==0) die('Error E1');

$fonts= EWGGetFonts();
if ($fonts===false) {
        EWGCacheFont();
        $fonts= EWGGetFonts();
	}

if ($fonts===false) die('Error E2');
if (!isset($fonts['php']) or count($fonts['php'])==0) die('Error E3');

mt_srand(crc32(time().microtime().mt_rand(1,9999999)));

$alfa="QWERTYUIPADYFHTLMNVX234679234679234679";
       	
$ecx=strlen($alfa)-1;
$code=@$_SESSION['EWGCaptcha'][$Capid];
$code=strtoupper($code);
if (strlen($code)==0) {
	$code='000000';
	//for($a=0;$a<6;$a++) $code.=$alfa[mt_rand(0,$ecx)];
	$_SESSION['EWGCaptcha'][$Capid]=strtolower($code);
	}
	
$im = imagecreatetruecolor(150, 40);
if (@$_GET['b']==1) $bka=true; else $bka=false;
$di= $bka ? 2 : 1;
$bg = imagecolorallocate($im, 0, 0, 0);
$textcolor[0] = imagecolorallocate($im, 255/$di, 0, 0);
$textcolor[1] = imagecolorallocate($im, 255/$di, 255/$di,0);
$textcolor[2] = imagecolorallocate($im, 0, 255/$di, 0);
$textcolor[3] = imagecolorallocate($im, 0, 200/$di, 255/$di);
$textcolor[4] = imagecolorallocate($im, 255/$di, 0, 255/$di);
$textcolor[5] = imagecolorallocate($im,192/$di, 255/$di, 192/$di);
$textcolor[6] = imagecolorallocate($im,255/$di, 192/$di, 255/$di);
$textcolor[7] = imagecolorallocate($im,192/$di, 192/$di, 192/$di);
$textcolor[8] = imagecolorallocate($im,255, 255, 255);

if ($bka) {
	$a=$textcolor[8];
	$textcolor[8]=$bg;
	$bg=$a;
	}

imagefilledrectangle($im,0,0,150,40,$bg);

$fuffa=array(
        imagecolorallocate($im,80*$di, 80*$di, 80*$di),
        imagecolorallocate($im,80*$di, 0, 0),
        imagecolorallocate($im,0, 80*$di, 0),
        imagecolorallocate($im,80*$di, 0, 80*$di),
        imagecolorallocate($im,80*$di, 0, 80*$di),
        imagecolorallocate($im,0, 80*$di, 80*$di),
        imagecolorallocate($im,80*$di, 80*$di, 0)
	);

$arr=array();
$xc = $fonts['captcha'] ? $fonts['captcha'] : $fonts['php'];
foreach($xc as $db) $arr[] = $db['file'];
$m=count($arr)-1;

$font=array(
	$arr[mt_rand(0,$m)],
	$arr[mt_rand(0,$m)],
	$arr[mt_rand(0,$m)],
	$arr[mt_rand(0,$m)])
	;

$xx=8;

$zz= 140;

for ($ax=0;$ax<120;$ax++) {
	$xa=mt_rand(0,150);
	$ya=mt_rand(0,40);
	$ca=$textcolor[mt_rand(0,5)];
	imagesetpixel($im,$xa,$ya,$ca);
	}

$rx=mt_rand(5,10);
for ($ax=0;$ax<$rx;$ax++) {
        imagefilledellipse ( $im ,mt_rand(0,150) , mt_rand(0,40) , mt_rand(4,16) , mt_rand(4,16) , $fuffa[mt_rand(0,7)] );
	} 
	
$op=mt_rand(0,8);

for ($ax=0;$ax<6;$ax++) {
	$ch=$code[$ax];
	$tp = $op ^ $ax;
	if ($tp>5) $tp=$ax % 8;
	$fnti = $ax&3;
	$pox = imagefttext($im,20,mt_rand(-15,15),$xx,27+mt_rand(-2,5),$textcolor[$tp],WGSubPath($_SERVER['DOCUMENT_ROOT'],$font[$fnti]),$ch);
	$xx+=22;
	if ($xx>$zz) break;
	}

header("Content-type: image/png");
imagepng($im);
?> 
