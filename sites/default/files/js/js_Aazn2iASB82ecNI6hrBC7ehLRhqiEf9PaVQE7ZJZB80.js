/**
 * @file
 * Cherries by @toddmotto, @cferdinandi, @adamfschwartz, @daniellmb.
 */
!function(t,e){"use strict";"function"==typeof define&&define.amd?define([],e):"object"==typeof exports?module.exports=e():t.dBlazy=e()}(this,function(){"use strict";var t={};return t.matches=function(t,e){var n=Element.prototype;return n.matches||(n.matches=n.matchesSelector||n.mozMatchesSelector||n.msMatchesSelector||n.oMatchesSelector||n.webkitMatchesSelector||function(t){for(var e=(window.document||window.ownerDocument).querySelectorAll(t),n=e.length;--n>=0&&e.item(n)!==this;);return n>-1}),!!t.matches(e)},t.closest=function(e,n){for(;e&&e!==document;e=e.parentNode)if(t.matches(e,n))return e;return null},t.extend=Object.assign||function(t){t=t||{};for(var e=1,n=arguments.length;e<n;e++)if(arguments[e])for(var r in arguments[e])arguments[e].hasOwnProperty(r)&&(t[r]=arguments[e][r]);return t},t.forEach=function(t,e,n){var r=Object.prototype;if("[object Object]"===r.toString.call(t))for(var o in t)r.hasOwnProperty.call(t,o)&&e.call(n,t[o],o,t);else for(var c=0,a=t.length;c<a;c++)e.call(n,t[c],c,t)},t.hasClass=function(t,e){return t.classList?t.classList.contains(e):-1!==t.className.indexOf(e)},t.on=function(e,n,r,o){e.addEventListener(n,function(e){for(var n=e.target;n&&n!==this;)t.matches(n,r)&&o.call(n,e),n=n.parentNode})},t.once=function(t){var e,n=!1;return function(){return n?e:(n=!0,e=t.apply(this,arguments),t=null,e)}},t.parse=function(t){try{return JSON.parse(t)}catch(t){return!1}},t.throttle=function(t,e,n){var r=0;return function(){var o=+new Date;o-r<e||(r=o,t.apply(n,arguments))}},t.resize=function(t,e){return window.onresize=function(){window.clearTimeout(e),e=window.setTimeout(t,200)},t},t.trigger=function(t,e,n){var r,o={detail:n||{},bubbles:!0,cancelable:!0};window.CustomEvent?r=new CustomEvent(e,o):(r=document.createEvent("CustomEvent"),r.initCustomEvent(e,!0,!0,o)),t.dispatchEvent(r)},t});
;
!function(o,t){"use strict";"function"==typeof define&&define.amd?define([window.dBlazy],t):"object"==typeof exports?module.exports=t(window.dBlazy):o.Bio=t(window.dBlazy)}(this,function(o){"use strict";function t(o){var t=this;t.options=i.extend({},t.defaults,o||{}),u=!1,d=!1,n(t)}function n(o){var t={rootMargin:o.options.rootMargin,threshold:o.options.threshold};o.options.selector=o.options.selector+":not(."+o.options.successClass+")",o.elms=(o.options.root||s).querySelectorAll(o.options.selector),o.count=o.elms.length,o.windowWidth=e.innerWidth||s.documentElement.clientWidth||s.body.clientWidth||e.screen.width,o.prepare(),o.observer=new IntersectionObserver(o.observing.bind(o),t),d||(o.observe(),d=!0)}var e=window,s=document,i=o,r=0,c=0,u=!1,d=!1,a=t.prototype;return a.constructor=t,a.count=0,a.counted=-1,a.erCounted=0,a._er=-1,a._ok=1,a.defaults={root:null,disconnect:!1,error:!1,success:!1,intersecting:!1,observing:!1,successClass:"b-loaded",selector:".b-lazy",errorClass:"b-error",bgClass:"b-bg",rootMargin:"0px",threshold:[0]},a.load=function(o){var t=this;t.isValid(o)?t.intersecting(o):i.forEach(o,function(o){t.isValid(o)&&t.intersecting(o)}),u||t.disconnect()},a.isLoaded=function(o){return o.classList.contains(this.options.successClass)},a.isValid=function(o){return"object"==typeof o&&void 0===o.length&&!this.isLoaded(o)},a.prepare=function(){},a.revalidate=function(o){var t=this;(t.count!==t.counted||!0===o)&&c<t.counted&&(u=!1,t.elms=(t.options.root||s).querySelectorAll(t.options.selector),t.observe(),c++)},a.intersecting=function(o){var t=this;"function"==typeof t.options.intersecting&&t.options.intersecting(o,t.options),i.trigger(o,"bio.intersecting",{options:t.options}),t.lazyLoad(o),t.counted++,u||t.observer.unobserve(o)},a.lazyLoad=function(o){},a.success=function(o,t,n){var e=this;"function"==typeof e.options.success&&e.options.success(o,t,n,e.options),e.erCounted>0&&e.erCounted--},a.error=function(o,t,n){var e=this;"function"==typeof e.options.error&&e.options.error(o,t,n,e.options),e.erCounted++},a.loaded=function(o,t,n){var e=this;e[t===e._ok?"success":"error"](o,t,n),o.classList.add(t===e._ok?e.options.successClass:e.options.errorClass)},a.equal=function(o,t){return o.nodeName.toLowerCase()===t},a.observe=function(){var o=this;r=o.elms.length,i.forEach(o.elms,function(t){o.isLoaded(t)||o.observer.observe(t)})},a.observing=function(o,t){var n=this;n.entries=o,u||(i.forEach(o,function(o){"function"==typeof n.options.observing&&n.options.observing(o,t,n.options),(o.isIntersecting||o.intersectionRatio>0)&&(n.isLoaded(o.target)||n.intersecting(o.target),r--)}),n.disconnect())},a.disconnect=function(o){var t=this;t.erCounted>0&&!o||((0===r||t.count===t.counted)&&t.options.disconnect||o)&&(t.observer.disconnect(),t.count=0,t.elms=null,u=!0)},a.destroy=function(o){var t=this;t.disconnect(o),t.observer=null},a.disconnected=function(){return u},a.reinit=function(){u=!1,d=!1,n(this)},t});
;
!function(t,e){"use strict";"function"==typeof define&&define.amd?define([window.dBlazy,window.Bio],e):"object"==typeof exports?module.exports=e(window.dBlazy,window.Bio):t.BioMedia=e(window.dBlazy,window.Bio)}(this,function(t,e){"use strict";function r(t){return i.apply(this,arguments)}var o=t,i=e,n="data-src",a=["src"],s=["srcset","src"],c=r.prototype=Object.create(e.prototype);return c.constructor=r,c.removeAttrs=function(t,e){o.forEach(e,function(e){t.removeAttribute("data-"+e)})},c.setAttrs=function(t,e){var r=this;o.forEach(e,function(e){r.setAttr(t,e)})},c.setAttr=function(t,e,r){if(t.hasAttribute("data-"+e)){var o=t.getAttribute("data-"+e);"src"===e?t.src=o:t.setAttribute(e,o),r&&t.removeAttribute("data-"+e)}},c.prepare=function(t){return function(){var e=this;if(e.options.breakpoints){var r=[];o.forEach(e.options.breakpoints,function(t){if(a.push(t.src.replace("data-","")),t.width>=e.windowWidth)return n=t.src,r.push(n),!1}),r.length>0&&(n=r[0])}return t.call(this)}}(c.prepare),c.lazyLoad=function(t){return function(e){if(!e.hasAttribute("data-bio-hit")){var r=this,i=e.parentNode,n=r.equal(e,"img"),a=void 0===e.src&&e.classList.contains(r.options.bgClass),s=i&&r.equal(i,"picture"),c=r.equal(e,"video");return s?(o.forEach(i.getElementsByTagName("source"),function(t){r.setAttr(t,"srcset",!0)}),r.setAttr(e,"src",!0),r.loaded(e,r._ok)):c?(o.forEach(e.getElementsByTagName("source"),function(t){r.setAttr(t,"src",!0)}),e.load(),r.loaded(e,r._ok)):n||a?r.setImage(e,a):e.getAttribute("data-src")&&e.hasAttribute("src")&&(r.setAttr(e,"src",!0),r.loaded(e,r._ok)),e.setAttribute("data-bio-hit",1),t.apply(this,arguments)}}}(c.lazyLoad),c.promise=function(t,e){var r=this;return new Promise(function(o,i){var a=new Image;a.src=t.getAttribute(e?n:"data-src"),t.hasAttribute("data-srcset")&&(a.srcset=t.getAttribute("data-srcset"));var c=function(){e?r.setBg(t):r.setAttrs(t,s)};a.onload=function(){c(),o(r._ok)},a.onerror=function(){c(),i(r._er)}})},c.setImage=function(t,e){var r=this;return r.promise(t,e).then(function(o){r.loaded(t,o),r.removeAttrs(t,e?a:s)}).catch(function(e){r.loaded(t,e)}).finally(function(){o.trigger(t,"bio.finally",{options:r.options})})},c.setBg=function(t){t.hasAttribute(n)&&(t.style.backgroundImage='url("'+t.getAttribute(n)+'")',t.removeAttribute("src"))},r});
;
!function(t,e,i,n,a){"use strict";function s(e){function s(t){var e=!!t.getAttribute("data-dimensions")&&i.parse(t.getAttribute("data-dimensions"));if(e){var n=Object.keys(e),a=n[0],s=n[n.length-1],l=function(t){return t>=r.windowWidth},o=n.filter(l).map(function(t){return e[t]}).shift();"undefined"===o&&(o=e[r.windowWidth>=s?s:a]),"undefined"!==o&&(t.style.paddingBottom=o+"%"),t.removeAttribute("data-ratio")}}function l(t){t.hasAttribute("style")||(t.style.paddingBottom=t.getAttribute("data-ratio")+"%"),t.removeAttribute("data-ratio")}var r=t.blazy,o=e.getAttribute("data-blazy"),c=o&&"1"!==o?i.parse(o):{},d=i.extend({},r.globals(),c),u=e.querySelectorAll("[data-dimensions]"),b=u.length>0,y=e.querySelectorAll("[data-ratio]"),g=y.length>0;r.options=d,r.init=r.run(d),i.resize(function(){r.windowWidth=n.innerWidth||a.documentElement.clientWidth||a.body.clientWidth||n.screen.width,b?i.forEach(u,s,e):g&&i.forEach(y,l,e),(r.isBlazy()||e.classList.contains("blazy--revalidate"))&&r.init.revalidate(!0)})(),e.classList.add("blazy--on")}t.blazy=t.blazy||{init:null,windowWidth:0,blazySettings:e.blazy||{},ioSettings:e.blazyIo||{},options:{},globals:function(){var t=this,e={success:t.clearing.bind(t),error:t.clearing.bind(t),selector:".b-lazy",errorClass:"b-error",successClass:"b-loaded"};return i.extend(t.blazySettings,t.ioSettings,e)},clearing:function(t){var e=this,a=t.classList.contains("b-responsive")&&t.hasAttribute("data-pfsrc");t.className=t.className.replace(/(\S+)loading/,"");var s=[i.closest(t,".is-loading"),i.closest(t,'[class*="loading"]')];i.forEach(s,function(t){null!==t&&(t.className=t.className.replace(/(\S+)loading/,""))}),n.picturefill&&a&&n.picturefill({reevaluate:!0,elements:[t]}),i.trigger(t,"blazy.done",{options:e.options})},isIo:function(){return this.ioSettings&&this.ioSettings.enabled&&"IntersectionObserver"in n},isBlazy:function(){return!this.isIo()&&"Blazy"in n},run:function(t){return this.isIo()?new BioMedia(t):new Blazy(t)}},t.behaviors.blazy={attach:function(e){var n=t.blazy;if(null===a.querySelector("[data-blazy]"))return n.options=n.globals(),void(n.init=n.run(n.globals()));var l=a.querySelectorAll(".blazy:not(.blazy--on)");i.once(i.forEach(l,s))}}}(Drupal,drupalSettings,dBlazy,this,this.document);
;
