(()=>{var e={341:(e,t,n)=>{(t=n(645)(!1)).push([e.id,".text-center{text-align:center}.text-right{text-align:right}a.add-from-address-book-init.button{background:#43454b;color:#fff;font-weight:600;font-size:1rem;border-radius:5px;padding:1rem;margin:0;text-decoration:none;display:inline-flex;flex-direction:row;align-items:center;gap:0.5rem}a.add-from-address-book-init.button:hover{opacity:.9}@media only screen and (max-width: 920px){a.add-from-address-book-init.button span:nth-of-type(2){display:none}}.wl-modal{top:35%}.wl-modal h1{font-size:2.617924em;line-height:1.214;letter-spacing:-1px}.wl-modal .wl-modal-header .close{padding:4px 8px}.wl-modal .wl-modal-body{max-height:700px}.wl-modal .wl-modal-body .form-row-wide{margin-top:1rem;display:flex;flex-direction:column;gap:0.8rem}.wl-modal .wl-modal-body input[type=text],.wl-modal .wl-modal-body textarea{padding:1rem;border-radius:5px;font-size:1rem;margin:0;width:auto}.wl-modal .wl-modal-footer .button{background:#43454b;color:#fff;font-weight:600;font-size:1rem;padding:1rem}.wl-modal .wl-modal-footer .button:hover{opacity:.9}\n",""]),e.exports=t},645:e=>{"use strict";e.exports=function(e){var t=[];return t.toString=function(){return this.map((function(t){var n=function(e,t){var n,r,o,i=e[1]||"",a=e[3];if(!a)return i;if(t&&"function"==typeof btoa){var c=(n=a,r=btoa(unescape(encodeURIComponent(JSON.stringify(n)))),o="sourceMappingURL=data:application/json;charset=utf-8;base64,".concat(r),"/*# ".concat(o," */")),l=a.sources.map((function(e){return"/*# sourceURL=".concat(a.sourceRoot||"").concat(e," */")}));return[i].concat(l).concat([c]).join("\n")}return[i].join("\n")}(t,e);return t[2]?"@media ".concat(t[2]," {").concat(n,"}"):n})).join("")},t.i=function(e,n,r){"string"==typeof e&&(e=[[null,e,""]]);var o={};if(r)for(var i=0;i<this.length;i++){var a=this[i][0];null!=a&&(o[a]=!0)}for(var c=0;c<e.length;c++){var l=[].concat(e[c]);r&&o[l[0]]||(n&&(l[2]?l[2]="".concat(n," and ").concat(l[2]):l[2]=n),t.push(l))}},t}},155:(e,t,n)=>{var r=n(379),o=n(341);"string"==typeof(o=o.__esModule?o.default:o)&&(o=[[e.id,o,""]]);r(o,{insert:"head",singleton:!1}),e.exports=o.locals||{}},379:(e,t,n)=>{"use strict";var r,o=function(){var e={};return function(t){if(void 0===e[t]){var n=document.querySelector(t);if(window.HTMLIFrameElement&&n instanceof window.HTMLIFrameElement)try{n=n.contentDocument.head}catch(e){n=null}e[t]=n}return e[t]}}(),i=[];function a(e){for(var t=-1,n=0;n<i.length;n++)if(i[n].identifier===e){t=n;break}return t}function c(e,t){for(var n={},r=[],o=0;o<e.length;o++){var c=e[o],l=t.base?c[0]+t.base:c[0],s=n[l]||0,u="".concat(l," ").concat(s);n[l]=s+1;var d=a(u),f={css:c[1],media:c[2],sourceMap:c[3]};-1!==d?(i[d].references++,i[d].updater(f)):i.push({identifier:u,updater:h(f,t),references:1}),r.push(u)}return r}function l(e){var t=document.createElement("style"),r=e.attributes||{};if(void 0===r.nonce){var i=n.nc;i&&(r.nonce=i)}if(Object.keys(r).forEach((function(e){t.setAttribute(e,r[e])})),"function"==typeof e.insert)e.insert(t);else{var a=o(e.insert||"head");if(!a)throw new Error("Couldn't find a style target. This probably means that the value for the 'insert' parameter is invalid.");a.appendChild(t)}return t}var s,u=(s=[],function(e,t){return s[e]=t,s.filter(Boolean).join("\n")});function d(e,t,n,r){var o=n?"":r.media?"@media ".concat(r.media," {").concat(r.css,"}"):r.css;if(e.styleSheet)e.styleSheet.cssText=u(t,o);else{var i=document.createTextNode(o),a=e.childNodes;a[t]&&e.removeChild(a[t]),a.length?e.insertBefore(i,a[t]):e.appendChild(i)}}function f(e,t,n){var r=n.css,o=n.media,i=n.sourceMap;if(o?e.setAttribute("media",o):e.removeAttribute("media"),i&&"undefined"!=typeof btoa&&(r+="\n/*# sourceMappingURL=data:application/json;base64,".concat(btoa(unescape(encodeURIComponent(JSON.stringify(i))))," */")),e.styleSheet)e.styleSheet.cssText=r;else{for(;e.firstChild;)e.removeChild(e.firstChild);e.appendChild(document.createTextNode(r))}}var p=null,m=0;function h(e,t){var n,r,o;if(t.singleton){var i=m++;n=p||(p=l(t)),r=d.bind(null,n,i,!1),o=d.bind(null,n,i,!0)}else n=l(t),r=f.bind(null,n,t),o=function(){!function(e){if(null===e.parentNode)return!1;e.parentNode.removeChild(e)}(n)};return r(e),function(t){if(t){if(t.css===e.css&&t.media===e.media&&t.sourceMap===e.sourceMap)return;r(e=t)}else o()}}e.exports=function(e,t){(t=t||{}).singleton||"boolean"==typeof t.singleton||(t.singleton=(void 0===r&&(r=Boolean(window&&document&&document.all&&!window.atob)),r));var n=c(e=e||[],t);return function(e){if(e=e||[],"[object Array]"===Object.prototype.toString.call(e)){for(var r=0;r<n.length;r++){var o=a(n[r]);i[o].references--}for(var l=c(e,t),s=0;s<n.length;s++){var u=a(n[s]);0===i[u].references&&(i[u].updater(),i.splice(u,1))}n=l}}}}},t={};function n(r){var o=t[r];if(void 0!==o)return o.exports;var i=t[r]={id:r,exports:{}};return e[r](i,i.exports,n),i.exports}n.n=e=>{var t=e&&e.__esModule?()=>e.default:()=>e;return n.d(t,{a:t}),t},n.d=(e,t)=>{for(var r in t)n.o(t,r)&&!n.o(e,r)&&Object.defineProperty(e,r,{enumerable:!0,get:t[r]})},n.o=(e,t)=>Object.prototype.hasOwnProperty.call(e,t),n.nc=void 0,(()=>{"use strict";function e(t){return e="function"==typeof Symbol&&"symbol"==typeof Symbol.iterator?function(e){return typeof e}:function(e){return e&&"function"==typeof Symbol&&e.constructor===Symbol&&e!==Symbol.prototype?"symbol":typeof e},e(t)}function t(e,t){for(var n=0;n<t.length;n++){var r=t[n];r.enumerable=r.enumerable||!1,r.configurable=!0,"value"in r&&(r.writable=!0),Object.defineProperty(e,o(r.key),r)}}function r(e,t,n){return(t=o(t))in e?Object.defineProperty(e,t,{value:n,enumerable:!0,configurable:!0,writable:!0}):e[t]=n,e}function o(t){var n=function(t,n){if("object"!==e(t)||null===t)return t;var r=t[Symbol.toPrimitive];if(void 0!==r){var o=r.call(t,"string");if("object"!==e(o))return o;throw new TypeError("@@toPrimitive must return a primitive value.")}return String(t)}(t);return"symbol"===e(n)?n:String(n)}n(155);var i,a=function(){function e(){!function(e,t){if(!(e instanceof t))throw new TypeError("Cannot call a class as a function")}(this,e),r(this,"afterSelect",(function(e,t,n){var r=jQuery("textarea.wl-em-to"),o=r.val();e.forEach((function(e,t){var n=!(!e.email||!e.email[0])&&e.email[0].address;n&&""!==o&&(o+=", "+n),n&&""===o&&(o+=n)})),r.val(o)})),r(this,"clearEmails",(function(){jQuery('input[name="emails[]"]').each((function(e,t){jQuery(t).val()&&""!==jQuery(t).val()||jQuery(t).parent("p.form-row").remove()}))})),r(this,"copyLink",(function(e){e.preventDefault();var t=document.getElementById("bswp-coupon-referral-copy");t.select(),t.setSelectionRange(0,99999),document.execCommand("copy");var n=jQuery(".bswp-copy-confirm");n.show(),setTimeout((function(){n.hide()}),1500)})),this.emailWrapper=jQuery("#referral-emails-wrapper"),this.maxRef=this.emailWrapper.data("max")?parseInt(this.emailWrapper.data("max")):5,this.message="You are only allowed to refer a total of "+this.maxRef+" individuals at a time!"}var n,o;return n=e,(o=[{key:"init",value:function(e){this.$=e;var t=e("body");"undefined"!=typeof cloudsponge&&(cloudsponge.init({displaySelectAllNone:!1,selectionLimit:this.maxRef,selectionLimitMessage:this.message,referrer:"better-sharing-wp:woo-wishlists",afterSubmitContacts:this.afterSelect}),t.on("click",".add-from-address-book-init",this.clickInit))}},{key:"clickInit",value:function(e){e.preventDefault(),this.maxRef=this.maxRef-1,cloudsponge.launch()}}])&&t(n.prototype,o),Object.defineProperty(n,"prototype",{writable:!1}),e}();(i=jQuery)(document).ready((function(){(new a).init(i)}))})()})();