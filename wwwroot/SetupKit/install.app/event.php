<?

function LocalError($st) {
	setCaption('erro',$st);
	WGSetActivity('error');
	return null;
	}

function catchDBError($num,$st) {
	global $ini;
	$ini['db']['ok']=false;
	$ini['db']['err'] = dechex($num).": $st";
	}

function form_running_post($rawData,$data) {
	global $ini;
	global $DBERROR;
	global $DBH;
	global $WEBGUI;
	
	if (@$ini['webgui']['cansetup']!='webgui') FatalError("Setup Bloccato");
	
	$path = $_SERVER['DOCUMENT_ROOT'].$WEBGUI['rvar']['APPPATH'];
	
	$DBERROR=catchDBError;
                
	if ($data['dbp1']!=$data['dbp2']) return LocalError("Le password del database non coincidono.");
	if ($data['rp1']!=$data['rp2']) return LocalError("Le password di root non coincidono.");
	
	$x = $_SERVER['DOCUMENT_ROOT'].'/etc/InstallHash';
	if (file_exists($x)) {
		$x = @file_get_contents($x);
		if ($x===false) FatalError("Non riesco a leggere `InstallHash`");
		list($a,$b)=@explode(' ',$x,2);
		$x = $a.' '.$data['root'].':'.$data['rp1'];
		$x = md5($x);
		$b=strtolower($b);
		if ($b!=$x) return LocalError("La login e la password di root non coincidono con quelle precedentemente segnalate nel file `InstallHash`\nRimuovere il file oppure usare le credenziali corrette.");		
		}
	
	$ini['db'] = array(
	     'mysql'=> ($data['mysql'] ? $data['mysql'] : '127.0.0.1') ,
		 'db'	=> $data['db'],
		 'dblog'=> $data['dbl'],
		 'dbpas'=> $data['dbp1'])
		 ;
	
	$ini['db']['ok'] = true;
	dbopen($DBH);
	if ($ini['db']['ok']==false) return LocalError("Errore accesso database: {$ini['db']['err']}");
	unset($ini['db']['ok']);
	unset($ini['db']['err']);
	if (!isset($ini['webgui'])) $ini['webgui']=array();
	$ini['webgui']['title'] = $data['title'];
	unset($ini['webgui']['autoexec']);
	if (isset($ini['webgui']['install-autoexec'])) {
		$ini['webgui']['autoexec']=$ini['webgui']['install-autoexec'];
		unset($ini['webgui']['install-autoexec']);	
		}
	
	unset($ini['allowdir']);
		
	$a = array(
	        'etc',
	        'etc/conf.d',
	        'etc/desktop.json',
	        'etc/start.json',
	        'etc/mime.json',
	        'etc/mimehome.json',
	        'etc/server.php',
	        'tmp',
	        'var/cache',
	        'var/cache/font.json',
	        'var/cache/font.css',
	        'home',
	        'usr',
	        'bin/app/grp',
	        'bin/usr',
	        'bin/dlg',
	        'bin/dll',
	        'bin/font',
	        'bin/mod',
	        'bin/php',
	        'bin/theme',
	        'bin/wgx',
	        'bin/mime',
	        'bin/mime/ico')
		;
		
	foreach($a as $b) {
	        $c=$_SERVER['DOCUMENT_ROOT'].'/'.$b;
	        if (@file_exists($c) and @chmod($c,0770)==false ) return LocalError("Non riesco ad impostare i permessi su $b");
	        }

	$dh=@opendir($path.'etc');
	if ($dh===false) FatalError("Non trovo la directory skel file di installazione {$path}etc");
	while($f=readdir($dh)) {
		if (is_dir( $path.'etc/'.$f ) or $f[0]=='.') continue;
		$dt = @file_get_contents($path.'etc/'.$f);
		if ($dt===false) return LocalError("Non riesco a leggere `$f`");
		
	        if (@file_put_contents( $_SERVER['DOCUMENT_ROOT'].'/etc/'.$f , $dt ) == false ) return LocalError("No riesco a copiare: $f");
		}
	closedir($dh);
	
	$dh=@opendir($path.'setup');
	if ($dh===false) FatalError("Non trovo la directory skel file di installazione {$path}setup");
	$lst=array();
	while($f=readdir($dh)) {
		$tf=$path.'setup/'.$f;
		if (is_dir( $tf ) or $f[0]=='.') continue;
		
		if (preg_match('/\.sql$/',$f)!=0) {
			$a = intval($f);
			$lst[$a]=@file_get_contents($tf);
			if ($lst[$a]===false) return LocalError("Non riesco a leggere `$tf`");
			}
		}
	closedir($dh);
	ksort($lst);
	
	$ini['db']['ok']=true;
	$ini['db']['err']='';
	
	foreach($lst as $db) {
		dbquery($DBH,$db);
		if ($ini['db']['ok']==false) return LocalError("Errore Query SQL: ".$ini['db']['err']); 
		}
	
	$x=WGCreateUser($data['root'],$data['rp1'],'root',array('root' => true));
	if ($x==false) LocalError("Non riesco a creare l'utente root.");
	
	unset($ini['db']['ok']);
	unset($ini['db']['err']);
	unset($ini['logon']);
	unset($ini['auth']);	
		
	if ($data['accesmode']==1) {
	        $ini['auth'] = array( 'realm' => $data['title']!='' ? $data['title'] : 'WebGui8') ;
	    } 
		
	if ($data['accesmode']==2) {
			$ini['logon']=array('enabled'=>1);
		} 
	
	$t0 = str_pad($data['debug'],4,'0');
	$ini['debug'] = array(
	        'debug'	=>	$t0[0],
	        'log'	=>	$t0[1],
	        'showid'=>	$t0[2],
	        'args'	=>	$t0[3])
		;
	
	$ini['webgui']['cookie'] = $data['cookie']!=0 ? '1' : '0';
		
	$fs =$path.'setup/server.conf';
	if (file_exists($fs)) {
	        $i = @parse_ini_file($fs,true);
	        if ($i===false) return LoaclError("Non riesco a leggere correttamente il file di installazione `$fs`");
	        foreach($i as $k => $st) {
		        if (!isset($ini[$k])) $ini[$k]=array();
		        foreach($st as $k1 => $v1) {
		        	$ini[$k][$k1]=$v1;
				}
			}
		}
	
	$fs=$path.'setup/install.php';
	if (file_exists($fs)) WGRequirePHP($fs,$data);
		
	unset($ini['webgui']['cansetup']);
	
	$i=';) <'.'? exit; ?'.'>';
	$i.="\n";
	foreach($ini as $k => $li) {
	        $i.="[{$k}]\n";
	        foreach($li as $vk => $vv) {
	        	if (!is_numeric($vv)) $vv='"'.addcslashes($vv,"\t\r\n\\\"`'").'"';
			$i.="{$vk}={$vv}\n";		
			}
		$i.="\n";
		}
	
	$fs=$_SERVER['DOCUMENT_ROOT'].'/etc/server.php';
	if ( file_put_contents($fs,$i)==false) return LocalError("Non riesco a scrivere su `$fs`");
	
	EWGCacheFont();
	
	WGSetActivity('setup');	
}

?>
