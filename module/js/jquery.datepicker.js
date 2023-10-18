/* Korean initialisation for the jQuery calendar extension. */
/* Written by DaeKwon Kang (ncrash.dk@gmail.com). */
$(function($){
	var tmpDate = new Date();
	var currYear = tmpDate.getFullYear();

	$.datepicker.regional['ko'] = {
		closeText: '닫기',
		prevText: '이전달',
		nextText: '다음달',
		currentText: '오늘',
		yearNames : "년",
		monthNames: ['01월','02월','03월','04월','05월','06월','07월','08월','09월','10월','11월','12월'],
		monthNamesShort: ['01월','02월','03월','04월','05월','06월','07월','08월','09월','10월','11월','12월'],
		dayNames: ['일','월','화','수','목','금','토'],
		dayNamesShort: ['일','월','화','수','목','금','토'],
		dayNamesMin: ['일','월','화','수','목','금','토'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		changeMonth: true,
		changeYear: true,
		showMonthAfterYear: true,
		showButtonPanel:true,
		reverseYearRange: true,
		yearRange: (currYear-80)+':'+(currYear+2),
		minDate: '-100y'
	};


	$.datepicker.regional['en'] = {
		closeText: 'Done',
		prevText: 'Prev',
		nextText: 'Next',
		currentText: 'Today',
		yearNames : "",
		monthNames: ['January','February','March','April','May','June','July','August','September','October','November','December'],
		monthNamesShort: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
		dayNames: ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'],
		dayNamesShort: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
		dayNamesMin: ['Su','Mo','Tu','We','Th','Fr','Sa'],
		weekHeader: 'Wk',
		dateFormat: 'yy-mm-dd',
		firstDay: 0,
		isRTL: false,
		changeMonth: true,
		changeYear: true,
		showMonthAfterYear: true,
		showButtonPanel:true,
		reverseYearRange: true,
		yearRange: (currYear+100)+':'+(currYear+2),
		minDate: '-100y'
	};

	$.datepicker.setDefaults($.datepicker.regional['ko']);
});