<?

function form_access_post($raw,$dta) {
	global $WEBGUI;
	$WEBGUI['win']['data']['access']=array(
		'f1'	=>	'',
		'f2'	=>	'')
		;
		
	$login = $dta['f1'];
	$pass = $dta['f2'];
	$x = EWGLogon($login,$pass);
	
	if ($x===false) {
	        WGSetActivity('error');
	        return array(
		        'f1'	=>	'',
		        'f2'	=>	'')
			;
		}
	return array();			
	}               
?>
