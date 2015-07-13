<?
//vercheck 6 1.1B

define('DBE_MASK'     , 0xDBE0);
define('DBE_CONN'     , 0xDBE1);
define('DBE_SEL'      , 0xDBE2);
define('DBE_ERR'      , 0xDBE3);
define('DBE_ID'       , 0xDBE4);
define('DBE_CLO'      , 0xDBE5);
define('DBE_INI'      , 0xDBE6);
define('DBE_EXT_MASK' , 0xDBF0);
define('DBE_EXT_STR'  , 0xDBF1);
define('DBE_EXT_COR'  , 0xDBF2);
define('DBE_EXT_DEF'  , 0xDBF3);
define('DBE_EXT_ERR'  , 0xDBF4);
define('DBE_EXT_INV'  , 0xDBF5);
define('DBE_EXT_MASK' , 0xCBF0);

define('DBE_EXT_TABE' , 0xCAC1);
define('DBE_EXT_QRY'  , 0xCAC2);

define('DBS_ARR_PAR'  , 0xA001);
$DBERROREXT = array();

function DEFAULTDBERROR($num,$st) {
    echo "<br>\nLIBDB: [ ".dechex($num)." ] $st<br>\n" . mysql_error();
    debug_print_backtrace();
    exit;
  }

$DBERROR=DEFAULTDBERROR;

function ERRORDB($rs,$err=DBE_ERR) {
  global $DBERROR;
  
  if ($rs!==FALSE) return FALSE;
  $erl='';
    switch($err) {
      case DBE_CONN:
        $erl='Connessione non riuscita';
        break;

      case DBE_SEL:
        $erl='Selezione del database non riuscita';        
        break;
      
      case DBE_ERR:
        $erl='Errore query';
        break;
      
      case DBE_ID:
        $erl="ID Mancante nell'array";
        break;
        
      case DBE_CLO:
        $erl='Database non aperto';  
        break;
        
      case DBE_INI:
        $erl='Configurazione DB non trovata nella variabile ini';
        break;
        
      case DBE_EXT_STR:
        $erl='Struttura non compatibile con il DB';
        break;
        
      case DBE_EXT_COR:
        $erl='Struttura DB Corrotto sul valore';
        break;
      
      case DBE_EXT_DEF:
        $erl='Errore nella definizione della struttura';
        break;
      
      case DBE_EXT_ERR:
        $erl='Struttura non acettata dal DB';
        break;
       
      case DBE_EXT_INV:
        $erl='Valode non valido';
        break;
        
	case DBE_EXT_QRY:
	$erl='dbex_buildquery_str: Chiamata senza una risorsa di tipo dbex_newqry';
	break;
	
	case DBE_EXT_TABE:
	$erl='dbex_do: Chiamata senza una tabella in oggetto su dbex_newqry';
	break;
        
      default:
        if (isset($DBERROREXT[$err])) $erl=$DBERROREXT[$err]; else $erl='Errore ('.base_convert($erl,10,16).')';
      }
  
  $DBERROR($err,$erl);
  return TRUE;
  }

function dbcountex(&$hx,$tabe,$where = '1' ) {
	  
  if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
  if (dbquery($hx,"SELECT COUNT(*) FROM `".mysql_real_escape_string($tabe,$hx['c'])."` WHERE $where")===FALSE) return FALSE;
  $hx['_'] = '';
	$db=dbget($hx);
	if ($db===FALSE) return FALSE;
	tabclose($hx);
	return $db['COUNT(*)'];
	}
	
function dbcount(&$hx,$tabe,$col,$like ) {
	if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
  if (dbquery($hx,"SELECT COUNT(*) FROM `".mysql_real_escape_string($tabe,$hx['c'])."` WHERE `".mysql_real_escape_string($col,$hx['c'])."` LIKE '".mysql_real_escape_string($like,$hx['c'])."'")===FALSE) return FALSE;
	$hx['_'] ='';
  $db=dbget($hx);
  if ($db===FALSE) return FALSE;
	tabclose($hx);	
	return $db['COUNT(*)'];
	}

