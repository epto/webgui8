<?

// <!-- Youtube API Interface -->


$apptoken='';
$developerkey=$ini['youtube']['devkey'];

function APILogin($apiset='0') {
	global $apptoken;
	global $developerkey;
	global $ini;

	$var="var/libyt.$apiset.stat";
	
	$t0=@file_get_contents($var);
	if ($t0!==FALSE) $t0=unserialize($t0);
	if ($t0!==FALSE) if (time()-$t0['last']>200) $t0=FALSE;
	if ($t0===FALSE) {
		$t0=ClientLogin($ini['youtube']['login'],$ini['youtube']['password'],$ini['youtube']['source'].$apiset);
		$t0['last']=time();
		if ($t0!==FALSE) @file_put_contents($var,serialize($t0));
		} else {
		  $apptoken=$t0['auth'];
		  }
		
	return $t0;
}

function ClientLogin($login,$password,$source='test',&$contents='') {
	global $apptoken;
	global $developerkey;
	global $ini;
	
	$opts = array(

  'http'=>array(
    'method'	=> 'POST',
	'timeout'	=> 5,
	'user_agent'=>'MyCompany-MyApp-1.0 Zend_Framework_Gdata/1.5.1',
	'connection' => 'close'	,
	'header' =>"Content-Type:application/x-www-form-urlencoded"	,
	'user_agent'=>$ini['sys']['ua']	,
	'content'	=> 'Email='.urlencode($login).'&Passwd='.urlencode($password).'&service=youtube&source='.urlencode($source)
		
	)
	)
	;
	
	if ($ini['proxy']['api']!='') {
		$opts['http']['proxy']="tcp://".$ini['proxy']['api'];
		$opts['http']['request_fulluri']=TRUE;
		}
	
	$context = stream_context_create($opts);
	
	
	$fp = @fopen('https://www.google.com/youtube/accounts/ClientLogin', 'r', false, $context);
	if ($fp===FALSE) return FALSE;
	$contents = '';
		
		while (!feof($fp)) { $contents .=@fread($fp, 8192);  }
	
	fclose($fp);
	
	$contents=str_replace("\r","",$contents);
	$contents=explode("\n",$contents);
	$cx=count($contents);

	$arr=array();

		for ($ax=0;$ax<$cx;$ax++) {
			list($k,$v)=explode('=',trim($contents[$ax]),2);
			if ($k!='') $arr[strtolower(trim($k))]=trim($v);
			} 
	
	if (!isset($arr['auth'])) return FALSE;
	
	$apptoken=$arr['auth'];
	
	return $arr;

}

function utf8toiso8859(&$str) {
	//$enc=mb_detect_encoding($str);
	//if ($enc===FALSE) $enc='ISO-8859-1, UTF-8';
	$str=mb_convert_encoding ($str,'ISO-8859-1','UTF-8');
	}
	
function SendXML($uri,$xml,$meta,$head='') {
	global $apptoken;
	global $developerkey;
	global $ini;
	
	if (strlen($head)) $head="\r\n".$head;
	
	$opts = array(

  'http'=>array(
    'method'	=> 'POST',
	'timeout'	=> 5,
	'user_agent'=>'MyCompany-MyApp-1.0 Zend_Framework_Gdata/1.5.1',
	'connection' => 'close'	,
	'header' =>"Content-Type: application/atom+xml\r\nAuthorization: GoogleLogin auth=$apptoken\r\nX-GData-Key: key=$developerkey\r\nX-GData-Client:".$ini['youtube']['source'].$head,
   'user_agent'=>$ini['sys']['ua']	,
   'content' => $xml
	)
	)
	;
	
	if ($ini['proxy']['api']!='') {
		$opts['http']['proxy']="tcp://".$ini['proxy']['api'];
		$opts['http']['request_fulluri']=TRUE;
		}
	
	$context = stream_context_create($opts);

	$fp = @fopen($uri, 'r', false, $context);
	
	if ($fp===FALSE)  return FALSE;

	$contents = '';
	
	while (!feof($fp)) { $contents .=fread($fp, 8192); }
	
	$meta=stream_get_meta_data($fp);
	fclose($fp);
	$meta=$meta['wrapper_data'];
	
	return $contents;
	}
	
