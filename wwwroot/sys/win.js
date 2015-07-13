var EWG = {
		"version"		:	{
			"id"	:0x8001,
			"v"		:8,
			"r"		:1,
			"s"		:"B",
			"b"		:1}
			,
		
		"ext"		:	{},
		
		"WIN_NORMAL"	:	1,
		"WIN_NOSIZE"	:	2,
		"WIN_POPUP"		:	4,
		"WIN_DIALOG"	:	8,
		
		"AJ_SYSTEM"		:	0,
		"AJ_SYSCALL"	:	1,
		"AJ_WINAJAX"	:	2,
		"AJ_SHELL"		:	3,
		"AJ_FLUX"		:	4,
		"AJ_EVENT"		:	5,
		"AJ_POST"		:	6,
		"AJ_DIALOG"		:	7,
		
		"WS_NORMAL"		:	0,
		"WS_MINIMZED"	:	1,
		"WS_MAXIMIZED"	:	2,
		
		"POX_NORMAL"	:	0,
	    "POX_CENX"		:	1,
	    "POX_CENY"		:	2,
	    "POX_CENTER"	:	3,
	    "POX_RIGHT"		:	4,
	    "POX_BOTTOM"	:	8,
	    "POX_RB"		:	12,
	   	   	
	   	"elementEvent" : {"onclick":1,"ondblclick":1,"onblur":1,"onfocus":1,"onchanged":1,"onload":1},
	   		
		"DesktopPlacer"	:	WGDefaultDesktopPlacer,
				
		"setDesktopPlacer" : function (f) { 
			EWG.DesktopPlacer=f; 
			if (EWG.DesktopPlacer) EWG.DesktopPlacer(WGlob.icons); 
			},
			
		"win" : function() { return WGlob.activeWindow; }
			
	} ;
	
var WGlob = {
	"server"		:	""	,
	"deb"			:	true,
	"maxWin"		:	0	,
	"winCnt"		:	0	,
	"maxZIndex"		:	1000,
	"maxIcon"		:	0	,
	"lang"			:	"_D_",
	"langRes"		:	{}	,
	"activeWindow"	:	null,
	"desktop"		:	null,
	"drag"			:	null,
	"currentWin"	:	null,
	"currentWinIco"	:	null,
	"dragSurface"	:	{} 	,
	"winOrder"		:	[]	,
	"windows"		:	{}	,
	"icons"			:	[]	,
	"fun"			:	{}	,
	"topTo"			:	null,
	"topWin"		:	null,
	"iconManager"	:	null,
	"closed"		:	[]	,
	"tasks"			:	{}	,
	"widgets"		:	{}	,
	"ajax"			:	{}	,
	"winCss"		:	{}	,
	"user"			:	{
		"user"		:	false	,
		"group"		:	false	,
		"can"		:	false
		}	,
	"modCnt"		:	0	,
	"modList"		:	{}	,
	"mod"			:	{}	,
	"dll"			:	{}	}
	;		

var WGWGX = {};

var WGMenu = {
		"cnt"	:	0,
		"cnto"	:	0,
		"menu"	:	[],
		"omenu"	:	[],
		"odiv"	:	null,
		"div"	:	null,
		"OMNList":	[],
		"rif"	:	null
		} ;

var WGProcSet = {
	"maxHinsances"	:	10,
	"maxGUIEvents"	:	5,
	"msgBox": {
		"charWidth" : 	6,
		"extraWidth":	50,
		"extraHeight":	88,
		"minCharWidth":	60,
		"minWidth":		300,
		"minHeight":	60
		},
	"userTray"  : {
		"Logout"	:	"Logout",
		"Passwd"	:	"Password",
		"Admin"		:	"Manager"		
		},
	"toast"		:	{
		"width"	:	400,
		"height":	20,
		"time"	:	5
		},
	"win"	:	{
	        "barHeight"	:	20,
	        "icoSize"	:	20,
	        "bottomHeight"	:	8,
	        "borderSize"	:	2,
	        "menuHeight"	:	20,
	        "bottomIco"	:	8,
	        "minWidth"	:	100,
	        "minHeight"	:	48,
	        "statusHeight":	20,
	        "titleBorder":0,
	        
	        "default"	:	{
				"title"	:	" "	,
				"x"		:	0	,
				"y"		:	0	,
				"w"		:	320	,
				"h"		:	240	,
				"html"	:	" " ,
				"i"		:	"/img/win_form.gif"
				},
	        
	        "icon"	:	{
				"i"	:	"/img/win_form.gif"
				}
	        }	,
	"menu"	:	{
			"itemWidth"		:	150,
			"itemHeight"	:	20,
			"icoSize"		:	20,
			"icoDx"			:	"/img/menu_sub.gif",
			"icoChk"		:	[
				"/img/CHK0.gif",
				"/img/CHK1.gif",
				"/img/CHK2.gif",
				"/img/CHK3.gif"
				]
		},

	"icon"	:	{
		"rx"	:	27,
		"ry"	:	26,
		"cx"	:	27,
		"cy"	:	27,
		"width"	:	70,
		"height":	70,
		"textHeight":	20,
		"oMargin"	:	1,
		"vMargin"	:	1,
		"minTipLen"	:	16
		},

	"boxIcon"	:	{
		"ask"	:	"/img/msgbox_ask.gif",
		"info"	:	"/img/msgbox_info.gif",
		"stop"	:	"/img/msgbox_stop.gif",
		"warn"	:	"/img/msgbox_warn.gif"
		},

	"winProc"	:	{
		"icoDx"	:	[
			{
				"i"		:	"/img/win_close.gif"	,
				"fun"	:	WGCloseThisWindow,
				"wty"	:	7
				},
			{
				"cls"	:	"WGChangeThisWindowSize",
				"fun"	:	WGChangeThisWindowSize,
				"wty"	:	1
				},
			{
				"i"	:	"/img/win_minimize.gif",
				"fun"	:	WGMinimizeThisWindow,
				"wty"	:	3
				}
			],

		"icoSx"	:	[
			{
				"i"		:	"/img/win_menu.gif",
				"isIcon":	true,
				"menu"	:	[
					{
						"req"	:	"WGResizable",
						"noWT"	:	[ EWG.WIN_NOSIZE, EWG.WIN_DIALOG ],
						"i"		:	"/img/win_maximize.gif",
						"t"		:	"Maximize/Normalize"	,
						"fun"	:	WGChangeThisWindowSize
						},
					{
						"req"	:	"WGResizable",
						"noWT"	:	[ EWG.WIN_DIALOG ],
						"i"		:	"/img/win_minimize.gif",
						"t"		:	"Minimize",
						"fun"	:	WGMinimizeThisWindow
						},
					{
						"i"		:	"/img/win_close.gif",
						"t"		:	"Close",
						"fun"	:	WGCloseThisWindow
						}
					],
				"fun"	:	WGWinIcoMenuOpen,
				"wty"	:	7
				},
				
			{
				"req"	:	"WGHasHistory",
				"i"		:	"/img/win_back.gif",
				"WGWinCallFun":"setBackground",
				"WGWinCallPar":"#8f8",
				"fun"	:	WGWinCall
				}
			]
			
		},
	"inputDialog":	{
		"font"	:	{
				"f"		:	"font.dlg",
				"cls"	:	"EWGFontDialogElement",
				"onCreate"	:	function (el) {
					el.innerHTML="AaBcCc123";
					el.WGValue={
						"TYPE"		:	"EWG.FONT",
						"fontFamily":	"Verdana",
						"fontSize"	:	"10pt",
						"fontWeight":	"normal"}
						;
					el.WGGetValue = function () { return this.WGValue; }
					},
				"onSet"		:	function (el,dta) {
					if (def(dta.fontFamily)) el.style.fontFamily=dta.fontFamily;
					if (def(dta.fontSize)) el.style.fontSize=dta.fontSize;
					if (def(dta.fontWeight)) el.style.fontWeight=dta.fontWeight;
					el.WGValue=dta;
					}
			},
		
		"color"	:	{
				"f"		:	"color.dlg",
				"onCreate"	:	function (el) {
					el.innerHTML="";
					el.WGValue='#ffffff';
					el.style.width='64px';
					el.style.height='20px';
					el.style.border='#000 1px solid';
					el.style.cursor='pointer';
					el.style.backgroundColor='#ffffff';
					el.WGGetValue = function () { return this.WGValue; }
					},
				"onSet"		:	function (el,dta) {
					el.style.backgroundColor=dta;
					el.WGValue=dta;
					}
			}
		
		},
			
	"winType"	:	{},
	"menuClass"	:	{
		"start" : {
			"itemWidth"		:	150,
			"itemHeight"	:	24,
			"icoSize"		:	24,
			"icoDx"			:	"/img/menu_sub.gif",
			"icoChk"		:	[
				"/img/CHK0.gif",
				"/img/CHK1.gif",
				"/img/CHK2.gif",
				"/img/CHK3.gif"] 
				}
			},
	"winClass"	:	{},
	"iconClass"	:	{
				"WGDIBig"	:	{
					"rx"	:	40,
					"ry"	:	40,
					"cx"	:	47,
					"cy"	:	44,
					"width"	:	110,
					"height":	110,
					"textHeight":	32
					}
			}
	} ;

var WGEvent = {
	"win"	:	{
		"clickBar"	:	function(div,data) {
		        if (def(div.WGWin)) div=div.WGWin;
				WGSetActiveWindowDiv(div);
				WGDoEvent(div,'activate');
			}
		}
	} ;

var WGWindowsDefault={
		"WGMinimized"	:	false,
		"WGMaximized"	:	false,
		"WGResizable"	:	true,
		"WGControlBox"	:	true,
		"WGHasHistory"	:	false,
		"WGPercentWidth":	0	,
		"WGPercentHeight":	0	,
		"WGStartCenter"	:	false,
		"WGURL"			:	null,
		"WGHistory"		:	null,
		"WGModal"		:	false,
		"WGTop"			:	false,
		"WGMaxNum"		:	0,
		"WGProcess"		:	null,
		"WGDDE"			:	null}
		;
	
var WGDefEvent = {
		"onformerror" : function(win,data) {
			var st="";
			var j = data.err.length;
			for (var i = 0; i < j ; i++) {
				st+=data.err[i]+"\n";
				}
			WGToast(st);
			}
	} ;	

var WGUIEvent = {
	"priority"	:	{}
	} ;

var WGAPI = {
	
		"reply"	:	function(js) {
			if (!js.hdc || !js.data) return;
			var win = WGGetWindow(js.hdc);
			if (!win) return;
			win.WGData = js.data;	
			},
	
		"open"	:	function(js) {
			if (!js.asNew) {
				if (js.hdc) {
					var win = WGGetWindow(js.hdc);
					if (win!=null && def(win.WGhdc)) {
						WGReloadWindowDiv(win,js);
						return;
						}
					} else if (js.name) {
					var win = WGGetWinByApp(js.name);
					if (win!=null) {
						WGReloadWindowDiv(win,js);
						return;
						}	
					}
				} else {
				var win = WGGetWindow(js.hdc);
				if (win!=null && def(win.WGhdc)) WGDestroyWindowDiv(win);
				}
			WGCreateWindow(js);
			},
			
		"close"	:	function(js) {
			var win = WGGetWindow(js);
				if (win!=null) win.close();
			},
		
		"doevent" : function(js) {
			var win = WGGetWindow(js.hdc);
				if (win!=null) win.doEvent(js.name,def(js.par) ? js.par : null);
			},
			
		"setcap" : function(js) {
			var win = WGGetWindow(js.hdc);
				if (win!=null) win.setCaption(js.name,js.par,js.mul);
			},
			
		"css"	: function(js) {
			var v = document.getElementById(js.id);
			if (v) {
				for( var k in js) {
					if (k=='id') continue;
					try {
						v.style[k]=js[k];
						} catch(DevNull) {}
					}
				}
			},
		"desktop"	: WGSetDesktop,
		"widget"	: WGAddWidget,
		"event"		: function (js) { WGDoGuiEvent(js.name,js.data) },
	
		"err"		: function (js) { WGError(js,WGStl('wgerror',"Error"),WGStl('wgcancel',"Cancel")); },
		"procSet"	: function (js) {
			for (var el in js) {
				WGProcSet[el]=js[el];
				}
			},
		
		"redraw"	: function(js) { WGRedraw(); },
			
		"theme"		: function(js) { WGSetGuiTheme(js); },
		
		"startBar"	: function (js) {
			WGCreateStartBar(js,'EWGStartBar');
			},
		
		"loadDLL"	: function (js) { WGLoadDLL(js); },
		"loadNamedDLL" : function(js) { WGLoadDLL(js.f,js.n); },
		"unloadDLL"	: WGUnloadDLL,
		"wgx"		: WGAddCustomControl,
		
		"lockGui"	: function (js) { WGLockGui(js.flg,js.msg); },
		
		"msgbox"	: function (js) {
			var win = WGGetWindow(js.hdc);
			if (win!=null) WGMsgBox(win,js.text,js.title,js.buttons,js.ico,def(js.onButton) ? js.onButton : null,null, js.class ? js.class : null);
			},
		
		"activity"	: function(js) {
			var win = WGGetWindow(js.hdc);
			if (win!=null) win.setActivity(js.act);
			},
				
		"toast"		: function(js) {
			WGToast(js.text,js.t ? js.t : false,js.x0 ? js.x0 : 0, js.y0 ? js.y0 : 0 );
			},

		"wincss"	: function(js) {
			WGLoadWinCss(js.id,js.css);
			},

		"setlang"	: function(js) {
			try { 
				WGlob.lang = js.id;
				WGlob.langRes = js.js; 
				if (def(js.js.procSet)) {
					var k;
					for(var i=0;i<4;i++) {
						for(k in js.js.procSet) {
							if (k=='ewgSXMenu'+i && def(WGProcSet.winProc.icoSx[0]) && def(WGProcSet.winProc.icoSx[0].menu) && def(WGProcSet.winProc.icoSx[0].menu[i])) WGProcSet.winProc.icoSx[0].menu[i].t=js.js.procSet[k];
							}
						}
					}
				} catch(DevNull) {}
			},
			
		"killall"	: WGKillAll,
		
		"logon"		: function(js) {
			WGlob.user={
				"user"	: js.user ? js.user : false ,
				"group" : js.group ? js.group : false,
				"can"	: js.can ? js.can : false }
				;
			},
		
		"logout"	: function(js) { WGHTTPAuthLogout(js.msg ? js.msg : false,js.but ? js.but : false); },
		
		"setHTML" : function(js) {
			var win = WGGetWindow(js.hdc);
				if (win!=null) win.setHTML(js.id,js.html);
			
			},
		
		"shell"		: shellExecute,	
		"boot"		: EPTOWebGuiBootProc	
	} ;

var WGUIEventStk = {
	"_c"	:	0
	} ;

