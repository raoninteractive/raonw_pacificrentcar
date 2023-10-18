//비동기 기본 셋팅
$.ajaxSetup({
	type: "POST",
	dataType: "json",
	async: true,
	beforeSend: function() { ajaxStatus=true; },
	complete: function() { ajaxStatus=false; },
	error: function(xhr, status, error){
		var errMsg  = "";
			errMsg += "☆★PROCESS ERROR★☆\n";
			if (xhr.statusText) {
				errMsg += "[ERROR STATUS: "+xhr.status+"] " + xhr.statusText + "\n";
			}

			if (xhr.responseText) {
				errMsg += "[ERROR MESSAGE]\n";
				errMsg += "==============================================\n\n";
				errMsg += xhr.responseText;
				errMsg += "\n\n==============================================";
			}

		alert("데이터 처리간 오류가 발생되었습니다." + errMsg);
		console.log(errMsg);

		ajaxStatus=false;
	}
});


//Ajax실행 체크
function ajaxStatusCheck() {
	if (ajaxStatus) {
		alert("이전작업을 처리중에 있습니다.\n잠시만 기다려주세요.");
		return false;
	}

	return true;
}

//Enter시 이벤트 실행
function enters(fnc){
	if (event.keyCode == 13) {
		fnc();
	}
}


//문자열 실제 길이 반환(한글2byte, 영문숫자 1byte)
//오브젝트 선택방식
function returnToByte(obj, checkmb){
	var val = document.getElementById(obj).value;
	var tmp_chr = null;
	var len = 0

	if (val != "") {
		for (var i = 0;i < val.length;i++) {
			tmp_chr = val.charAt(i);

			if (!checkmb) checkmb = true;
			if (checkmb == true) {
				if (escape(tmp_chr).length > 4) {
					len += 2;
				} else {
					len += 1;
				}
			} else {
				len += 1;
			}
		}
	}

	return len;
}

//문자열 실제 길이 반환(한글2byte, 영문숫자 1byte)
//텍스트 입력방식
function returnToByte2(val, checkmb){
	var tmp_chr = null;
	var len = 0

	if (val != "") {
		for (var i = 0;i < val.length;i++) {
			tmp_chr = val.charAt(i);

			if (!checkmb) checkmb = true;
			if (checkmb == true) {
				if (escape(tmp_chr).length > 4) {
					len += 2;
				} else {
					len += 1;
				}
			} else {
				len += 1;
			}
		}
	}

	return len;
}


//문자열이 길이 만큼 문자열을 잘라서 반환 (한글2byte, 영문숫자 1byte)
function returnToCut(val, bytes, checkmb) {
	var return_str = '';
	var tmp_chr = null;
	var len = 0;

	if (val != "") {
		for (var i = 0;i < val.length;i++) {
			tmp_chr = val.charAt(i);

			if (!checkmb) checkmb = true;
			if (checkmb == true) {
				if (escape(tmp_chr).length > 4) {
					len += 2;
				} else {
					len += 1;
				}
			} else {
				len += 1;
			}

			if (bytes >= len) {
				return_str += tmp_chr;
			} else {
				return return_str;
			}
		}
	}

	return return_str;
}



//핸드폰번호 체크
function phoneRegExpCheck(phoneNum, txt, types){
	if (types == "-") {
		var patternPhone = /^0(10|11|16|17|18|19)-[0-9]{1}[0-9]{2,3}-[0-9]{4}$/;
		var patternTypeTxt = "010-1234-5678";
	} else {
		var patternPhone = /^0(10|11|16|17|18|19)[0-9]{1}[0-9]{2,3}[0-9]{4}$/;
		var patternTypeTxt = "01012345678";
	}

	if(!patternPhone.test(phoneNum)) {
		if (txt) {
			alert(txt + ' 형식이 올바르지 않습니다.\n' + txt + '를 다시 확인해주세요.\n\n※올바른 형식 : ' + patternTypeTxt);
		}
		return false;
	}

	return true;
}