function LoadXML($uri) {
	global $apptoken;
	global $developerkey;
	global $ini;
	
	$opts = array(

  'http'=>array(
    'method'	=> 'GET',
	'timeout'	=> 5,
	'user_agent'=>'MyCompany-MyApp-1.0 Zend_Framework_Gdata/1.5.1',
	'connection' => 'close'	,
	'user_agent'=>$ini['sys']['ua']	,
	'header' =>"Authorization: GoogleLogin auth=$apptoken\r\nX-GData-Key: key=$developerkey\r\nX-GData-Client:".$ini['youtube']['source']
	)
	)
	;
	
	if ($ini['proxy']['api']!='') {
		$opts['http']['proxy']="tcp://".$ini['proxy']['api'];
		$opts['http']['request_fulluri']=TRUE;
		}
	
	$context = stream_context_create($opts);

	$fp = @fopen($uri, 'r', false, $context);
	
	if ($fp===FALSE)  return FALSE;

	$contents = '';
	
	while (!feof($fp)) { $contents .=fread($fp, 8192); }
	
	fclose($fp);
	
	
	
	$t0= simplexml_load_string($contents);
	
	return $t0;
	}

function getvideoid(&$entry) {
	$media = $entry->children('http://search.yahoo.com/mrss/');
	$attrs = $media->group->player->attributes();
	list($t0,$t1)=explode('=',$attrs['url']);
	return $t1;
	}

 function parseVideoEntry(&$entry,$force=FALSE,&$sidppp='') {     
 	  global $dbh;
	  $sidppp=array();	   
      $obj= array();
    
  	  $obj['date'] = $entry->published;
	  $obj['user'] = $entry->author->name;    
  
      $media = $entry->children('http://search.yahoo.com/mrss/');
      $obj['title'] = $media->group->title;
	  $sidppp['title']=$obj['title'];
      $obj['desc'] = $media->group->description;

	  $obj['tags']= $media->group->keywords;
	  $obj['cat'] = $media->group->category;
       $attrs = $media->group->player->attributes();
      $obj['url'] = $attrs['url'];
	  list($t1,$t0)=explode('=',$obj['url']);
	  $obj['sid']=$t0;
	  $sidppp['sid']=$t0;
	  //if (!$force) if (isfuffa($dbh,$obj['sid'])!==FALSE) return FALSE;

	  $cx=count($media->group->thumbnail);
	  $obj['img']=array();
	  
	  		for ($ax=0;$ax<$cx;$ax++) {
      			$attrs = $media->group->thumbnail[$ax]->attributes();
      			$obj['img'][$ax] = $attrs['url']; 
				}
            
      $yt = $media->children('http://gdata.youtube.com/schemas/2007');
      $attrs = $yt->duration->attributes();
      $obj['len'] = $attrs['seconds']; 

      $yt = $entry->children('http://gdata.youtube.com/schemas/2007');
      $attrs = $yt->statistics->attributes();
      $obj['hit'] = intval(@$attrs['viewCount']); 
	  $obj['favorite']=intval(@$attrs['favoriteCount']);
	  
	  
	  
      $gd = $entry->children('http://schemas.google.com/g/2005'); 
      if ($gd->rating) { 
        $attrs = $gd->rating->attributes();
        $obj['rating'] = $attrs['average']; 
		$obj['raters']=intval(@$attrs['numRaters']);
      } else {
        $obj['rating'] = 0;  
		$obj['raters']=0;       
      }

      $gd = $entry->children('http://schemas.google.com/g/2005');
      if ($gd->comments->feedLink) { 
        $attrs = $gd->comments->feedLink->attributes();
        $obj['commentsURL'] = $attrs['href']; 
        $obj['commentsCount'] = $attrs['countHint']; 
      }
      
      // get feed URL for video responses
      $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
      $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/2007#video.responses']"); 
      if (count($nodeset) > 0) {
        $obj['responsesURL'] = $nodeset[0]['href'];      
      }

      $entry->registerXPathNamespace('feed', 'http://www.w3.org/2005/Atom');
      $nodeset = $entry->xpath("feed:link[@rel='http://gdata.youtube.com/schemas/2007#video.related']"); 
      if (count($nodeset) > 0) {
        $obj['relatedURL'] = $nodeset[0]['href'];      
      }
  
  	  $obj['uploads']="http://gdata.youtube.com/feeds/api/users/".$obj['user']."/uploads";
  
	  foreach($obj as $k=>$v) {
	  	if ($k!=='img') $obj[$k]="".$v;
	  	}
	  
	  $tags=explode(',',$obj['tags']);
		$cx=count($tags);
		for ($ax=0;$ax<$cx;$ax++) {
			$tags[$ax]=strtolower(trim($tags[$ax]));
			}
	  $obj['tags']=$tags;
	  
      return $obj;     
	   
    }   