function dbDupH(&$dbh) {
  return array(
    'c' => $dbh['c']  ,
    'r' =>  ''        ,
    's' =>  1         ,
    '_' =>  ''        )
    ;
  }

function dbopen(&$hx,$db='') {
	global $ini;
	if (!isset($ini)) ERRORDB(FALSE,DBE_INI);
	if (!isset($ini['db']['db'])) ERRORDB(FALSE,DBE_INI);
	if (isset($ini['db']['multiplexer'])) {
		$t0=$_SERVER['SERVER_NAME'];
		$t0=str_replace(array("\nwww.","\n"),'',"\n".$t0);
		if (!isset($ini['db-'.$t0])) {
		
			$t0 = base_convert(abs(crc32($t0.base_convert(str_replace('.','',$t0),36,16))),10,36);
			$t0 = 'srv-'.$t0;
			if (!isset($ini[$t0])) {echo $t0; ERRORDB(FALSE,DBE_INI); }
			
			$ini['db'] = array_merge($ini['db'],$ini[$t0]);
			} else {
	
			$ini['db'] = array_merge($ini['db'],$ini['db-'.$t0]);
			}
		}

  if (isset($ini['multiserver'])) {
  	$t0=$_SERVER['SERVER_ADDR'];
  	$t0='ip'.str_replace(array(':','.'),'-',$t0);
  	if (isset($ini['multiserver'][$t0])) {
  	
  	
	  	  	$t0=explode('|',$ini['multiserver'][$t0]);
  		  	$ini['db']['dbpas'] = $t0[0];
  		  	if (isset($t0[1])) $ini['db']['dblog'] = $t0[1];
  		  	if (isset($t0[2])) $ini['db']['db'] = $t0[2];
  		  	if (isset($t0[3])) $ini['db']['mysql'] = $t0[3];
  		  	
		  	} else {
		  	$t1=explode('.',$_SERVER['SERVER_NAME']);
			$t0=$t1[count($t1)-2];
			if ($t0=='') $t0=$t1[count($t1)-1];
			if (!isset($ini['multiserver'][$t0])) {echo $t0; ERRORDB(FALSE,DBE_INI); }
			$t0=explode('|',$ini['multiserver'][$t0]);
  		  	$ini['db']['dbpas'] = $t0[0];
  		  	if (isset($t0[1])) $ini['db']['dblog'] = $t0[1];
  		  	if (isset($t0[2])) $ini['db']['db'] = $t0[2];
  		  	if (isset($t0[3])) $ini['db']['mysql'] = $t0[3];
			}
  	}		

	
  if (!isset($ini['db']['dblog'])) ERRORDB(FALSE,DBE_INI);
  if (!isset($ini['db']['dbpas'])) ERRORDB(FALSE,DBE_INI);
  if (!isset($ini['db']['mysql'])) ERRORDB(FALSE,DBE_INI);
  
  $connessione = mysql_connect($ini['db']['mysql'],$ini['db']['dblog'], $ini['db']['dbpas']);
	if (ERRORDB($connessione,DBE_CONN)) return FALSE;
	$hx=array('c' => $connessione,'r' => '', 's' =>1);
	if ($db=="") $db=$ini['db']['db'];
	if (ERRORDB(mysql_select_db($db),DBE_SEL)) return FALSE;
	$hx['_'] = '';
  return TRUE;
	}
	
function dbclose(&$hx) {
	@mysql_free_result($hx['r']);
 	mysql_close($hx['c']);
	$hx['s']=0;
	}

function tabclose(&$hx) {
	@mysql_free_result($hx['r']);
	
	}


function dbdelete(&$hx,$tabe,$db) {
	if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
  $query="DELETE FROM `".mysql_real_escape_string($tabe,$hx['c'])."` WHERE `id` ='".mysql_real_escape_string($db['id'],$hx['c'])."' LIMIT 1;";
  $hx['_'] = $tabe;
	$risultato2 = mysql_query($query);
	if (ERRORDB($risultato2,DBE_ERR)) return FALSE;
	@mysql_free_result($risultato2);
	}
	
