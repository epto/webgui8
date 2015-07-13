<?

function getThemeList($par) {
	$dh=@opendir($_SERVER['DOCUMENT_ROOT'].'/img/');
	if ($dh===false) FatalError("Can't open theme directory");
	while($file = readdir($dh)) {
	        if ($file[0]=='.') continue;
	        if (is_dir($_SERVER['DOCUMENT_ROOT'].'/img/'.$file)) {
		        $js = json_decode( @ file_get_contents( $_SERVER['DOCUMENT_ROOT'].'/img/'.$file.'/procset.json') , true);
			if (!is_array($js)) continue;
			$a = $file;
			$b = isset($js['themeInfo']['title']) ? $js['themeInfo']['title'] : $a; 
			$par['v.'.$a] = $b; 		
			}
	        }
	closedir($dh);
	return $par;	
	}

function form_change_post($dta) {
	$thm = $dta['lang'];
	if (WGChangeTheme($thm)) WGToast(WGSL('newTheme',"Theme changed"));  
	WGSetActivity('main');
	}               
?>
