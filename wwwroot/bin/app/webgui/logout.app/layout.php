<?
EWGLogout(WGSL("term","Session closed"),"Ok");
if (!WGisHTTPAuth()) {
?>
<div class="WGWActivity" data-wgid="main">
<div class="sysLogout"><%<term_h>%><br><br>
<a href="#_close()" class="WGButton">Ok</a></div>
</div>
<? } ?>
