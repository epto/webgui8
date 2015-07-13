<?

function getLanguageList($par) {
	$dh=@opendir($_SERVER['DOCUMENT_ROOT'].'/etc/lang');
	if ($dh===false) FatalError("Can't open laguages directory");
	while($file = readdir($dh)) {
	        if (strpos("$file\n",".json\n")===false) continue;
	        $js = json_decode( @ file_get_contents( $_SERVER['DOCUMENT_ROOT'].'/etc/lang/'.$file) ,true);
		if (is_array($js)) {
		        list($a)=explode('.',$file,2);
		        $b= isset($js['LANG']) ? $js['LANG'] : $a;
			$par['v.'.$a] = $b; 
			} 
		}
	closedir($dh);
	return $par;	
	}

function form_change_post($dta) {
	$lang = $dta['lang'];
   
   	if (preg_match('/^[a-z]{2}\-[a-z]{2}$|^[a-z]{2}$|^\_D\_$/',$lang)==0) {
	        WGSetActivity('main');
	        return;
		}
	
	if (!WGSetLang($lang)) {
	        WGSetActivity('main');
	        return;
		}
	
	WGCloseWin();
	WGShellExecute("/bin/app/webgui/lang.app");
	}               
?>
