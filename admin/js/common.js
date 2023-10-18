$(function(){
	var tmpDate = new Date();
	var currYear = tmpDate.getFullYear();

	$.datepicker.regional['ko'] = {
		closeText: '닫기',
		prevText: '이전달',
		nextText: '다음달',
		currentText: '오늘',
		dateFormat: "yy-mm-dd",
		monthNames: ['01월','02월','03월','04월','05월','06월','07월','08월','09월','10월','11월','12월'],
		monthNamesShort: ['01월','02월','03월','04월','05월','06월','07월','08월','09월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		showOn: 'both',
		changeMonth: true,
		changeYear: true,
		setDate: new Date(),
		yearRange: (currYear-80)+':'+(currYear+2),
		buttonImage: '/admin/images/btn_datepicker.gif',
		buttonImageOnly: true
	};

	$.datepicker.setDefaults($.datepicker.regional['ko']);


	//파일검색
	$(document).on('change', ('.upload-hidden'), function(){
		var $target  = $(this);
		var fileType = $target.attr('upload-type');
		var fileSize = $target.attr('upload-size');
		var fileExt  = $target.attr('upload-ext');
		if (!fileType) fileType = "file";
		if (!fileSize) fileSize = 100;
		if (fileType == "img" && !fileExt) {
			fileExt = "gif, png, jpg, jpeg";
		} else {
			if (!fileExt) fileExt = "ai, psd, mp3, mp4, avi, wmv, wav, htm, html, gif, png, jpg, jpeg, txt, csv, xml, odt, hwp, hwps, ppt, pptx, xls, xlsx, doc, docx, zip, alz, 7z, tar, tgz, rar, pdf";
		}

		if(window.FileReader){
			var filePath = $target.val();
			var fileName = $target[0].files[0].name;
		} else {
			var filePath = $target.val();
			var fileName = $target.val().split('/').pop().split('\\').pop();
		}

		//파일명 공백체크
		//if (fileName && /\s/gi.test(fileName)) {
		//	alert("파일명에 공백이 들어가있습니다.\n파일명에 공백을 제거 후 다시 등록해주세요.");
		//	fileReset($target);
		//	return false;
		//}
		if (fileName && returnToByte2(fileName, false) > 100) {
			alert("파일명은 100자를 초과할 수 없습니다.\n파일명을 변경 후 다시 등록해주세요.");
			fileReset($target);
			return false;
		}

		//파일용량 체크
		if($target.get(0).files.length > 0) {
			var thisSize = $target.get(0).files[0].size;

			//MB기준
			if (thisSize > fileSize * (1024 * 1024)) {
				alert("업로드 할 수 있는 파일용량이 초과 되었습니다.\n첨부된 파일의 용량은 "+ getByte(thisSize,2) +" 입니다.\n" + getByte(fileSize * (1024 * 1024),2) +" 이하로 첨부해주세요.");

				fileReset($target);
				return false;
			}
		}

		//파일확장자
		var extArr  = fileExt.replace(/\s/gi,'').split(',');
		var thisExt = fileName.split('.').pop().toLowerCase();

		if(thisExt && $.inArray(thisExt, extArr) == -1) {
			if (fileType == "img") {
				alert(fileExt +' 파일만 업로드 할수 있습니다.');
			} else if (fileType == "video" || fileType == "audio") {
				alert(fileExt +' 파일만 업로드 할수 있습니다.');
			} else {
				alert('업로드를 지원하지 않는 확장자입니다.\n파일 확장자를 다시 확인 해주시거나 업로드 할 파일을 압축 후\n압축파일형식(zip, alz, 7z, tar, tgz, rar) 으로 등록해주세요.');
			}

			fileReset($target);
			return false;
		}

		$target.siblings('.input_box').children('input').val(filePath);
		$target.closest(".box").find(".cancel_btn").show();
	});

	//메뉴
	$(".gnb > ul > li > a").on("click", function(){
		if ($(this).next(".depth").is(":visible")){
			$(this).parents("li").removeClass("curr");
			$(this).next(".depth").slideUp(300);
		} else {
			$(".gnb > ul > li").removeClass("curr");
			$(".depth").slideUp(300);
			$(this).parents("li").addClass("curr");
			$(this).next(".depth").slideDown(300);
		}
	});
	//$(".gnb > ul > li").removeClass("curr");
	$(".gnb > ul > li").not(".curr").find(".depth").slideUp(0);
})


//첨부파일박스삭제
function inputFileBoxReset($target) {
	$target.siblings('.input_box').children('input').val("");
	$target.closest(".box").find(".cancel_btn").hide();

	if (navigator.appName.indexOf("Explorer") > -1) {
		$target.replaceWith( $target.clone(true) );
	} else {
		$target.val("");
	}
}


//폼양식 초기화
function formBoxReset(form) {
	$("#"+form)[0].reset();
	selectboxInit();
	try {
		oEditors[0].exec('LOAD_CONTENTS_FIELD');
	} catch (e) {}
}

//layer popup
function commonLayerOpen(thisClass){
	$('.'+thisClass).show();
}
function commonLayerClose(thisClass){
	$('.'+thisClass).hide();
}


//QueryStringToJson
var util = {
	query2json : function(querystring){
		var j, q;
		q = decodeURIComponent(querystring).replace(/\?/,"").split("&");
		j = {};

		$.each(q, function(i, arr){
			arr = arr.split("=");
			return j[arr[0]] = arr[1];
		});

		return j;
	}
}

//윈도우 팝업
function popupOpen(url,id,w,h,r,s){
	var popId = id;
	var popUrl = url;
	var screenW = window.screen.width;
	var screenH = window.screen.height;
	var w = (w==undefined) ? screenW : w;
	var h = (h==undefined) ? screenH : h;
	var lft = (screenW - w)/2;
	var t = (screenH - h)/2 -50;
	var popOption = "width="+w+", height="+h+", left="+lft+", top="+t+", resizable=no, scrollbars=1;";//팝업창 옵션

	var pop = window.open("/blank.html",id,popOption);
	pop.location.href = popUrl;
}