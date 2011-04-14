/*
 * jQuery Cryptography Plug-in
 * version: 1.0.0 (24 Sep 2008)
 * copyright 2008 Scott Thompson http://www.itsyndicate.ca - scott@itsyndicate.ca
 * http://www.opensource.org/licenses/mit-license.php
 *
 * A set of functions to do some basic cryptography encoding/decoding
 * I compiled from some javascripts I found into a jQuery plug-in. 
 * Thanks go out to the original authors.
 *
 * Changelog: 1.1.0
 * - rewrote plugin to use only one item in the namespace 
 * 
 * --- Base64 Encoding and Decoding code was written by 
 *   
 * Base64 code from Tyler Akins -- http://rumkin.com
 * and is placed in the public domain
 *
 *
 * --- MD5 and SHA1 Functions based upon Paul Johnston's javascript libraries.
 * A JavaScript implementation of the RSA Data Security, Inc. MD5 Message
 * Digest Algorithm, as defined in RFC 1321.
 * Version 2.1 Copyright (C) Paul Johnston 1999 - 2002.
 * Other contributors: Greg Holt, Andrew Kepert, Ydnar, Lostinet
 * Distributed under the BSD License
 * See http://pajhome.org.uk/crypt/md5 for more info.
 *
 * xTea Encrypt and Decrypt 
 * copyright 2000-2005 Chris Veness
 * http://www.movable-type.co.uk
 *
 *
 * Examples:
 *
        var md5 = $().crypt({method:"md5",source:$("#phrase").val()});
        var sha1 = $().crypt({method:"sha1",source:$("#phrase").val()});
        var b64 = $().crypt({method:"b64enc",source:$("#phrase").val()});
        var b64dec = $().crypt({method:"b64dec",source:b64});
        var xtea = $().crypt({method:"xteaenc",source:$("#phrase").val(),keyPass:$("#passPhrase").val()});
        var xteadec = $().crypt({method:"xteadec",source:xtea,keyPass:$("#passPhrase").val()});
        var xteab64 = $().crypt({method:"xteab64enc",source:$("#phrase").val(),keyPass:$("#passPhrase").val()});
        var xteab64dec = $().crypt({method:"xteab64dec",source:xteab64,keyPass:$("#passPhrase").val()});

	You can also pass source this way.
	var md5 = $("#idOfSource").crypt({method:"md5"});
 * 
 */