//일반전화 체크
function telRegExpCheck(telNum, txt, types){
	if (types == "-") {
		var patternTel = /^0(2|31|33|32|42|43|41|44|50|53|54|55|52|51|60|63|61|62|64|70|303|505)-[0-9]{1}[0-9]{2,3}-[0-9]{4}$/;		//일반전화
		var patternTel2 = /^1(544|644|661|800|833|522|566|600|670|599|688|666|877|855|577|588|899)-[0-9]{4}$/;			//대표전화
		var patternTypeTxt = "02-1234-5678";
	} else {
		var patternTel = /^0(2|31|33|32|42|43|41|44|50|53|54|55|52|51|60|63|61|62|64|70|303|505)[0-9]{1}[0-9]{2,3}[0-9]{4}$/;
		var patternTel2 = /^1(544|644|661|800|833|522|566|600|670|599|688|666|877|855|577|588|899)[0-9]{4}$/;			//대표전화
		var patternTypeTxt = "0212345678";
	}

	if(!patternTel.test(telNum) && !patternTel2.test(telNum)) {
		if (txt) {
			alert(txt + ' 형식이 올바르지 않습니다.\n' + txt + '를 다시 확인해주세요.\n\n※올바른 형식 : ' + patternTypeTxt);
		}
		return false;
	}

	return true;
}

//사업자번호 체크
function bizRegNumExpCheck(bizRegNum) {
	var pattern = /[0-9]{3}-[0-9]{2}-[0-9]{5}$/;
	if(!pattern.test(bizRegNum)) {
		alert('사업자번호 형식이 올바르지 않습니다.\n사업자번호를 다시 확인해주세요.\n\n※올바른 형식 : 123-45-67890');
		return false;
	}


	// 넘어온 값의 정수만 추츨하여 문자열의 배열로 만들고 10자리 숫자인지 확인합니다.
	if ((bizRegNum = (bizRegNum+'').match(/\d{1}/g)).length != 10) {
		alert("사업자번호가 올바르지 않습니다.");
		return false;
	}

	// 합 / 체크키
	var sum = 0, key = [1, 3, 7, 1, 3, 7, 1, 3, 5];

	// 0 ~ 8 까지 9개의 숫자를 체크키와 곱하여 합에더합니다.
	for (var i = 0 ; i < 9 ; i++) { sum += (key[i] * Number(bizRegNum[i])); }

	// 각 8번배열의 값을 곱한 후 10으로 나누고 내림하여 기존 합에 더합니다.
	// 다시 10의 나머지를 구한후 그 값을 10에서 빼면 이것이 검증번호 이며 기존 검증번호와 비교하면됩니다.
	var result = (10 - ((sum + Math.floor(key[8] * Number(bizRegNum[8]) / 10)) % 10)) == Number(bizRegNum[9]);
	if (!result) {
		alert("유효하지 않는 사업자 번호 입니다.\n사업자번호를 다시 확인해주세요.");
		return false;
	}

	return true;
}


//날짜유효성 체크
function isDateCheck(val, txt, types) {

	if (types == "-") {
		var result = moment(val, "YYYY-MM-DD").isValid();
		var typeTxt = "2020-01-01";
	} else {
		var result = moment(val, "YYYYMMDD").isValid();
		var typeTxt = "20200101";
	}

	if (!result && txt) {
		alert(txt + ' 형식이 올바르지 않습니다.\n' + txt + '을 다시 확인해주세요.\n\n※올바른 형식 : ' + typeTxt);
	}

	return result;
}

//스팸방지코드
function spamImageChange(){
	var now = new Date();
	$("#spam_img").attr("src", $("#spam_img").attr("src").split('?')[0] + '?x=' + now.toUTCString());
}


//쿠키저장
function setCookie(name,value,expiredays) {
	var today = new Date();

	if (expiredays != "") {
		today.setDate(today.getDate()+expiredays);
		document.cookie = name + "=" + escape(value) + "; path=/; expires=" + today.toGMTString() + ";";
	} else {
		document.cookie = name + "=" + escape(value) + "; path=/;";
	}
}


