var SCROLL_TOP = 0;


//모바일 체크
var mobileW = 960;
function mobileSizeFlag(){
	var wWeight = window.innerWidth || document.documentElement.clientWidth || document.body.clientWidth;
	var flag  = mobileW >= wWeight;

	return flag;
}

//메뉴 스크립트
var gnbSetTime;
var gnbFn = {
	init: function(t) {
		$gnb = $("#gnb"),
		$head = $("#header"),
		$btn = $(".btn_gnb"),
		SCROLL_TOP = $(window).scrollTop();

		$btn.on("click", function(){
			$(this).toggleClass("on");
			$(".gnbbox").toggle();
		});

        $gnb.find(".menu > li").on("click",function(e){
          if(mobileSizeFlag() && $(this).parent().find(".depth2").length > 0){
              var $this = $(this);

			  if ($this.find(".depth2").is(":visible")){
				  $this.removeClass("on");
			  } else {
				$this.siblings(".on").removeClass("on");
				$this.addClass("on");
			  }
          }
        }).find(">a").on("click",function(e){
          if(mobileSizeFlag() && $(this).parent().find(".depth2").length > 0){
			e.preventDefault();
           }
        });
	},
	open: function(t) {
		if ($("#header .modal-cover").length < 1){
			$("#header").append("<div class='modal-cover et1'>");
			$(".modal-cover.et1").animate({"opacity":1},200);
		}

		$(".modal-cover.et1").off().on("click",function(){
			gnbFn.close();
		});
	},
    close: function(s) {
	  if(!mobileSizeFlag()){}else{
		  $("body").off("touchmove.lnb");
	   }


	  if(s != "switch"){
		$(".modal-cover").animate({"opacity":0},100,function(){$(this).remove(); });
	  }
      openModelPopup = null;
	}
};


//탭
function tab(o,s){
  $obj  = $(o);

  $obj.each(function(){
    var $this = $(this);
    var $total = $this.find("li").length;
    var $first = s-1;
    var $prev = $first;
    var tab_id = new Array();
    var $btn = $this.find("li");
    var $start = $btn.eq($first);

    for( var i=0; i<$total; i++){
      tab_id[i] = $btn.eq(i).find("a").attr("href");
      $(tab_id[i]).css("display","none");
      $(tab_id[$first]).css("display","block");
    }

    $start.addClass("on");

   $btn.bind("click",function(){
    var $this = $(this);
    var $index = $(this).index();

    if(!$this.hasClass("link")){
          if(!$this.hasClass("on")){
           $btn.each(function(){
            $(this).removeClass("on");
           });
           $this.addClass("on");
           $(tab_id[$prev]).css("display","none");
           $(tab_id[$index]).css("display","block");
           $prev = $index;
        }
        $this.trigger("resize");

        return false;

    }
   });

  });//each
}//tab


//메인 -공지사항 :자동 슬라이딩 및 버튼 제어
function tickerTit(e,s) {
	var obj = $(e);
	var tickerHeight = obj.find('li').height();
	var ticker = function() {
		timer = setTimeout(function(){
			if (obj.find('li').length > 1 ){
				obj.find('li:first-child').animate( {marginTop: -tickerHeight}, 400, function() {
					$(this).detach().appendTo(obj).removeAttr('style');
				});
				ticker();
			}
		}, s);
	};
	ticker();
}




//팝업쿠키
function closePopup(e) {
	$(e).closest('.mainPopup').hide();

	if($(".mainPopup:visible").length < 1){
		$(".wrap_mainPopup").hide();
		$("html").css("overflow","");
	} else {
		$("html").css("overflow","hidden");
	}
}


function getCookie( name ) {
   var nameOfCookie = name + "=";
   var x = 0;
   while ( x <= document.cookie.length )
   {
       var y = (x+nameOfCookie.length);
       if ( document.cookie.substring( x, y ) == nameOfCookie ) {
           if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 )
               endOfCookie = document.cookie.length;
           return unescape( document.cookie.substring( y, endOfCookie ) );
       }
       x = document.cookie.indexOf( " ", x ) + 1;
       if ( x == 0 )
           break;
   }
   return "";
}

