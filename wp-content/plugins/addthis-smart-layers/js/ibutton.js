/*
 * iButton jQuery Plug-in
 *
 * Copyright 2011 Giva, Inc. (http://www.givainc.com/labs/)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * 	http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Date: 2011-07-26
 * Rev:  1.0.03
 */
(function(e){e.iButton={version:"1.0.03",setDefaults:function(g){e.extend(f,g)}};e.fn.iButton=function(j){var k=typeof arguments[0]=="string"&&arguments[0];var i=k&&Array.prototype.slice.call(arguments,1)||arguments;var h=(this.length==0)?null:e.data(this[0],"iButton");if(h&&k&&this.length){if(k.toLowerCase()=="object"){return h}else{if(h[k]){var g;this.each(function(l){var m=e.data(this,"iButton")[k].apply(h,i);if(l==0&&m){if(!!m.jquery){g=e([]).add(m)}else{g=m;return false}}else{if(!!m&&!!m.jquery){g=g.add(m)}}});return g||this}else{return this}}}else{return this.each(function(){new c(this,j)})}};var a=0;e.browser.iphone=(navigator.userAgent.toLowerCase().indexOf("iphone")>-1);var c=function(n,i){var s=this,h=e(n),t=++a,k=false,u={},o={dragging:false,clicked:null},w={position:null,offset:null,time:null},i=e.extend({},f,i,(!!e.metadata?h.metadata():{})),y=(i.labelOn==b&&i.labelOff==d),z=":checkbox, :radio";if(!h.is(z)){return h.find(z).iButton(i)}else{if(e.data(h[0],"iButton")){return}}e.data(h[0],"iButton",s);if(i.resizeHandle=="auto"){i.resizeHandle=!y}if(i.resizeContainer=="auto"){i.resizeContainer=!y}this.toggle=function(B){var A=(arguments.length>0)?B:!h[0].checked;h.attr("checked",A).trigger("change")};this.disable=function(B){var A=(arguments.length>0)?B:!k;k=A;h.attr("disabled",A);v[A?"addClass":"removeClass"](i.classDisabled);if(e.isFunction(i.disable)){i.disable.apply(s,[k,h,i])}};this.repaint=function(){x()};this.destroy=function(){e([h[0],v[0]]).unbind(".iButton");e(document).unbind(".iButton_"+t);v.after(h).remove();e.data(h[0],"iButton",null);if(e.isFunction(i.destroy)){i.destroy.apply(s,[h,i])}};h.wrap('<div class="'+e.trim(i.classContainer+" "+i.className)+'" />').after('<div class="'+i.classHandle+'"><div class="'+i.classHandleRight+'"><div class="'+i.classHandleMiddle+'" /></div></div><div class="'+i.classLabelOff+'"><span><label>'+i.labelOff+'</label></span></div><div class="'+i.classLabelOn+'"><span><label>'+i.labelOn+'</label></span></div><div class="'+i.classPaddingLeft+'"></div><div class="'+i.classPaddingRight+'"></div>');var v=h.parent(),g=h.siblings("."+i.classHandle),p=h.siblings("."+i.classLabelOff),m=p.children("span"),j=h.siblings("."+i.classLabelOn),l=j.children("span");if(i.resizeHandle||i.resizeContainer){u.onspan=l.outerWidth();u.offspan=m.outerWidth()}if(i.resizeHandle){u.handle=Math.min(u.onspan,u.offspan);g.css("width",u.handle)}else{u.handle=g.width()}if(i.resizeContainer){u.container=(Math.max(u.onspan,u.offspan)+u.handle+20);v.css("width",u.container);p.css("width",u.container-5)}else{u.container=v.width()}var r=u.container-u.handle-6;var x=function(B){var C=h[0].checked,A=(C)?r:0,B=(arguments.length>0)?arguments[0]:true;if(B&&i.enableFx){g.stop().animate({left:A},i.duration,i.easing);j.stop().animate({width:A+4},i.duration,i.easing);l.stop().animate({marginLeft:A-r},i.duration,i.easing);m.stop().animate({marginRight:-A},i.duration,i.easing)}else{g.css("left",A);j.css("width",A+4);l.css("marginLeft",A-r);m.css("marginRight",-A)}};x(false);var q=function(A){return A.pageX||((A.originalEvent.changedTouches)?A.originalEvent.changedTouches[0].pageX:0)};v.bind("mousedown.iButton touchstart.iButton",function(A){if(e(A.target).is(z)||k||(!i.allowRadioUncheck&&h.is(":radio:checked"))){return}A.preventDefault();o.clicked=g;w.position=q(A);w.offset=w.position-(parseInt(g.css("left"),10)||0);w.time=(new Date()).getTime();return false});if(i.enableDrag){e(document).bind("mousemove.iButton_"+t+" touchmove.iButton_"+t,function(C){if(o.clicked!=g){return}C.preventDefault();var A=q(C);if(A!=w.offset){o.dragging=true;v.addClass(i.classHandleActive)}var B=Math.min(1,Math.max(0,(A-w.offset)/r));g.css("left",B*r);j.css("width",B*r+4);m.css("marginRight",-B*r);l.css("marginLeft",-(1-B)*r);return false})}v.bind("mouseup.iButton_"+t+" touchend.iButton_"+t,function(D){if(o.clicked!=g){return false}D.preventDefault();var E=true;if(!o.dragging||(((new Date()).getTime()-w.time)<i.clickOffset)){var B=h[0].checked;h.attr("checked",!B);if(e.isFunction(i.click)){i.click.apply(s,[!B,h,i])}}else{var A=q(D);var C=(A-w.offset)/r;var B=(C>=0.5);if(h[0].checked==B){E=false}h.attr("checked",B)}v.removeClass(i.classHandleActive);o.clicked=null;o.dragging=null;if(E){h.trigger("change")}else{x()}return false});h.bind("change.iButton",function(){x();if(h.is(":radio")){var B=h[0];var A=e(B.form?B.form[B.name]:":radio[name="+B.name+"]");A.filter(":not(:checked)").iButton("repaint")}if(e.isFunction(i.change)){i.change.apply(s,[h,i])}}).bind("focus.iButton",function(){v.addClass(i.classFocus)}).bind("blur.iButton",function(){v.removeClass(i.classFocus)});if(e.isFunction(i.click)){h.bind("click.iButton",function(){i.click.apply(s,[h[0].checked,h,i])})}if(h.is(":disabled")){this.disable(true)}if(e.browser.msie){v.find("*").andSelf().attr("unselectable","on");h.bind("click.iButton",function(){h.triggerHandler("change.iButton")})}if(e.isFunction(i.init)){i.init.apply(s,[h,i])}};var f={duration:200,easing:"swing",labelOn:"ON",labelOff:"OFF",resizeHandle:"auto",resizeContainer:"auto",enableDrag:true,enableFx:true,allowRadioUncheck:false,clickOffset:120,className:"",classContainer:"ibutton-container",classDisabled:"ibutton-disabled",classFocus:"ibutton-focus",classLabelOn:"ibutton-label-on",classLabelOff:"ibutton-label-off",classHandle:"ibutton-handle",classHandleMiddle:"ibutton-handle-middle",classHandleRight:"ibutton-handle-right",classHandleActive:"ibutton-active-handle",classPaddingLeft:"ibutton-padding-left",classPaddingRight:"ibutton-padding-right",init:null,change:null,click:null,disable:null,destroy:null},b=f.labelOn,d=f.labelOff})(jQuery);