//쿠키불러오기
function getCookie( name ) {
   var nameOfCookie = name + "=";
   var x = 0;
   while ( x <= document.cookie.length ) {
	   var y = (x+nameOfCookie.length);

	   if ( document.cookie.substring( x, y ) == nameOfCookie ) {
			if ( (endOfCookie=document.cookie.indexOf( ";", y )) == -1 ) {
				endOfCookie = document.cookie.length;
			}
			return unescape( document.cookie.substring( y, endOfCookie ) );
	   }
	   x = document.cookie.indexOf( " ", x ) + 1;
	   if ( x == 0 ) break;
   }

   return "";
}


//모바일 앱 체크
var mobileAppCheck = function(){
	var ua, webViewName, os, v, version;
	var toString = function(str){
		return (str == null || str == "") ? "" : str.toString();
	}
	ua = window.navigator.userAgent;
	webViewName = ua.toLowerCase().match(/(webview)_(android|ios|iphone)/g);
	os = toString( toString(webViewName).match(/android|ios|iphone/g) );
	version = toString( toString( ua.match(/version(([0-9].{1,}\d+)?)/g) ).match(/[0-9].{1,}\d+?$/g) );
	return{
		isApp : (webViewName == null) ? false : true
		, appInfo:{ "os" : os, "version" : version }
		, version : version
		, appOs : function(){
			if(os == "iphone"){
				os = "ios";
			}
			return os;
		}

		, isAndroidApp : (os == "android")
		, isIosApp : (os == "ios")
		, isMobile : /iPhone|iPod|iPad|Android|Windows CE|BlackBerry|Symbian|Windows Phone|webOS|Opera Mini|Opera Mobi|POLARIS|IEMobile|lgtelecom|nokia|SonyEricsson/i.test(ua)
		, isAndroidBrowser : /Android/i.test(ua)
		, isIosBrowser : /iPhone|iPod|iPad/i.test(ua)
	}
}


//이미지 리사이즈정보 리턴 (가로|세로)
function imgThumbResize(imgWidth, imgHeight, maxWidth, maxHeight){
	if (imgWidth > maxWidth){
		ratio = maxWidth / imgWidth;

		imgWidth = maxWidth;
		imgHeight = parseInt(imgHeight * ratio);
	}

	if (imgHeight > maxHeight){
		ratio = maxHeight / imgHeight;

		imgHeight = maxHeight;
		imgWidth = parseInt(imgWidth * ratio);
	}

	strImgSize = imgWidth +"|"+ imgHeight;

	return strImgSize;
}


//이미지 미리보기 팝업호출
function imgPreviwePopupOpen(file_path){
	var imgFile = "<img src=\""+file_path+"\" onclick=\"self.close()\">"
	var objImg = new Image();
		objImg.src = file_path;

		objImg.onload = function(){
			var imgWidth = objImg.width;
			var imgHeight = objImg.height;

			var resizing = imgThumbResize(imgWidth, imgHeight, $(document).width()-180, $(window).height()-180);
			imgWidth  = resizing.split("|")[0];
			imgHeight = resizing.split("|")[1];

			var left = (screen.width/2)-(imgWidth/2);
			var top = (screen.height/2)-(imgHeight/2);

			var look="width="+imgWidth+",height="+imgHeight +",top="+top+",left="+left;
			popwin=window.open("","img_pop",look)
			popwin.document.open()
			popwin.document.write("<title>미리보기</title><body topmargin=0 leftmargin=0>"+imgFile+"</body>")
			popwin.document.close()
		}
}

//만나이 계산
function calcAge(birth) {
	var date = new Date();
	var year = date.getFullYear();
	var month = (date.getMonth() + 1);
	var day = date.getDate();
	if (month < 10) month = '0' + month;
	if (day < 10) day = '0' + day;
	var monthDay = month + day;

	birth = birth.replace('-', '').replace('-', '');
	if (birth != "" && birth.length == 8) {
		var birthdayy = birth.substr(0, 4);
		var birthdaymd = birth.substr(4, 4);

		age = monthDay < birthdaymd ? year - birthdayy - 1 : year - birthdayy;
	} else {
		age = "";
	}

	return age;
}

function addZero(val) {
	val = val.toString();
	return val.length < 2 ? '0'+val : val;
}