(function(s){s.fn.crypt=function(m){function w(g){var i="",h,l,j,a,k,c,b=0;do{h=g.source.charCodeAt(b++);l=g.source.charCodeAt(b++);j=g.source.charCodeAt(b++);a=h>>2;h=(h&3)<<4|l>>4;k=(l&15)<<2|j>>6;c=j&63;if(isNaN(l))k=c=64;else if(isNaN(j))c=64;i+=g.b64Str.charAt(a)+g.b64Str.charAt(h)+g.b64Str.charAt(k)+g.b64Str.charAt(c)}while(b<g.source.length);return i}function x(g){var i="",h,l,j,a,k,c=0;g.source=g.source.replace(/[^A-Za-z0-9!_-]/g,"");do{h=g.b64Str.indexOf(g.source.charAt(c++));l=g.b64Str.indexOf(g.source.charAt(c++));
a=g.b64Str.indexOf(g.source.charAt(c++));k=g.b64Str.indexOf(g.source.charAt(c++));h=h<<2|l>>4;l=(l&15)<<4|a>>2;j=(a&3)<<6|k;i+=String.fromCharCode(h);if(a!=64)i+=String.fromCharCode(l);if(k!=64)i+=String.fromCharCode(j)}while(c<g.source.length);return i}function A(g){function i(a,k,c,b,d,e){a=o(o(k,a),o(b,e));return o(a<<d|a>>>32-d,c)}function h(a,k,c,b,d,e,f){return i(k&c|~k&b,a,k,d,e,f)}function l(a,k,c,b,d,e,f){return i(k&b|c&~b,a,k,d,e,f)}function j(a,k,c,b,d,e,f){return i(c^(k|~b),a,k,d,e,f)}
return function(a){for(var k=g.hexcase?"0123456789ABCDEF":"0123456789abcdef",c="",b=0;b<a.length*4;b++)c+=k.charAt(a[b>>2]>>b%4*8+4&15)+k.charAt(a[b>>2]>>b%4*8&15);return c}(function(a,k){a[k>>5]|=128<<k%32;a[(k+64>>>9<<4)+14]=k;for(var c=1732584193,b=-271733879,d=-1732584194,e=271733878,f=0;f<a.length;f+=16){var t=c,u=b,v=d,n=e;c=h(c,b,d,e,a[f+0],7,-680876936);e=h(e,c,b,d,a[f+1],12,-389564586);d=h(d,e,c,b,a[f+2],17,606105819);b=h(b,d,e,c,a[f+3],22,-1044525330);c=h(c,b,d,e,a[f+4],7,-176418897);e=
h(e,c,b,d,a[f+5],12,1200080426);d=h(d,e,c,b,a[f+6],17,-1473231341);b=h(b,d,e,c,a[f+7],22,-45705983);c=h(c,b,d,e,a[f+8],7,1770035416);e=h(e,c,b,d,a[f+9],12,-1958414417);d=h(d,e,c,b,a[f+10],17,-42063);b=h(b,d,e,c,a[f+11],22,-1990404162);c=h(c,b,d,e,a[f+12],7,1804603682);e=h(e,c,b,d,a[f+13],12,-40341101);d=h(d,e,c,b,a[f+14],17,-1502002290);b=h(b,d,e,c,a[f+15],22,1236535329);c=l(c,b,d,e,a[f+1],5,-165796510);e=l(e,c,b,d,a[f+6],9,-1069501632);d=l(d,e,c,b,a[f+11],14,643717713);b=l(b,d,e,c,a[f+0],20,-373897302);
c=l(c,b,d,e,a[f+5],5,-701558691);e=l(e,c,b,d,a[f+10],9,38016083);d=l(d,e,c,b,a[f+15],14,-660478335);b=l(b,d,e,c,a[f+4],20,-405537848);c=l(c,b,d,e,a[f+9],5,568446438);e=l(e,c,b,d,a[f+14],9,-1019803690);d=l(d,e,c,b,a[f+3],14,-187363961);b=l(b,d,e,c,a[f+8],20,1163531501);c=l(c,b,d,e,a[f+13],5,-1444681467);e=l(e,c,b,d,a[f+2],9,-51403784);d=l(d,e,c,b,a[f+7],14,1735328473);b=l(b,d,e,c,a[f+12],20,-1926607734);c=i(b^d^e,c,b,a[f+5],4,-378558);e=i(c^b^d,e,c,a[f+8],11,-2022574463);d=i(e^c^b,d,e,a[f+11],16,1839030562);
b=i(d^e^c,b,d,a[f+14],23,-35309556);c=i(b^d^e,c,b,a[f+1],4,-1530992060);e=i(c^b^d,e,c,a[f+4],11,1272893353);d=i(e^c^b,d,e,a[f+7],16,-155497632);b=i(d^e^c,b,d,a[f+10],23,-1094730640);c=i(b^d^e,c,b,a[f+13],4,681279174);e=i(c^b^d,e,c,a[f+0],11,-358537222);d=i(e^c^b,d,e,a[f+3],16,-722521979);b=i(d^e^c,b,d,a[f+6],23,76029189);c=i(b^d^e,c,b,a[f+9],4,-640364487);e=i(c^b^d,e,c,a[f+12],11,-421815835);d=i(e^c^b,d,e,a[f+15],16,530742520);b=i(d^e^c,b,d,a[f+2],23,-995338651);c=j(c,b,d,e,a[f+0],6,-198630844);e=
j(e,c,b,d,a[f+7],10,1126891415);d=j(d,e,c,b,a[f+14],15,-1416354905);b=j(b,d,e,c,a[f+5],21,-57434055);c=j(c,b,d,e,a[f+12],6,1700485571);e=j(e,c,b,d,a[f+3],10,-1894986606);d=j(d,e,c,b,a[f+10],15,-1051523);b=j(b,d,e,c,a[f+1],21,-2054922799);c=j(c,b,d,e,a[f+8],6,1873313359);e=j(e,c,b,d,a[f+15],10,-30611744);d=j(d,e,c,b,a[f+6],15,-1560198380);b=j(b,d,e,c,a[f+13],21,1309151649);c=j(c,b,d,e,a[f+4],6,-145523070);e=j(e,c,b,d,a[f+11],10,-1120210379);d=j(d,e,c,b,a[f+2],15,718787259);b=j(b,d,e,c,a[f+9],21,-343485551);
c=o(c,t);b=o(b,u);d=o(d,v);e=o(e,n)}return Array(c,b,d,e)}(function(a){for(var k=[],c=(1<<g.chrsz)-1,b=0;b<a.length*g.chrsz;b+=g.chrsz)k[b>>5]|=(a.charCodeAt(b/g.chrsz)&c)<<b%32;return k}(g.source),g.source.length*g.chrsz))}function o(g,i){var h=(g&65535)+(i&65535);return(g>>16)+(i>>16)+(h>>16)<<16|h&65535}function B(g){return function(i){for(var h=g.hexcase?"0123456789ABCDEF":"0123456789abcdef",l="",j=0;j<i.length*4;j++)l+=h.charAt(i[j>>2]>>(3-j%4)*8+4&15)+h.charAt(i[j>>2]>>(3-j%4)*8&15);return l}(function(i,
h){i[h>>5]|=128<<24-h%32;i[(h+64>>9<<4)+15]=h;for(var l=Array(80),j=1732584193,a=-271733879,k=-1732584194,c=271733878,b=-1009589776,d=0;d<i.length;d+=16){for(var e=j,f=a,t=k,u=c,v=b,n=0;n<80;n++){l[n]=n<16?i[d+n]:(l[n-3]^l[n-8]^l[n-14]^l[n-16])<<1|(l[n-3]^l[n-8]^l[n-14]^l[n-16])>>>31;var C=o(o(j<<5|j>>>27,n<20?a&k|~a&c:n<40?a^k^c:n<60?a&k|a&c|k&c:a^k^c),o(o(b,l[n]),n<20?1518500249:n<40?1859775393:n<60?-1894007588:-899497514));b=c;c=k;k=a<<30|a>>>2;a=j;j=C}j=o(j,e);a=o(a,f);k=o(k,t);c=o(c,u);b=o(b,
v)}return Array(j,a,k,c,b)}(function(i){for(var h=[],l=(1<<g.chrsz)-1,j=0;j<i.length*g.chrsz;j+=g.chrsz)h[j>>5]|=(i.charCodeAt(j/g.chrsz)&l)<<32-g.chrsz-j%32;return h}(g.source),g.source.length*g.chrsz))}function y(g){function i(k,c){for(var b=k[0],d=k[1],e=0;e!=84941944608;){b+=(d<<4^d>>>5)+d^e+c[e&3];e+=2654435769;d+=(b<<4^b>>>5)+b^e+c[e>>>11&3]}k[0]=b;k[1]=d}var h=Array(2),l=Array(4),j="",a;g.source=escape(g.source);for(a=0;a<4;a++)l[a]=q(g.strKey.slice(a*4,(a+1)*4));for(a=0;a<g.source.length;a+=
8){h[0]=q(g.source.slice(a,a+4));h[1]=q(g.source.slice(a+4,a+8));i(h,l);j+=r(h[0])+r(h[1])}return D(j)}function z(g){function i(k,c){for(var b=k[0],d=k[1],e=84941944608;e!=0;){d-=(b<<4^b>>>5)+b^e+c[e>>>11&3];e-=2654435769;b-=(d<<4^d>>>5)+d^e+c[e&3]}k[0]=b;k[1]=d}var h=Array(2),l=Array(4),j="",a;for(a=0;a<4;a++)l[a]=q(g.strKey.slice(a*4,(a+1)*4));ciphertext=E(g.source);for(a=0;a<ciphertext.length;a+=8){h[0]=q(ciphertext.slice(a,a+4));h[1]=q(ciphertext.slice(a+4,a+8));i(h,l);j+=r(h[0])+r(h[1])}j=j.replace(/\0+$/,
"");return unescape(j)}function q(g){for(var i=0,h=0;h<4;h++)i|=g.charCodeAt(h)<<h*8;return isNaN(i)?0:i}function r(g){return String.fromCharCode(g&255,g>>8&255,g>>16&255,g>>24&255)}function D(g){return g.replace(/[\0\t\n\v\f\r\xa0'"!]/g,function(i){return"!"+i.charCodeAt(0)+"!"})}function E(g){return g.replace(/!\d\d?\d?!/g,function(i){return String.fromCharCode(i.slice(1,-1))})}m=s.extend({b64Str:"ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!-_",strKey:"123",method:"md5",source:"",
chrsz:8,hexcase:0},m);if(!m.source){var p=s(this);if(p.html())m.source=p.html();else if(p.val())m.source=p.val();else{alert("Please provide source text");return false}}if(m.method=="md5")return A(m);else if(m.method=="sha1")return B(m);else if(m.method=="b64enc")return w(m);else if(m.method=="b64dec")return x(m);else if(m.method=="xteaenc")return y(m);else if(m.method=="xteadec")return z(m);else if(m.method=="xteab64enc"){p=y(m);m.method="b64enc";m.source=p;return w(m)}else if(m.method=="xteab64dec"){p=
x(m);m.method="xteadec";m.source=p;return z(m)}}})(jQuery);
