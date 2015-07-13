<?
if (!defined("EWGINCLUDE")) exit;
if (EWGLogon(@$JSON['data']['a'],@$JSON['data']['b']) and WGSession()) $OUTJSON['ok']=true; else $OUTJSON['err']="Access Denied";
?>