function dbfirst(&$hx,$tabe,$campo,$valo) {
	if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
  $hx['_'] = $tabe;
  $query="SELECT * FROM `".mysql_real_escape_string($tabe,$hx['c'])."` WHERE `".mysql_real_escape_string($campo,$hx['c'])."`='".mysql_real_escape_string ($valo,$hx['c'])."' LIMIT 1";
 	$risultato2 = mysql_query($query);
	if (ERRORDB($risultato2,DBE_ERR)) return FALSE;
	$linea = mysql_fetch_array($risultato2, MYSQL_ASSOC);
	@mysql_free_result($risultato2);
	return $linea;
	}
	
function dbfirstval(&$hx,$tabe,$campo,$valo,$get) {
	if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
  $hx['_'] = $tabe;
  $query="SELECT `".mysql_real_escape_string ($get,$hx['c'])."` as ax FROM `".mysql_real_escape_string ($valo,$hx['c'])."` WHERE `".mysql_real_escape_string($campo,$hx['c'])."`='".mysql_real_escape_string ($valo,$hx['c'])."' LIMIT 1";

 	$risultato2 = mysql_query($query);
	if (ERRORDB($risultato2,DBE_ERR)) return FALSE;
	$linea = mysql_fetch_array($risultato2, MYSQL_ASSOC);
	@mysql_free_result($risultato2);
	if ($linea===false) return false;
	return $linea['ax'];
	}

function dbexists(&$hx,$tabe,$campo,$valo) {
	if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
  $hx['_'] = $tabe;
  $query="SELECT COUNT(*) as r FROM `".mysql_real_escape_string($tabe,$hx['c'])."` WHERE `".mysql_real_escape_string($campo,$hx['c'])."`='".mysql_real_escape_string ($valo,$hx['c'])."' LIMIT 1";

 	$risultato2 = mysql_query($query);
	if (ERRORDB($risultato2,DBE_ERR)) return FALSE;
	$linea = mysql_fetch_array($risultato2, MYSQL_ASSOC);
	@mysql_free_result($risultato2);
	return $linea['r']>0 ? true:false;
	}

function dbfirstex(&$hx,$tabe,$qry) {
	if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
  $hx['_'] = $tabe;
  $query="SELECT * FROM `".mysql_real_escape_string($tabe,$hx['c'])."` WHERE $qry";
	$risultato2 = mysql_query($query);
	if (ERRORDB($risultato2,DBE_ERR)) return FALSE;
	$linea = mysql_fetch_array($risultato2, MYSQL_ASSOC);
	@mysql_free_result($risultato2);
	return $linea;
	}

function dbget(&$hx) {
		 if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
		 if ($hx['r']===FALSE) return FALSE;
     return mysql_fetch_array($hx['r'], MYSQL_ASSOC);
		 }
	
function dblist(&$hx,$table,$query) {
  if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
	$hx['r'] = mysql_query("SELECT * FROM `".mysql_real_escape_string($table,$hx['c'])."` WHERE $query");
	$hx['_'] = $table;
	if (ERRORDB($hx['r'],DBE_ERR)) return FALSE;
	}
	
function dbquery(&$hx,$query) {
  if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
  $hx['_'] = '';
	$hx['r'] = mysql_query($query);
	if (ERRORDB($hx['r'],DBE_ERR)) return FALSE;
	}

function dbinsert(&$hx,$table,$array) {
	if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
	$hx['_'] = $table;
	if (!isset($array['id'])) $array['id']=null;
		
	$query='';
	$valo='';
	foreach($array as $key => $value) {
		$key=mysql_real_escape_string($key,$hx['c']);
		$value=mysql_real_escape_string($value,$hx['c']);
		$query.="`$key` ,";
		$valo.="'$value' ,";
		}

	$query=substr($query,0,strlen($query)-1);
	$valo=substr($valo,0,strlen($valo)-1);	
	
	$query="INSERT INTO `".mysql_real_escape_string($table,$hx['c'])."` ( $query ) VALUES ( $valo );";

	$hx['r'] = mysql_query($query) ;
	if (ERRORDB($hx['r'],DBE_ERR)) return FALSE;
	tabclose($hx);
	
	}
	

