!function(t,e){var n,l=t.fn.domManip,a="_tmplitem",r=/^[^<]*(<[\w\W]+>)[^>]*$|\{\{\! /,p={},i={},o={key:0,data:{}},u=0,c=0,f=[];function m(e,n,l,a){var r={data:a||0===a||!1===a?a:n?n.data:{},_wrap:n?n._wrap:null,tmpl:null,parent:n||null,nodes:[],calls:g,nest:w,wrap:v,html:k,update:T};return e&&t.extend(r,e,{nodes:[],parent:n}),l&&(r.tmpl=l,r._ctnt=r._ctnt||r.tmpl(t,r),r.key=++u,(f.length?i:p)[u]=r),r}function s(e,n,l){var r,p=l?t.map(l,function(t){return"string"==typeof t?e.key?t.replace(/(<\w+)(?=[\s>])(?![^>]*_tmplitem)([^>]*)/g,"$1 "+a+'="'+e.key+'" $2'):t:s(t,e,t._ctnt)}):e;return n?p:((p=p.join("")).replace(/^\s*([^<\s][^<]*)?(<[\w\W]+>)([^>]*[^>\s])?\s*$/,function(e,n,l,a){y(r=t(l).get()),n&&(r=d(n).concat(r)),a&&(r=r.concat(d(a)))}),r||d(p))}function d(e){var n=document.createElement("div");return n.innerHTML=e,t.makeArray(n.childNodes)}function $(e){return new Function("jQuery","$item","var $=jQuery,call,__=[],$data=$item.data;with($data){__.push('"+t.trim(e).replace(/([\\'])/g,"\\$1").replace(/[\r\t\n]/g," ").replace(/\$\{([^\}]*)\}/g,"{{= $1}}").replace(/\{\{(\/?)(\w+|.)(?:\(((?:[^\}]|\}(?!\}))*?)?\))?(?:\s+(.*?)?)?(\(((?:[^\}]|\}(?!\}))*?)\))?\s*\}\}/g,function(e,n,l,a,r,p,i){var o,u,c,f=t.tmpl.tag[l];if(!f)throw"Unknown template tag: "+l;return o=f._default||[],p&&!/\w$/.test(r)&&(r+=p,p=""),r?(r=h(r),i=i?","+h(i)+")":p?")":"",u=p?r.indexOf(".")>-1?r+h(p):"("+r+").call($item"+i:r,c=p?u:"(typeof("+r+")==='function'?("+r+").call($item):("+r+"))"):c=u=o.$1||"null",a=h(a),"');"+f[n?"close":"open"].split("$notnull_1").join(r?"typeof("+r+")!=='undefined' && ("+r+")!=null":"true").split("$1a").join(c).split("$1").join(u).split("$2").join(a||o.$2||"")+"__.push('"})+"');}return __;")}function _(e,n){e._wrap=s(e,!0,t.isArray(n)?n:[r.test(n)?n:t(n).html()]).join("")}function h(t){return t?t.replace(/\\'/g,"'").replace(/\\\\/g,"\\"):null}function y(e){var n,l,r,o,f,s="_"+c,d={};for(r=0,o=e.length;r<o;r++)if(1===(n=e[r]).nodeType){for(f=(l=n.getElementsByTagName("*")).length-1;f>=0;f--)$(l[f]);$(n)}function $(e){var n,l,r,o,f=e;if(o=e.getAttribute(a)){for(;f.parentNode&&1===(f=f.parentNode).nodeType&&!(n=f.getAttribute(a)););n!==o&&(f=f.parentNode?11===f.nodeType?0:f.getAttribute(a)||0:0,(r=p[o])||((r=m(r=i[o],p[f]||i[f])).key=++u,p[u]=r),c&&$(o)),e.removeAttribute(a)}else c&&(r=t.data(e,"tmplItem"))&&($(r.key),p[r.key]=r,f=(f=t.data(e.parentNode,"tmplItem"))?f.key:0);if(r){for(l=r;l&&l.key!=f;)l.nodes.push(e),l=l.parent;delete r._ctnt,delete r._wrap,t.data(e,"tmplItem",r)}function $(t){r=d[t+=s]=d[t]||m(r,p[r.parent.key+s]||r.parent)}}}function g(t,e,n,l){if(!t)return f.pop();f.push({_:t,tmpl:e,item:this,data:n,options:l})}function w(e,n,l){return t.tmpl(t.template(e),n,l,this)}function v(e,n){var l=e.options||{};return l.wrapped=n,t.tmpl(t.template(e.tmpl),e.data,l,e.item)}function k(e,n){var l=this._wrap;return t.map(t(t.isArray(l)?l.join(""):l).filter(e||"*"),function(t){return n?t.innerText||t.textContent:t.outerHTML||(e=t,(l=document.createElement("div")).appendChild(e.cloneNode(!0)),l.innerHTML);var e,l})}function T(){var e=this.nodes;t.tmpl(null,null,null,this).insertBefore(e[0]),t(e).remove()}t.each({appendTo:"append",prependTo:"prepend",insertBefore:"before",insertAfter:"after",replaceAll:"replaceWith"},function(e,l){t.fn[e]=function(a){var r,i,o,u,f=[],m=t(a),s=1===this.length&&this[0].parentNode;if(n=p||{},s&&11===s.nodeType&&1===s.childNodes.length&&1===m.length)m[l](this[0]),f=this;else{for(i=0,o=m.length;i<o;i++)c=i,r=(i>0?this.clone(!0):this).get(),t(m[i])[l](r),f=f.concat(r);c=0,f=this.pushStack(f,e,m.selector)}return u=n,n=null,t.tmpl.complete(u),f}}),t.fn.extend({tmpl:function(e,n,l){return t.tmpl(this[0],e,n,l)},tmplItem:function(){return t.tmplItem(this[0])},template:function(e){return t.template(e,this[0])},domManip:function(e,a,r,i){if(e[0]&&t.isArray(e[0])){for(var o,u=t.makeArray(arguments),f=e[0],m=f.length,s=0;s<m&&!(o=t.data(f[s++],"tmplItem")););o&&c&&(u[2]=function(e){t.tmpl.afterManip(this,e,r)}),l.apply(this,u)}else l.apply(this,arguments);return c=0,n||t.tmpl.complete(p),this}}),t.extend({tmpl:function(e,n,l,a){var r,u=!a;if(u)a=o,e=t.template[e]||t.template(null,e),i={};else if(!e)return e=a.tmpl,p[a.key]=a,a.nodes=[],a.wrapped&&_(a,a.wrapped),t(s(a,null,a.tmpl(t,a)));return e?("function"==typeof n&&(n=n.call(a||{})),l&&l.wrapped&&_(l,l.wrapped),r=t.isArray(n)?t.map(n,function(t){return t?m(l,a,e,t):null}):[m(l,a,e,n)],u?t(s(a,null,r)):r):[]},tmplItem:function(e){var n;for(e instanceof t&&(e=e[0]);e&&1===e.nodeType&&!(n=t.data(e,"tmplItem"))&&(e=e.parentNode););return n||o},template:function(e,n){return n?("string"==typeof n?n=$(n):n instanceof t&&(n=n[0]||{}),n.nodeType&&(n=t.data(n,"tmpl")||t.data(n,"tmpl",$(n.innerHTML))),"string"==typeof e?t.template[e]=n:n):e?"string"!=typeof e?t.template(null,e):t.template[e]||t.template(null,r.test(e)?e:t(e)):null},encode:function(t){return(""+t).split("<").join("&lt;").split(">").join("&gt;").split('"').join("&#34;").split("'").join("&#39;")}}),t.extend(t.tmpl,{tag:{tmpl:{_default:{$2:"null"},open:"if($notnull_1){__=__.concat($item.nest($1,$2));}"},wrap:{_default:{$2:"null"},open:"$item.calls(__,$1,$2);__=[];",close:"call=$item.calls();__=call._.concat($item.wrap(call,__));"},each:{_default:{$2:"$index, $value"},open:"if($notnull_1){$.each($1a,function($2){with(this){",close:"}});}"},if:{open:"if(($notnull_1) && $1a){",close:"}"},else:{_default:{$1:"true"},open:"}else if(($notnull_1) && $1a){"},html:{open:"if($notnull_1){__.push($1a);}"},"=":{_default:{$1:"$data"},open:"if($notnull_1){__.push($.encode($1a));}"},"!":{open:""}},complete:function(t){p={}},afterManip:function(e,n,l){var a=11===n.nodeType?t.makeArray(n.childNodes):1===n.nodeType?[n]:[];l.call(e,n),y(a),c++}})}(jQuery);