<?
if (!isset($ini)) return;

if (count($_POST) and $_POST['logonui']==1) {
	if (!$DBH) dbopen($DBH);
		if (WGLogonUser($_POST['a'],$_POST['b']) and WGSession()) {
			$WEBGUIhttpAuth=true;
			return;
			}
	}
?>
<!doctype html>
<html>
	<head>
		<meta charset="utf-8"/>
		<title><?=htmlspecialchars(@$ini['webgui']['title'],ENT_QUOTES); ?></title>
	<?
	if (@$ini['webgui']['cookie']) echo "<script src=\"/sys/cookie.js\"></script>\n";
	?>
	<link rel="stylesheet" href="/sys/win.css">
	<link rel="stylesheet" href="/img/style.css">
	
	<!-- 
	EPTO WebGui V 8.0
	(C) 2015 EPTO (A)
	-->
</head>
<body>
	<div class="EWGLogonui">
		<div class="EWGLogonTitle"><?= htmlspecialchars( @$ini['logon']['title'] ? @$ini['logon']['title'] : 'Logon', ENT_QUOTES); ?></div>
		<form action="/" method="POST">
		<input type="hidden" name="logonui" value="1">
			<table class="EWGLogonForm">
			<tr><td>Login:</td><td><input type="text" name="a" value="" class="EWGLogonInput"></td></tr>
			<tr><td>Password:</td><td><input type="password" name="b" value="" class="EWGLogonInput"></td></tr>
			<tr><td colspan="2"><input type="submit" class="EWGLogonSubmit" value="<?= htmlspecialchars( @$ini['logon']['submit'] ? @$ini['logon']['submit'] : 'Logon', ENT_QUOTES); ?>"></td></tr>
			</table>
		</form>
	</div>
</body>
</html>
