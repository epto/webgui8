<?

function checkAccess() {
	if (!WGSession() or !WGCan('root')) FatalError("Access denied");
	}

function updateUserList() {
	global $DBH;
	global $WEBGUI;
	
	dbquery($DBH,"SELECT id,name, (SELECT name FROM `ewggroup` WHERE `ewguser`.`group`=`ewggroup`.`id` LIMIT 1) as 'group' FROM `ewguser` WHERE 1 ORDER BY `name` ASC");
	$WEBGUI['win']['data']['main']['usr'] = array();
	while($db=dbget($DBH)) $WEBGUI['win']['data']['main']['usr'][] = $db;
	}

function LocalError($st) {
	setCaption('errormsg',$st);
	WGSetActivity('error');
	return false;
	}

function layout_create() {
        checkAccess();
	updateUserList();
        }

function ajax_delusr($par) {
	global $JSON;
	global $DBH;
	checkAccess();
	$db=dbfirst($DBH,'ewguser','id',$par);
	if ($db===false) FatalError(WGSL("euid","Unknown UID"));
	$c=json_decode($db['can'],true);
	if ($c['root']) FatalError(WGSL("nroot","Can't delete a root user"));      
	$x = WGRemoveUser($db['name']);
	if ($x) {
		WGToast(WGSL("udel","User removed"));
	        updateUserList();
	        WGSetActivity('main');
		} else LocalError(WGSL("uerr","User not removed"));
	}

function form_passwd_post($raw,$data) {
	global $DBH;
	checkAccess();
	if ($data['f1']!=$data['f2']) return LocalError(WGSL("perr","Password error"));
	if ($data['uid']==0) FatalError(WGSL("euid","Unknown UID"));
	$db=dbfirst($DBH,'ewguser','id',$data['uid']);
	if ($db===false) FatalError(WGSL("euid","Unknown UID"));
	$x=WGPasswd($db['name'],false,$data['f1']);
	if ($x) WGToast(WGSL("pokc","Password changed")); else LocalError(WGSL("p2err","Password not changed"));
	$data['f1']='';
	$data['f2']='';
	WGSetActivity('main');
	return $data;
	}

function form_newusr_post($raw,$data) {
	checkAccess();
	if ($data['f1']!=$data['f2']) return LocalError(WGSL("perr","Password error"));
	$x = WGCreateUser($data['login'],$data['f1'],$data['group']);
	if ($x) {
	        WGToast(WGSL("nus","User created"));
	        updateUserList();
	        WGSetActivity('main');
		} else LocalError(WGSL("nusc","User not created"));
	}

?>
