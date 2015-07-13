/*
 * CookieFix v 0.1 B
 * Cookie XSS JavaScript protection.
 * Copyright (C) 2015 by epto@mes3hacklab.org
 * 
 * CookieFix is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 * 
 * This source code is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License
 * along with this source code; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 */

try {
	Object.defineProperty(document, "cookie", {
		"get": function () {
			if (document.onreadcookie) document.onreadcookie();
			var msg = 	document.cookiemessage ? 
						document.cookiemessage : 
						"Warning:\nYou're under attack, somebody is trying to steal your session.\n"+
						"Sign out and verify the connection, the URL of the page etc.";
						
			var r = '';
			var d = new Date();
			var n = d.getTime(); 
			var p = [ "JSESSIONID" , "PHPSESSID" , "ASPSESSIONID" , "SESSIONID" ];
			var rp = p[3 & (Math.floor(Math.random()*3) ^ n)];
			for (var a=0;a<16;a++) {
				var x = a^Math.floor(Math.random()*16)^Math.floor(n);
				x = x &15;
				r = r + x.toString(16);
				}
			n = n + Math.ceil(86400000 + Math.random()*8640000);
			d = new Date(n);
			alert(msg);
			
			for (var cp in p) try {
				document.cookie=p[cp]+"=;expires=Thu, 01 Jan 1970 00:00:00 GMT";
				} catch(DevNull) {}
				
			if (document.cookielogoutpage) top.location.href=document.cookielogoutpage;
			return rp+"="+r+"; expires="+d.toUTCString();
		}
		}) ;
} catch(DevNull) { 
	alert('Warning: Cookie protection disabled!'); 
	}