function dbupdate(&$hx,$tabe,$linea) {
	if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
	$hx['_'] = $tabe;
	$id=$linea['id'];	
	if ($id=='') {
		ERRORDB(FALSE,DBE_ID);
		return FALSE;
		}	
	
	$query="UPDATE `".mysql_real_escape_string($tabe,$hx['c'])."` SET ";
	$id=mysql_real_escape_string($linea['id'],$hx['c']);
	unset ($linea['id']);
	
	foreach($linea as $key => $value) {
		$key=mysql_real_escape_string ($key,$hx['c']);
		if ($value=='') $query.="`$key` = NULL ,"; else $query.="`$key` = '".mysql_real_escape_string ($value,$hx['c'])."',";
		}
						
	$query=substr($query,0,strlen($query)-1);
	$query.=" WHERE `id` ='$id' LIMIT 1 ;";
			
	$hx['r'] = mysql_query($query);
	if (ERRORDB($hx['r'],DBE_ERR)) return FALSE;
	tabclose($hx);			
	}
	
function dbincrement(&$dbh,$table) {
  //if ($hx['s']==0) {ERRORDB(FALSE,DBE_CLO);return FALSE;}
  if (ERRORDB(dbquery($dbh,"SHOW TABLE STATUS LIKE '".mysql_real_escape_string ($table,$dbh['c'])."'"),DBE_ERR)) return FALSE;
  $db = dbget($dbh);
  return $db['Auto_increment'];
  }  

function dbFlag(&$db,$flg,$bool=true) {
  $flg=$flg[0];
  if ($bool==false) $db['flags'] = str_replace($flg,'',$db['flags']); else if (strpos($db['flags'],$flg)===FALSE) $db['flags'].=$flg;
  }

	
function dbencode(&$hx,$st) {
  return mysql_real_escape_string($st,$hx['c']);
  }

//////////// nuove ///////////////

function dbwhere(&$hx,$ary,$cmp='=',$or=false) {
	$out=array();
	if ($or) $or='OR'; else $or='AND';
	
	foreach($ary as $k => $v) {
		$out[] = '`' . mysql_real_escape_string($k,$hx['c']) . "` $cmp '" . mysql_real_escape_string($v,$hx['c']) ."'";
		}
	return implode(" $or ",$out);
	}

function dbsearch(&$hx,$tabe,$ary,$limit='',$cmp='=',$or=false) {
	$qry = dbwhere($hx,$ary,$cmp,$or);
	if ($limit!='') $limit="LIMIT $limit";
	dbquery($hx,"SELECT * FROM `". mysql_real_escape_string($tabe,$hx['c'])."` WHERE $qry $limit");
	}

function dbexistsarr(&$hx,$tabe,$ary) {
	dbsearch($hx,$tabe,$ary,1);
	return dbget($hx);
	}


function dbex_newqry($dbh,$tabe='',$ext='') {
	$dbh['qry'] = array();
	$dbh['tabe']=$tabe;
	$dbh['ext']=$ext;
	return $dbh;
	}

function dbex_addGrep(&$dbh,$k='*',$v='') { 
	if (!isset($dbh['grep'])) $dbh['grep']=array();
	$dbh['grep'][$k]=$v;
	}

function dbex_do(&$dbh) {
	if ($dbh['tabe']=='') return ERRORDB(false,DBE_EXT_TABE);
	if (!isset($dbh['grep'])) $dbh['grep']=array('*' =>'');
	$grp=array();
	foreach($dbh['grep'] as $k => $v) {
		if ($v!='') $grp[]="$v as $k"; else $grp[]=$k;
		}
	
	$grp=implode(',',$grp);
	
	$sql="SELECT $grp FROM `".dbenc($dbh,$dbh['tabe'])."` WHERE ".dbex_buildquery_str($dbh)." ".$dbh['ext'];
	$dbh['ext']='';
	//echo "\n<!-- $sql -->\n";
	dbquery($dbh,$sql);
	}

