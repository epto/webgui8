<?
Window($APPL['file']['filename'],'/img/form.gif','150','150');

$javaScript='
var im = WGWin.getByID("load");
im.style.display="none";
im = WGWin.getByID("img");
var w = im.naturalWidth;
var h = im.naturalHeight;
if (!w || !h) return;

var mw = Math.ceil(WGScrWidth()*0.80);
var mh = Math.ceil(WGScrHeight()*0.80);

if (w<mw && h<mh) {
	w+=32;
	h+=48;
	if (w<150) w=150;
	if (h<150) h=150;
	WGWin.setSize(w,h); 
	} else {
	var r = h/w;
	if (w>h) {
	        w = mw-32;
	        h = Math.ceil(w*r);
	        } else {
		h = mh - 48;
		w = Math.ceil(h/r);
		}

	if (w>mw || h>mh) {
	        w=Math.floor(w/2);
	        h=Math.floor(h/2);
		}
	
	im.style.width=w+"px";
	im.style.height=h+"px";
	WGWin.setSize(w+32,h+48);
	}
im.style.visibility="visible";
';
winEvent("procImg",$javaScript);

?>
<div style="text-align: center;"><div style="position: absolute; left: 0px; top: 0px; width: 100%; height: 100%; overflow: hidden; line-height: 130px; text-align: center; font-size: 12pt;" data-wgid="load">Loading</div>
<img src="<?=htmlspecialchars($APPL['file']['path'],ENT_QUOTES); ?>" alt="" style="margin: 4px auto; visibility: hidden;" data-wgonload="procImg" data-wgid="img">
</div>
<? endWin(); ?>