function QuerySearch($url) {
	$maxp=10000;
	$fmax=TRUE;
	$clov=0;
	
	$arr=Array();	
	for ($pag=1;$pag<$maxp;$pag++) {
		
		$idx=(($pag-1)*50)+1;
		if (strpos($url,'?')!==FALSE) $feedURL = "$url&max-results=50&start-index=$idx"; else $feedURL = "$url?max-results=50&start-index=$idx";
		$sxml = LoadXML($feedURL); //simplexml_load_file($feedURL);
		if ($sxml===FALSE) return;
		if ($fmax) {
			$counts = $sxml->children('http://a9.com/-/spec/opensearchrss/1.0/');
      		$total = $counts->totalResults;
			$maxp=intval($total/50);
			if ($total % 50) $maxp++;
			$fmax=FALSE; 
			}
		
		foreach ($sxml->entry as $entry) {
			$obj=parseVideoEntry($entry);
			if ($obj!==FALSE) {
			   	   $arr[$clov]=$obj;
				   $clov++;
				   }
			}//entry
		}//pag
		
	return $arr;
}

function QueryCount($url,$logon=FALSE) {
		if ($logon) $sxml = LoadXML($url); else $sxml=simplexml_load_file($url);
		if ($sxml===FALSE) return FALSE;
		$counts = $sxml->children('http://a9.com/-/spec/opensearchrss/1.0/');
   		$total = $counts->totalResults;
	return $total;
}
	
function LoadVideo($vid) {
	$feedURL = 'http://gdata.youtube.com/feeds/api/videos/' . $vid;
    $entry = @simplexml_load_file($feedURL);
    if ($entry===FALSE) return FALSE;
	return parseVideoEntry($entry);
	}
	
function download($url,$file) {
	global $ini;
	$opts = array(

  		'http'=>array(
    		'method'	=> 'GET',
			'timeout'	=> 5,
			'user_agent'=>'Mozilla/5.0 (Windows; U; Windows NT 5.0; it; rv:1.8.1.12) Gecko/20080201 Firefox/2.0.0.12',
			'connection' => 'close'	,
			'header' =>"Accept-Language: it-it,en-us;q=0.8,it;q=0.5,en;q=0.3\r\nAccept-Encoding: gzip,deflate\r\nAccept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"
			)
		)
	;
	
	if ($ini['proxy']['download']!='') {
		$opts['http']['proxy']="tcp://".$ini['proxy']['download'];
		$opts['http']['request_fulluri']=TRUE;
		}
	
	$context = stream_context_create($opts);
	$url=str_replace(" ","%20",$url);

	$fp = fopen($url, 'rb', false, $context);
	if ($fp===FALSE) return FALSE;
	$contents = '';
	$q=fopen($file,'wb');
	if ($q===FALSE) return FALSE;
	
		while (!feof($fp)) { fwrite($q,fread($fp, 8192)); }
	
	fclose($fp);
	fclose($q);
	return TRUE;	
	}

function getlink($video_url){
	global $ini;
   $opts = array(

  		'http'=>array(
    		'method'	=> 'GET',
			'timeout'	=> 5,
			'user_agent'=>'Mozilla/5.0 (Windows; U; Windows NT 5.0; it; rv:1.8.1.12) Gecko/20080201 Firefox/2.0.0.12',
			'connection' => 'close'	,
			'header' =>"Accept-Language: it-it,en-us;q=0.8,it;q=0.5,en;q=0.3\r\nAccept-Encoding: gzip,deflate\r\nAccept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"
			)
		)
	;
	
	if ($ini['proxy']['api']!='') {
		$opts['http']['proxy']="tcp://".$ini['proxy']['api'];
		$opts['http']['request_fulluri']=TRUE;
		}
	
   $html = @file_get_contents($video_url,FALSE,$context);
   if(preg_match("/var fullscreenUrl = '(.*?)';/",$html,$match)){
      $download_url = $match[1];
      $download_url = preg_replace('/\/watch_fullscreen/',"http://youtube.com/get_video.php",$download_url);
      return $download_url;
   }
}

	
	
?>