function dbex_sub($dbh) {
	$dbh['qry'] = array();
	return $dbh;
	}

function dbex_add(&$dbh,$con,$k,$op,$v) { 		dbex_addqry($dbh,$con,$k,$op,$v,$con); 	}
function dbex_and(&$dbh,$k,$op,$v) 	{ $con='AND'; 	dbex_addqry($dbh,$con,$k,$op,$v,$con); 	}
function dbex_or(&$dbh,$k,$op,$v) 	{ $con='OR'; 	dbex_addqry($dbh,$con,$k,$op,$v,$con); 	}
function dbex_and_sub(&$dbh,$sub) 	{ $dbh['qry'][] = array('AND','(',$sub['qry']);		}
function dbex_or_sub(&$dbh,$sub) 	{ $dbh['qry'][] = array('OR','(',$sub['qry']);		}
function dbex_ext(&$dbh,$ext)		{ $dbh['ext'] = $ext; 					}

function dbex_addqry(&$dbh,$con='AND',$k,$op='=',$v,$opar='AND') {
	if (is_array($v)) {
			
			$t0=array();
			foreach($v as $vv) $t0[] = array($opar,$op,$k,$vv);
			$dbh['qry'][] = array($con,'(',$t0);
			$t0='';
			
		} else $dbh['qry'][] = array($con,$op,$k,$v);
	}

function dbex_buildquery_str(&$dbh) {
	$wh='';
	$op=false;
	if (!is_array($dbh['qry'])) return ERRORDB(false,DBE_EXT_QRY);
	if (count($dbh['qry'])==0) return '1';
	
	foreach($dbh['qry'] as $cq) {
		if ($op) $wh.=$cq[0].' '; else $op=true;
		if ($cq[1]=='(') {
			$ch=$dbh;
			$ch['qry'] = $cq[2];
			$wh.='( '.dbex_buildquery_str($ch);
			$ch='';
			$wh.=') ';
			} else {
			$wh.= '`'. dbenc($dbh,$cq[2]).'` '.$cq[1]." '".
				( (is_numeric($cq[3])) ? floatval($cq[3]) : dbenc($dbh,$cq[3]) ).
				"' ";
			}
		}
	$dbh['qry']=array();
	return trim($wh,' ');
	
	}

function dbex_search_userquery(&$dir,$st,$fields='keys',$tit=true) {
	$st=str_replace(
		explode(' '," \\ / * . , : ; ( ) \" ! | % & = ? ' ` { } ~ [ ] + # ° § < > ^ \$ % @ _ - \t \r \n \0")
			,' ',$st)
			;
	if (!is_array($fields)) $fields=explode(' ',$fields);
	
	while(strpos($st,'  ')!==false) $st=str_replace('  ',' ',$st);
	$st=trim($st,' ');
	$st=htmlentities($st,ENT_QUOTES);
	$st=explode(' ',$st);
	$q=array();
	$tts='';
	foreach ($st as $k) {
		if (is_numeric($k) AND strlen($k)<4) { $tts.='% '; continue; }
		if (strlen($k)<3) {$tts.='% '; continue; }
		$q[]="% $k %";
		$tts.="$k ";
		}
	
	if (count($q)==0) return false;
	
	if ($tit) {
		$tts=str_replace(array(' % ',' %','% ','%%'),'%',$tts);
		while(strpos($tts,'  ')!==false) $tts=str_replace('  ',' ',$tts);
		while(strpos($tts,'%%')!==false) $tts=str_replace('%%','%',$tts);
		$tts=trim($tts," % ");
		
		if ($tts!='' AND $tts!=' ' AND $tts!='%') {
			foreach($fields as $fi) dbex_or($dir,$fi,'LIKE','%'.$tts.'%');
			}
		}
	
	foreach($fields as $fi) dbex_addqry($dir,'OR',$fi,'LIKE',$q,'AND');
	}
	 
function dbenc(&$hx,$dta) {
  return mysql_real_escape_string($dta,$hx['c']);
  }

	 	 
?>
