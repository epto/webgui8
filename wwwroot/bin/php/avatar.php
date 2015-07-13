<?
@session_start();
$x=isset($_SESSION['EWGUser']) ? $_SESSION['EWGUser'] : false;
if ($x!==false) $x="/home/{$x['name']}/avatar.png";
if ($x===false or !file_exists($_SERVER['DOCUMENT_ROOT'].$x)) $x="/img/user-avatar.png";
header("Location: $x",true);
?>
