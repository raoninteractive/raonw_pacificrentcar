function popWidth(){
	var wid = $(".wrap_mainPopup .mainPopup").width();
    var leng = $(".wrap_mainPopup .mainPopup:visible").length;
    var width = wid * leng;

    $(".wrap_mainPopup").css({"width": width});
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

//팝업 닫기
function closePopup(e) {
    var $target = $(e).closest('.mainPopup');
    var pop_no = $target.attr("id").replace(/[^0-9]/gi, '');

    //팝업 숨김
    $target.hide();

    //오늘 하루 안보기
    if ($("#todayPopupHide"+pop_no).is(":checked")) {
        setCookieAt00('notToday'+pop_no ,'Y', 1);
    }

	//var wid = $(".wrap_mainPopup .mainPopup").width();
	//var leng = $(".wrap_mainPopup .mainPopup:visible").length;
	//$(".wrap_mainPopup").css({"width":wid*leng});
}



//상단 헤더 배너 닫기
function topClosePopup(e) {
    var $target = $(e).closest('.topad');

    //팝업 숨김
    $target.slideUp("fast");

    //오늘 하루 안보기
    if ($("#todayTopHide").is(":checked")) {
        setCookieAt00('notTodayTop' ,'Y', 1);
    }
}

$(function() {
	$( ".mainPopup" ).draggable();

    //상단 헤더 배너 체크
    if(getCookie("notTodayTop")!="Y"){
        $(".topad").show();
    } else {
        $(".topad").hide();
    }
});

