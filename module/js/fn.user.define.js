/* 빈공백 제거 함수 */
//String.prototype.trim = function() {
//	return this.replace(/\s/g, "");
//}


/* 빈공백 체크 함수 */
String.prototype.isNull = function() {
	var this_val = this;
	var nullState = false;

	if (!typeof(this_val) || this_val == "" || this_val == null) nullState = true;

	return nullState;
}


/* 왼쪽 문자열 자르기 함수 */
String.prototype.left = function(length) {
	if (this.length <= length) {
		return this;
	} else {
		return this.substring(0, length);
	}
}


/* 오른쪽 문자열 자르기 함수 */
String.prototype.right = function(length) {
	if (this.length <= length) {
		return this;
	} else {
		return this.substring(this.length - length, this.length);
	}
}


/* 숫자형 확인 함수 */
String.prototype.isNumeric = function() {
	var this_val = String(this);

	if (this_val.indexOf(" ") != -1 || this_val == "") return false;
	else if (isNaN(this_val)) return false;
	else return true;
}
Number.prototype.isNumeric = function() {
	var this_val = String(this);

	if (this_val.indexOf(" ") != -1 || this_val == "") return false;
	else if (isNaN(this_val)) return false;
	else return true;
}


/* 천단위 콤마 추가 함수 */
String.prototype.addComma = function() {
	var this_val = String(this);

	/*
	var output = "";

	for (var i=this_val.length; i>=0; i--){
		if((this_val.length-i)%3==1 && output.length!=0 && this_val.charAt(i) != "-") {
			output = "," + output;
		}

		output = this_val.charAt(i) + output;
	}

	return output;
	*/

	var regx = new RegExp(/(-?\d+)(\d{3})/);
	var bExists = this_val.indexOf(".", 0);//0번째부터 .을 찾는다.
	var strArr = this_val.split('.');
	while (regx.test(strArr[0])) {//문자열에 정규식 특수문자가 포함되어 있는지 체크
		//정수 부분에만 콤마 달기 
		strArr[0] = strArr[0].replace(regx, "$1,$2");//콤마추가하기
	}
	if (bExists > -1) {
		//. 소수점 문자열이 발견되지 않을 경우 -1 반환
		this_val = strArr[0] + "." + strArr[1];
	} else { //정수만 있을경우 //소수점 문자열 존재하면 양수 반환 
		this_val = strArr[0];
	}

	return this_val;//문자열 반환
}
Number.prototype.addComma = function() {
	var this_val = String(this);

	/*
	var output = "";

	for (var i=this_val.length; i>=0; i--){
		if((this_val.length-i)%3==1 && output.length!=0 && this_val.charAt(i) != "-") {
			output = "," + output;
		}

		output = this_val.charAt(i) + output;
	}

	return output;
	*/

	var regx = new RegExp(/(-?\d+)(\d{3})/);
	var bExists = this_val.indexOf(".", 0);//0번째부터 .을 찾는다.
	var strArr = this_val.split('.');
	while (regx.test(strArr[0])) {//문자열에 정규식 특수문자가 포함되어 있는지 체크
		//정수 부분에만 콤마 달기 
		strArr[0] = strArr[0].replace(regx, "$1,$2");//콤마추가하기
	}
	if (bExists > -1) {
		//. 소수점 문자열이 발견되지 않을 경우 -1 반환
		this_val = strArr[0] + "." + strArr[1];
	} else { //정수만 있을경우 //소수점 문자열 존재하면 양수 반환 
		this_val = strArr[0];
	}

	return this_val;//문자열 반환
}


/* 천단위 콤마 제거 함수 */
String.prototype.removeComma = function() {
	return this.replace(/,/g, "");
}


/* 영문 대문자 전환 함수 */
String.prototype.ucase = function() {
	return this.toUpperCase();
}


/* 영문 소문자 전환 함수 */
String.prototype.lcase = function() {
	return this.toLowerCase();
}


/* 10진수 형변환 함수 */
String.prototype.toInt = function() {
	//예) 1,000.55 → 1000
	var this_val = this;

	if (typeof(this_val) == 'number') {
		this_val = parseInt(this_val, 10);
	} else {
		if (this_val.length > 0) {
			this_val = this_val.replace(/[^0-9]/gi, '');			
			if (this_val.length > 0) this_val = parseInt(this_val, 10);
		}
	}

    return this_val
}


/* 숫자 형변환 함수 */
String.prototype.toNumber = function() {
	//예) 1,000.55 → 1000.55
	var this_val = this;

	if (typeof(this_val) == 'number') {
		this_val = Number(this_val, 10);
	} else {
		if (this_val.length > 0) {
			this_val = this_val.replace(/[^0-9,.]/gi, '');
			this_val = Number(this_val.replace(/[^0-9.]/gi, ''), 10);
		}
	}

	return this_val;
}


/* 지정자리 반올림 */
String.prototype.toRound = function(pos) {	
	var this_val = this.toNumber();
		pos = !pos ? 0 : pos;
	var digits = Math.pow(10, pos);
	var sign = 1;

	if (this_val < 0) sign = -1;

	//음수이면 양수처리후 반올림 한 후 다시 음수처리
	this_val = this_val * sign;
	var num = Math.round(this_val * digits) / digits;
	num = num * sign;

	return num.toFixed(pos);
}

Number.prototype.toRound = function(pos) {	
	var this_val = this;
		pos = !pos ? 0 : pos;
	var digits = Math.pow(10, pos);
	var sign = 1;

	if (this_val < 0) sign = -1;

	//음수이면 양수처리후 반올림 한 후 다시 음수처리
	this_val = this_val * sign;
	var num = Math.round(this_val * digits) / digits;
	num = num * sign;

	return num.toFixed(pos);
}


/* 지정자리 올림 */
String.prototype.toCeil = function(pos) {	
	var this_val = this.toNumber();
		pos = !pos ? 0 : pos;
	var digits = Math.pow(10, pos);
	var num = Math.ceil(this_val * digits) / digits;

	return num.toFixed(pos);
}