function setCookie( name, value, expiredays ) {
   var todayDate = new Date();
   todayDate.setDate( todayDate.getDate() + expiredays );
   document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function setCookieAt00( name, value, expiredays ) {
	var todayDate = new Date();
	todayDate = new Date(parseInt(todayDate.getTime() / 86400000) * 86400000 + 54000000);
	if ( todayDate > new Date() )  {
		expiredays = expiredays - 1;
	}
	todayDate.setDate( todayDate.getDate() + expiredays );
	document.cookie = name + "=" + escape( value ) + "; path=/; expires=" + todayDate.toGMTString() + ";"
}

function closePopupNotToday1(e){
	setCookieAt00('notToday'+e ,'Y', 1);
}


$(function () {
	//메뉴
	gnbFn.init();

	//하단 공지사항
	//tickerTit(".foo_notice ul", 5000);

	//top 버튼 클릭시 이벤트
	$(".scroll_top").click(function (){
		$("html, body").animate({scrollTop:0}, 500);
	});


	//말줄임
	if ($('.t-dot').length > 0)	{
	 $('.t-dot').dotdotdot({
		  ellipsis: '...',//말줄임 뭘로 할지
		  watch : true, //윈도우 창에따라서 업데이트 할건지, 윈도우가 리사이즈될 때 업데이트할 건지
		  wrap : 'letter',//word(단어단위), letter(글 단위), children(자식단위) 자르기
		  tolerance : 0 //글이 넘치면 얼만큼 height 늘릴건지
	  });
	}


	if ($("input.calender").length > 0){
		//달력
		$.datepicker.regional['ko'] = {
			closeText: '닫기',
			prevText: '이전달',
			nextText: '다음달',
			currentText: '오늘',
			monthNames: ['.01','.02','.03','.04','.05','.06','.07','.08','.09','.10','.11','.12'],
			monthNamesShort: ['01월','02월','03월','04월','05월','06월','07월','08월','09월','10월','11월','12월'],
			dayNames: ['일','월','화','수','목','금','토'],
			dayNamesShort: ['일','월','화','수','목','금','토'],
			dayNamesMin: ['일','월','화','수','목','금','토'],
			weekHeader: 'Wk',
			dateFormat: 'yy-mm-dd',
			firstDay: 0,
			isRTL: false,
			showMonthAfterYear: true,
			//showButtonPanel:true,
			showOn: "both",
			buttonImage: "images/common/ico_calender.png",
			closeText:'취소',
			onClose: function () {
				if ($(window.event.srcElement).hasClass('ui-datepicker-close')) {
					$(this).val('');
				}
			}
		};
		$.datepicker.setDefaults($.datepicker.regional['ko']);


		//달력
		$('.datepicker_first').datepicker({
			dateFormat: "yy-mm-dd",
			showOtherMonths: true,
			showOn: "both",
			buttonImage: "../images/common/ico_calender.png",
			onClose: function(selectedDate) {
				$(".datepicker_last").datepicker("option", "minDate", selectedDate);
			}
		});
		$('.datepicker_last').datepicker({
			dateFormat: "yy-mm-dd",
			showOtherMonths: true,
			showOn: "both",
			buttonImage: "../images/common/ico_calender.png",
			onClose: function(selectedDate) {
				$(".datepicker_first").datepicker("option", "maxDate", selectedDate);
			}
		});
		$('.datepicker').datepicker({
			dateFormat: "yy-mm-dd",
			showOn: "both",
			buttonImage: "../images/common/ico_calender.png"
		});
		$('.datepicker2').datepicker({
			dateFormat: "yy-mm-dd",
			showOn: "both",
			buttonImage: "images/common/ico_select.png"
		});
	}

	// 테이블 내용 있을때에 클릭시 바로 보이게
	$(document).on("click", ".tr_q", function(){
		if ($(this).next('.tr_a').is(':hidden')){
			$('.tr_a').hide();
			$(this).addClass("on").next('.tr_a').show();
		} else {
			$('.tr_a').hide();
		}
	});
});



//윈도우 팝업
function popupOpen(url,id,w,h,r,s){
  var popId = id;
  var popUrl = url;
  var l = (window.screen.width - w)/2;
  var t = (window.screen.height - h)/2 - 50;
  var resize = (r==undefined) ? "no" : r;
  var scroll = (s==undefined) ? "no" : s;
  var popOption = "width="+w+", height="+h+", left="+l+", top="+t+", resizable="+resize+", scrollbars=1;";    //팝업창 옵션

  window.open(popUrl,id,popOption);
};



//팝업 열기
/*
function openLayerPopup(id){
	var $obj = $("#" +id);
	var $winH = $(window).width();
	var $objWidht = $obj.outerWidth();
	var $objHeight = $obj.outerHeight();
	var scrollTop = $(window).scrollTop();

	$obj.css({
		"margin-left" : -($objWidht/2),
		"margin-top" : -($objHeight/2)
	});

	$(".layerPopup").hide();
	$("#" +id).fadeIn(300);
	$("#" +id).before("<div class='popup_dim'></div>");


	$(".b-close, .popup_dim").on("click", function(){
		$obj.fadeOut(100);
		$(".popup_dim").remove();
	});
}*/


//팝업 열기
function openLayerPopup(id){
	var $obj = $("#" +id);
	var $winH = $(window).width();
	var $objwidth = $obj.width();
	var $objHeight = $obj.height();
	var scrollTop = $(window).scrollTop();


	$("#"+id).bPopup({
		closeClass : "b-close",
		onClose: function() {
			$(".b-modal").remove();
		}
	});
}




/*
 *	jQuery dotdotdot 1.6.1
 *
 *	Copyright (c) 2013 Fred Heusschen
 *	www.frebsite.nl
 *
 *	Plugin website:
 *	dotdotdot.frebsite.nl
 *
 *	Dual licensed under the MIT and GPL licenses.
 *	http://en.wikipedia.org/wiki/MIT_License
 *	http://en.wikipedia.org/wiki/GNU_General_Public_License
 */

!function(a){function c(a,b,c){var d=a.children(),e=!1;a.empty();for(var g=0,h=d.length;h>g;g++){var i=d.eq(g);if(a.append(i),c&&a.append(c),f(a,b)){i.remove(),e=!0;break}c&&c.detach()}return e}function d(b,c,g,h,i){var j=b.contents(),k=!1;b.empty();for(var l="table, thead, tbody, tfoot, tr, col, colgroup, object, embed, param, ol, ul, dl, blockquote, select, optgroup, option, textarea, script, style",m=0,n=j.length;n>m&&!k;m++){var o=j[m],p=a(o);"undefined"!=typeof o&&(b.append(p),i&&b[b.is(l)?"after":"append"](i),3==o.nodeType?f(g,h)&&(k=e(p,c,g,h,i)):k=d(p,c,g,h,i),k||i&&i.detach())}return k}function e(a,b,c,d,h){var k=!1,l=a[0];if("undefined"==typeof l)return!1;for(var m=j(l),n=-1!==m.indexOf(" ")?" ":"\u3000",o="letter"==d.wrap?"":n,p=m.split(o),q=-1,r=-1,s=0,t=p.length-1;t>=s&&(0!=s||0!=t);){var u=Math.floor((s+t)/2);if(u==r)break;r=u,i(l,p.slice(0,r+1).join(o)+d.ellipsis),f(c,d)?t=r:(q=r,s=r),t==s&&0==t&&d.fallbackToLetter&&(o="",p=p[0].split(o),q=-1,r=-1,s=0,t=p.length-1)}if(-1==q||1==p.length&&0==p[0].length){var v=a.parent();a.remove();var w=h?h.length:0;if(v.contents().size()>w){var x=v.contents().eq(-1-w);k=e(x,b,c,d,h)}else{var y=v.prev(),l=y.contents().eq(-1)[0];if("undefined"!=typeof l){var m=g(j(l),d);i(l,m),h&&y.append(h),v.remove(),k=!0}}}else m=g(p.slice(0,q+1).join(o),d),k=!0,i(l,m);return k}function f(a,b){return a.innerHeight()>b.maxHeight}function g(b,c){for(;a.inArray(b.slice(-1),c.lastCharacter.remove)>-1;)b=b.slice(0,-1);return a.inArray(b.slice(-1),c.lastCharacter.noEllipsis)<0&&(b+=c.ellipsis),b}function h(a){return{width:a.innerWidth(),height:a.innerHeight()}}function i(a,b){a.innerText?a.innerText=b:a.nodeValue?a.nodeValue=b:a.textContent&&(a.textContent=b)}function j(a){return a.innerText?a.innerText:a.nodeValue?a.nodeValue:a.textContent?a.textContent:""}function k(b,c){return"undefined"==typeof b?!1:b?"string"==typeof b?(b=a(b,c),b.length?b:!1):"object"==typeof b?"undefined"==typeof b.jquery?!1:b:!1:!1}function l(a){for(var b=a.innerHeight(),c=["paddingTop","paddingBottom"],d=0,e=c.length;e>d;d++){var f=parseInt(a.css(c[d]),10);isNaN(f)&&(f=0),b-=f}return b}function m(a,b){return a?(b="string"==typeof b?"dotdotdot: "+b:["dotdotdot:",b],"undefined"!=typeof window.console&&"undefined"!=typeof window.console.log&&window.console.log(b),!1):!1}if(!a.fn.dotdotdot){a.fn.dotdotdot=function(e){if(0==this.length)return e&&e.debug===!1||m(!0,'No element found for "'+this.selector+'".'),this;if(this.length>1)return this.each(function(){a(this).dotdotdot(e)});var g=this;g.data("dotdotdot")&&g.trigger("destroy.dot"),g.data("dotdotdot-style",g.attr("style")),g.css("word-wrap","break-word"),"nowrap"===g.css("white-space")&&g.css("white-space","normal"),g.bind_events=function(){return g.bind("update.dot",function(b,e){b.preventDefault(),b.stopPropagation(),j.maxHeight="number"==typeof j.height?j.height:l(g),j.maxHeight+=j.tolerance,"undefined"!=typeof e&&(("string"==typeof e||e instanceof HTMLElement)&&(e=a("<div />").append(e).contents()),e instanceof a&&(i=e)),q=g.wrapInner('<div class="dotdotdot" />').children(),q.empty().append(i.clone(!0)).css({height:"auto",width:"auto",border:"none",padding:0,margin:0});var h=!1,k=!1;return n.afterElement&&(h=n.afterElement.clone(!0),n.afterElement.remove()),f(q,j)&&(k="children"==j.wrap?c(q,j,h):d(q,g,q,j,h)),q.replaceWith(q.contents()),q=null,a.isFunction(j.callback)&&j.callback.call(g[0],k,i),n.isTruncated=k,k}).bind("isTruncated.dot",function(a,b){return a.preventDefault(),a.stopPropagation(),"function"==typeof b&&b.call(g[0],n.isTruncated),n.isTruncated}).bind("originalContent.dot",function(a,b){return a.preventDefault(),a.stopPropagation(),"function"==typeof b&&b.call(g[0],i),i}).bind("destroy.dot",function(a){a.preventDefault(),a.stopPropagation(),g.unwatch().unbind_events().empty().append(i).attr("style",g.data("dotdotdot-style")).data("dotdotdot",!1)}),g},g.unbind_events=function(){return g.unbind(".dot"),g},g.watch=function(){if(g.unwatch(),"window"==j.watch){var b=a(window),c=b.width(),d=b.height();b.bind("resize.dot"+n.dotId,function(){c==b.width()&&d==b.height()&&j.windowResizeFix||(c=b.width(),d=b.height(),p&&clearInterval(p),p=setTimeout(function(){g.trigger("update.dot")},10))})}else o=h(g),p=setInterval(function(){var a=h(g);(o.width!=a.width||o.height!=a.height)&&(g.trigger("update.dot"),o=h(g))},100);return g},g.unwatch=function(){return a(window).unbind("resize.dot"+n.dotId),p&&clearInterval(p),g};var i=g.contents(),j=a.extend(!0,{},a.fn.dotdotdot.defaults,e),n={},o={},p=null,q=null;return j.lastCharacter.remove instanceof Array||(j.lastCharacter.remove=a.fn.dotdotdot.defaultArrays.lastCharacter.remove),j.lastCharacter.noEllipsis instanceof Array||(j.lastCharacter.noEllipsis=a.fn.dotdotdot.defaultArrays.lastCharacter.noEllipsis),n.afterElement=k(j.after,g),n.isTruncated=!1,n.dotId=b++,g.data("dotdotdot",!0).bind_events().trigger("update.dot"),j.watch&&g.watch(),g},a.fn.dotdotdot.defaults={ellipsis:"... ",wrap:"word",fallbackToLetter:!0,lastCharacter:{},tolerance:0,callback:null,after:null,height:null,watch:!1,windowResizeFix:!0,debug:!1},a.fn.dotdotdot.defaultArrays={lastCharacter:{remove:[" ","\u3000",",",";",".","!","?"],noEllipsis:[]}};var b=1,n=a.fn.html;a.fn.html=function(a){return"undefined"!=typeof a?this.data("dotdotdot")&&"function"!=typeof a?this.trigger("update",[a]):n.call(this,a):n.call(this)};var o=a.fn.text;a.fn.text=function(b){if("undefined"!=typeof b){if(this.data("dotdotdot")){var c=a("<div />");return c.text(b),b=c.html(),c.remove(),this.trigger("update",[b])}return o.call(this,b)}return o.call(this)}}}(jQuery);



//팝업처리
(function(c){c.fn.bPopup=function(A,E){function L(){a.contentContainer=c(a.contentContainer||b);switch(a.content){case "iframe":var d=c('<iframe class="b-iframe" '+a.iframeAttr+"></iframe>");d.appendTo(a.contentContainer);t=b.outerHeight(!0);u=b.outerWidth(!0);B();d.attr("src",a.loadUrl);l(a.loadCallback);break;case "image":B();c("<img />").load(function(){l(a.loadCallback);F(c(this))}).attr("src",a.loadUrl).hide().appendTo(a.contentContainer);break;default:B(),c('<div class="b-ajax-wrapper"></div>').load(a.loadUrl,a.loadData,function(d,b,e){l(a.loadCallback,b);F(c(this))}).hide().appendTo(a.contentContainer)}}function B(){a.modal&&c('<div class="b-modal '+e+'"></div>').css({backgroundColor:a.modalColor,position:"fixed",top:0,right:0,bottom:0,left:0,opacity:0,zIndex:a.zIndex+v}).appendTo(a.appendTo).fadeTo(a.speed,a.opacity);C();b.data("bPopup",a).data("id",e).css({left:"slideIn"==a.transition||"slideBack"==a.transition?"slideBack"==a.transition?f.scrollLeft()+w:-1*(x+u):m(!(!a.follow[0]&&n||g)),position:a.positionStyle||"absolute",top:"slideDown"==a.transition||"slideUp"==a.transition?"slideUp"==a.transition?f.scrollTop()+y:z+-1*t:p(!(!a.follow[1]&&q||g)),"z-index":a.zIndex+v+1}).each(function(){a.appending&&c(this).appendTo(a.appendTo)});G(!0)}function r(){a.modal&&c(".b-modal."+b.data("id")).fadeTo(a.speed,0,function(){c(this).remove()});a.scrollBar||c("html").css("overflow","auto");c(".b-modal."+e).unbind("click");f.unbind("keydown."+e);k.unbind("."+e).data("bPopup",0<k.data("bPopup")-1?k.data("bPopup")-1:null);b.undelegate(".bClose, ."+a.closeClass,"click."+e,r).data("bPopup",null);clearTimeout(H);G();return!1}function I(d){y=k.height();w=k.width();h=D();if(h.x||h.y)clearTimeout(J),J=setTimeout(function(){C();d=d||a.followSpeed;var e={};h.x&&(e.left=a.follow[0]?m(!0):"auto");h.y&&(e.top=a.follow[1]?p(!0):"auto");b.dequeue().each(function(){g?c(this).css({left:x,top:z}):c(this).animate(e,d,a.followEasing)})},50)}function F(d){var c=d.width(),e=d.height(),f={};a.contentContainer.css({height:e,width:c});e>=b.height()&&(f.height=b.height());c>=b.width()&&(f.width=b.width());t=b.outerHeight(!0);u=b.outerWidth(!0);C();a.contentContainer.css({height:"auto",width:"auto"});f.left=m(!(!a.follow[0]&&n||g));f.top=p(!(!a.follow[1]&&q||g));b.animate(f,250,function(){d.show();h=D()})}function M(){k.data("bPopup",v);b.delegate(".bClose, ."+a.closeClass,"click."+e,r);a.modalClose&&c(".b-modal."+e).css("cursor","pointer").bind("click",r);N||!a.follow[0]&&!a.follow[1]||k.bind("scroll."+e,function(){if(h.x||h.y){var d={};h.x&&(d.left=a.follow[0]?m(!g):"auto");h.y&&(d.top=a.follow[1]?p(!g):"auto");b.dequeue().animate(d,a.followSpeed,a.followEasing)}}).bind("resize."+e,function(){I()});a.escClose&&f.bind("keydown."+e,function(a){27==a.which&&r()})}function G(d){function c(e){b.css({display:"block",opacity:1}).animate(e,a.speed,a.easing,function(){K(d)})}switch(d?a.transition:a.transitionClose||a.transition){case "slideIn":c({left:d?m(!(!a.follow[0]&&n||g)):f.scrollLeft()-(u||b.outerWidth(!0))-200});break;case "slideBack":c({left:d?m(!(!a.follow[0]&&n||g)):f.scrollLeft()+w+200});break;case "slideDown":c({top:d?p(!(!a.follow[1]&&q||g)):f.scrollTop()-(t||b.outerHeight(!0))-200});break;case "slideUp":c({top:d?p(!(!a.follow[1]&&q||g)):f.scrollTop()+y+200});break;default:b.stop().fadeTo(a.speed,d?1:0,function(){K(d)})}}function K(d){d?(M(),l(E),a.autoClose&&(H=setTimeout(r,a.autoClose))):(b.hide(),l(a.onClose),a.loadUrl&&(a.contentContainer.empty(),b.css({height:"auto",width:"auto"})))}function m(a){return a?x+f.scrollLeft():x}function p(a){return a?z+f.scrollTop():z}function l(a,e){c.isFunction(a)&&a.call(b,e)}function C(){z=q?a.position[1]:Math.max(0,(y-b.outerHeight(!0))/2-a.amsl);x=n?a.position[0]:(w-b.outerWidth(!0))/2;h=D()}function D(){return{x:w>b.outerWidth(!0),y:y>b.outerHeight(!0)}}c.isFunction(A)&&(E=A,A=null);var a=c.extend({},c.fn.bPopup.defaults,A);a.scrollBar||c("html").css("overflow","hidden");var b=this,f=c(document),k=c(window),y=k.height(),w=k.width(),N=/OS 6(_\d)+/i.test(navigator.userAgent),v=0,e,h,q,n,g,z,x,t,u,J,H;b.close=function(){r()};b.reposition=function(a){I(a)};return b.each(function(){c(this).data("bPopup")||(l(a.onOpen),v=(k.data("bPopup")||0)+1,e="__b-popup"+v+"__",q="auto"!==a.position[1],n="auto"!==a.position[0],g="fixed"===a.positionStyle,t=b.outerHeight(!0),u=b.outerWidth(!0),a.loadUrl?L():B())})};c.fn.bPopup.defaults={amsl:50,appending:!0,appendTo:"body",autoClose:!1,closeClass:"b-close",content:"ajax",contentContainer:!1,easing:"swing",escClose:!0,follow:[!0,!0],followEasing:"swing",followSpeed:500,iframeAttr:'scrolling="no" frameborder="0"',loadCallback:!1,loadData:!1,loadUrl:!1,modal:!0,modalClose:!0,modalColor:"#000",onClose:!1,onOpen:!1,opacity:.7,position:["auto","auto"],positionStyle:"absolute",scrollBar:!0,speed:250,transition:"fadeIn",transitionClose:!1,zIndex:9997}})(jQuery);


