<?
$usr = WGSessionUser();
if ($usr===false) FatalError("Access denied");
WGSetTitle(WGSL("chg","Change password").": {$usr['name']}");
?><div class="WGWActivity" data-wgid="access">
<br><img src="<%<APPPATH>%>/icon.png" alt=""><br>Loading...<br>
</div>
<div class="WGWActivity" data-wgid="accessok">
<br><img src="<%<APPPATH>%>/icon.png" alt=""><br>
<%<chgd_h>%>.<br>
<a href="#_close()" class="WGButton" data-wgpox="-8,-8">Ok</a>
</div>
<div class="WGWActivity SysAppLogonErr" data-wgid="error">
<br><img src="<%<APPPATH>%>/icon.png" alt=""><br>
<%<error_h>%>:<br>
<%<perr_h>%>.
<a href="#main" class="WGButton" data-wgpox="-8,-8"><%<retry_h>%></a>
</div>
