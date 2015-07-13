<?
if (!setWindowDialog()) FatalError("This program cannot be run in window mode");
$palette='000000#000080#008040#008080#800000#ff8000#c0c0c0#808080#0080ff#00ff00#00ffff#ff0000#00ffff#ff00ff#ffff00#ffffff#ff8080#ffff80#80ff80#80ffff#ff80c0#ff80ff#ff0080#bf0000#008080#004080#8000ff#808000#8080c0#800080#804040#c8c8c8#0000ff#8080ff#d5d500#ffbc79';
$palette=explode("#",$palette);

?><!--@COORD
	rgb		29 8
	prev	119	8
	lay		8 8
-->
<div class="WGWActivity" data-wgid="main">
<form method="POST" action="#" name="main">

<div class="dlgCClay" style="background-color: #f00;" data-wgpox="lay,lay#0"><span>R</span></div>
<div class="dlgCClay" style="background-color: #0f0;" data-wgpox="lay,lay#24"><span>G</span></div>
<div class="dlgCClay" style="background-color: #00f;" data-wgpox="lay,lay#48"><span>B</span></div>
	
<div class="dlgCColC" data-wgpox="rgb,rgb#0">
	<div class="dlgCCol1">
		<div class="dlgCC dlgCCMeno clgCCU" data-wgonclick="menoR">-</div>
		<div class="dlgCC dlgCCVal" data-wgid="cr"><input type="text" name="r" value="0" data-wgid="crv" data-wgconnect="main.r" data-wgreg="[0-9]{1,3}" data-wgerr="Invalid color value."></div>
		<div class="dlgCC dlgCCPiu clgCCU" data-wgonclick="piuR">+</div>
	</div>
</div>

<div class="dlgCColC" data-wgpox="rgb,rgb#24">
	<div class="dlgCCol1">
		<div class="dlgCC dlgCCMeno clgCCU" data-wgonclick="menoG">-</div>
		<div class="dlgCC dlgCCVal" data-wgid="cg"><input type="text" name="g" value="0" data-wgid="cgv" data-wgconnect="main.g" data-wgreg="[0-9]{1,3}" data-wgerr="Invalid color value."></div>
		<div class="dlgCC dlgCCPiu clgCCU" data-wgonclick="piuG">+</div>
	</div>
</div>

<div class="dlgCColC" data-wgpox="rgb,rgb#48">
	<div class="dlgCCol1">
		<div class="dlgCC dlgCCMeno clgCCU" data-wgonclick="menoB">-</div>
		<div class="dlgCC dlgCCVal" data-wgid="cb"><input type="text" name="b" value="0" data-wgid="cbv" data-wgconnect="main.b" data-wgreg="[0-9]{1,3}" data-wgerr="Invalid color value."></div>
		<div class="dlgCC dlgCCPiu clgCCU" data-wgonclick="piuB">+</div>
	</div>
</div>

<div data-wgid="dlgCCprev" class="dlgCCprev" data-wgpox="prev,prev"><span data-wgid="code"></span></div>

<div class="dlgCCPaletteC">
	<div class="dlgCCPalette">
	<?
	
	foreach ($palette as $C) {
		$cod=$C;
		$r=hexdec(substr($C,0,2));
		$g=hexdec(substr($C,2,2));
		$b=hexdec(substr($C,4,2));
		
		echo "<div class=\"dlgCCPal\" data-wgonclick=\"setcolor\" data-coder=\"$r\" data-codeg=\"$g\" data-codeb=\"$b\" style=\"background-color: #$cod;\"></div>";
		}
	?>
	</div>
</div>
<a href="#okka()" class="WGButton" data-wgpox="-8,-8,48" data-wglay="C">OK</a>
</form>

</div>
