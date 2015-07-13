<?
if (!setWindowDialog()) FatalError("This program cannot be run in window mode");
$ff=array();

$fnt= EWGGetFonts();
foreach($fnt['font'] as $li) {
	$ff[] = $li['face'];
	}	

foreach(array(
	'Verdana',
	'Helvetica, sans-serif',	
	'sans-serif', 	
	'Times, serif', 	
	'Times New Roman, serif', 	
	'serif', 	
	'Courier New, monospace', 	
	'Courier, monospace', 	
	'Fixed, monospace', 	
	'monospace', 	
	'cursive', 	
	'fantasy')
	as $li) $ff[] = $li;
	 
?>
<!--@COORD
	text	8	8
	sel 	76	8
	tsize	65	20
	ssize	100	20
-->
<div class="WGWActivity" data-wgid="main">

<div 
	class="FontPreview" 
	data-wgid="prev" 
	data-wgpox="-8,8,200,60"
	data-wgonclick="update">AaBbCc123</div>

	<form method="POST" action="#" name="main">
	<div data-wgpox="text,text#0,tsize,tsize" data-wglay="RH">Font:</div>
	<div data-wgpox="text,text#20,tsize,tsize" data-wglay="RH">Size:</div>
	<div data-wgpox="text,text#40,tsize,tsize" data-wglay="RH">Weight:</div>
	
	<select 
		name="fontFamily"  
		data-wgconnect="main.fontFamily"
		data-wgpox="sel,sel#0,ssize,ssize">
		<?
		foreach($ff as $v) {
		        if (strpos($v,',')===false) {
					$a=$v;
					} else {
					list($a)=explode(',',$v);
					}
		        echo "<option value=\"".htmlspecialchars($v,ENT_QUOTES)."\">".htmlspecialchars($a,ENT_QUOTES)."</option>\n";
			}
		?>
	</select>
	
	<select 
		name="fontSize" 
		data-wgconnect="main.fontSize"
		data-wgpox="sel,sel#20,ssize,ssize">
		<?
		for ($a=1;$a<73;$a++) echo '<option value="'.$a.'pt">'.$a."</option>\n";
		?>
	</select>
	
	<select 
		name="fontWeight" 
		data-wgconnect="main.fontWeight"
		data-wgpox="sel,sel#40,ssize,ssize">
		<option value="normal" default>Normal</option>
		<option value="bold">Bold</option>
	</select>
	
	</form>

<a href="#font()" class="WGButton" data-wgpox="-8,-8,48" data-wglay="C">OK</a>
	
</div>