//윈도우 팝업
function popupOpen(url,id,w,h,r,s){
	var popId = id;
	var popUrl = url;
	var screenW = window.screen.width;
	var screenH = window.screen.height;
	var w = (w==undefined) ? screenW : w;
	var h = (h==undefined) ? screenH : h;
	var r = (r==undefined) ? 'yes' : r;
	var s = (s==undefined) ? '1' : s;
	var lft = (screenW - w)/2;
	var t = (screenH - h)/2 -50;
	var popOption = "width="+w+", height="+h+", left="+lft+", top="+t+", resizable="+r+", scrollbars="+s+";";//팝업창 옵션

	var pop = window.open("",id,popOption);
	pop.location.href = "/toss.html?url=" + encodeURIComponent(popUrl);
}


//다음 우편번호 검색 팝업호출
function postCode(postcode, addr, addr_detail) {
	var popupType = "L";
	var width = $(window).width() - 50;			//우편번호서비스가 들어갈 element의 width
	var height = $(window).height() - 100;		//우편번호서비스가 들어갈 element의 height
	var borderWidth = 5;						//샘플에서 사용하는 border의 두께
	var borderColor = "#cdcdcd";

	if (width > 600) width = 500;				//풀사이즈 100%
	if (height > 600) height = 600;				//풀사이즈 100%

	$("#addr_layer_popup").remove();
	var closeBox = $("<span>X</span>").css({
				"width": "20px",
				"height": "20px",
				"line-height": "20px",
				"font-weight": "bold",
				"text-align": "center",
				"color": "#fff",
				"background": borderColor,
				"cursor": "pointer",
				"position": "absolute",
				"right": "-"+borderWidth+"px",
				"top": "-"+borderWidth+"px",
				"z-index": "100000"
			}).click(function(){
				$('#addr_layer_popup').remove()
				if (addr) {
					document.getElementById(addr).focus();
				}
			});

	$("<div id=\"addr_layer_popup\"></div>").html(closeBox)
		.css({
			"position":"absolute",
			"top":0,
			"left":0 ,
			"overflow":"hidden",
			"z-index":"200",
			"-webkit-overflow-scrolling":"touch",
			"width":width,
			"height": height,
			"border": borderWidth+"px solid " + borderColor
		})
		.appendTo(document.body).hide();

	var element_layer = document.getElementById("addr_layer_popup");

	var clsDaumPostCode = new daum.Postcode({
		oncomplete: function(data) {
			// 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

			// 도로명 주소의 노출 규칙에 따라 주소를 조합한다.
			// 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
			var checkAddr = '';	// 주소 변수
			var extraAddr = ''; // 도로명 조합형 주소 변수

			//사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
			if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
				checkAddr = data.roadAddress;
			} else { // 사용자가 지번 주소를 선택했을 경우(J)
				checkAddr = data.jibunAddress;
			}

			// 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
			if(data.userSelectedType === 'R'){
				// 법정동명이 있을 경우 추가한다. (법정리는 제외)
				// 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
				if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
					extraAddr += data.bname;
				}
				// 건물명이 있고, 공동주택일 경우 추가한다.
				if(data.buildingName !== '' && data.apartment === 'Y'){
					extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
				}
				// 도로명, 지번 조합형 주소가 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
				if(extraAddr !== ''){
					extraAddr = ' (' + extraAddr + ')';
				}
				// 도로명, 지번 주소의 유무에 따라 해당 조합형 주소를 추가한다.
				if(checkAddr !== ''){
					checkAddr += extraAddr;
				}
			}

			// 우편번호와 주소 정보를 해당 필드에 넣는다.
			if (postcode) {
				document.getElementById(postcode).value = data.zonecode; //5자리 새우편번호 사용
			}

			if (addr) {
				document.getElementById(addr).value = checkAddr;
			}

			if (addr_detail) {
				document.getElementById(addr_detail).focus();
			}

			$("#addr_layer_popup").remove();
		},
		width : "100%",
		height : "100%"
	})

	if (popupType == "P") {
		clsDaumPostCode.open({
			popupName: "postcode_pop",
			left: (window.screen.width / 2) - (width / 2),
			top: (window.screen.height / 2) - (height / 2)
		});
	} else if (popupType == "L") {
		clsDaumPostCode.embed(element_layer);

		$("#addr_layer_popup").show();

		// 실행되는 순간의 화면 너비와 높이 값을 가져와서 중앙에 뜰 수 있도록 위치를 계산한다.
		element_layer.style.left = (((window.innerWidth || document.documentElement.clientWidth) - width)/2 - borderWidth) + 'px';
		element_layer.style.top = (((window.innerHeight || document.documentElement.clientHeight) - height)/2 - borderWidth) + 'px';
	}
}


