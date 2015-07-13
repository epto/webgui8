<?

function form_access_post($dta) {
	global $OUTJSON;
	global $CMD;
	global $WEBGUI;
	global $APPL;
	  
	$usr = WGSessionUser();
	if ($usr===false) FatalError("Access denied");
	if ($dta['f2']!=$dta['f3']) {
		WGSetActivity("error");
	        return;
		}
		
	$x = WGPasswd($usr['name'],$dta['f1'],$dta['f2']);
	
	if ($x==false) {
	        WGSetActivity("error");
	        return;
		}
		
	WGSetActivity("accessok");
	}               
?>
