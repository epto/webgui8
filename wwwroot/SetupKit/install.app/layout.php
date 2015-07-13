<? 
$ini=WGGetIni();
if (@$ini['webgui']['cansetup']!='webgui') FatalError("Setup locked"); 

?>
 <div class="WGWActivity" data-wgid="error">
	<div class="setupLoader setupError">
		<div>
		<img src="<%<APPPATH_H>%>img/error.png" alt=""><br>
		<b>Errore:</b><br>
		Qualcosa è andato storto.<br>
		<span data-wgid="erro"></span><br>
		</div>
	</div>
	<a href="#main" class="WGButton" data-wgpox="-8,-8">Riprova</a>
</div>

<div class="WGWActivity" data-wgid="running">
	<div class="setupLoader">
		<div>
		<img src="<%<APPPATH_H>%>img/wgloading.gif" alt=""><br>
		Istallazione in corso<br>
		Attendere...
		</div>
	</div>
</div>

<div class="WGWActivity" data-wgid="setup">
	<div class="setupLoader">
		<div>
		<img src="<%<APPPATH_H>%>img/setup.png" alt=""><br>
		<b>Installazione completata.</b><br>
		Occorre riavviare la WebGui per rendere effettive le nuove impostazioni.<br>
		Clicca sul bottone qui sotto o ricarica la pagina.<br>
		<span class="consiglio">Rimuovi questa directory:<br><%<APPPATH_H>%></span>
		</div>
	</div>
	<div class="WGButton" data-wgpox="-8,-8" onclick="location.href='/'; location.reload();";>Riavvia</div>
</div>