//기본도메인에 www형식으로 변환
var locationProtocol	= location.protocol + "//";
var locationPort		= location.port;
var locationHostname	= (location.hostname.indexOf("www.") > -1 || location.hostname.indexOf("m.") > -1) ? location.hostname : "www." + location.hostname;
var locationDomain		= locationProtocol + locationHostname;
var locationPath		= location.pathname;
var locationQueryString = location.href.split("?")[1]
	locationQueryString = (locationQueryString == undefined) ? '' : "?" + locationQueryString

if (location.hostname != locationHostname){
	//location.replace(locationDomain + locationPath + locationQueryString);
}


function dateDiff(datepart, fromdate, todate) {
	fromdate = fromdate.replace(/[^0-9]/gi,"");
	todate = todate.replace(/[^0-9]/gi,"");

	var input_date1 = new Date(fromdate.substr(0,4),fromdate.substr(4,2)-1,fromdate.substr(6,2));
	var input_date2 = new Date(todate.substr(0,4),todate.substr(4,2)-1,todate.substr(6,2));

	datepart = datepart.toLowerCase();
	var diff = input_date2 - input_date1;


	var divideBy = {
			w:604800000,
			d:86400000,
			h:3600000,
			n:60000,
			s:1000
		};

	return Math.floor( diff/divideBy[datepart]);
}

// dateAdd('2013-01-01', 'Y', 10)
// type : Y:년도 증가, M:월증가, D:일증가, 음수이면 감소
// return 2023-01-01
function dateAdd(dt, type, num){
	var tmpDate = new Date();
	var yy = parseInt(dt.substring(0,4));
	var mm = parseInt(dt.substring(5,7));
	var dd = parseInt(dt.substring(8,10));

	mm--;

	if(type.toUpperCase() == "Y"){
		yy = yy + parseInt(num);
	} else if (type.toUpperCase() == "M"){
		mm = mm + parseInt(num);
	} else if (type.toUpperCase() == "D"){
		dd = dd + parseInt(num);
	}

	tmpDate.setFullYear(yy, mm, dd);
	var newY = tmpDate.getFullYear();
	var newM = tmpDate.getMonth() + 1;
	var newD = tmpDate.getDate();
	newM = (parseInt(newM) < 10)? "0" + newM : newM;
	newD = (parseInt(newD) < 10)? "0" + newD : newD;

	return newY + "-" + newM + "-" + newD;
}


//지정금액 절삭처리 ( 금액, 타입, 절삭금액 단위 )
function priceCutting(aprice, stype, n) {
    // 원단위처리(R:반올림, C:올림, F:버림)
    var remove_price = 0;
    stype = stype ? stype : "R";
    remove_price = aprice / n;

    if(stype == "F") {
        remove_price = Math.floor(remove_price);
    } else if (stype == "R") {
        remove_price = Math.round(remove_price);
    } else if (stype == "C") {
        remove_price = Math.ceil(remove_price);
    }

    remove_price = remove_price * n;
    return remove_price;
}

//현재날짜및시간 리턴 (YYYYMMDDHH24MISS)
function nowTime(){
	var time  = new Date()
	//var ttime = time.toISOString()
	var year  = time.getFullYear()
	var month = ("0"+(time.getMonth()+1)).right(2);
	var day   = ("0"+time.getDate()).right(2);

	var hour   = ("0"+time.getHours()).right(2);
	var minute = ("0"+time.getMinutes()).right(2);
	var second = ("0"+time.getSeconds()).right(2);

	return year+""+month+""+day+""+hour+""+minute+""+second;
}