var WGWindowsProto = {
				
		"close"	: function() {
			WGDoEvent(this,'close');
			WGIMARemoveWin(this);
			if (def(this.WGContent)) this.WGContent.innerHTML='';
			WGDestroyWindowDiv(this);
			WGUpdateTaskBar(); 
			},
		
		"getWinState"	:	function() { return this.WGWinState; },
		
		"setWinState"	:	function(s) {
			if (s == EWG.WS_MINIMZED) this.minimize();
			if (s == EWG.WS_NORMAL) this.normalize();
			if (s == EWG.WS_MAXIMIZED) this.maximize();
			},
		
		"maximize"	:	function() {
			this.WGWinState = EWG.WS_MAXIMIZED;
			WGIMARemoveWin(this);
			if (this.WGMinimized) {
				this.WGMinimized=false;
				this.WGVisible=true;
				this.style.visibility='visible';
				if (def(this.WGBottomContent)) this.WGBottomContent.style.visibility='visible';
				WGUpdateTaskBar();
				}
			
			if (this.WGMaximized) return false;
			this.WGMaximized=true;
			this.WGNormalRect={
				"x"	:	this.getLeft(),
				"y"	:	this.getTop(),
				"w"	:	this.WGWidth,
				"h"	:	this.WGHeight}
				;
				
			this.style.left="0px";
			this.style.top="0px";
			this.style.width=WGlob.desktop.clientWidth;
			this.style.height=WGlob.desktop.clientHeight;
			this.WGWidth=WGlob.desktop.clientWidth;
			this.WGHeight=WGlob.desktop.clientHeight;
			this.WGTitleBar.WGPox.x=0;
			this.WGTitleBar.WGPox.y=0;
			this.classList.add("WGWinMaximized");
			WGWinResize(this);
			WGDoEvent(this,'resize');
			return false;
			},

		"normalize"	:	function() {
			this.WGWinState = EWG.WS_NORMAL;
			WGIMARemoveWin(this);
			if (this.WGMinimized) {
				this.WGMinimized=false;
				this.WGVisible=true;
				this.style.visibility='visible';
				WGUpdateTaskBar();
				}
				
			if (!this.WGMaximized) return false;
			if (!def(this.WGNormalRect)) return false;
			this.WGMaximized=false;
			this.style.left=this.WGNormalRect.x+"px";
			this.style.top=this.WGNormalRect.y+"px";
			this.style.width=this.WGNormalRect.w+"px";
			this.style.height=this.WGNormalRect.h+"px";
			this.WGWidth=this.WGNormalRect.w;
			this.WGHeight=this.WGNormalRect.h;
			this.WGTitleBar.WGPox.x=this.WGNormalRect.x;
			this.WGTitleBar.WGPox.y=this.WGNormalRect.y;
			this.classList.remove("WGWinMaximized");
			WGWinResize(this);
			WGDoEvent(this,'resize');
			return false;			
			},
		
		"deiconize" : function() {
			if (this.WGMaximized) this.WGWinState = EWG.WS_MAXIMIZED; else this.WGWinState = EWG.WS_NORMAL;
			if (!this.WGMinimized) return false;
			this.WGMinimized=false;
			this.WGVisible=true;
			this.style.visibility='visible';
			if (def(this.WGBottomContent)) this.WGBottomContent.style.visibility='visible';
			WGIMARemoveWin(this);
			WGUpdateTaskBar();
			WGDoEvent(this,'resize');
			},
			
		"minimize" : function() {
			this.WGWinState = EWG.WS_MINIMZED;
			if (this.WGMinimized) return false;
			this.WGMinimized=true;
			this.WGVisible=false;
			this.style.visibility='hidden';
			if (def(this.WGBottomContent)) this.WGBottomContent.style.visibility='hidden';
			WGIMAddWin(this);
			WGUpdateTaskBar();
			WGDoEvent(this,'minimize');
			},
		
		"changeMode" : function() {
			if (this.WGMaximized) return this.normalize(); else return this.maximize();
			},
		
		"stl" : function(st,def) {
			var rs = def;
			var i = st.indexOf('_');
			var m='';
			if (i>-1) {
				m = st.substr(i+1);
				st = st.substr(0,i);
				}
			if (def(this.WGWinLang[st])) rs = this.WGWinLang[st];
			if (m=='') return rs;
			if (m=='c') return WGUtil.addcslashes(rs);
			if (m=='h') return toHTML(rs);
			if (m=='hc') return toHTML(GUtil.addcslashes(rs));		 
			if (m=='ch') return GUtil.addcslashes(toHTML(rs));
			throw "Invalid stl encoding `"+m+"`";
			},
		
		"setSize"	:	function(w,h) {
			if (w<this.WGMinWidth) w=this.WGMinWidth;
			if (h<this.WGMinHeight) w=this.WGMinHeight;
			this.WGWidth=w;
			this.WGHeight=h;
			WGWinResize(this);
			WGDoEvent(this,'resize');
			},
			
		"getWidth" : function() { return this.WGWidth; },
		"getHeight" : function() { return this.WGHeight; },
		"getScaleWidth" : function() { return this.WGContent.clientWidth; },
		"getScaleHeight" : function() { return this.WGContent.clientHeight; },
		
		"getInnerWidth": function() { return this.WGInnerWidth; },
		"getInnerHeight": function() { return this.WGInnerHeight; },
		"getExtraWidth": function() { return this.WGExtraWidth; },
		"getExtraHeight": function() { return this.WGExtraHeight; },
		
		"autoResize" : function() { WGAutoResize(this); },
				
		"setStyleByData" : function(id,path) {
			var e = this.getByID(id);
			if (!e) return false;
			var d = WGGetByDot(this.WGData,path);
			if (!d) return false;
			for (var i in d) {
				if (i=='TYPE') continue;
				try {
					e.style[i] = d[i];
					} catch(DevNull) {}
				}
			return true;
			},
		
		"setBackground" : function(col) {
			this.WGContent.style.backgroundColor=col;
			},
			
		"setColor" : function(col) {
			this.WGContent.style.color=col;
			},
		
		"setURL"	:	function(url) {
			if (this.WGHasHistory) this.WGHistory.push(this.WGURL);
			this.WGURL=url;
			},
		
		"setTitle"	:	function(s) {
			this.WGTitle=s;
			this.WGTitleBar.innerHTML=toHTML(s);
			},
			
		"setOverflow":	function(b) {
			b=b ? true : false;
			this.WGContent.style.overflow= b ? "auto" : "hidden";
			},
		
		"setScrollbars" : function(h,v) {
			this.WGContent.style.overflowX= h ? "scroll" : "hidden";
			this.WGContent.style.overflowY= v ? "scroll" : "hidden";
			},
		
		"setAlpha"	:	function(v100) {
			try { this.style.opacity = v100/100; } catch(DevNull) {}
			try { this.style.filter="alpha(opacity="+v100+")"; } catch(DevNull) {}
			},
		
		"doEvent"	:	function(name,par) {
			WGDoEvent(this,name,par);
			},
			
		"resetHTML"	:	function(html) {
			this.innerHTML=html;
			WGInitGuiContent(this,html);
			WGDoEvent(this,'reload');
			},
					
		"setActivity"	:	function(id) {
			WGSetActivity(this,id);
			},
		
		"prevInstances"	:	function() {
			var v = this.getName();
			return def(WGlob.tasks[v]) ? WGlob.tasks[v].w : null;
			},
		
		"numInstances" : function() { 
			var v = this.getName();
			return def(WGlob.tasks[v]) ? WGlob.tasks[v].c : null;
			},
		
		"maxInstances" : function() { 
			var v = this.getName();
			return def(WGlob.tasks[v]) ? WGlob.tasks[v].m : null;
			},
		
		"setSingleInstance" : function() {
			var v = this.getName();
			if (!def(WGlob.tasks[v])) return false;
			for (var k in WGlob.tasks[v]) {
				if (WGlob.tasks[v].w[k]!=this) WGDestroyWindowDiv(WGlob.tasks[v].w[k]);
				}
				
			WGlob.tasks[v].w = {};
			w[v]=this;
			WGlob.tasks[v].c = 1;
			return true;		
			},
		
		"msgbox"	:	function(text,title,buttons,ico,onButton,onDirectButton) {
			return WGMsgBox(this,text,title,buttons,ico,onButton,onDirectButton)
			},
		
		"update":	function() { WGSetElementsData(this.WGWin); },
		
		"getByID"	:	function(id) {
			if (def(this.WGIDS[id])) return this.WGIDS[id];
			return null;
			},
		
		"setCaption":	function(id,capt,crLf) {
			var el = this.getByID(id);
			if (el==null) alert("Window element `"+id+"` not found");
			if (def(el.value)) el.value=capt; else el.innerHTML = toHTML(capt,crLf);
			},
		
		"setHTML" : function(id,html) {
			var el = this.getByID(id);
			if (el==null) alert("Window element `"+id+"` not found");
			if (def(el.innerHTML)) {
				el.innerHTML = html;
				WGCrawleHTMLSet(el,this.WGIDS,this.WGSetObj,this);
				}
			},
			
		"getValue"	:	function(id) {
			var el = this.getByID(id);
			if (el==null) alert("Window element `"+id+"` not found");
			el=WGInputGetData(el);
			if (el.v) return v; else return null;
			},
		
		"getDialogValue" : function() {
			
			if (!this.WGisDialog || !def(this.WGDialogBy)) {
				alert("No dialog handle for `"+this.WHhdc+"`");
				return null;
				}
			
			var win = WGGetWindow(this.WGDialogBy);
			if (!def(win.WGCurrentDialogElement) || !def(win.WGCurrentDialogElement.WGDialogVal)) return null;
			return win.WGCurrentDialogElement.WGDialogVal;			
			},
			
		"getDialogTag" : function() {
			
			if (!this.WGisDialog || !def(this.WGDialogBy)) {
				alert("No dialog handle for `"+this.WHhdc+"`");
				return null;
				}
			
			var win = WGGetWindow(this.WGDialogBy);
			if (!def(win.WGCurrentDialogElement) || !def(win.WGCurrentDialogElement.WGDialogTag)) return null;
			return win.WGCurrentDialogElement.WGDialogTag;			
			},			
			
		"dialogReturn" : function(data,noClose) { 
			if (!this.WGisDialog || !def(this.WGDialogBy)) {
				alert("No dialog handle for `"+this.WHhdc+"`");
				if (!noClose) WGDestroyWindowDiv(this);
				return;
				}
			
			var ret = data ? data : this.getData();
			var win = WGGetWindow(this.WGDialogBy);
									
			if (win!=null) {
				if (this.WGDialogDatum!=null) win.setDatum(this.WGDialogDatum,ret);
				if (this.WGDialogFun!=null) WGDoEvent(win,this.WGDialogFun,ret);
				if (def(win.WGOnDialogSel) && win.WGOnDialogSel!=null && def(win.WGCurrentDialogElement) && win.WGCurrentDialogElement!=null) {
					win.WGOnDialogSel(win.WGCurrentDialogElement,ret);
					}
				win.WGCurrentDialogElement=null;
				win.update();
				}
			
			if (!noClose) WGDestroyWindowDiv(this);			
			},
		
		"openDialog" : function(file,ownerParanName,ownerEventName,dialogParam) {
			WGOpenDialog(file,this.WGWin.WGhdc,ownerParanName,ownerEventName,dialogParam);
			},
		
		"shell" : function(hr) {
			var i = hr.indexOf('#');
			var f = hr.indexOf('()');
						
			if (i==0 && f==-1) {
				hr = hr.substr(1);
				WGSetActivity(this.WGWin,hr);
				return true;
				}
			
			if (i==0 && f!=-1) {
				hr=hr.substring(1,f);
				if (hr.indexOf('_')==0) {
					hr=hr.substr(1);
					if (def(this.WGWin[hr])) this.WGWin[hr]();
					} else {
					WGDoEvent(this.WGWin,hr);
					}
				return true;
				}
			
			if (i!=0) {
				shellExecute(hr,this.WGWin.WGhdc);
				return true;
				}
				
			return false;
			},
				
		"submit"	:	function(fAll,act,onsumbit,name) {	
			if (!act) act = this.WGCurrentActivity;

			var o = {
				"t"		:	EWG.AJ_POST,
				"hdc"	:	this.WGhdc,
				"url"	:	this.WGURL,
				"app"	:	this.WGName,
				"frm"	:	name ? name : false,
				"act"	:	act,
				"a"		:	fAll ? true : false
				} ;
						
			if (fAll) {
				o.par = this.WGData;
				} else {
				o.par = def(this.WGData[act]) ? this.WGData[act] : null;
				}	
			var win = this;
			WGSetElementsData(win);
			AJAXService("/",o,function (js) {	
				if (act) win.setActivity(act);
				if (def(js.postReply)) {	
					if (def(win.WGData[act])) WGObjMerge(win.WGData[act],js.postReply); else win.WGData[act]=js.postReply;
					WGSetElementsData(win);
					}
				if (onsubmit) onsubmit(def(js.postReply) ? js.postReply : null);
				ServerData(js);
				}) ;
			},
		
		"callAJAX" : function(api,data,callback) { this.AJAX(api,data,callback,'*'); },
				
		"AJAX"		:	function(api,data,callback,addFormData) {
			var oAJAX = {
				"t"		:	EWG.AJ_WINAJAX,
				"api"	:	api,
				"hdc"	:	this.WGhdc,
				"url"	:	this.WGURL,
				"app"	:	this.WGName,
				"act"	:	this.WGCurrentActivity,
				"par"	:	data
				} ;
				
			if (addFormData) {
				if (addFormData=="*") {
					oAJAX.form=this.WGData; 
					} else {
					if (def(this.WGData[addFormData])) try { oAJAX.data.form=this.WGData[addFormData]; } catch(DevNull) {}
					}
				}
				
			AJAXService("/",oAJAX,
				function (json) { 
					ServerData(json);
					if (callback) callback(json.ajaxReturn ? json.ajaxReturn : null);
					}) ;
			},
		
		"setData"	:	function(data) { this.WGData = data; },
		"getData"	:	function() { return this.WGData; },	
		
		"setActDatum"	:	function(key,datum) { 
					var a = this.WGCurrentActivity;
					if (!def(this.WGData[a])) this.WGData[a]={};
					this.WGData[a][key]=datum; 
					},
					
		"getActDatum"	:	function(key,defa) { 
				var a = this.WGCurrentActivity;
				if (!def(this.WGData[a])) return defa;
				return def(this.WGData[a][key]) ? this.WGData[key] : defa ; 
				},
		
		"getDatum"		:	function(path) {
				if (path.indexOf('.')==-1) path=this.WGCurrentActivity+'.'+path;
				return WGGetByDot(this.WGData,path);
				},
		
		"setDatum"		:	function(path,val) {
				if (path.indexOf('.')==-1) path=this.WGCurrentActivity+'.'+path;
				this.WGData=WGSetByDot(this.WGData,path,val);
				},
		
		"getName"	:	function() { return this.WGName; },
		
		"invalidInput"	:	function(id,err) {
			var el = this.getByID(id);
			if (el==null) return false;
			
			var verr = el.dataset.wgerr ? el.dataset.wgerr : null;
			if (err) verr=err;
			if (!err) err="Invalid value for `"+id+"`";
			WGInputError(el,err);
			return true;
			},
		
		"getCoord"	: function(id,xy) {
			id=String(id);
			var i = id.indexOf('.');
			var ext=0;
			if (i==0) {
				var tok=id.split(/\./);
				var tl=tok.length;
				if (tl>2) ext=tok[2];
				var el=this.getByID(tok[1]);
			
				if (el==null) return 0;
				if (ext=='w') return el.offsetWidth;
				if (ext=='h') return el.OffsetHeight;
				
				if (ext=='') ext=0;
				var rect=el.getBoundingClientRect();
				
				if (ext=='x') return rect.left;
				if (ext=='y') return rect.top;
				
				if (ext=='-x') return rect.right;
				if (ext=='-y') return rect.bottom;
				
				}
			
			if (xy) xy='WGCoordY'; else xy='WGCoordX';
			i = id.indexOf('#');
		
			if (i>-1) {
				i=id.split(/\#/);
				id=i[0];
				if (i[1]=='') i[1]='0';
				ext=parseInt(i[1]);
				}
				
			if (def(this[xy]) && def(this[xy][id])) return this[xy][id]+ext;
				
			if (WGIsNumeric(id)) {		
				var v= parseInt(id);
				return (v*16)+ext;
				}
			return 0;
			},
		
		"getXCoord"	: function(id) { return this.getCoord(id,false);},
		"getYCoord"	: function(id) { return this.getCoord(id,true);	},
		
		"getActivity"	: 	function() { return this.WGCurrentActivity; },	
		"getHDC"	:	function() { return this.WGhdc; },
		"getTitle"	:	function() { return this.WGTitle; },
		"getWidth"	:	function() { return this.WGWidth; },
		"getHeight"	:	function() { return this.WGHeight; },
		"getLeft"	:	function() { return this.WGTitleBar.WGLeft; },
		"getTop"	:	function() { return this.WGTitleBar.WGTop; },
		"newTag"	:	function(tagName) { return WGNewTag(this,tagName) ;},
			
		"move"		:	function(x,y,w,h) {
			if (this.WGMaximized) this.setWinState(EWG.WIN_NORMAL);
			
			if (x=='c') {
				x = Math.floor((WGScrWidth()/2) - (this.WGWidth/2));
				}
				
			if (y=='c') {
				y = Math.floor((WGScrHeight()/2) - (this.WGHeight/2));
				}
				
			if (def(x)) {
					this.WGTitleBar.WGPox.x=x;
					this.WGTitleBar.WGLeft=x;
					this.style.left=x+'px';
				}
			if (def(y)) {
				this.WGTitleBar.WGPox.y=y;
				this.WGTitleBar.WGTop=y;
				this.style.top=y+'px';
				}
				
			if (def(w) && w>0) this.WGWidth=w;
			if (def(h) && h>0) this.WGHeight=h;
			
			WGWinResize(this);
			}
	
	} ;

function WGAddFormType(name,obj) {
	WGProcSet.inputDialog[name] = obj;
	if (def(obj.onInit)) obj.onInit();
	}

function WGIsNumeric(input){
    var RE = /^-{0,1}\d*\.{0,1}\d+$/;
    return (RE.test(input));
}

function WGUnloadDLL(nam) {
	var e = document.getElementsByTagName("head")[0];
	
	var t=['','-j','-c'];
	for (var i = 0; i< 3; i++) {
		var n = nam+t[i];
		if (def(WGlob.dll[n])) {
			try { e.removeChild(WGlob.dll[n].o); } catch(DevNull) {}
			delete(WGlob.dll[n]);
			}
		}
	}

function WGLoadWinCss(id,css) {
	if (def(WGlob.winCss[id])) {
		WGlob.winCss[id].innerHTML=css;
		} else {
		var o = document.createElement('style');
		o.innerHTML=css;
		var head = document.getElementsByTagName("head")[0];
		head.appendChild(o);
		WGlob.winCss[id]=o;
		}
	}

function WGLoadDLL(lpFile,nam) {
	var i = lpFile.lastIndexOf('.');
	if (i==-1) return null;
	var ext = lpFile.substr(i+1);
	var k=false;
	if (!nam) {
		nam = lpFile.substr(i);
		i = nam.lastIndexOf('/');
		if (i>-1) nam=nam.substr(i+1);
		k=true;
	}
	var o = null;
	
	if (ext == 'js') {
		o = document.createElement('script');
		o.setAttribute("type","text/javascript");
		o.setAttribute("src", lpFile);
		try { o.appendChild(document.createTextNode("")); } catch(e) {}
		if (k) nam+='-j';
		}
	
	if (ext == 'css') {
		o = document.createElement("link");
		o.setAttribute("rel", "stylesheet");
        o.setAttribute("type", "text/css");
        o.setAttribute("href", lpFile);
        try { o.appendChild(document.createTextNode("")); } catch(e) {}
        if (k) nam+='-c';
		}
	
	if (o==null) return null;
	
	try {
		var e = document.getElementsByTagName("head")[0];
		e.appendChild(o);
		} catch(DevNull) {
		document.head.appendChild(o);	
		}
	
	WGlob.dll[nam] = {
		"e"	:	o,
		"f"	:	lpFile,
		"t"	:	ext	}
		;
	
	return nam;
	}

function WGToast(string,tim,x0,y0) {
	if (def(WGlob.toast)) WGRemoveToast();
	var v = document.createElement('div');
	var vc = document.createElement('div');
	var vd = document.createElement('div');
	vd.setAttribute('class','WGToastXButton');
	vd.innerHTML='X';
	vc.style.position='relative';
	vc.innerHTML=toHTML(string,true);
	vc.appendChild(vd);
	v.setAttribute('class','WGToast');
	var w=WGProcSet.toast.width;
	v.style.width=w+'px';
	var h = 0;
	vc.style.height='auto';
	v.style.overflow='visible';
	v.appendChild(vc);
	v.WGInner=vc;
	h = vc.clientHeight;
	if (h==0) h=1;
		
	var x = Math.ceil((WGScrWidth() / 2)-(w/2));
	var y = Math.ceil((WGScrHeight() / 2)-(h/2));
	
	if (x0) {
		if (x0>-1) v.style.left=x0+'px'; else v.style.right=(-x0)+'px';
		} else v.style.left=x+'px';
		
	if (y0) {
		if (y0>-1) v.style.top=y0+'px'; else v.style.bottom=(-y0)+'px';
		} else v.style.top=y+'px';
		
	v.style.width=w+'px';
	v.style.height=h+'px';
	
	WGlob.desktop.appendChild(v);
	v.onclick=WGRemoveToast;
	vd.onclick=WGRemoveToast;
	v.onmousemove=WGToastCountinue;
	
	WGToTop(v);
	if (!tim) tim = WGProcSet.toast.time;
	
	WGlob.toast={
		"e"	:	v	,
		"x"	: 	setTimeout(WGCompleteToast, 10),
		"c"	:	false,
		"j"	:	false,
		"t"	:	(tim>0) ? setTimeout(WGRemoveToast, tim*1000) : null}
		;
	WGCompleteToast();
	}
	
function WGToastCountinue() {
	if (!def(WGlob.toast)) return;
	if (WGlob.j) return;
	try { clearTimeout(WGlob.toast.t); } catch(DevNull) {}
	WGlob.j=true; 
	}	
	
function WGCompleteToast() {
	if (!def(WGlob.toast)) return;
	if (WGlob.toast.c) return;
	var v = WGlob.toast.e;
	var vc = v.WGInner;
	var h = vc.offsetHeight;
	if (h==0) h= vc.clientHeight;
	if (h==0) h=100; else WGlob.toast.c=true;
	var y = Math.ceil((WGScrHeight() / 2)-(h/2));
	v.style.top=y+'px';
	v.style.height=h+'px';
	try { clearTimeout(WGlob.toast.x); } catch(DevNull) {}
	}

function WGRemoveToast() {
	if (!def(WGlob.toast)) return;
	try { clearTimeout(WGlob.toast.t); } catch(DevNull) {}
	try { WGlob.desktop.removeChild(WGlob.toast.e); } catch(DevNull) {}
	delete(WGlob.toast);
	}

function WGReloadWindowDiv(v,obj) {
	v.setTitle(obj.title);
	v.WGEvent={};
	
	if (def(obj.event)) {
			for (var el in obj.event) v.WGEvent[el]=obj.event[el];
			}
			
	if (def(obj.data)) {
		for (var el in obj.data) v.WGData[el]=obj.data[el];
		}
	
	if (def(obj.statusBar)) WGAddWinStatusBar(v,def(obj.statusBar.h) ? obj.statusBar.h : WGProcSet.win.statusHeight,obj.statusBar.html);
	
	if(def(obj.html) && obj.html!='') {
		WGInitGuiContent(v,obj.html);
		WGDoEvent(v,'load',v);
		} else WGDoEvent(v,'reload',v);
	}

function WGGetActiveWindow() { return WGlob.activeWindow; }

function shellExecute(cmd,hdc,par) {
	var o = {
		"t"		:	EWG.AJ_SHELL,
		"url"	:	cmd}
	if (par) o.par=par;
	if (hdc) o.hdc=hdc;
	AJAXService("/",o,ServerData);	
	}	
	
function WGGetWindow(hdc) {
	var id = 'win'+ hdc+'W';
	if (def(WGlob.windows[id])) return WGlob.windows[id];
	return null;
	}
	
function WGGetHdcList() {
	var a = [];
	for (var k in WGlob.windows) {
		if (!def(WGlob.windows[k].WGhdc)) continue;
		a.push({
			"h"	:	WGlob.windows[k].WGhdc,
			"a"	:	def(WGlob.windows[k].name) ? WGlob.windows[k].name : false }
			) ;
		}
	return a;
	}	
	
function WGDoGuiEvent(name,data,div) {
	var pri = def(WGUIEvent.priority[name]) ? WGUIEvent.priority[name] : false;
	if (!pri && !def(WGUIEvent[name])) return;
	if (def(WGUIEvent[name])) data=WGUIEvent[name](data,div);
	if (data==false || data==null) return;
	if (!def(WGUIEventStk[name])) WGUIEventStk[name]=[];
	WGUIEventStk[name].push(data);
	if (WGUIEventStk._c++>(WGProcSet.maxGUIEvents ? WGProcSet.maxGUIEvents : 5)) pri=true;
	if (pri) WGSendGUIEvents();
	}

function WGSendGUIEvents() {
	if (WGUIEventStk._c==0) return;
	var  o = {
		"t"		:	EWG.AJ_EVENT,
		"par"	:	WGClone(WGUIEventStk)}
		;
	
	WGUIEventStk = {
		"_c"	:	0} 
		;
	
	AJAXService("/",o,ServerData);	
	}

function WGDisableITP() {
	if (!def(WGlob.ITP)) return;
	if (WGlob.ITP.tmp!=null) try { clearTimeout(WGlob.ITP.tmp); } catch(DevNull) {}
	if (def(WGlob.ITP)) delete(WGlob.ITP);
	}

function WGEnableITP() {
		WGlob.ITP = {
			"freq"	:	WGProcSet.ITPFreq ? WGProcSet.ITPFreq : 60,
			"tmp"	:	null	,
			"fun"	:	null	,
			"s"		:	0		,
			"c"		:	0		,
			"act"	:	[]		}
			;
			
		WGlob.ITP.tmp = setTimeout( WGITPHandler , WGlob.ITP.freq*1000 );
	}	
	
function WGITPHandler() {
	if (WGlob.ITP.s!=0) {
		if (WGlob.ITP.tmp!=null) try { clearTimeout(WGlob.ITP.tmp); } catch(DevNull) {}
		WGlob.ITP.tmp=null;
		return;
		}
	
	if (WGlob.ITP.fun!=null) try { WGlob.ITP.fun(); } catch(exc) { alert("ITP.fun Error: `"+exc+"`"); }
	var j = WGlob.ITP.act.length;
	for (var i = 0 ; i < j ;i++) {
		try {
			WGlob.ITP.act[i]();
			} catch(exc) {
				alert("ITP.act["+i+"] Error: `"+exc+"`");
				}
		}
	
	WGlob.ITP.act=[];
	WGSendGUIEvents();
	WGlob.ITP.tmp = setTimeout( WGITPHandler , WGlob.ITP.freq*1000 );
	}	
	
function WGUIAddEventListener(name,pri,fun) {
	if (name=='priority') return;
	WGUIEvent.priority[name]=pri ? true:false;
	WGUIEvent[name]=fun;
	}

function WGUIRemoveEventListener(name) {
	if (def(WGUIEvent.priority[name])) delete(WGUIEvent.priority[name]);
	if (def(WGUIEvent[name])) delete(WGUIEvent[name]);
	if (def(WGUIEventStk[name])) delete(WGUIEventStk[name]);
	}

function WGRequestTop(win,div) {
	WGlob.topTo=null;
	WGlob.topWin=null;
	WGToTop(div);
	WGlob.topTo = div;
	WGlob.topWin=win;
	}

function WGScrWidth() { return WGlob.desktop.clientWidth; }
function WGScrHeight() { return WGlob.desktop.clientHeight; }

function WGWidth(div) { return div.offsetWidth; }
function WGHeight(div) { return div.offsetHeight; }

function WGCloneArray(a) {
	var b= [];
	var j = a.length;
	for (var i = 0; i<j; i++) {
		if ( isArr(a[i])) {
			b.push( WGCloneArray( a[i] ));
			continue;
		}
		
		if (a[i] instanceof Object) {
			b.push(WGCloneObject( a[i] ));
			continue;
		}
			
		b.push(a[i]);
		}
	return b;
	}

function WGCloneObject(a) {
	var b = {};
	for( var k in a ) {
		
		if ( isArr(a[k])) {
			b[k] = WGCloneArray( a[k] );
			continue;
		}	
		
		if ( a[k] instanceof Object) {
			b[k] = WGCloneObject( a[k] );
			continue;
			}
		b[k] = a[k];
		}
	return b;
	}

function WGClone(a) { 
	if (isArr(a)) return WGCloneArray(a);
	if (a instanceof Object) return WGCloneObject(a);
	return a;
	}

function def(o) {
	if (typeof o != "undefined") return true; else return false;
	}
	
function isArr(o) {
	if (
			(Array && Array.isArray(o) && def(o.length) && !def(o.substr) ) ||
			o instanceof Array
			) return true;
			
	return false;
	}
	
function isObj(o) {
	if (!isArr(o) && o instanceof Object && !def(o.length)) return true;
	return false;	
	}

function WGNopEvent(e) { 
	e.preventDefault();
	e.WGNone=true;
	try { e.stopImmediatePropagation(); } catch(DevNull) {}
	return false;
	}

function WGCreateIcon(obj,x0,y0,cls) {
	var v = document.createElement('div');
	WGlob.maxWin++;
	v.setAttribute('id','WGDIcon'+WGlob.maxWin);
	v.setAttribute('class','WGDIcon');
	if (!def(cls) && def(obj.cls)) cls=obj.cls;
	if (!def(x0) && def(obj.x0)) x0=obj.x0; 
	if (!def(y0) && def(obj.y0)) y0=obj.y0;
	
	if (cls) v.classList.add(cls);
	if (def(x0)) v.style.left=x0+'px';
	if (def(y0)) v.style.top=y0+'px';
	if (def(obj.fun) || def(obj.l)) v.ondblclick=WGDblClickDIcon;
	v.WGIco=obj;
	v.onclick=WGClickDIcon;
	
	var vi = document.createElement('div');
	vi.setAttribute('class','WGDIconCont');
	v.appendChild(vi);
	
	if (def(obj.b)) {
		var bi = document.createElement('img');
		bi.setAttribute('class','WGDIconBack');
		bi.src=obj.b;
		vi.appendChild(bi);
		v.WGIcoBack=bi;
		} else v.WGIcoBack=null;
		
	var i = document.createElement('img');
	i.src=obj.i;
	i.setAttribute('class','WGDIconIcon');
	
	if (obj.t.length>=WGProcSet.icon.minTipLen)	i.setAttribute('title',obj.t);
	
	v.WGElIco=i;
	vi.appendChild(i);
	
	var par = null;
	if (cls && def(WGProcSet["icoClass"]) && def(WGProcSet.icoClass[cls])) par = WGProcSet.icoClass[cls]; else par=WGProcSet.icon;
		
	var j = 0;
	var ca = -90;
	
	if (def(obj.arr)) {
		j = obj.arr.length;
		v.WGIcons=[];
		var sa = 360 / j;
			
		if ((j & 1) == 0 ) ca=Math.floor(sa/2)-90;
			
		for (var i = 0; i < j; i++) {
			var ei = document.createElement('img');
			ei.src = obj.arr[i].i;
			ei.setAttribute("class","WGDIconSub");
			if (def(obj.arr[i].fun) || def(obj.arr[i].l)) ei.ondblclick=WGDblClickDIconS;
			if (def(obj.arr[i].t)) ei.setAttribute('title',obj.arr[i].t);
			ei.onclick=WGClickDIconS;
			ei.WGIco=obj.arr[i];
			ei.WGDIcon = v;
			v.WGIcons.push(ei);
			ei.WGIndex=i;
			var x = par.cx + Math.ceil(par.rx*Math.cos((ca % 360) * .017453292519943295));
			ei.style.left=x+"px";
			x = par.cy + Math.ceil(par.ry*Math.sin((ca % 360)* .017453292519943295));
			ca+=sa;
			ei.style.top=x+"px";
			vi.appendChild(ei);
			}
		
		}
	
	i = document.createElement('div');
	i.setAttribute("class","WGDIconText");
	v.WGIconText=i;
	v.WGBaloon=null;
	i.innerHTML='<span>'+toHTML(obj.t ? obj.t : "(No name)")+'</span>';
	vi.appendChild(i);
	if (def(obj.fun) || def(obj.l)) i.onclick=WGClickDIcon;
	return v;	
	}

function WGCreateDesktopIcon(obj,x0,y0,cls) {
	var i = WGCreateIcon(obj,x0,y0,cls);
	i.classList.add("WGDIconDesk");
	WGlob.icons.push(i);
	WGlob.desktop.appendChild(i);
	if (EWG.DesktopPlacer) EWG.DesktopPlacer(WGlob.icons); 
	}
	
function WGRemoveDesktopIcon(div) {
	var o = [];
	var j = WGlob.icons.length;
	for (var i=0; i<j; i++) {
		if (WGlob.icons[i]==div) {
			try { WGlob.desktop.removeChild(div); } catch(DevNull) {}
			} else {
			o.push(WGlob.icons[i]);	
			}
		}
	WGlob.icons=o;
	if (EWG.DesktopPlacer) EWG.DesktopPlacer(WGlob.icons); 
	}

function WGSetDesktop(arr) {
	var j = WGlob.icons.length;
	for (var i=0; i<j; i++) {
		try { WGlob.desktop.removeChild(WGlob.icons[i]); } catch(DevNull) {}
		}
	
	WGlob.icons=[];
	if (EWG.DesktopPlacer) EWG.DesktopPlacer(WGlob.icons); 
	
	j = arr.length;
	for (var i=0; i<j; i++) {
		WGCreateDesktopIcon(arr[i]);
		}
	}

function WGClickDIcon(evt) {
	if (WGChkEvent(evt)) return false;
	evt.preventDefault();
	var r = this.classList.contains("WGDIconSelected");
	WGClearClass("WGDIconSelected");
	if (!r) this.classList.add("WGDIconSelected");
	return false;
	}

function WGClickDIconS(evt) {
	if (WGChkEvent(evt)) return false;
	var r = this.WGDIcon.classList.contains("WGDIconSelected");
	WGClearClass("WGDIconSelected");
	if (!r) this.WGDIcon.classList.add("WGDIconSelected");
	return WGStopEvent(evt);
	}

function WGDblClickDIcon(evt) {
	if (WGChkEvent(evt)) return false;
	WGClearClass("WGDIconSelected");
	if (!def(this.WGIco)) return false;
	if (def(this.WGIco.fun)) this.WGIco.fun(this);
	if (def(this.WGIco.l)) shellExecute(this.WGIco.l);
	return false;
	}

function WGDblClickDIconS(evt) {
	if (WGChkEvent(evt)) return false;
	WGClearClass("WGDIconSelected");
	if (!def(this.WGIco)) return false;
	if (def(this.WGIco.fun)) this.WGIco.fun(this);
	if (def(this.WGIco.l)) shellExecute(this.WGIco.l);
	return WGStopEvent(evt);
	}

function WGCreateIconObj(img,back,title,onClick,arr,scale,cls) {
	var o = { 
		"p"	: null,
		"i" : img ,
		"fun":	onClick}
		;

	if (back) o.b=back;
	if (title) o.t=title;
	if (arr) o.sub=arr;
	if (scale) o.s=scale; else o.s=1;
	if (cls) o.cls=cls;
	return o;
	}
	
function WGIconAddSubObj(obj,ico,onClick) {
	if (!def(obj.arr)) obj.arr=[];
	obj.arr.push({
		"i"	:	ico	,
		"fun":	onClick})
		;
	}

function WGClickOnWindow() {
	if (WGlob.currentWin!=this) WGSetActiveWindowDiv(this); 
	}

function WGSetActiveWindowDiv(div) {
	if (!div.WGVisible) return;
	
	for (var hdc in WGlob.windows) {
	        var di = WGlob.windows[hdc];
	        if (di==null) continue;
		di.classList.remove("WGWinActive");
		if (!di.classList.contains("WGWinDisactive")) di.classList.add("WGWinDisactive");
		} 
		
	div.classList.add("WGWinActive");
	div.classList.remove("WGWinDisactive");
	
	WGToTop(div);
	
	var j = WGlob.winOrder.length;
	for (var i=0;i<j;i++) {
	        if (WGlob.winOrder[i]==div) {
		        WGlob.winOrder.splice(i,1);
		        WGlob.winOrder.push(div);
			return;
			}
		}
	WGlob.activeWindow=div;
	} 
	
function WGCurWindow(el) {
	if (!el) el=this;
	for (var i = 0 ; i < 256; i++) {
		if (def(el.WGWin)) return el.WGWin;
		var par = el.parentElement
		if (par==body) return null;
		if (par) el=par; else break;
		}
	return null;
	}
	
function WGTargetSelf(el,url) {
	if (!el) el=this;
	if (!url) url=el.dataset.wgurl;
	var w = WGCurWindow(el);
	shellExecute(url,w!=null ? w.WGhdc : null);
	}	
	
function WGCrawleRemoveClass(el,cls) {
	var arr = el.children;
	var j = arr.length;
	try { if (el.classList.contains(cls)) el.classList.remove(cls); } catch(DevNull) {}
	for( var i = 0; i < j ; i++ ) {
			var ce = arr[i];
			try { if (ce.classList.contains(cls)) ce.classList.remove(cls); } catch(DevNull) {}
			if (def(ce.children) && ce.children!=null) WGCrawleRemoveClass(ce,cls);
			}
	}	
	
function WGSetDivFocus(el) {
	if (!el) el=this;
	var par = WGCurWindow(el);
	if (par==null) {
		
		} else {
		WGCrawleRemoveClass(par,'WGSelectedElement');
		}
	el.classList.add("WGSelectedElement");
	}	

function WGWinCall() {
	if (!def(this.WGWinCallFun)) return false;
	var fun=this.WGWinCallFun;
	var par = null;
	if (def(this.WGWinCallPar)) par=this.WGWinCallPar;
	var win = WGGetThisWindow(this);
	if (win==null) return false;
	if (def(win[fun])) win[fun](par);
	return false;
	}

function WGGetThisWindow(div) {
	var win = null;	
	if (def(div.WGWin)) win=div.WGWin; else if (WGlob.currentWinIco) win=WGlob.currentWinIco;
	if (win==null && def(div.WGhdc)) win=div;
	return win;
	}

function WGCloseThisWindow() { 
	var win = WGGetThisWindow(this);
	if (win==null) return false;
	win.close();
	return false;
	}

function WGMinimizeThisWindow() {
	var win = WGGetThisWindow(this);
	if (win==null) return false;
	win.minimize();
	return false;
	}
	
function WGMaximizeThisWindow() {
	var win = WGGetThisWindow(this);
	if (win==null) return false;
	win.maximize();
	return false; 
	}
	
function WGChangeThisWindowSize() {
	var win = WGGetThisWindow(this);
	if (win==null) return false;
	win.changeMode();
	return false; 
	}

var WGAddMemberRecursivelyCounter=1;

function WGAddMemberRecursively(arr,add,subName) {
	WGAddMemberRecursivelyCounter++;
	WGAddMemberRecursivelyEx(arr,add,subName);
	}
	
function WGAddMemberRecursivelyEx(arr,add,subName) {
	var j = arr.length;
	for (var i = 0 ; i < j ; i++) {
		if (!def(arr[i].WGNoAddMember)) arr[i].WGNoAddMember=0;
		if (arr[i].WGNoAddMember==WGAddMemberRecursivelyCounter) continue;
		arr[i].WGNoAddMember=WGAddMemberRecursivelyCounter;
		for (var item in add) arr[i][item]=add[item];
		if (def(arr[i][subName]) && isArr(arr[i][subName])) WGAddMemberRecursivelyEx(arr[i][subName],add,subName);
		}
	}

function WGWinIcoMenuOpen(evt) {
	WGStopEvent(evt);
	WGoOver();

	var x0 = WGProcSet.win.borderSize+1; 
	var y0 = WGProcSet.win.borderSize+WGProcSet.win.barHeight+1;
	
	WGlob.currentWinIco=this.WGWin;	
	var v = WGBuildMenu(this.WGWin,this.menu,x0,y0);
	WGToTop(v);
	}

function WGDestroyWindowDiv(div) {
    WGlob.activeWindow=null;
    WGlob.closed.push(div.WGhdc); 
    
	if (div.WGModalLock) try { WGlob.desktop.removeChild(div.WGModalLock); } catch(DevNull) {}
	try { WGlob.desktop.removeChild(div); } catch(DevNull) {}
	div.WGVisible=false;
	delete(WGlob.windows[div.id]);
	var j = WGlob.winOrder.length;
	
	for (var i=0;i<j;i++) {
	        if (WGlob.winOrder[i]==div) {
		        WGlob.winOrder.splice(i,1);
		        break;
			}
		}
	j = WGlob.winOrder.length-1;       	
    if (j>-1) WGSetActiveWindowDiv(WGlob.winOrder[j]);
    
    if (def(div.WGName)) {
		var myName = div.WGName
		WGlob.tasks[myName].c--;
		if (def(WGlob.tasks[myName].w[div.id])) delete(WGlob.tasks[myName].w[div.id]);
		if (WGlob.tasks[myName].c<1) delete(WGlob.tasks[myName]);
		}
    
    WGUpdateTaskBar();
	WGoOver();
	WGDoGuiEvent('closewin',div.WGhdc);
	}

function WGGetWinByApp(nam) {
	if (!def(WGlob.tasks[nam])) return null;
	for(var k in WGlob.tasks[nam]) {
		return WGlob.tasks[nam][k];
		}
	}

function WGToTop(div) {
	WGlob.maxZIndex++;
	div.style.zIndex=WGlob.maxZIndex;
	if (WGlob.topTo!=null) {
		if (WGlob.topWin==null || div==WGlob.topWin) {
			WGlob.maxZIndex++;
			WGlob.topTo.style.zIndex=WGlob.maxZIndex;
			WGlob.topTo=null;
			WGlob.topWin=null;
			}
		}
		
	var h = WGlob.maxZIndex+1;
	for (var w in WGlob.widgets) {
		WGlob[w].style.zIndex=h++;
		}
	}

function WGAddWidget(obj) {
	var v = null;
	v = document.getElementById('WGWig'+obj.name);
	if (v==null) {
		v= document.createElement('div');
		v.setAttribute('id','WGWig'+obj.name);
		}
	
	v.classList.remove('WGWidget');
	v.innerHTML = obj.html;
	WGAddWidgetDiv(v,obj.name);
	}

function WGAddWidgetDiv(div,name) {
	WGToTop(div);
	div.classList.add("WGWidget");
	WGlob.widgets[name]=div;
	WGblob.desktop.appendChild(div);
	}

function WGAddWinStatusBar(v,height,html) {
	if (!def(v.WGBottomContent)) {
		var b = document.createElement('div');
		b.setAttribute('id','win'+v.WGhdc+'BO');
		b.setAttribute('class','WGBottomBar');
		v.appendChild(b);
		v.WGBottomContent=b;
		}
	
	var e = v.WGBottomContent;
	e.style.overflow='hidden';
	e.style.height=height+'px';
	e.style.left=WGProcSet.win.borderSize+'px';
	e.style.bottom=WGProcSet.win.borderSize+'px';
	var ow = v.WGWidth;
	var x = (ow-(WGProcSet.win.borderSize*2)-WGProcSet.win.bottomIco);
	e.style.width=x+'px';
	v.WGBottomHeight=height+(WGProcSet.win.borderSize*2);
	e.WGWin = v;
	v.WGBotIDS = {};
	v.WGBotSetObj=[];
	e.innerHTML=html;
	e.style.visibility='hidden';
	WGCrawleHTMLSet(v.WGBottomContent,v.WGBotIDS,v.WGBotSetObj,v.WGWin);
	e.style.visibility='visible';
	}

function WGRedraw() {
	for (var k in WGlob.windows) {
		try { WGWinResize(WGlob.windos[k]); } catch(DevNull) {}
		}
	}

function WGWinResize(v) {
	var ow = v.WGWidth;
	var oh = v.WGHeight;
	var ph = WGProcSet.win.barHeight;
	var myBottomHeight= def(v.WGBottomHeight) ? v.WGBottomHeight : WGProcSet.win.bottomHeight;
	v.WGTitleBar.style.height=ph+"px";
	ph++;
	var e = v.WGMain;
	e.style.left=WGProcSet.win.borderSize+"px";
	e.style.top=WGProcSet.win.borderSize+"px";
	var x=ow-(WGProcSet.win.borderSize*2);
	var inw = x;
	e.style.width=x+"px";
	x=oh-(WGProcSet.win.borderSize*2)-myBottomHeight;
	e.style.height=x+"px";
	
	e=v.WGTitleBar;
	var addB = WGProcSet.win.titleBorder;
	e.style.left=(addB+v.WGicoSxWidth)+"px";
	x=(ow-(WGProcSet.win.borderSize*2)-v.WGicoSxWidth-v.WGicoDxWidth)-(addB*2);
	e.style.width=x+"px";
	
	if (v.WGMenuBar) {
		ph+=WGProcSet.win.menuHeight+1;
		e=v.WGMenuBar;
		e.style.top=(1+WGProcSet.win.barHeight)+"px";
		}
	
	e=v.WGContent;
	e.style.top = ph+"px";
	x=oh-(WGProcSet.win.borderSize*2)-ph;
	x-=myBottomHeight;
	e.style.height=x+"px";
	var inh = x;
	
	e=v.WGWinBottom;
	e.style.bottom='0px';
	e.style.left='0px';
	e.style.width='100%';
	e.style.height=myBottomHeight+"px";
	
	if (def(v.WGBottomContent)) {
		e = v.WGBottomContent;
		x = myBottomHeight-(WGProcSet.win.borderSize*2);
		e.style.height=x+'px';
		x = (ow-(WGProcSet.win.borderSize*2)-WGProcSet.win.bottomIco);
		e.style.width=x+'px';
		}
	
	v.style.width=ow+"px";
	v.style.height=oh+"px";
	v.WGInnerWidth=inw;
	v.WGInnerHeight=inh;
	v.WGExtraWidth=ow-inw;
	v.WGExtraHeight=oh-inh;
	}
	
function WGSetDragableSurface(div) {
        if (WGlob.dragSurface[div.id]) return;
        div.onmousemove=WGDragSurfaceMove;
        WGlob.dragSurface[div.id]=true;
        }	
	
function WGSetDragableObject(barDiv,surDiv,startX,startY,moveAnother) {
	WGSetDragableSurface(surDiv);
	barDiv.WGDragSurface = surDiv; 
	barDiv.onmousedown=WGDragSurfaceStart;
        barDiv.onmouseup=WGDragSurfaceEnd;
        WGlob.drag=null;
        barDiv.WGPox={"x":startX,"y":startY};
        if (moveAnother) barDiv.WGMove=moveAnother;
	}
	
function WGDragSurfaceStart() {
	var O=this;
	
	if (def(O.WGWin) && O.WGWin.WGMaximized) {
		WGoOver(); 
		return false;
	}
	
	WGlob.drag = {
	        "div"	:	O	,
	        "x"	:	0       ,
	        "y"	:	0	,
	        "r"	:	false	}
	        ;

	WGoOver();        
	return false;
	}	

function WGDragSurfaceEnd() {
    WGlob.drag = null;
    WGDoEvent(this,'endMove',null);
	return false;
    }	
	      
function WGGetMouseButton(e) { return e.button; }
	                                    
function WGDragSurfaceMove(e) {           
	if (WGlob.drag==null) return;
	var mx=parseInt(e.clientX);
	var my=parseInt(e.clientY);             
	
	if (WGGetMouseButton(e)!=0) return WGDragSurfaceEnd();
	
	if (mx<0) mx=0;
	if (my<0) my=0;
	
	if (WGlob.drag.r) {
	        
		var div = WGlob.drag.div;
		var cx = div.WGPox.x;
		var cy = div.WGPox.y;

		cx+= (mx-WGlob.drag.x);
		cy+= (my-WGlob.drag.y);
		
		div.WGPox.x=cx;
		div.WGPox.y=cy;
				
		if (div.WGMove) div=div.WGMove;	
		div.style.left=cx+"px";
		div.style.top=cy+"px";
		e.WGLeft=cx;
		e.WGTop=cy;
		WGDoEvent(div,'move',e);
				
		} else {
			WGlob.drag.r=true;
			var div = WGlob.drag.div;
			if (div.WGMove) div=div.WGMove;
			WGToTop(div);
			WGDoEvent(div,'clickBar',null);
			}

	WGlob.drag.x=mx;
	WGlob.drag.y=my;
	
	return false;
	}	

function WGDoEvent(div,name,data) {
	
	var exception="";
	if (!def(div.WGEvent) && def(div.WGWin)) div=div.WGWin;
		
	try {
		if (def(div.WGhdc) && def(WGEvent.win[name])) WGEvent.win[name](div,data);
		} catch(exception) {
		alert("Exception in WGEvent.event `"+name+"` on element `"+div.id+"` Error:\n"+exception); 
		}
	
	try {
		var x=null;
		
		if (def(div.WGEvent) && def(div.WGEvent[name])) {
			x=div.WGEvent[name](div,data);
			} else {
			if (def(WGDefEvent[name])) WGDefEvent[name](div,data);	
			}
			
		if (!def(x)) return null; else return x;
		
		} catch(exception) {
		alert("Exception in event `"+name+"` on element `"+div.id+"` Error:\n"+exception); 
		}
	return null;
	}
   
function toHTML(s,nl2br) {
	s=String(s);
	s=s.replace(/\&/g,'&amp;');
	s=s.replace(/\</g,'&lt;');
	s=s.replace(/\>/g,'&gt;');
	s=s.replace(/\'/g,'&#39;');
	s=s.replace(/\"/g,'&quot;');
	if (nl2br) s=s.replace(/\n/g,"<br>");
	return s;
	}   
   
function WGWinMenuClick() {
	if (!def(this.WGWin)) return;
	WGDoEvent(this.WGWin,'icoMenuClick',this);
	return false;
	}
   
function WGPerc(v,mx) {
	if (!v.replace) v=String(v);
	v=v.replace('%','');
	v=parseFloat(v);
	return Math.ceil( mx * (v/100.0));
	}
      
function WGPoxObj(o) {
	var m = def(o.pox) ? o.pox : 0;
	var sw = WGScrWidth();
	var sh = WGScrHeight();
	if (!def(o.x)) o.x=0;
	if (!def(o.y)) o.y=0;

	if (!def(o.w) || !o.w) o.w=320;
	if (!def(o.h) || !o.h) o.h=240;
	
	var t = String(o.w);
	if (t.indexOf('%')!=-1) o.w=WGPerc(t,sw);
	t = String(o.w);
	if (t.indexOf('%')!=-1) o.h=WGPerc(t,sh);
	
	if ((m&1)!=0) o.x = Math.ceil((sw/2)-(o.w/2));
	if ((m&2)!=0) o.y = Math.ceil((sh/2)-(o.h/2));
	if ((m&8)!=0) o.x=sw-o.w-o.x;
	if ((m&16)!=0) o.y=sh-o.h-o.y;
	
	if (o.x<0) o.x=0;
	if (o.y<0) o.y=0;
	if (o.x>sw) o.x=sw;
	if (o.y>sh) o.y=sh;
	}   
   
function WGParseWinMenuF(arr,obj) {
	var j = arr.length;
	for (var i=0;i<j;i++) {
		if (arr[i]==obj.winType) return true;
		}
	return false;
	}   
   
function WGParseWinMenu(mnu,obj) {
	var rs=[];
	var j = mnu.length;
	for (var i = 0; i<j; i++) {
		if (def(mnu[i].noWT) && WGParseWinMenuF(mnu[i].noWT,obj)) continue;
		if (def(mnu[i].onWT) && WGParseWinMenuF(mnu[i].onWT,obj)) continue;
		rs.push(mnu[i]);
		}
	return rs;
	}   
   
function WGCreateWindowDiv(obj) {
	var myName=null;
	WGlob.maxWin++;
	var hdc = WGlob.maxWin;
	if (def(obj.hdc)) hdc=obj.hdc;
	if (def(obj.name)) myName=obj.name; else myName="_win"+hdc;

	if (def(WGlob.tasks[myName])) {
		if (WGlob.tasks[myName].n>=WGlob.tasks[myName].m) {
			alert("Too many instances for `"+myName+"`");
			return false;
			}
		} else {
		WGlob.tasks[myName] = {
			"n"	:	myName	,
			"w"	:	{}		,
			"c"	:	0		,
			"m"	:	obj.maxHinstances ? obj.maxHinstances : WGProcSet.maxHinsances}
			;	
		}

	if (!def(obj.pox)) {
		var jol=(Math.floor(WGlob.winCnt/8) % 8)*192;
		var kol=(WGlob.winCnt % 8)*32;
		if (!obj.x) obj.x = jol+kol+32;
		if (!obj.y) obj.y = kol+16;
		if (!obj.w) obj.w = 320;
		if (!obj.h) obj.h = 240;
	} else WGPoxObj(obj);
	
	WGlob.winCnt++;
	
	if (!obj.title) obj.title=' ';
	if (!def(obj.icon)) obj.icon=WGClone(WGProcSet.win.icon);
	if (!def(obj.icon.t)) obj.icon.t=obj.title;
		
	obj.title=toHTML(obj.title);
	var v = document.createElement('div');
	v.setAttribute('id','win'+ hdc+'W');
	v.setAttribute('class','WGWin');
		
	v.WGName=myName;
	obj.name=myName;
	
	WGlob.tasks[myName].c++;
	WGlob.tasks[myName].w[v.id]=v;
		
	if (def(obj.dialog)) {
		obj.modal=true;
		v.WGisDialog=true;
		v.WGDialogBy = obj.dialog.by;
		v.WGDialogFun = def(obj.dialog.fun) ? obj.dialog.fun : null;
		v.WGDialogDatum = def(obj.dialog.datum) ? obj.dialog.datum : null;
		obj.winType==EWG.WIN_DIALOG;
		} else v.WGisDialog=false;
	
	if (def(obj.class)) v.classList.add(obj.class);
	if (def(obj.winType)) {
		if (obj.winType==EWG.WIN_NORMAL) v.classList.add("EWGWinNormal");
		if (obj.winType==EWG.WIN_NOSIZE) v.classList.add("EWGWinNoSize");
		if (obj.winType==EWG.WIN_POPUP) v.classList.add("EWGWinPopup");
		if (obj.winType==EWG.WIN_DIALOG) v.classList.add("EWGWinDialog");
		}
		
	if (def(obj.coord)) {
		if (def(obj.coord.x)) v.WGCoordX=obj.coord.x;
		if (def(obj.coord.y)) v.WGCoordY=obj.coord.y;
		}
		
	v.WGVisible=true;
	v.WGWMaximized=false;	
	v.WGIcon=obj.icon;
	v.WGEvent={};
	v.WGData={};
	v.WGWinState=EWG.WS_NORMAL;
	v.WGMinWidth= obj.minWidth ? obj.minWidth : WGProcSet.win.minWidth;
	v.WGMinHeight= obj.minHeight ? obj.minHeight : WGProcSet.win.minHeight;
	if (!def(obj.modal)) obj.modal=false;
	v.WGIsModal=obj.modal;
	if (def(obj.winLang)) v.WGWinLang = obj.winLang; else v.WGWinLang={};
	
	for (var field2 in WGWindowsDefault) {
		if (def(obj[field2])) v[field2]=obj[field2]; else v[field2]=WGWindowsDefault[field2];
		}
		
	if (def(obj.event)) {
		for (var el in obj.event) v.WGEvent[el]=obj.event[el];
		}
		
	if (def(obj.data)) {
		for (var el in obj.data) v.WGData[el]=obj.data[el];
		}
	
	v.WGEvent.WGWin = v;
	v.WGEvent.WGData = v.WGData;
	if (def(obj.url)) v.WGURL=obj.url;
	var m = document.createElement('div');
	m.setAttribute('id','win'+ hdc+'M');
	m.setAttribute('class','WGWinInner');
	v.WGMain=m;
	
	var b = document.createElement('div');
	b.setAttribute('id','win'+ hdc+'B');
	b.setAttribute('class','WGWinTitleCont');
	v.WGTitleCont = b;
	m.appendChild(b);
	b = document.createElement('div');
	b.setAttribute('id','win'+ hdc+'B');
	b.setAttribute('class','WGWinTitleBar');
	b.innerHTML = obj.title;
	v.WGTitleBar = b;
	v.WGTitleCont.appendChild(b);
	
	if (def(obj.menu) && obj.menu.length>0) {	
		var e = document.createElement('div');
		e.setAttribute('id','win'+ hdc+'E');
		e.setAttribute('class','WGWinMenuBar');
		m.appendChild(e);
		v.WGMenuBar=e;
		WGBuildOMenu(e,obj.menu,null,v); //cls880
		} else v.WGMenuBar=null;
		
	var c = document.createElement('div');
	c.setAttribute('id','win'+ hdc+'C');
	c.setAttribute('class','WGWinContent');
	v.WGContent = c;
	m.appendChild(c);
	
	var o = document.createElement('div');
	o.setAttribute('id','win'+ hdc+'O');
	o.setAttribute('class','WGWinBottom');
	v.WGWinBottom=o;
	v.appendChild(o);
	
	v.WGhdc=hdc;
		
	WGSetDragableObject(b,WGlob.desktop,obj.x,obj.y,v);
	
	v.style.left=obj.x+"px";
	v.style.top=obj.y+"px";
	v.WGWidth=obj.w;
	v.WGHeight=obj.h;
	v.appendChild(m);
	var iCount=0;
	if (obj.icoSx) {
	        var j = obj.icoSx.length;
	        v.icoSx=[];
	        for (var i=0;i<j;i++) {
													
				if (def(obj.icoSx[i].req)) {
					if (!def(obj[obj.icoSx[i].req]) || obj[obj.icoSx[i].req] == false) continue;
					}
					
				var cls = false;
				if (def(obj.icoSx[i].cls)) cls=obj.icoSx[i].cls;
	        	var ie = document.createElement( cls ? 'div' : 'img');
	        	ie.setAttribute('id','win'+ hdc+'Is'+i);
	        	ie.setAttribute('class','WGWinIco');
	        	ie.style.left=(i*WGProcSet.win.icoSize)+"px";
	        	ie.style.top="0px";
	        	ie.WGWin = v;
	        	
	        	if (def(obj.icoSx[i].WGWinCallFun)) ie.WGWinCallFun=obj.icoSx[i].WGWinCallFun;
	        	if (def(obj.icoSx[i].WGWinCallPar)) ie.WGWinCallPar=obj.icoSx[i].WGWinCallPar;
	        	
	        	if (obj.icoSx[i].isIcon) obj.icoSx[i].i=v.WGIcon.i;
	        	if (cls) { 
					if (def(obj.icoSx[i].i)) ie.style.backgroundImage='url('+obj.icoSx[i].i+')'; 
					ie.classList.add(cls);
					} else ie.src=obj.icoSx[i].i;
					
	        	ie.WGcls=cls;
	        	if (def(obj.icoSx[i].menu)) {
					ie.menu=WGParseWinMenu(obj.icoSx[i].menu,obj);
					ie.onclick=WGWinMenuClick;
				}
	        	
	        	if (def(obj.icoSx[i].fun)) ie.onclick=obj.icoSx[i].fun;
	        	v.icoSx.push(ie);
	        	v.WGTitleCont.appendChild(ie);
	        	iCount++;
	        	}
		v.WGicoSxWidth=iCount*WGProcSet.win.icoSize;
		} else v.WGicoSxWidth=0;
		
	iCount=0;
	if (obj.icoDx) {
	        var j = obj.icoDx.length;
	        v.icoDx=[];
	        for (var i=0;i<j;i++) {
				if (def(obj.icoDx[i].req)) {
					if (!def(obj[obj.icoDx[i].req]) || obj[obj.icoDx[i].req] == false) continue;
					}
					
				var cls = false;
				if (def(obj.icoDx[i].cls)) cls=obj.icoDx[i].cls;
	        	var ie = document.createElement( cls ? 'div' : 'img');
	        	ie.setAttribute('id','win'+ hdc+'Id'+i);
	        	ie.setAttribute('class','WGWinIco');
	        	ie.style.right=(i*WGProcSet.win.icoSize)+"px";
	        	ie.style.top="0px";
	        	ie.WGWin = v;
	        	
	        	if (def(obj.icoDx[i].WGWinCallFun)) ie.WGWinCallFun=obj.icoDx[i].WGWinCallFun;
	        	if (def(obj.icoDx[i].WGWinCallPar)) ie.WGWinCallPar=obj.icoDx[i].WGWinCallPar;
	        	
	        	if (obj.icoDx[i].isIcon) obj.icoDx[i].i=v.WGIcon.i;
	        	if (cls) { 
					if (def(obj.icoDx[i].i)) ie.style.backgroundImage='url('+obj.icoDx[i].i+')'; 
					ie.classList.add(cls);
					} else ie.src=obj.icoDx[i].i;
					
	        	ie.WGcls=cls;
	        	if (def(obj.icoDx[i].menu)) {
					ie.menu=WGParseWinMenu(obj.icoDx[i].menu,obj);
					ie.onclick=WGWinMenuClick;
				}
	        	if (def(obj.icoDx[i].fun)) ie.onclick=obj.icoDx[i].fun;
	        	v.icoDx.push(ie);
	        	v.WGTitleCont.appendChild(ie);
	        	iCount++;
	        	}
		v.WGicoDxWidth=iCount*WGProcSet.win.icoSize;
		} else v.WGicoDxWidth=0;

	var t = document.createElement('div');
	t.setAttribute('id','win'+ hdc+'T');
	t.setAttribute('class','WGBottomIco');
	v.WGBottomIco=t;
	v.appendChild(t);
	v.WGTitle=obj.title;
	v.WGMenu=obj.menu;
	
	v.WGContent.WGhdc=hdc;
	v.WGMain.WGhdc=hdc;
	v.WGTitleBar.WGhdc=hdc;
	v.WGWinBottom.WGhdc=hdc;
	
	v.WGContent.WGWin=v;
	
	if (v.WGMenuBar!=null) {
		v.WGMenuBar.WGWin=v;
		v.WGMenuBar.WGhdc=hdc;
		}
		
	v.WGMain.WGWin=v;
	v.WGTitleBar.WGWin=v;
	v.WGWinBottom.WGWin=v;
	v.onclick=WGClickOnWindow;	
	v.WGTitleBar.onclick=WGClickTitleBar;	
	if (v.WGHistory==null) v.WGHistory=[];
	
	for (var member in WGWindowsProto) {
	        v[member]=WGWindowsProto[member];
		}	
	
	v.WGMaximized=false;
	v.WGMinimized=false;
	v.WGModalLock=null;
	v.WGParentWin=null;
	v.WGFormData=null;
	v.WGWin=v;
	
	if (def(obj.winCss)) {
		v.WGContent.classList.add(obj.winCss);
		v.WGWinCSS=obj.winCss;
		}
		
	if (def(obj.statusBar)) {
		WGAddWinStatusBar(v,def(obj.statusBar.h) ? obj.statusBar.h : WGProcSet.win.statusHeight,obj.statusBar.html);
		}
	
	if (obj.WGMaximized) v.maximize(); else WGWinResize(v);
	if (obj.WGMinimized) v.mimimize();
		
	if (WGlob.desktop) WGlob.desktop.appendChild(v);
	WGlob.windows[v.id]=v;
	WGlob.winOrder.push(v);
	if (obj.modal) WGCreateLock(v);
	WGToTop(v);
	if (v.WGEvent.onLoad) v.WGEvent.onLoad();
	if (!def(obj.noActivate)) WGSetActiveWindowDiv(v);
		
	if (obj.html) {
		if (obj.isStatic) c.innerHTML=obj.html; else WGInitGuiContent(v,obj.html);
		obj.html=null;
		}
	
	if (obj.autoResize) WGAutoResize(v);
		
	WGDoEvent(v,'load',v);
	
	return v;
	}
	
function WGClickTitleBar() {
	WGDoEvent(this,'clickBar',null);
	WGoOver();
	}	

function WGSetDesktopObject(div) {
	WGlob.desktop=div;
	WGSetDragableSurface(WGlob.desktop);
	//WGlob.desktop.onmousedown=WGDesktopClick;
	WGlob.desktop.onclick=WGDesktopClick;
	}

function WGGetPosition(element) {
    var xPosition = 0;
    var yPosition = 0;
  
    while(element) {
        xPosition += (element.offsetLeft - element.scrollLeft + element.clientLeft);
        yPosition += (element.offsetTop - element.scrollTop + element.clientTop);
        element = element.offsetParent;
    }
    return { "x": xPosition, "y": yPosition };
}

function WGCreateLock(win) {
	var v = document.createElement('div');
	v.setAttribute('id','WGLock'+win.WGhdc);
	v.setAttribute('class','WGLock');
	if (win.WGModalLock) try { WGlob.desktop.removeChild(win.WGModalLock); } catch(DevNull) {}
	win.WGModalLock=v;
	WGlob.desktop.appendChild(v);
	WGToTop(v);
	WGToTop(win);
	}

function WGBuildOMenu(div,menu,cls,win) {
	var m = document.createElement('div');
	div.classList.add('WGOMenu');
	if (div.id=='') {
		div.setAttribute('id',"wgomnu"+WGMenu.cnto);
		WGMenu.cnto++;
	}
	
	if (cls) div.classList.add(cls);
	m.setAttribute('class','WGOMenuMain');
	div.WGOMenu=menu;
	var j = menu.length;
	for (var i = 0; i < j; i++) {
		var s = document.createElement('div');
		s.setAttribute('class','WGOMenuItem');
		if (def(menu[i].i)) {
					s.innerHTML='<div style="background-image: url(' + toHTML(menu[i].i)+');" alt="" class="WGOMenuIco"></div>' + toHTML(menu[i].t);
				} else {
					s.innerHTML=toHTML(menu[i].t);
				}
		s.WGOMenuParent=div;
		s.WGOMenuID=i;
		if (win) s.WGWin=win;
		if (def(menu[i].cls)) s.classList.add(menu[i].cls);
		m.appendChild(s);
		menu[i].div = s;
		if (!def(menu[i].l)) s.classList.add('WGOMenuLock'); else s.onclick=WGOMenuItemClick;
		}
	div.WGOMenuMain = m;
	div.appendChild(m);
	}

function WGOmenuClearClass() {
	try {
		var arr = document.querySelectorAll(".WGOmenuSelected");
		var j=arr.length;
		for (var i = 0 ; i < j ; i++) {
			arr[i].classList.remove('WGOmenuSelected');
			}
		} catch(DevNull) {}
	}
	
function WGClearClass(cls) {
	try {
		var arr = document.querySelectorAll("."+cls);
		var j=arr.length;
		for (var i = 0 ; i < j ; i++) {
			arr[i].classList.remove(cls);
			}
		} catch(DevNull) {}
	}

function WGChangeClass(cls,ncls) {
	try {
		var arr = document.querySelectorAll("."+cls);
		var j=arr.length;
		for (var i = 0 ; i < j ; i++) {
			try {
				arr[i].classList.remove(cls);
				if (!arr[i].classList[i].contains(ncls)) arr[i].classList.add(ncls);
				} catch(DevNull2) {}
			}
		} catch(DevNull) {}
	}

function WGOMenuItemClick(evt) { 
	WGoOver();
	this.classList.add('WGOmenuSelected');
	
	var id = this.WGOMenuID;
	var div = this.WGOMenuParent;
	var mnu = div.WGOMenu[id];
	var pox = WGGetPosition(this);
	var cls=null;
	if (def(mnu.cls)) cls=mnu.cls;
	
	var e= WGBuildMenu(WGlob.desktop,mnu.l,pox.x,pox.y/*+WGProcSet.win.menuHeight*/,null,def(this.WGWin) ? this.WGWin : null); 
	WGRequestTop(null,e);
	return WGStopEvent(evt);
	}

function WGBuildMenu(div,menu,x0,y0,cls,win) {
	if (!x0) x0=0;
	if (!y0) y0=0;
	var menuClass=WGProcSet.menu;
	
	var v = document.createElement('div');
	v.setAttribute('id','WGMenuContainer'+WGMenu.cnt);
	WGMenu.cnt++;
	v.setAttribute('class','WGMenuContainer');
	v.onclick=WGStopEvent;
		
	if (def(cls) && cls!=null) {
			v.classList.add(cls);
			v.WGClass=cls;
			if (def(WGProcSet.menuClass[cls])) menuClass=WGProcSet.menuClass[cls];
		} else v.WGClass=null;
		
	var j = menu.length;
	v.WGItem=[];
	v.WGX0=x0;
	v.WGY0=y0;
	v.WGSubList=[];
	
	for (var ix = 0; ix < j; ix++) {
		var cur = menu[ix];
		
		if (def(cur.chk)) cur.i = menuClass.icoChk[cur.chk&3];
		
		var m = document.createElement('div');
		m.setAttribute('id','WGMenuItem'+WGMenu.cnt);
		m.setAttribute('class','WGMenuItem');
		m.WGMenu = cur;
		var i=null;
		
		if (def(cur.fun)) cur.l="";
		
		if (def(cur.i)) {
			i = document.createElement('img');
			i.src = toHTML(cur.i);
			i.setAttribute('id','WGMenuItemIco'+WGMenu.cnt);
			i.setAttribute('class','WGMenuItemIco');
			m.appendChild(i);
			m.WGIco=i;
			}
		
		if (def(cur.t)) {
			i = document.createElement('div');
			i.setAttribute('id','WGMenuItemText'+WGMenu.cnt);
			i.setAttribute('class','WGMenuItemText');
			if (!def(cur.l)) i.classList.add('WGMenuLock');
			i.innerHTML=toHTML(cur.t);
			i.style.left=menuClass.icoSize+"px";
			i.style.width=menuClass.itemWidth+"px";
			m.appendChild(i);
			m.WGText=i;
			} else {
			i = document.createElement('div');
			i.setAttribute('id','WGMenuItemText'+WGMenu.cnt);
			i.setAttribute('class','WGMenuItemNoText');
			i.style.left=menuClass.icoSize+"px";
			i.style.left="0px";
			i.style.width=(menuClass.icoSize+menuClass.itemWidth)+"px";
			m.appendChild(i);
			m.WGText=i;
			}		
		
		if (def(cur.d)) {
			i = document.createElement('div');
			i.setAttribute('id','WGMenuItemRight'+WGMenu.cnt);
			i.setAttribute('class','WGMenuItemRight');
			i.innerHTML=toHTML(cur.d);
			m.appendChild(i);
			m.WGRight=i;
			}
		
		if (isArr(cur.l)) { 
			i = document.createElement('img');
			i.setAttribute('id','WGMenuIcoDx'+WGMenu.cnt);
			i.setAttribute('class','WGMenuIcoDx');
			i.src=WGProcSet.menu.icoDx;
			i.style.width=WGProcSet.menu.icoSize+"px";
			i.style.height=WGProcSet.menu.icoSize+"px";
			m.appendChild(i);
			m.WGMenuIcoDx=i;
			} else m.WGMenuIcoDx=null;
		
		m.onclick=WGMenuItemClick;
		if (win) m.WGWin=win;
		
		m.style.width=(menuClass.icoSize+menuClass.itemWidth)+"px";
		m.style.height=menuClass.itemHeight+"px";
		
		if (def(m.WGIco)) {
			m.WGIco.style.width=menuClass.icoSize+"px";
			m.WGIco.height.width=menuClass.icoSize+"px";
			}
			
		m.WGAnchor = {
			"d":	div,
			"x":	x0+menuClass.itemWidth+menuClass.icoSize+1,
			"y":	y0+ix*menuClass.itemHeight
			} ;
		
		m.WGParent = v;	
		v.WGItem.push(m);
		v.appendChild(m);
		v.WGSubList.push(m);
		WGMenu.cnt++;
		}
	
	v.WGParent=div;	
	if (WGMenu.div==null) {
		WGMenu.div = v;
		WGMenu.rif=div;
		}
		
	WGMenu.menu.push(v);
	
	v.style.width=(menuClass.icoSize+menuClass.itemWidth)+"px";
	v.WGSubMenu=null;
	v.style.left=x0+"px";
	v.style.top=y0+"px";
	v.WGRifDiv=div;
	div.appendChild(v);
	WGToTop(v);
	return v;
	}

function WGChkEvent(e) {
	if (e.WGNone) return true;
	e.preventDefault();
	return false;
	}

function WGStopEvent(e) { 
	var evt = e ? e:window.event;
	if (evt.stopPropagation)    evt.stopPropagation();
	if (evt.cancelBubble!=null) evt.cancelBubble = true;
	e.WGNone=true;
	return false;
	}

function WGoOver() { 
	if (def(WGlob.toast)) try { WGRemoveToast(); } catch(DevNull) {}
	if (WGMenu.div!=null) try { WGMenuClose(); } catch(DevNull) {}
	if (WGlob.TrayDXElement) try { WGlob.desktop.removeChild(WGlob.TrayDXElement); } catch(DevNull) {}
	WGlob.currentWinIco=null;
	}

function WGMenuClose() {
	var j = WGMenu.menu.length;
	if (j==0 || WGMenu.div==null) return;
	for (var i = 0; i < j; i++) {
		var cur = WGMenu.menu[i];
		try { cur.WGParent.removeChild(cur); } catch(DevNull) {}
		}
	
	WGMenu.menu=[];
	WGMenu.div=null;
	WGMenu.rif=null;
	WGlob.currentWinIco=null;
	WGOmenuClearClass();
	}

function WGDesktopClick(e) {
	WGMenuClose();
	}

function WGCloseSubMenu(mn) {
	mn.WGRifDiv.removeChild(mn);
	}

function WGMenuItemClick(e) {
	WGStopEvent(e);
	if (!def(this.WGMenu.l)) return false;
	var menuClass=WGProcSet.menu;
	if (this.WGMenu.WGClass!=null && def(WGProcSet.menuClass[this.WGMenu.WGClass])) menuClass=WGProcSet.menuClass[this.WGMenu.WGClass]; 
	
	var url = this.WGMenu.l;
	if (isArr(url)) { 
		if (this.WGParent.WGSubMenu!=null) WGCloseSubMenu(this.WGParent.WGSubMenu);
		this.WGParent.WGSubMenu=null;
		this.WGParent.WGSubMenu = WGBuildMenu(this.WGAnchor.d,url,this.WGAnchor.x,this.WGAnchor.y,this.WGParent.WGClass);
		} else {
		
		if (def(this.WGMenu.chk)) {
			if ((this.WGMenu.chk & 2)==0) {
				this.WGMenu.chk^=1;
				this.WGIco.src=menuClass.icoChk[this.WGMenu.chk&3];
				}
			return false;
			}
		
		if (def(this.WGMenu.fun)) this.WGMenu.fun();
		
		if (url!="") {
			if (def(this.WGWin) && this.WGWin) this.WGWin.shell(url); else shellExecute(url);
			}		
		WGMenuClose();
		}
	return false;
	}
	
function WGUpdateTaskBar() {
	var j = WGlob.winOrder.length-1;
	for (var i = j; i>-1; i--) {
		if (!WGlob.winOrder[i].WGVisisble) continue;
		WGSetActiveWindowDiv(WGlob.winOrder[i]);
		break;
		}
	
	if (WGlob.iconManager==null) return;
	WGlob.maxZIndex++;
	WGlob.iconManager.style.zIndex=WGlob.maxZIndex;
	}	

function WGCreateIconManager(div) {
	if (WGlob.iconManager!=null) return;
	div.classList.add('WGIconManager');
	div.WGIcons={};
	WGlob.iconManager=div;
	WGToTop(div);
	div.onmouseover=WGIMAMouseOver;
	div.onmouseout=WGIMAMouseOut;
	WGIMAMouseOut();
	}
	
function WGIMAMouseOver() {
	WGlob.maxZIndex++;
	WGlob.iconManager.style.zIndex=WGlob.maxZIndex;
	WGlob.iconManager.classList.add('WGIconManagerUp');
	WGlob.iconManager.classList.remove('WGIconManagerDn');
	}

function WGIMAMouseOut() {
	WGlob.iconManager.classList.remove('WGIconManagerUp');
	WGlob.iconManager.classList.add('WGIconManagerDn');
	}	
	
function WGIMAddWin(winDiv) {
	var i = document.createElement('div');
	i.setAttribute('id','WGIcon'+winDiv.WGhdc);
	i.setAttribute('class','WGIconManangerIcon');
	i.WGHdc=winDiv.WGhdc;
	i.WGWin=winDiv;
	i.setAttribute('style','background-image: url('+toHTML(winDiv.WGIcon.i)+')');
	i.onclick=WGIMAClickIcon;
	WGlob.iconManager.WGIcons[winDiv.id]=i;
	WGlob.iconManager.appendChild(i);
	WGUpdateTaskBar();
	}

function WGIMARemoveWin(winDiv) {
	
	try { WGlob.iconManager.removeChild(WGlob.iconManager.WGIcons[winDiv.id]); } catch(DevNull) {}
	try { delete(WGlob.iconManager.WGIcons[winDiv.id]); } catch(DevNull) {}
	
	WGUpdateTaskBar();
	}
	
function WGIMAClickIcon() {
	this.WGWin.deiconize();
	}

function WGAppendMenu(ma,mb) {
	var o = [];
	var j = ma.length;
	for (var i = 0 ; i < j ; i++) o.push(ma[i]);
	j = mb.length;
	for (var i = 0 ; i < j ; i++) o.push(mb[i]);
	return O;
	}

function WGInitGuiContent(win,html) {
	var h = win.WGContent;
	
	win.WGIDS = {};
	win.WGSetObj=[];
		
	if (def(win.WGBottomContent)) {
		for(var ke in win.WGBotIDS) {
			win.WGIDS[ke]=win.WGBotIDS[ke];
			}
		var jk = win.WGBotSetObj.length; 
		for (var ji = 0; ji<jk ;ji++) {
			win.WGSetObj.push(win.WGBotSetObj[ji]);
			}
		}

	win.WGActivity={};
	h.style.display='none';
	h.innerHTML=html;
	var ar = h.getElementsByTagName("a");
	var j = ar.length;
	for (var i = 0 ; i < j ; i++ ) WGProcATag(win,ar[i]);
	ar = h.getElementsByClassName("WGWActivity");
	j = ar.length;
	for (var i = 0; i < j ; i++ ) {
		var ac = ar[i];
		ac.WGWin = win;
		var id = def(ac.dataset.wgid) ? ac.dataset.wgid : "activity"+(i+1);
		ac.WGActivityID=id;
		if (id!='main') {
			ac.style.display='none'; 
			ac.WGUsed=true;
			} else {
			ac.style.display='block';
			ac.WGUsed=false;
			}
		win.WGActivity[id]=ac;
		}
		
	ar = h.getElementsByTagName("form");
	j = ar.length;
	for (var i = 0; i < j; i++ ) {
		ar[i].onsubmit=WGOnThisFormSubmit;
		ar[i].WGWin=win;
		}
	
	WGCrawleHTMLSet(win.WGContent,win.WGIDS,win.WGSetObj,win);
	
	h.style.display='block';
	win.WGCurrentActivity=null;
	WGSetActivity(win,"main");
	}

function WGASetElementEvent(win,ele) {
	ele.WGWin=win;
	for (var k in ele.dataset) {
		var i = k.indexOf('wgon');
		var ok=k;
		if (i!=0) continue;
		k=k.substr(2);

		if (!def(EWG.elementEvent[k])) continue;
		var s = ele.dataset[ok];
		var eveK=k;		
		if (!def(ele.WGSetEvent)) ele.WGSetEvent={};
		
		if (s.indexOf('#')==0) {
			s=s.substr(1);
			ele.WGSetEvent[k] = s;
			ele[k]=function () { this.WGWin.setActivity(this.WGSetEvent[k]); } ;
			continue;
			}
		
		ele.WGSetEvent[k] = ele.dataset[ok];
		
		ele[k]=function () { WGDoEvent(this.WGWin,this.WGSetEvent[eveK],this); } ;
		}
		
	if (ele.dataset.wgupdate || ele.dataset.wgconnect) {
		ele.onblur=WGInputOnBlur;
		ele.onchange=WGInputOnBlur;
		
		if (ele.tagName=='SELECT') {
			ele.onselect=WGInputOnBlur;
			ele.onclick=WGInputOnBlur;
			}
		}
		
	}
	
function WGInputOnBlur() {
	var v = WGGetElementValue(this);	
	if (v==null) {
		WGDoEvent(this.WGWin,this.WGWin.WGCurrentActivity+'_'+this.name+'_error',this);
		} else {
		var flt = WGDoEvent(this.WGWin,this.WGWin.WGCurrentActivity+'_'+this.name+'_change',v);
		if (def(flt) && flt!=null) v=flt;
		var x = def(this.dataset.wgupdate) ? this.dataset.wgupdate : this.dataset.wgconnect;
		if (x=='this' || x=='.') {
			if (!def(this.WGWin.WGData[this.WGWin.WGCurrentActivity])) this.WGWin.WGData[this.WGWin.WGCurrentActivity]={};
			this.WGWin.WGData[this.WGWin.WGCurrentActivity][this.name]=v;		
			} else {
			this.WGWin.WGData=WGSetByDot(this.WGWin.WGData,x,v); 
			}
		}
	if(!def(this.dataset.wgnup)) this.WGWin.update();
	}
	
function WGOpenDialog(file,ownerHDC,ownerParanName,ownerEventName,dialogParam,onDialogSel,dialogValue,tagData) {
	var o = {
			"t"	:	EWG.AJ_DIALOG,
			"url":	file,
			"par":	dialogParam ? dialogParam : {} ,
			"by":	ownerHDC,
			"hdc":	ownerHDC,
			"datum":ownerParanName,
			"val":	dialogValue ? dialogValue : null,
			"tag":	tagData ? tagData : null,
			"fun":	ownerEventName}
			;
	var w = WGGetWindow(ownerHDC);
	if (w==null) return;
	w.WGCurrentDialog=o;
	w.WGOnDialogSel=onDialogSel ? onDialogSel : null;
	AJAXService("/",o,ServerData);
	}
	
function WGInputDialogFocus(evt) {
	evt.preventDefault();
	if (!def(this.WGDialogParam)) return false;
	var o = this.WGDialogParam;
	var v = null;
	if (def(this.WGGetValue)) {
		v = this.WGGetValue();
		} else if (def(this.WGValue)) {
		v = this.WGValue;	
		}
		
	var tda={};
	
	for(var kd in this.dataset) {
		tda[kd]=this.dataset[kd];
		}
	
	this.WGDialogVal = v;
	this.WGDialogTag = tda;		
	
	WGOpenDialog(o.f,o.h,o.d,o.e,o.p,def(o.onSet) ? o.onSet : null,v,tda); 
	this.WGWin.WGCurrentDialogElement=this;
	return false;
	}	
	
function WGSetInputDialog(win,e) {
	
	e.WGWin = win;
	e.onclick=WGInputDialogFocus;
	var o = {
		"h"	:	win.WGhdc,
		"f"	:	e.dataset.wgdialog,
		"d"	:	e.dataset.wgdata ? e.dataset.wgdata : false,
		"e"	:	e.dataset.wgid ? (e.dataset.wgid+"_change") : "dialog_return",
		"p"	:	e.dataset.wgdialogparam ? e.dataset.wgdialogparam : false}
		;
		
	if (def(WGProcSet.inputDialog[e.dataset.wgdialog])) {
		var x = WGProcSet.inputDialog[e.dataset.wgdialog];
		for(var k in x) {
			o[k]=x[k];
			}
		}
	e.classList.add("WGDialogInput");
	if (def(o.cls)) e.classList.add(o.cls);
	
	if (e.dataset.wgvalue) o.WGValue=e.dataset.wgvalue;
	if (e.dataset.wgvaluej) o.WGValue=JSON.parse(e.dataset.wgvaluej);
	
	e.WGDialogParam=o;
	if (def(o.onCreate)) o.onCreate(e);
	
	}
	
function WGParseCommentNode(win,el) {
	var otxt=el.textContent;
	otxt=String(otxt+"");
	try { el.textContent=' '; } catch(DevNull) {}
	otxt=otxt.trim();
	var txt=otxt.split(/\n+/);
	
	var type = txt[0].trim();
			
	if (type=='@COORD') {
		WGParseCoordNode(win,txt);
		return;
		}
	}
	
function WGParseCoordNode(win,arr) {
	var j = arr.length;
	if (!def(win.WGCoordX)) win.WGCoordX={};
	if (!def(win.WGCoordY)) win.WGCoordY={};
	
	for (var i=1; i<j; i++) {
		var li = arr[i].trim();
		if (li=='') continue;
		var tok = li.split(/\s+/);
		if (tok.length<3) continue;
		win.WGCoordX[tok[0]]=parseInt(tok[1]);
		win.WGCoordY[tok[0]]=parseInt(tok[2]);
		}
	}
	
function WGCrawleHTMLSet(el,bas,setObj,win) {
	var ar = el.childNodes;
	var j = ar.length;
		
	for (var i = 0; i < j; i++) {
			var e=ar[i];
			if (e.nodeName=='#comment') WGParseCommentNode(win,e);
			}
	
	ar = el.children;
	j = ar.length;
	for (var i = 0; i<j ; i++) {
		try {
			var e = ar[i];
			if (!def(e.tagName)) continue;
			if (e.dataset) {
							
				if (e.dataset.wgid) {
					var id = e.dataset.wgid;
					bas[id]=e;
					}
				
				if (e.dataset.wgspec && e.dataset.wgspec=='imgclr') e.onclick=WGClickReload;
				
				if (e.dataset.wgstl) {
					var rsc = WGStl(e.dataset.wgstl,"");
					if (rsc.length>0) {
						rsc=toHTML(rsc);
						e.innerHTML=rsc;
						}
					rsc=null;
					}
												
				if (e.dataset.wgpox) {
					var xywh = String(e.dataset.wgpox);
					xywh=xywh.split(/\,/);
					e.style.position='absolute';
					e.style.display='block';
					e.style.margin='0px';
					var xyj=xywh.length;
					for (var xyi=0;xyi<xyj;xyi++) {
						if (!WGIsNumeric(xywh[xyi])) {
							if ((xyi&1)==0) xywh[xyi]=win.getXCoord(xywh[xyi]); else xywh[xyi]=win.getYCoord(xywh[xyi]);
							}
						}
					if (xyj>0) e.style[ (xywh[0]>0 ? 'left' : 'right') ]=Math.abs(xywh[0])+'px';
					if (xyj>1) e.style[ (xywh[1]>0 ? 'top' : 'bottom') ]=Math.abs(xywh[1])+'px';
					if (xyj>2) e.style.width=Math.abs(xywh[2])+'px';
					if (xyj>3) e.style.height=Math.abs(xywh[3])+'px';
					}
				
				if (e.dataset.wglay) {
					var xywh = String(e.dataset.wglay);
					if (xywh.indexOf('R')>-1) e.style.textAlign='right';
					if (xywh.indexOf('L')>-1) e.style.textAlign='left';
					if (xywh.indexOf('C')>-1) e.style.textAlign='center';
					if (xywh.indexOf('V')>-1) e.style.lineHeight=e.clientHeight+'px';
					if (xywh.indexOf('H')>-1) e.style.overflow='hidden';
					}
				
				if (e.dataset.wgcoord) {
					if (!def(win.WGCoordX)) win.WGCoordX={};
					if (!def(win.WGCoordY)) win.WGCoordY={};
					var rect = e.getBoundingClientRect();
					win.WGCoordX[e.dataset.wgcoord]=rect.left;
					win.WGCoordY[e.dataset.wgcoord]=rect.top;
					}
				
				if (e.dataset.wgaddcoord) {
					if (!def(win.WGCoordX)) win.WGCoordX={};
					if (!def(win.WGCoordY)) win.WGCoordY={};
					var rect = e.getBoundingClientRect();
					if (!def(win.WGCoordX[e.dataset.wgaddcoord])) win.WGCoordX[e.dataset.wgaddcoord]=rect.left;
					if (!def(win.WGCoordY[e.dataset.wgaddcoord])) win.WGCoordY[e.dataset.wgaddcoord]=rect.top;
					win.WGCoordX[e.dataset.wgaddcoord]=win.WGCoordX[e.dataset.wgaddcoord]+e.offsetWidth+1;
					win.WGCoordY[e.dataset.wgaddcoord]=win.WGCoordY[e.dataset.wgaddcoord]+e.offsetHeight+1;
					}
				
				if (e.dataset.wgdialog) WGSetInputDialog(win,e);
								
				if (e.dataset.wgdata && !def(e.dataset.wgdialog)) {
						var dt = e.dataset.wgdata;
						setObj.push({
							"p":dt,
							"e":e})
							;
						}
						
				if (e.dataset.wgconnect) {
						if (!e.dataset.wgdialog) {
							var dt = e.dataset.wgconnect;
							setObj.push({
								"p":dt,
								"e":e})
								;
							}
						}
							
				WGASetElementEvent(win,e);			
				}
			if (!e.children) continue;
			var ch = e.children;
			WGCrawleHTMLSet(e,bas,setObj,win);
			} catch(DevNull) {}
		}
		
	if (!def(el.isWGX) && el.tagName=='DIV') {
			try { 
				WGIfCreateWGWGX(win,el);
			} catch(DevNull) {
				alert("Can't create custom control WGX on "+el.getAttribute('class'));
				}
		}
	}
	
function WGSetElementsData(win) { 		
	if (!def(win.WGSetObj) || win.WGSetObj==null) return;
	var lst=win.WGSetObj;
	var j = lst.length;
	for (var i = 0; i<j; i++) {
		try {
			var p = lst[i].p;
			if (p=='this') p=win.WGCurrentActivity+'.'+lst[i].e.name;
			var v = WGGetByDot(win.WGData,lst[i].p);
			
			var x = WGDoEvent(win,win.WGCurrentActivity+'_'+lst[i].e.name+'_update',v);
			if (def(x) && x!=null) v=x;
					
			if (v!=null) WGInputSetData(lst[i].e,v);
			
			} catch(DevNull) {}
		} 
	}	
	
function WGClickReload() {
	if (!def(this.WGReloadCount)) {
		this.WGReloadCount=0;
		this.WGOrgSrc=this.src;
		}
	this.WGReloadCount++;
	this.src=this.WGOrgSrc+'&wgReloader='+this.WGReloadCount+'_'+Math.random();
	}	
	
function WGGetByDot(obj,path) {
	var o=obj;
	path=path.split(".");
	var j = path.length;
	for (var i = 0; i<j ; i++ ) {
		if (def(o[path[i]])) o=o[path[i]]; else return null;
		}
	return o;
	}
	
function WGSetByDot(obj,path,val) {
	var o=obj;
	path=path.split(".");
	var j = path.length;
	var js='o';
	
	for (var i = 0; i < j; i++) {
		var p = path[i];
		if (p=='') continue;
		p=p.replace(/\"/g,"\\\"");
		js+='["'+p+'"]';
		try { eval("if (!def("+js+")) "+js+"={};"); } catch(DevNull) {}
		}
		
	if (isObj(val)) {
		val = JSON.stringify(val);
		} else {
		val = String(val);
		val = val.replace(/\"/g,"\\\"");
		val = '"'+val+'"';
		}
		
	js+='='+val+';';
	try { eval(js) ; } catch(DevNull) { return obj; }
	return o;
	}

function WGSetActivity(win,aid) {
	
	if (!def(win.WGActivity[aid])) {
		if (aid!="main") alert("Undefined activity `"+aid+"` in window `"+win.id+"`");
		return false;
		}
	
	
	WGSetElementsData(win);
	
	var h = win.WGContent;
	var ar = h.getElementsByClassName("WGWActivity");
	var j = ar.length;
	for (var i = 0 ; i < j; i++ ) {
		var ca = ar[i];
		if (ca.WGActivityID != aid) ca.style.display='none';
		}
		
	win.WGActivity[aid].style.display='block';
	win.WGActivity[aid].WGUsed=true;
	win.WGCurrentActivity=aid;
	WGDoEvent(win,"activity_"+aid,win);
	
	}

function WGSetRetDialogTag(win,a) {
	a.WGWin=win;
	a.onclick=WGAHrefDialogReturn;
	}

function WGAHrefDialogReturn(evt) {
	evt.preventDefault();
	var v = null;
	if (this.dataset.wgvalue) v = this.dataset.wgvalue;
	if (this.dataset.wgvaluej) v = JSON.parse(this.dataset.wgvaluej);
	if (v==null) v=this.WGWin.WGData;
	this.WGWin.dialogReturn(v);
	return false;
	}

function WGProcATag(win,a) {
	if (def(a.WGProc)) return;
	var hr = a.href;
	a.WGProc=hr;
	var i = 0;
	a.WGWin=win;	
	if (hr=='#_dialogReturn()') {
		WGSetRetDialogTag(win,a,hr.substr(2));
		return;
		}
	
	i = hr.indexOf('#');
			
	if (i==-1) {
		a.setAttribute("target","_blank");
		a.WGLinkable=true;
		return;
		}
	
	var pz = hr.substr(i+1);
	var ph = hr.substr(0,i);
	var lhr = location.href;
	i = lhr.indexOf('#');
	if (i!=-1) lhr=lhr.substr(0,i);
	
	if (ph!=lhr) {
		a.setAttribute("target","_blank");
		a.WGLinkable=true;
		return;
		}
	
	hr=pz;
	a.WGLinkable=false;
	i = hr.indexOf('?');
	if (i==0) { 
		a.WGHref=hr.substr(1);
		a.WGType=4;
		a.onclick=WGTagActivity;
		a.WGLinkable=false;
		return;
		}
		
	i = hr.indexOf('()');
	if (i==-1) {
		a.WGHref = hr;
		a.WGType=1;
		a.WGWin=win;
		a.onclick=WGTagActivity;
		return;
		} else {
				
		var cmd = hr.substr(0,i);
		i = cmd.indexOf('_');
		if (i==0) {
			a.WGHref=cmd.substr(1);
			a.WGType=3;
			a.onclick=WGTagActivity;
			return;
			} else {
			a.WGHref=cmd; 
			a.WGType=2;
			a.onclick=WGTagActivity;
			return;
			}
		}
	}
	
function WGTagActivity(evt) {
	if (this.WGType == 1) WGSetActivity(this.WGWin,this.WGHref);
	if (this.WGType == 2 && def(this.WGWin.WGEvent[this.WGHref])) this.WGWin.WGEvent[this.WGHref](this.WGWin);
	if (this.WGType == 3 && def(WGlob.fun[this.WGHref])) WGlob.fun[this.WGHref](this.WGWin);
	if (this.WGType == 3 && def(this.WGWin[this.WGHref])) this.WGWin[this.WGHref]();
	if (this.WGType == 4) shellExecute(this.WGHref,this.WGWin.WHhdc);
	return false;
	}

function WGCreateWindow(obj,winType) {
	var typ=EWG.WIN_NORMAL;
	if (winType) typ=winType; else if (def(obj.winType)) typ=obj.winType;
	for (var k in WGProcSet.win.default) {
		if (!def(obj[k])) obj[k]=WGProcSet.win.default[k];
		}
	
	obj.winType=typ;
	obj.WGResizable=(typ==EWG.WIN_NORMAL) ? true : false;
	
	if (def(obj.iconImage)) {
			obj.icon= {
					"i"	:	obj.iconImage} 
					;
			}
			
	if (!def(obj.icon)) obj.icon=WGClone(WGProcSet.win.icon);
	
	var kName=["icoSx","icoDx"];
	var jk = kName.length;
	for (var ki = 0 ; ki < jk ; ki++ ) {
		var kn = kName[ki];
		var ti = [];
		var j = WGProcSet.winProc[kn].length;
		for (var i = 0; i < j ; i++ ) {
			if ( def(WGProcSet.winProc[kn][i].wty) && (typ&WGProcSet.winProc[kn][i].wty)==0) continue;
			ti.push(WGProcSet.winProc[kn][i]);			
			} 
	
		if (def(obj[kn])) {
			j = obj[kn].length;
			for (var i = 0; i < j ; i++ ) {
				ti.push(obj[kn][i]);
				}
			}
		obj[kn]=ti;
		}
		
	return WGCreateWindowDiv(obj);
	}

function WGInputError(el,str) {
	if (!el.WGError) el.classList.add("WGInputError");
	el.WGError=true;
	el.WGErr=str;
	}

function WGInputSetData(el,val) {

	if (def(el.WGSetValue)) {
		el.WGSetValue(val);
		return;
		}
	
	if ((
		el.tagName!='SELECT' &&
		el.tagName!='INPUT'	 &&
		el.tagName!='TEXTAREA')
		|| !def(el.type) || !def(el.value)) {
				el.innerHTML=toHTML(val);
				return;
		}
		
	if (el.tagName=='SELECT') {
		if (val==null) {
			el.selectedIndex=-1;
			return;
			}
		var j = el.options.length;
		for (var i = 0; i<j ; i++) {
			if (el.options[i].value==val) {
				el.selectedIndex=i;
				return;
				}
			}
		el.selectedIndex=-1;
		return;
		}
	
	if (el.tagName=='INPUT' && (el.type=='checkbox' || el.type=='radio')) {
		el.checked = val ? true:false;
		return;
		}
	el.value=val;
	}

function WGInputGetData(el) {
	
	if (def(el.WGGetValue)) return el.WGGetValue(); 
	
	var vt = el.dataset.wgtype ? el.dataset.wgtype : 'str';
	var vreg = el.dataset.wgreg ? el.dataset.wgreg : null;
	var vdef = el.dataset.wgdefault ? el.dataset.wgdefault : "";
	var verr = el.dataset.wgerr ? el.dataset.wgerr : null;
	var vmax = el.dataset.wglmax ? el.dataset.wglmax : 65535;
	var vmin = el.dataset.wglmin ? el.dataset.wglmin : 0;
	var vreq = el.dataset.wgreq ? true : false;
	var vnam = el.name;
	var vv = null;
	el.WGError=false;
	el.WGErr=null;
	el.classList.remove("WGInputError");
	
	if (verr==null) verr="Invalid value for `"+el.name+"`";
	
	if (el.tagName=='TEXTAREA') vv=el.value;
	if (el.tagName=='SELECT') {
		var t0 = el.selectedIndex;
		if (t0!=-1) vv= el.options[t0].value;
		}

	if (el.tagName=='INPUT') {
		if (el.type=='checkbox') {
			if (el.checked) vv = true; else vv=false;
		} else if (el.type=='radio') {
			if (el.checked) vv = el.value; else vv=null;
			} else {
				vv=el.value;
			}
		}	

	if (!vv && vdef) vv=vdef;
	if (!vv && vreq) WGInputError(el,verr);
	if (vv!=null) {
		var l = String(vv);
		l=l.length;
		if (l<vmin) WGInputError(el,verr);
		if (l>vmax) WGInputError(el,verr);
		if (vreg!=null) {
			try {
				var t0 = new RegExp(vreg);
				if (!t0.test(vv)) WGInputError(el,verr);
				} catch(Err) { alert("Regex Error: "+Err); }
			}
		
		if (el.dataset.wgsha1) {
			if (WGUtil.Sha1( el.dataset.wgsalt ? (vv+el.dataset.wgsalt) : vv)!=el.dataset.wgsha1) WGInputError(el,verr);
			}
			
		} else return null;
		
	return {
		"ok":	(el.WGError==false)	,
		"e"	:	el.WGErr			,
		"k"	:	vnam				,
		"v"	:	vv					}
		;
	}
	
function WGGetElementValue(el) {
	var x = WGInputGetData(el);
	if (x==null) return null;
	if (x.ok) return x.v;
	return null;
	}

function WGThisFormGetData(form) {
	var err = [];
	var j = form.elements.length;
	var obj = {};
	for (var i = 0 ; i < j ; i++) {
		var el = form.elements[i];
		if (el.type=='submit') continue;
		var rs = WGInputGetData(el);
		if (!rs.ok) err.push(rs.e);
		obj[rs.k]=rs.v;
		}
	
	var dr = form.querySelectorAll(".WGDialogInput");
	j = dr.length; 
	
	for(var i = 0; i<j;i++) {
		var dl = dr[i];
		if (!def(dl.dataset.wgname)) continue;
		var nm = dl.dataset.wgname;
		if (def(dl.WGGetValue)) {
			obj[nm]=dl.WGGetValue();
			} else {
			if (def(dl.WGValue)) obj[nm]=dl.WGValue;	
			}
		}
		
	return {
		"ok"	:	(err.length==0)	,
		"err"	:	err				,
		"data"	:	obj				}
		;
	}
	
function WGOnThisFormSubmit(evt) {
	evt.preventDefault();

	var rs = WGThisFormGetData(this);
	var tar = null;
	if (def(this.dataset.wgtarget)) tar=String(this.dataset.wgtarget);
	if (def(this.dataset.wgname)) tar=String(this.dataset.wgname);
	if (tar==null && typeof this.name === 'string') tar=this.name;
	if (tar==null || !tar) tar = this.WGWin.getActivity();
	var ors=rs;
	rs.target=tar;
	
	if (rs.ok) {
		rs=rs.data;

		var x = WGDoEvent(this.WGWin,"onsubmit_"+tar,rs);
		if (def(x) && x!=null) rs=x;
		
		x = WGDoEvent(this.WGWin,"onsubmit",rs);
		if (def(x) && x!=null) rs=x;
		
		if (!def(this.WGWin.WGData[tar])) this.WGWin.WGData[tar]={};
		for (jk in rs) {
			this.WGWin.WGData[tar][jk]=rs[jk];
			}
				
		if (this.dataset.wgpostto) this.WGWin.setActivity(this.dataset.wgpostto);
		if (this.WGWin.WGURL && !def(this.dataset.wgnopost)) {
			this.WGWin.submit( 
				def(this.dataset.wgpostall),tar, 
				function (js) { WGDoEvent(this.WGWin,"onreply_"+this.name,js); } ,
				def(this.dataset.wgform) ? this.dataset.wgform : false
				) ;
			}
		} else {
		WGDoEvent(this.WGWin,"onformerror",rs);	
		if (this.dataset.wgerrorto) this.WGWin.setActivity(this.dataset.wgerrorto);
		}
	
	return false;
	}	

function WGError(text,title,button,classe) {
	if (!classe) classe='EWGMsgBoxError';
	WGMsgBox(null,text,title,[ button ],WGProcSet.boxIcon.warn,null,null,classe);
	}

function WGNewTag(win,tagName) {
	var x = document.createElement(tagName);
	x.WGWin=win;
	x.WGEvent=win.WGEvent;
	x.WGData=win.WGData;
	return x;
	}

function WGGetTextCharWidth(text,mw) {
	text=String(text);
	var tx=text.split(/\n|\<br\>/);
	if (!mw) mw=80;
	
	var j=tx.length;
	for (var i = 0; i < j ; i++) {
		tx[i]=tx[i].replace(/\s+/g, " ");
		var ji = tx[i].length;
		var ft=false;
		var lw=0;
		for (var ii = 0 ; ii < ji ; ii ++ ) {
			var ch = tx[i].charAt(ii);
			if (!ft) lw++;
			if (ch=='<') ft=true;
			if (ch=='>') ft=false;
			}
		if (lw>mw) mw=lw;
		}
		
	return mw;
	}

function WGMsgBox(owner,text,title,buttons,ico,onButton,onDirectButton,boxClass) {
		
	var twi = WGGetTextCharWidth(text,WGProcSet.msgBox.minCharWidth);
	if (!twi) twi=WGProcSet.msgBox.minCharWidth;
	twi = Math.ceil((twi*WGProcSet.msgBox.charWidth)+WGProcSet.msgBox.extraWidth);
	
	var msg = {
		"name"	:	"WEBGUIMsgBox",
		"w"		:	twi,
		"h"		:	150,
		"modal"	:	true,
		"x"		:	Math.ceil((WGlob.desktop.clientWidth/2)-(twi/2)),
		"y"		:	Math.ceil((WGlob.desktop.clientHeight/2)-150),
		"title"	:	title,
		"winType":	EWG.WIN_DIALOG,
		"event":	{},
		"data"	:	{
			"onButton" : onButton ? onButton : null
			}
		} ;
	
	if (boxClass) msg.class=boxClass;
	if (onDirectButton) msg.event.onDirectButton=onDirectButton;

	var ht = '<div class="WGMsgBoxContent" class="WGWActivity" data-wgid="main"><div class="WGMsgBoxIcon"';
	if (ico) ht+=' style="background-image: URL('+toHTML(ico)+');"';
	ht+='></div><div class="WGMsgBoxText" data-wgid="MsgBoxText">'+toHTML(text,true)+'</div><div class="WGMSGBoxButtons">';
		
	var j = buttons.length;
	for (var i = 0 ; i < j ; i++) {
		ht+='<a class="WGMsgBoxButton" href="#button'+i+'()">'+toHTML(buttons[i])+'</a> ';
		msg.event["button"+i]=function (win) {
			if (win.WGEvent.onDirectButton) win.WGEvent.onDirectButton(win,i);
			if (win.WGData.onButton!=null && owner!=null) WGDoEvent(owner,win.WGData.onButton,i);
			win.close();
			} ;
		}
		
	ht+='</div></div>';
	msg.html=ht;
	ht=null;
	
	msg.event["load"] = function() { 
				var e = this.WGWin.getByID('MsgBoxText');
				if (!e) return;
				var h = e.clientHeight;
				var w = e.clientWidth;
				
				if (w<WGProcSet.msgBox.minWidth) w=WGProcSet.msgBox.minWidth;
				if (h<WGProcSet.msgBox.minHeight) h=WGProcSet.msgBox.minHeight;
				
				var mw=Math.ceil(WGScrWidth()/2);
				var mh=Math.ceil(WGScrHeight()/2);
				if (h) {
					if (h>mh || w>mw) e.style.overflow='auto';
						
					if (h>mh) h=mh;
					if (w>mw) w=mw;
					
					h+=WGProcSet.msgBox.extraHeight;
					if (w) w+=WGProcSet.msgBox.extraWidth; else w=this.WGWin.getWidth();
					this.WGWin.setSize(w,h);
					this.WGWin.move('c','c');
					}
				}
			;
			
	return WGCreateWindow(msg);
	}

function WGScreenRect() {
	var w = window,
    d = document,
    e = d.documentElement,
    g = d.getElementsByTagName('body')[0],
    x = w.innerWidth || e.clientWidth || g.clientWidth,
    y = w.innerHeight|| e.clientHeight|| g.clientHeight;
	return { "w" : x, "h" : y };
	}

function WGHTTPAuthLogout(msg,tit) {
	WGKillAll(false);
	if (msg && !tit) tit='Close';
	WGlob.desktop.innerHTML='';
	var scr= WGScreenRect();
	var v = document.createElement('div');
	v.setAttribute('class','EWGHTTPLogout');
	v.style.position='fixed';
	v.style.left='0px';
	v.style.top='0px';
	v.style.width=scr.w+'px';
	v.style.height=scr.h+'px';
	v.onmousemove=WGDragSurfaceEnd;
	
	document.body.appendChild(v);
	
	if (WGlob.logonByWeb) {
		location.reload();
		return;
		}
	
	if (msg) {
		var wi = WGMsgBox(null,msg,"Logout",[ tit ],WGProcSet.boxIcon.info,null,function (js) { 
			v.style.zIndex='99999'; 
			location.href='//'+location.hostname+'/logout.php'; 
			}) ;
			
		try { WGlob.desktop.removeChild(wi.WGModalLock); } catch(DevNull) {}
		}

	if (def(WGlob.startBar)) WGlob.startBar.style.display='none';
	
	try { document.execCommand("ClearAuthenticationCache"); } catch(DevNull) {}
	
	var xmlhttp = loadAJAX();
	try {
		xmlhttp.open("GET", '/logout.php', true, "__logout__", "__logout__");
		xmlhttp.send("");
		xmlhttp.abort();
		} catch(DevNull) {}
	try {	
		xmlhttp = loadAJAX();
		xmlhttp.open("GET", '//'+location.hostname+'/logout.php', true, "__logout__", "__logout__");
		xmlhttp.send("");
		xmlhttp.abort();
		} catch(DevNull) {}
	
	try { document.cookie=''; } catch(DevNull) {}	
	WGlob.desktop=null;
	WGlob.rax=null;
	WGlob.user=null;
	var ti = document.getElementById('EWGTrayDX');
	if (ti) ti.style.display='none';
	}

function WGKillAll(name) {
	
	if (!name) {
		var x=[];
		for (var k in WGlob.windows) {
			x.push(k);
			}
			
		var j = x.length;
		for (var i = 0; i<j; i++ ) WGDestroyWindowDiv(WGlob.windows[x[i]]);
		
		WGlob.tasks={};
		WGlob.winOrder=[];
		WGlob.windows={};
		return;
		}
	
	if (!def(WGlob.tasks[name])) return false;
	for (var k in WGlob.tasks[name]) {
		WGDestroyWindowDiv(WGlob.tasks[name].w[k]);
		}
		
	delete(WGlob.tasks[name]);
	}
	
function WGCreateStartBar(menu,inDiv) {
	var b=null;
	var r=null;
	var k=null;
	
	if (!inDiv && WGlob.startBarIn) inDiv=WGlob.startBarIn.id;
	
	if (def(WGlob.startBarElements) && def(WGlob.startBar) && WGlob.startBar) {
		var jl = WGlob.startBarElements.length;
		for (il=0;il<jl;il++) {
			try {
				WGlob.startBarElements[il].style.display='none';
				WGlob.startBar.removeChild(WGlob.startBarElements[il]);
				} catch(DevNull) {}
			try {	
				WGlob.startBar.WGRight.removeChild(WGlob.startBarElements[il]);
				} catch(DevNull) {}
			}
		}
	
	if (def(WGlob.startBar)) {
		b = WGlob.startBar;
		r = b.WGRight;
		} else {
		b = document.createElement('div');
		b.setAttribute('id','WGStartBar');
		b.setAttribute('class','WGStartBar');
		r = document.createElement('div');
		r.setAttribute('class','WGStartRight');
		b.appendChild(r);
		b.WGRight=r;
		if (inDiv) inDiv=document.getElementById(inDiv);
		if (!inDiv) inDiv=document.body;
		inDiv.appendChild(b);
		WGToTop(inDiv);
		}
	
	WGlob.startBarElements=[];
	b.WGItem=[];
		
	var j = menu.length;
	for (var i = 0 ; i < j ; i++ ) {
		var m = menu[i];
		var tc = document.createElement('div');
		tc.setAttribute('class','WGStartLeftCont');
		WGlob.startBarElements.push(tc);
		var t = document.createElement('div');
		WGlob.startBarElements.push(t);
		t.WGMenu=m;
		t.onclick=WGStartBarClick;
		if (m.r) {
			r.appendChild(t);
			t.WGParent=r;
			} else {
			t.setAttribute('class','WGStartLeft');
			t.innerHTML=toHTML(m.t);
			tc.appendChild(t);
			b.appendChild(tc);
			t.WGParent=b;
			t.WGCont=tc;
			}	
		if (m.i) t.style.backgroundImage='URL('+toHTML(m.i)+')';
		b.WGItem.push(t);
		}
	
	WGlob.startBar=b;	
	WGlob.startBarIn= inDiv!=document.body ? inDiv.id : null;
	}
	
function WGStartBarClick(evt) {
	evt.preventDefault();
	WGoOver();
	var cur = this.WGMenu.l;
	
	var pox = WGGetPosition(this);
	if (isArr(cur)) {
		var e= WGBuildMenu(WGlob.desktop,cur,pox.x,0,'start'); 
		WGRequestTop(null,e);
		} else shellExecute(cur);
	
	return false;
	}
	
function WGCursorLoading(m)	{
	if (!WGlob.desktop) return;
	try {
		if (WGlob.cursorTimeout) try { clearTimeout(WGlob.cursorTimeout); } catch(DevNull) {}
		WGlob.cursorTimeout=null;
		if (WGlob.desktop.classList.contains("EWGDeskProgress")) WGlob.desktop.classList.remove("EWGDeskProgress");
		if (WGlob.desktop.classList.contains("EWGDeskWait")) WGlob.desktop.classList.remove("EWGDeskWait");
		
		if (m) {
			var st;
			if (m<2) st="EWGDeskProgress"; else st="EWGDeskWait";
			WGlob.desktop.classList.add(st);
			}
		} catch(DevNull2) {}
	}
	
function loadAJAX() {
	var ajax=null;
	if (window.XMLHttpRequest) {
		ajax=new XMLHttpRequest();
		} else {
		ajax=new ActiveXObject("Microsoft.XMLHTTP");
		}
		
	if (ajax==null || !def(ajax)) try { 
		ajax=new XMLHttpRequest(); 
		} catch(DevNull) { 
			alert("XMLHttpRequest not available!\n"+DevNull); 
			}
	return ajax;
	}	
	
function AJAXService(file,object,callback) {
	object.glob = WGlob.ajax;
	object.rax = WGlob.rax;
	object.Win = WGGetHdcList();
	object.Clo = WGlob.closed;
	object.css = [];
	
	for (var kc in WGlob.winCss) {
		object.css.push(kc);
		}
	
	var post = JSON.stringify(object);
	var HTTP1 = loadAJAX();
		
	if (file.charAt(0)!='/') file=WGlob.server+"/"+file;
	
	HTTP1.open('POST',file,true);
	HTTP1.setRequestHeader("Content-Type", "application/json");

	HTTP1.onreadystatechange= function () {
		if (HTTP1.readyState == 4) {
			WGCursorLoading(0);
			if (HTTP1.status!=200) {
				if (HTTP1.statusText) if (HTTP1.statusText.length>0) alert('Server status error:\n'+HTTP1.statusText);
				
				} else {
				try {
					eval("post="+HTTP1.responseText+";");
					} catch(err) { 
					try {
						alert("Data error in reply:\n"+err+"\n"+ (HTTP1.responseText.length <512 ? HTTP1.responseText : ''));
						} catch(e) {
						alert("Data error in reply");
						}
					return false;
					}
					
				try {
					post=WGObjectConvert(post);
					} catch(e) { alert("Post conversion error\n"+e); }
				return callback(post);		   
				} 
			}
		
		}
	WGCursorLoading(1);		
	HTTP1.send(post);
	WGlob.closed=[];
	}
	
function ServerData(js) {
	var ax,cmd,api,v;

	if (js.err) alert(js.err);
	if (js.info) alert(js.info);
	
	if (js.cmd) {
		for(ax in js.cmd) {
			api = js.cmd[ax];	
			cmd = api.api;
			if (WGAPI[cmd]) WGAPI[cmd](api.data); else alert("API `"+cmd+"` not defined!");
			}
		}
	}
	
function SysCall(api,par) {
	var o = {
		"t"		:	EWG.AJ_SYSCALL,
		"api"	:	api,
		"par"	:	par}
	AJAXService("/",o,ServerData);	
	}	

function WGObjectConv(j) {
	try {
		if (!def(j['__'])) return j;
		var js = eval('(function(){ return {'+j['__']+'}; })();');
		for (var k in j) {
			js[k]=j[k];
			}
		return js;
		} catch(e) {
			alert("JSON: JavaScript error\n"+e);
			}
	}
	
	
function WGObjectConvert(obj) {	
	if (isArr(obj)) return WGMemberConvertArr(obj);
	if (obj instanceof Object) return WGMemberConvertObj(obj);
	return obj;
	}
	
function WGMemberConvertArr(arr) {
	
	var q=[];
	var j = arr.length;
	for (var i = 0; i < j ; i++ ) {
		if (isArr(arr[i])) {
			q.push(WGMemberConvertArr(arr[i]));
			continue;
			}
		
		if (arr[i] instanceof Object) {
			q.push(WGMemberConvertObj(arr[i]));
			continue;
			}
		q.push(arr[i]);			
		}
		
	return q;
	}
	
function WGMemberConvertObj(obj) {
	var q={};
	obj=WGObjectConv(obj);
	
	for (var k in obj) {
		if (k=='__') continue;
		var i = k.indexOf('_');
		if (i!=0) {
			
			if (isArr(obj[k])) {
				q[k] = WGMemberConvertArr(obj[k]);
				continue;
				}
			
			if (obj[k] instanceof Object) {
				q[k] = WGMemberConvertObj(obj[k]);
				continue;
				}
			q[k] = obj[k];
			continue;
			}
		var vk = k.substr(1);
		var r = (vk.indexOf('_')==0);
		if (r) vk=vk.substr(1);
		if (r) {
				eval("q[vk]="+obj[k]+";");
			} else {
				eval("q[vk]=function("+obj[k]['p']+") {"+obj[k]['f']+"};");
				}		
		}
	return q;
	}
	
function WGStartWebGUI(desk,im) {
	if (WGlob.desktop) return;
	var con = document.getElementById(desk);
    
    if (!con) {
		con = document.createElement('div');
		document.body.appendChild(con);
		WGToTop(con);
		}
    
    if (im) im = document.getElementById(im);
	if (!im) {
		im = document.createElement('div');
		im.setAttribute('id','iconManager');
		con.appendChild(im);
		}
	
	con.classList.add("WGDesktop");
	WGSetDesktopObject(con);
	WGCreateIconManager(im);
	}	

function WGCan(perm) {
	if (!def(WGlob.user) || !WGlob.user || !WGlob.user.user) return false;
	if (!def(WGlob.user.can)) return false;
	if (WGlob.user.can.root) return true;
	if (!def(WGlob.user.can[perm])) return false;
	return WGlob.user.can[perm];
	}
	
function WGTrayDXClick() {
	WGoOver();
	
	if (!def(WGlob.user) || !WGlob.user || !WGlob.user.user) {
		shellExecute(".sys:logon.app");
		return;
		}
	
	var v = document.createElement('div');
	v.setAttribute('class','EWGUserPanel');
	var list = [
			{
			"t"	:	"Users",
			"can":	"root",
			"i"	:	"Manager",
			"f"	:	function () { WGoOver(); shellExecute(".sys:manager.app"); }
			},
			{
			"t"	:	"Passwd",
			"i"	:	"Passwd",
			"f"	:	function () { WGoOver(); shellExecute(".sys:passwd.app"); }
			},
			
			{
			"t"	:	"Logout",
			"i"	:	"Logout",
			"f"	:	function () { WGoOver(); shellExecute(".sys:logout.app"); }
			}]
			;
			
		
	var e = document.createElement('img');
	e.setAttribute('class','EWGUserPanelImg');
	e.src="/bin/php/avatar.php";
	v.appendChild(e);
	e = document.createElement('div');
	e.setAttribute('class','EWGUserPanelDiv');
	v.appendChild(e);
	var t = document.createElement('div');
	t.setAttribute('class','EWGUserPanelUsr');
	t.innerHTML=toHTML(WGlob.user.user);
	e.appendChild(t);
	var j = list.length;
	
	for (var i = 0; i<j ; i++) {
		if (def(list[i].can) && !WGCan(list[i].can)) continue;
		if (def(WGProcSet.userTray[list[i].i])) list[i].t=WGProcSet.userTray[list[i].i];
		list[i].t = WGStl('ewgtray'+list[i].i,list[i].t);
		t = document.createElement('div');
		t.setAttribute('class','EWGUserPanelEL EWGUserPanel'+list[i].i);
		t.innerHTML=toHTML(list[i].t);
		t.onclick=list[i].f;
		e.appendChild(t);
		}
	
	t = document.createElement('div');
	t.setAttribute('class','EWGUserPanelClo');
	t.innerHTML='X';
	t.onclick=WGoOver;
	e.appendChild(t);	
	WGlob.desktop.appendChild(v);
	WGlob.TrayDXElement = v;
	}

function WGSetGuiTheme(lpStr) {
	if (def(WGlob.curTheme)) WGChangeClass(WGlob.curTheme,lpStr);
	if (!WGlob.EWG.Screen.classList.contains(lpStr)) WGlob.EWG.Screen.classList.add(lpStr);
	}
	
function EPTOWebGuiLoad() {
	WGlob.rax='';
	var h=1;
	for (var i = 0 ; i < 16; i++ ) {
		var r = Math.ceil(Math.random()*65535);
		var d = new Date();
		var n = d.getTime(); 
		r^=n^h;
		h=r>>8;
		r=r&31;
		WGlob.rax+=String.fromCharCode(r+33);
		}
	
	if (top!=window) {
		alert('Frame/IFrame error. Please logout');
		top.location.href='/bin/app/webgui/logout.app';
		}
	
	if (WGlob.logonUI) {
		WGLogonUI();
		return;
		}
		
	WGSysMod('oninit');
	try {
		var o = {
			"t"	:	EWG.AJ_SYSTEM,
			"api":	"boot",
			"data"	: {
					"w"	:	document.body.clientWidth,
					"h"	:	document.body.clientHeight,
					"saw":	def(screen.availWidth) ? screen.availWidth : false,
					"sah":	def(screen.availHeight) ? screen.availHeight : false,
					"c"	:	navigator.cookieEnabled,
					"lang":	def(navigator.language) ? navigator.language : false,
					"start": def(WGlob.autoStart) ? WGlob.autoStart : false
					}
				} ;
				
		AJAXService("/",o,ServerData);	
		} catch(err) {
			alert("Incompatible browser.\nError: "+err);
			}
	}

function EPTOWebGuiBootProc(js) {
	if (def(WGlob.EWG)) return;
	WGlob.EWG={};
	var theme = def(js.theme) ? js.theme : false;
	if (!theme) theme='WGTemeDefault';
	var c = document.createElement('div');
	c.setAttribute('id','EPTOWebGui');
	c.setAttribute('class',theme);
	WGlob.curTheme = theme;
	WGlob.EWG.Screen = c;	
	document.body.appendChild(c);
	var b = document.createElement('div');
	b.setAttribute('id','EWGStartBar');
	c.appendChild(b);
	WGlob.EWG.Start = b;
	b = document.createElement('div');
	b.setAttribute('id','EWGTrayDX');
	c.appendChild(b);
	WGlob.EWG.TrayDX = b;
	b.onclick=WGTrayDXClick;
	b = document.createElement('div');
	b.setAttribute('id','EWGDesktop');
	c.appendChild(b);
	WGlob.EWG.Desktop = b;
	var d = document.createElement('div');
	d.setAttribute('id','iconManager');
	b.appendChild(d);
	WGlob.EWG.IconManager = d;
	WGScrenResize();
	WGStartWebGUI('EWGDesktop','iconManager');
	WGCreateStartBar(def(js.start) ? js.start : [] ,'EWGStartBar');
	document.body.onresize=WGScrenResize;
	WGSysMod('onstartgui');
	WGSysClear();
	}	

function WinOf(el) {
	if (!el) el=this;
	if (def(el.WGWin)) return el.WGWin;
	if (def(el.WGhdc)) return el;
	var x = WIN(el);
	if (!def(el.WGWin)) el.WGWin=x;
	return x;
	}
	
function WIN(el) {
	if (!el) el=this;
	if (def(el.WGWin)) return el.WGWin;
	if (def(el.WGhdc)) return el;
	
	for( var k in el) {
		try {
			if (el[k]==null) continue;
			if (el[k] instanceof Object || el[k] instanceof Node || el[k] instanceof HTMLElement) {
				if (def(el[k].WGWin)) return el[k].WGWin;
				if (def(el[k].WGhdc)) return el[k];
				}
			} catch(DevNull) {}
		}
		
	var q=el;
	for (var i = 0; i<255; i++) {
		try {
			if (def(q.parentNode)) {
				if (def(q.parentNode.WGWin)) return q.parentNode.WGWin;
				if (def(q.parentNode.WGhdc)) return q.parentNode;
				q=q.parentNode;
				} else break;
			} catch(DevNull) {}
		} 
	throw "Can't find current window";		
	}
	
function WGScrenResize() {
	var w = WGlob.EWG.Screen.clientWidth;
	var h = WGlob.EWG.Screen.clientHeight;
	var bh = WGlob.EWG.Start.clientHeight;
	WGlob.EWG.Desktop.style.height = (h-bh)+"px";
	if (WGlob.desktop==null) return;
	for (var w in WGlob.windows) {
		var fi = WGlob.windows[w];
		if (fi.getWinState() == EWG.WS_MAXIMIZED) fi.setWinState(EWG.WS_NORMAL);
		}
	}	
	
function WGObjMerge(oa,ob) {
	
	for(var k in ob) {
		if (k=='WGWin') continue;
		if (k=='WGData') continue;
		if (k=='WGMenu') continue;
		
		if (isArr(ob[k])) {
			oa[k]=ob[k];
			continue;
			}
		
		if (isObj(ob[k])) {
			WGObjMerge(oa[k],ob[k]);
			continue;
			}
		
		oa[k]=ob[k];
		}
	}
	
function WGIfCreateWGWGX(win,el) {
	if (el.isWGX) return;
	if (el.tagName!='DIV') return;	
	for(var cls in WGWGX) {
		if (el.classList.contains(cls)) {
			WGCreateWGWGX(win,cls,el);
			return;
			}
		}
	}
	
function WGCreateWGWGX(win,wgx,el) {
	if (def(WGWGX[wgx].proto)) {
		for(var k in WGWGX[wgx].proto) {
			el[k]=WGWGX[wgx].proto[k];
			}
		}
	el.WGWin=win;	
	if (def(WGWGX[wgx].create)) WGWGX[wgx].create(el);
	el.isWGX=true;
	}
	
function WGAddCustomControl(obj) {
	var nam = obj.name;
	if (def(obj.dll)) {
		var j = obj.dll.length;
		for(var i = 0; i < j; i++) {
			WGLoadDLL(obj.dll[i],'WGWGX'+name+i);
			}
		}
	nam='WGWGX'+nam;
	WGWGX[nam]=obj;
	if (def(WGWGX[nam].initControl)) WGWGX[nam].initControl();
	}

function WGDefaultDesktopPlacer() {
	var j = WGlob.icons.length;
	var sw=WGScrWidth();
	var sh=WGScrHeight();
	var iw=WGProcSet.icon.width+WGProcSet.icon.oMargin;
	var ih=WGProcSet.icon.height+WGProcSet.icon.textHeight+WGProcSet.icon.vMargin;
	var miw = Math.floor(sw/iw);
	var mih = Math.floor((sh-(ih/2))/ih);
	var cx=0;
	var cy=0;
	var x=0;
	for (var i = 0; i < j ; i++ ) {
		WGlob.icons[i].style.position='absolute';
		x = iw*cx+WGProcSet.icon.oMargin;
		WGlob.icons[i].style.left=x+'px';
		x = ih*cy+WGProcSet.icon.vMargin;
		WGlob.icons[i].style.top=x+'px';
		cy++;
		if (cy>=mih) {
			cy=0;
			cx++;
			}
		}
	}

function WGAddSysMod(nam,pri,obj) {
	WGlob.modCnt++;
	pri='p'+pri;
	if (!def(WGlob.mod[pri])) WGlob.mod[pri]={};
	if (!def(WGlob.mod[pri][nam])) {
			WGlob.mod[pri][nam]=obj;
			if (def(WGlob.mod[pri][nam].onload)) try { WGlob.mod[pri][nam].onload(); } catch(Err) { alert("WGAddSysMod: Error in mod `"+nam+"`:\n"+Err); }
			WGlob.modList[nam]=pri;
			}
	}
	
function WGSysMod(eve) {
	for (var pri in WGlob.mod) {
		for(var nam in WGlob.mod[pri]) {
			if (def(WGlob[pri][nam][eve])) try { WGlob[pri][nam][eve](); } catch(Err) { alert("WGSysMod: Error in mod `"+nam+'/'+pri+"`:\n"+Err); }
			}
		}
	}
	
function WGSysClear() { WGlob.mod={}; };

function WGSetStartFile(fi) {
	WGlob.autoStart=fi;
	}

function WGLogonUI() {
	var dk = document.createElement('div');
	dk.setAttribute('class','EWGLogonUI');
	dk.setAttribute('id','EWGLogonUI');
	
	dk.style.position='absolute';
	dk.style.left='0px';
	dk.style.top='0px';

	var w=parseInt(window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth);
	var h=parseInt(window.innerHeight|| document.documentElement.clientHeight|| document.body.clientHeight);
	
	dk.style.width='100%';
	dk.style.height='100%';
	dk.style.overflow='hidden';
	
	WGSetDesktopObject(dk);
	if (!WGProcSet.logonUI) WGProcSet.logonUI={};
	
 	var win = WGCreateWindow( {
		"title"	: (WGProcSet.logonUI.title ? WGProcSet.logonUI.title: "WebGui Login"),
		"winType"	:	EWG.WIN_DIALOG,
			"x"		:	Math.ceil((w/2)-160),
			"y"		:	Math.ceil((h/2)-65),
			"w"		:	320,
			"h"		:	130,
		"html"	:	
				'<div class="WGActivity" data-wgid="main"><form action="#" method="POST" data-wgid="logon" name="logon">'+
				'<input type="hidden" name="logonui" value="1">'+
				'<table class="EWGLogonForm">'+
				'<tr><td>'+toHTML(WGProcSet.logonUI.login ? WGProcSet.logonUI.login : 'Login')+
				':</td><td><input type="text" name="a" value="" class="EWGLogonInput" data-wgconnect="main.a"></td></tr>'+
				'<tr><td>'+toHTML(WGProcSet.logonUI.password ? WGProcSet.logonUI.password : 'Password')+
				':</td><td><input type="password" name="b" value="" class="EWGLogonInput" data-wgconnect="main.b"></td></tr>'+
				'<tr><td colspan="2"><input type="submit" class="EWGLogonSubmit" value="'+
				toHTML(WGProcSet.logonUI.submit ? WGProcSet.logonUI.submit : 'Login')+'"></td></tr>'+
				'</table></form></div>',
		"data" : {
			"main" : { "a" : "" , "b" : "" }
			},
		"event" : {
			"onsubmit_logon" : function(win,js) {
				
				if (WGlob.winOrder.length>1) {
					var j = WGlob.winOrder.length;
					for (var i = 0; i<j ; i++) {
						if (WGlob.winOrder[i]!=win && WGlob.winOrder[i].WGName=='WEBGUIMsgBox') {
							WGSetActiveWindowDiv(WGlob.winOrder[i]);
							break;
							}
						}					
					return false;
					}
								 
				var o = {
						"t"	:	EWG.AJ_SYSTEM,
						"api":	"logonui",
						"data"	: js }
							
				AJAXService("/",o,function (js) {
					if (js.err) {
							WGError(
								js.err,
								(WGProcSet.logonUI.error ? WGProcSet.logonUI.error : 'Error'),
								(WGProcSet.logonUI.cancel ? WGProcSet.logonUI.cancel : 'Cancel'),
								'EWGLogonUIWin');
							}
					if (js.ok) {
						WGlob.logonUI=false;
						WGlob.logonByWeb=true;
						WGDestroyWindowDiv(win);
						
						try { 
							document.body.removeChild(WGlob.desktop); 
							} catch(DevNull) {}
						
						WGlob.desktop=null;
						EPTOWebGuiLoad();
						}
					});	
				}
			}
		} ) ;
	
	win.classList.add("EWGLogonUIWin");
	dk.appendChild(win);
	document.body.appendChild(dk);
	}

function WGLockGui(flh,msg) {
	msg = WGStl('lockGui' + ( msg ? msg : '') ,"WebGui Locked");
	
	if (flh & 2) {
			WGKillAll(false);
			if (WGlob.desktop) WGlob.desktop.innerHTML='';
			}
			
	if (flh) {
		if (WGlob.lockGui) return;
		WGlob.lockGui = document.createElement('div');
		WGlob.lockGui.setAttribute('class','EWGLockGUI');
		WGlob.lockGui.style.position='fixed';
		WGlob.lockGui.style.left='0px';
		WGlob.lockGui.style.top='0px';
		WGlob.lockGui.style.overflow='hidden';
		var xy =WGScreenRect();
		
		WGlob.lockGui.style.width=xy.w+'px';
		WGlob.lockGui.style.height=xy.h+'px';
		WGlob.lockGui.style.lineHeight=xy.h+'px';
		WGlob.lockGui.style.zIndex='999999';
		WGlob.lockGui.innerHTML=toHTML(msg);
		document.body.appendChild(WGlob.lockGui);
		} else {
		if (!WGlob.lockGui) return;	
		WGlob.lockGui.style.width='0px';
		WGlob.lockGui.style.height='0px';
		WGlob.lockGui.style.lineHeight='0px';
		WGlob.lockGui.style.zIndex='0';
		WGlob.lockGui.style.display='none';
		WGlob.lockGui.innerHTML='';
		try { document.body.removeChild(WGlob.lockGui); } catch(DevNull) {}
		}
	}

function WGAutoResize(win) {
	var mw = Math.ceil(WGScrWidth() * 0.8);
	var mh = Math.ceil(WGScrHeight() * 0.8);
	var ca = win.getActivity();
	var dv = win.getByID(ca);
	if (!dv) {
		dv=win.WGContent;
		dv.style.overflow='auto';
		}
	var ew = win.getExtraWidth();
	var eh = win.getExtraHeight();
	dv.style.overflow='auto';
	var h = 0;
	h = dv.scrollHeight;
	if (!h) h= dv.offsetHeight;
	h+=eh;
	
	var w = 0;
	w = dv.scrollWidth;
	if (!w) w = dv.offsetWidth;
	
	w+=ew;
	if (h>mh) h=mh;
	if (w>mw) w=mw;
	win.setSize(w,h);
	w = Math.ceil((WGScrWidth()/2) - (w/2));
	h = Math.ceil((WGScrHeight()/2) - (h/2));
	win.move(w,h);
	}

function WGIsMobile() {
	if( 
		navigator.userAgent.match(/Android/i)
		|| navigator.userAgent.match(/webOS/i)
		|| navigator.userAgent.match(/iPhone/i)
		|| navigator.userAgent.match(/iPad/i)
		|| navigator.userAgent.match(/iPod/i)
		|| navigator.userAgent.match(/BlackBerry/i)
		|| navigator.userAgent.match(/Windows Phone/i)
		|| navigator.userAgent.match(/Mobile/i)
		|| def(window.orientation)
 ){
    return true;
  }
 else {
    return false;
  }
	
	}

function WGStl(id,defs) {
	return def(WGlob.langRes[id]) ? WGlob.langRes[id] : defs;
	}

var WGUtil = {
	
	"addcslashes" : function(str) {
		var esc="'\">`<&";
		str=str+'';
		var j = str.length;
		var rs='';
		for (var i = 0 ; i<j ; i++) {
			var ch = str.charCodeAt(i);
			var ca = str.charAt(i)+'';

			if (ch>127) {
				var cl = (ch>>12)&15;
				rs+="\\u"+cl.toString(16);
				cl = (ch>>8)&15;
				rs+=cl.toString(16);
				cl = (ch>>4)&15;
				rs+=cl.toString(16);
				cl = ch&15;
				rs+=cl.toString(16);
				continue;
				}

			if (ch==9) {
				rs+="\\t";
				continue;
				}

			if (ch==10) {
				rs+="\\n";
				continue;
				}

			if (ch==13) {
				rs+="\\r";
				continue;
				}

			if (ch<32) {
				rs+="\\u000"+ch.toString(16);
				continue;
				}

			if (ch==0x5c) {
				rs+="\\\\";
				continue;
				}

			if (esc.indexOf(ca)!=-1) {
				rs+="\\u00"+ch.toString(16);
				continue;
				}
			rs+=ca;
			}
		return rs;
		},
				
	"utf8Encode" : function(st) {
        return unescape( encodeURIComponent( st ) );
		},
	
	"utf8Decode" : function(st) {
        try {
            return decodeURIComponent( escape( st ) );
        } catch (DevNull) {
            return st; 
        }
    },
	
	"Sha1f" : function(s, x, y, z)  {
	    switch (s) {
	        case 0: 
	        return (x & y) ^ (~x & z);           
	        
	        case 1: 
	        return  x ^ y  ^  z;                 
	        
	        case 2: 
	        return (x & y) ^ (x & z) ^ (y & z);  
	        
	        case 3: 
	        return  x ^ y  ^  z; 
		}                
    },
	    
	"Rol" : function(x, n) { 
		return (x<<n) | (x>>>(32-n)); 
		},
	
	"toHex" : function(n) {
		var rs="";
		var v;
			for (var i=7; i>=0; i--) { 
				v = (n>>>(i*4)) & 0xf; 
				rs += v.toString(16); 
				}
		return rs;
		},
	
	"Sha1" : function(msg) {
		msg = WGUtil.utf8Encode(msg);
		var K = [ 0x5a827999, 0x6ed9eba1, 0x8f1bbcdc, 0xca62c1d6 ];
		msg += String.fromCharCode(0x80); 
		var l = msg.length/4+2; 
		var N = Math.ceil(l/16); 
		var M = new Array(N);
			
		for (var i=0; i<N; i++) {
				M[i] = new Array(16);
				for (var j=0; j<16; j++) {
						M[i][j] = 
						(msg.charCodeAt(i*64+j*4) << 24) | 
						(msg.charCodeAt(i*64+j*4+1) << 16) | 
						(msg.charCodeAt(i*64+j*4+2) << 8) | 
						(msg.charCodeAt(i*64+j*4+3))
						;
					}
				}
		M[N-1][14] = ((msg.length-1) * 8) / Math.pow(2, 32); 
		M[N-1][14] = Math.floor(M[ N-1 ][14]);
		M[N-1][15] = ((msg.length-1) * 8) & 0xffffffff;

		var H0 = 0x67452301;
		var H1 = 0xefcdab89;
		var H2 = 0x98badcfe;
		var H3 = 0x10325476;
		var H4 = 0xc3d2e1f0;

		var W = new Array(80); 
		var a;
		var b;
		var c;
		var d;
		var e;
		
		for (var i=0; i<N; i++) {
			
			for (var t=0;  t<16; t++) {
				W[t] = M[i][t];
				}
			
				for (var t=16; t<80; t++) {
				W[t] = WGUtil.Rol(W[t-3] ^ W[t-8] ^ W[t-14] ^ W[t-16], 1);
				}

			a = H0; 
			b = H1; 
			c = H2; 
			d = H3; 
			e = H4;

				for (var t=0; t<80; t++) {
					var s = Math.floor(t / 20);
					var T = ( WGUtil.Rol(a ,5 ) + WGUtil.Sha1f(s,b,c,d) + e + K[s] + W[t] ) & 0xffffffff;
					e = d;
					d = c;
					c = WGUtil.Rol(b, 30);
					b = a;
					a = T;
				}

			H0 = (H0+a) & 0xffffffff;
			H1 = (H1+b) & 0xffffffff;
			H2 = (H2+c) & 0xffffffff;
			H3 = (H3+d) & 0xffffffff;
			H4 = (H4+e) & 0xffffffff;
			}

		return WGUtil.toHex(H0) + 
			WGUtil.toHex(H1) + 
			WGUtil.toHex(H2) +
			WGUtil.toHex(H3) + 
			WGUtil.toHex(H4);
		} 
	} ;

function Init() { 
	EPTOWebGuiLoad();
	}	

function debug(st) { alert(st); }
