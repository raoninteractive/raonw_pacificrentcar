/*
	많이쓰는 keycode 정의
	e==8 : Backspace key
	e==9 : Tab key
	e==46 : Delete key
	e >= 48 && e <= 57 : 키보드 숫자키 0~9
	e >= 96 && e <= 105 : 키보드 오른쪽 숫자패드 0~9
	e==37 : 화살표 ←
	e==38 : 화살표 ↑
	e==39 : 화살표 →
	e==40 : 화살표 ↓
	e==189 : 키보드 숫자키 -(hyphen)
	e==109 : 키보드 오른쪽 숫자패드 -(hyphen)
	e==190 : 키보드 숫자키 .(콤마)
	e==110 : 키보드 오른쪽 숫자패드 .(콤마)

*/

/* 숫자키만 입력 받을수 있게 설정 */
function onlyNum(){
	$(document).on("focus blur", ".onlyNum", function(event){
		event.target.value = event.target.value.replace(/[^0-9]/g, "");
	})		
	$(".onlyNum").css("ime-mode", "disabled");
}

/* 숫자키,- 만 입력 받을수 있게 설정 */
function onlyNumHyphen(){
	$(document).on("focus blur", ".onlyNumHyphen", function(event){
		event.target.value = event.target.value.replace(/[^0-9-]/g, "");
	})	
	$(".onlyNumHyphen").css("ime-mode", "disabled");
}

/* 숫자키, .(콤마) 만 입력 받을수 있게 설정 */
function onlyNumDot(){
	$(document).on("focus blur", ".onlyNumDot", function(event){
		event.target.value = event.target.value.replace(/[^0-9.]/g, "");
	})	
	$(".onlyNumDot").css("ime-mode", "disabled");
}

/* 숫자키, 영문[대/소]만 입력 받을수 있게 설정 */
function onlyEngNum(){
	$(document).on("focus blur", ".onlyEngNum", function(event){
		event.target.value = event.target.value.replace(/[^a-zA-Z0-9]/gi, "");
	})	
	$(".onlyEngNum").css("ime-mode", "disabled");
}

/* 숫자키, 영문[소]만 입력 받을수 있게 설정 */
function onlyLEngNum(){
	$(document).on("focus blur", ".onlyLEngNum", function(event){
		event.target.value = event.target.value.replace(/[^a-z0-9]/gi, "");
	})	
	$(".onlyLEngNum").css("ime-mode", "disabled");
}

/* 숫자키, 영문[대]만 입력 받을수 있게 설정 */
function onlyUEngNum(){
	$(document).on("focus blur", ".onlyUEngNum", function(event){
		event.target.value = event.target.value.replace(/[^A-Z0-9]/gi, "");
	})	
	$(".onlyUEngNum").css("ime-mode", "disabled");
}

//한글만 입력 받을수 있게 설정
function onlyHan(){
	$(document).on("focus blur", ".onlyHan", function(event){
		event.target.value = event.target.value.replace(/[^가-힣ㄱ-ㅎㅏ-ㅣ\x20]/gi, "");
	})	
	$(".onlyHan").css("ime-mode", "active");
}


//한글입력체크
function isHangeul(str){
	//var pattern = /[\u3131-\u314e|\u314f-\u3163|\uac00-\ud7a3]/g;
	//return (str.test(language)) ? true : false;

	var chk_cnt = 0;

	for (var i = 0; i < str.length; i++) {
		if (escape(str.charAt(i)).length >= 4) chk_cnt++;
	}

	return (chk_cnt > 0) ? true : false;
}

//영문만입력체크
function isOnlyEng(str){
	var chk_cnt = 0;
	var chk_Exp = /[a-zA-Z]/g;

	for (var i = 0; i < str.length; i++) {
		if (!chk_Exp.test(str)) chk_cnt++;
	}

	return (chk_cnt > 0) ? false : true;
}


//숫자만입력체크
function isOnlyNum(str){
	var chk_cnt = 0;
	var chk_Exp = /[0-9,.]/g;

	for (var i = 0; i < str.length; i++) {
		if (!chk_Exp.test(str)) chk_cnt++;
	}

	return (chk_cnt > 0) ? false : true;
}


//한자만입력체크
function isOnlyCha(str){
	var chk_cnt = 0;
	var chk_Exp = /[\u4E00-\u9FD5]/g;

	for (var i = 0; i < str.length; i++) {
		if (!chk_Exp.test(str)) chk_cnt++;
	}

	return (chk_cnt > 0) ? false : true;
}

//URL형식 체크
function isURL(domain) {
	var pattern = new RegExp('^(https?:\\/\\/)?' +								// protocol
				'((([a-z\d](([a-z\d-]*[a-z\d])|([ㄱ-힣]))*)\.)+[a-z]{2,}|' +	// domain name
				'((\\d{1,3}\\.){3}\\d{1,3}))' +									// OR ip (v4) address
				'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*' +								// port and path
				'(\\?[;&a-z\\d%_.~+=-]*)?' +									// query string
				'(\\#[-a-z\\d_]*)?$','i');										// fragment locator
	if(!pattern.test(domain)) {
		return false;
	} else {
		return true;
	}
}

$(function(){
	onlyNum();
	onlyNumHyphen();
	onlyNumDot();
	onlyEngNum();
	onlyLEngNum();
	onlyUEngNum();
	onlyHan();
})