//byte 용량을 환산하여 반환
function getByte(fileSize, fixed) {
    var str;

    //MB 단위 이상일때 MB 단위로 환산
    if (fileSize >= 1024 * 1024) {
        fileSize = fileSize / (1024 * 1024);
        fileSize = (fixed === undefined) ? fileSize.toFixed(0) : fileSize.toFixed(fixed);
        str = fileSize.addComma() + 'MB';
    }
    //KB 단위 이상일때 KB 단위로 환산
    else if (fileSize >= 1024) {
        fileSize = fileSize / 1024;
        fileSize = (fixed === undefined) ? fileSize.toFixed(0) : fileSize.toFixed(fixed);
        str = fileSize.addComma() + 'KB';
    }
    //KB 단위보다 작을때 byte 단위로 환산
    else {
        fileSize = (fixed === undefined) ? fileSize.toFixed(0) : fileSize.toFixed(fixed);
        str = fileSize.addComma() + 'byte';
    }
    return str;
}

//캡챠 보안문자 입력 새로고침
function captchaImageChange(){
	var now = new Date();
	$(".captcha_img").attr("src", $(".captcha_img").attr("src").split('?')[0] + '?x=' + now.toUTCString());
}

//AJAX공통
var AJ = {
	config : {
		ajaxCheck : true,
		asyncCheck : true
	},
	callAjax : function($url, $params, $callback, $dataType, $type, $async) {
		if ($dataType == undefined) $dataType = "json";
		if ($type == undefined) $type = "post";
		if ($async == undefined) $async = this.config.asyncCheck;

		if ($async && !ajaxStatusCheck()) return false;

		$.ajax({
			type: $type,
			dataType: $dataType,
			url: $url,
			async: $async,
			beforeSend: function() { ajaxStatus=true; },
			data: $params
		}).done(function(data){
			ajaxStatus=false;

			$callback(data);

			try {
				selectboxInit();
				formSubmitInit();
				autoCompleteInit();
			} catch(e) {}
		})
	},
	ajaxForm : function($form, $url, $callback, $dataType, $async){
		if (this.config.ajaxCheck && !ajaxStatusCheck()) return false;
		if ($dataType == undefined) $dataType = "json";
		if ($async == undefined) $async = this.config.asyncCheck;

		var enctype = "x-www-form-urlencoded";
	    if ($form.attr("enctype")) enctype = $form.attr("enctype");

		$form.ajaxForm({
			dataType: $dataType,
			async: this.asyncCheck,
			beforeSend: function() { ajaxStatus=true; },
			success: function(data) {
				ajaxStatus=false;

				$callback(data);

				try {
					selectboxInit();
					formSubmitInit();
					autoCompleteInit();
				} catch(e) {}
			},
			complete: function (xhr) {
				ajaxStatus=false;

				var status = xhr.status;
				var responseText = xhr.responseText;
				var msg;

				if (status=="200" || status=="0") {
				} else {
					try {
						eval("responseText = "+responseText+"");
						msg = responseText.message;
					} catch(e) {
						console.log(e.message);
					}

					if (!msg) msg = "처리도중 오류가 발생하였습니다.";
					alert(msg);
				}
			}
		});

		$form.attr("method", "post");
		$form.attr("action", $url);
		$form.submit();

		$form.ajaxFormUnbind();
	}
}

$(function(){
	//모바일웹 input 태그에 maxlength 처리
	$(document).on("input", ":input", function(){
		var this_val = $(this).val();
		var this_len = this_val.length;
		var max_len  = $(this).attr("maxlength");

		if(this_len > max_len) {
			$(this).val( this_val.slice(0, max_len) );
		}
	})

	//INPUT 자동완성기능 끔
	function autoCompleteInit() {
		$(":input[type=text]").each(function(){
			$(this).attr("autocomplete", "off")
		})
	}

	//FORM 전송시 INPUT BOX 한개 있을경우 자동전송 막기위한 처리
	$("form").each(function(){
		if ($(this).find("input[id=tmpFormHack]").size() == 0) {
			$(this).prepend("<input type='text' id='tmpFormHack' style='display:none' />");
		}
	})
})