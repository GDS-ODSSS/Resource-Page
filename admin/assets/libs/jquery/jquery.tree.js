!function(t){t.widget("mjs.nestedSortable",t.extend({},t.ui.sortable.prototype,{options:{tabSize:20,disableNesting:"mjs-nestedSortable-no-nesting",errorClass:"mjs-nestedSortable-error",doNotClear:!1,listType:"ul",maxLevels:0,protectRoot:!1,rootID:null,rtl:!1,isAllowed:function(t,e){return!0}},_create:function(){return this.element.data("sortable",this.element.data("nestedSortable")),t.ui.sortable.prototype._create.apply(this,arguments)},destroy:function(){return this.element.removeData("nestedSortable").unbind(".nestedSortable"),t.ui.sortable.prototype.destroy.apply(this,arguments)},_mouseDrag:function(e){this.position=this._generatePosition(e),this.positionAbs=this._convertPositionTo("absolute"),this.lastPositionAbs||(this.lastPositionAbs=this.positionAbs);var i=this.options;if(this.options.scroll){var s=!1;this.scrollParent[0]!=document&&"HTML"!=this.scrollParent[0].tagName?(this.overflowOffset.top+this.scrollParent[0].offsetHeight-e.pageY<i.scrollSensitivity?this.scrollParent[0].scrollTop=s=this.scrollParent[0].scrollTop+i.scrollSpeed:e.pageY-this.overflowOffset.top<i.scrollSensitivity&&(this.scrollParent[0].scrollTop=s=this.scrollParent[0].scrollTop-i.scrollSpeed),this.overflowOffset.left+this.scrollParent[0].offsetWidth-e.pageX<i.scrollSensitivity?this.scrollParent[0].scrollLeft=s=this.scrollParent[0].scrollLeft+i.scrollSpeed:e.pageX-this.overflowOffset.left<i.scrollSensitivity&&(this.scrollParent[0].scrollLeft=s=this.scrollParent[0].scrollLeft-i.scrollSpeed)):(e.pageY-t(document).scrollTop()<i.scrollSensitivity?s=t(document).scrollTop(t(document).scrollTop()-i.scrollSpeed):t(window).height()-(e.pageY-t(document).scrollTop())<i.scrollSensitivity&&(s=t(document).scrollTop(t(document).scrollTop()+i.scrollSpeed)),e.pageX-t(document).scrollLeft()<i.scrollSensitivity?s=t(document).scrollLeft(t(document).scrollLeft()-i.scrollSpeed):t(window).width()-(e.pageX-t(document).scrollLeft())<i.scrollSensitivity&&(s=t(document).scrollLeft(t(document).scrollLeft()+i.scrollSpeed))),!1!==s&&t.ui.ddmanager&&!i.dropBehaviour&&t.ui.ddmanager.prepareOffsets(this,e)}this.positionAbs=this._convertPositionTo("absolute");var o=this.placeholder.offset().top;this.options.axis&&"y"==this.options.axis||(this.helper[0].style.left=this.position.left+"px"),this.options.axis&&"x"==this.options.axis||(this.helper[0].style.top=this.position.top+"px");for(var r=this.items.length-1;r>=0;r--){var l=this.items[r],n=l.item[0],h=this._intersectsWithPointer(l);if(h&&!(n==this.currentItem[0]||this.placeholder[1==h?"next":"prev"]()[0]==n||t.contains(this.placeholder[0],n)||"semi-dynamic"==this.options.type&&t.contains(this.element[0],n))){if(t(n).mouseenter(),this.direction=1==h?"down":"up","pointer"!=this.options.tolerance&&!this._intersectsWithSides(l))break;t(n).mouseleave(),this._rearrange(e,l),this._clearEmpty(n),this._trigger("change",e,this._uiHash());break}}var a=this.placeholder[0].parentNode.parentNode&&t(this.placeholder[0].parentNode.parentNode).closest(".ui-sortable").length?t(this.placeholder[0].parentNode.parentNode):null,p=this._getLevel(this.placeholder),c=this._getChildLevels(this.helper),d=this.placeholder[0].previousSibling?t(this.placeholder[0].previousSibling):null;if(null!=d)for(;"li"!=d[0].nodeName.toLowerCase()||d[0]==this.currentItem[0]||d[0]==this.helper[0];){if(!d[0].previousSibling){d=null;break}d=t(d[0].previousSibling)}var u=this.placeholder[0].nextSibling?t(this.placeholder[0].nextSibling):null;if(null!=u)for(;"li"!=u[0].nodeName.toLowerCase()||u[0]==this.currentItem[0]||u[0]==this.helper[0];){if(!u[0].nextSibling){u=null;break}u=t(u[0].nextSibling)}var f=document.createElement(i.listType);return this.beyondMaxLevels=0,null!=a&&null==u&&(i.rtl&&this.positionAbs.left+this.helper.outerWidth()>a.offset().left+a.outerWidth()||!i.rtl&&this.positionAbs.left<a.offset().left)?(a.after(this.placeholder[0]),this._clearEmpty(a[0]),this._trigger("change",e,this._uiHash())):null!=d&&(i.rtl&&this.positionAbs.left+this.helper.outerWidth()<d.offset().left+d.outerWidth()-i.tabSize||!i.rtl&&this.positionAbs.left>d.offset().left+i.tabSize)?(this._isAllowed(d,p,p+c+1),d.children(i.listType).length||d[0].appendChild(f),o&&o<=d.offset().top?d.children(i.listType).prepend(this.placeholder):d.children(i.listType)[0].appendChild(this.placeholder[0]),this._trigger("change",e,this._uiHash())):this._isAllowed(a,p,p+c),this._contactContainers(e),t.ui.ddmanager&&t.ui.ddmanager.drag(this,e),this._trigger("sort",e,this._uiHash()),this.lastPositionAbs=this.positionAbs,!1},_mouseStop:function(e,i){this.beyondMaxLevels&&(this.placeholder.removeClass(this.options.errorClass),this.domPosition.prev?t(this.domPosition.prev).after(this.placeholder):t(this.domPosition.parent).prepend(this.placeholder),this._trigger("revert",e,this._uiHash()));for(var s=this.items.length-1;s>=0;s--){var o=this.items[s].item[0];this._clearEmpty(o)}t.ui.sortable.prototype._mouseStop.apply(this,arguments)},serialize:function(e){var i=t.extend({},this.options,e),s=this._getItemsAsjQuery(i&&i.connected),o=[];return t(s).each(function(){var e=(t(i.item||this).attr(i.attribute||"id")||"").match(i.expression||/(.+)[-=_](.+)/),s=(t(i.item||this).parent(i.listType).parent(i.items).attr(i.attribute||"id")||"").match(i.expression||/(.+)[-=_](.+)/);e&&o.push((i.key||e[1])+"["+(i.key&&i.expression?e[1]:e[2])+"]="+(s?i.key&&i.expression?s[1]:s[2]:i.rootID))}),!o.length&&i.key&&o.push(i.key+"="),o.join("&")},toHierarchy:function(e){var i=t.extend({},this.options,e),s=(i.startDepthCount,[]);return t(this.element).children(i.items).each(function(){var e=function e(s){var o=(t(s).attr(i.attribute||"id")||"").match(i.expression||/(.+)[-=_](.+)/);if(o){var r={id:o[2]};return t(s).children(i.listType).children(i.items).length>0&&(r.children=[],t(s).children(i.listType).children(i.items).each(function(){var t=e(this);r.children.push(t)})),r}}(this);s.push(e)}),s},toArray:function(e){var i=t.extend({},this.options,e),s=i.startDepthCount||0,o=[],r=2;return o.push({item_id:i.rootID,parent_id:"none",depth:s,left:"1",right:2*(t(i.items,this.element).length+1)}),t(this.element).children(i.items).each(function(){r=function e(r,l,n){var h,a,p=n+1;t(r).children(i.listType).children(i.items).length>0&&(l++,t(r).children(i.listType).children(i.items).each(function(){p=e(t(this),l,p)}),l--);h=t(r).attr(i.attribute||"id").match(i.expression||/(.+)[-=_](.+)/);if(l===s+1)a=i.rootID;else{var c=t(r).parent(i.listType).parent(i.items).attr(i.attribute||"id").match(i.expression||/(.+)[-=_](.+)/);a=c[2]}h&&o.push({item_id:h[2],parent_id:a,depth:l,left:n,right:p});n=p+1;return n}(this,s+1,r)}),o=o.sort(function(t,e){return t.left-e.left})},_clearEmpty:function(e){var i=t(e).children(this.options.listType);!i.length||i.children().length||this.options.doNotClear||i.remove()},_getLevel:function(t){var e=1;if(this.options.listType)for(var i=t.closest(this.options.listType);i&&i.length>0&&!i.is(".ui-sortable");)e++,i=i.parent().closest(this.options.listType);return e},_getChildLevels:function(e,i){var s=this,o=this.options,r=0;return i=i||0,t(e).children(o.listType).children(o.items).each(function(t,e){r=Math.max(s._getChildLevels(e,i+1),r)}),i?r+1:r},_isAllowed:function(e,i,s){var o=this.options,r=!!t(this.domPosition.parent).hasClass("ui-sortable"),l=this.placeholder.closest(".ui-sortable").nestedSortable("option","maxLevels");!o.isAllowed(this.currentItem,e)||e&&e.hasClass(o.disableNesting)||o.protectRoot&&(null==e&&!r||r&&i>1)?(this.placeholder.addClass(o.errorClass),this.beyondMaxLevels=l<s&&0!=l?s-l:1):l<s&&0!=l?(this.placeholder.addClass(o.errorClass),this.beyondMaxLevels=s-l):(this.placeholder.removeClass(o.errorClass),this.beyondMaxLevels=0)}})),t.mjs.nestedSortable.prototype.options=t.extend({},t.ui.sortable.prototype.options,t.mjs.nestedSortable.prototype.options)}(jQuery);