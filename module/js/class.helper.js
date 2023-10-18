var clsJsHelper = function(language){
	var obj;

	this.returnMsgType = "alert";
	this.returnMsg = "";
	this.msgByteCheck = true;

	if (typeof language == "undefined") {
		language = "kor";
	}

	var msg_data;
	this.setMsgByteCheck = function(check_stste) {
		if (check_stste) {
			msg_data = {
				"msg_basic": "{0} 입력해 주세요.",
				"msg_select": "{0} 선택해 주세요.",
				"msg_checked": "{0} 선택해 주세요.",
				"msg_do_not_space": "{0} 공백을 입력할 수 없습니다.",
				"msg_do_not_start_space": "{0} 공백으로 시작할 수 없습니다.",
				"msg_check_mail_exp": "정확한 메일 형식이 아닙니다.\n{0} 다시 입력해 주세요.",
				"msg_check_mail_hanmail": "{0} 한메일은 사용하실 수 없습니다.\n다른 이메일 주소를 입력해 주세요.",
				"msg_check_specialword": "{0} 특수문자는 사용하실 수 없습니다.",
				"msg_check_number": "{0} 숫자만 입력하실 수 있습니다.",
				"msg_check_number_hyphen": "{0} 숫자와 - 만 입력하실 수 있습니다.",
				"msg_kor_check_input_min_length": "{0} 한글 {2}자, 영문/숫자 {1}자 이상 입력해 주세요.",
				"msg_kor_check_input_max_length": "{0} 한글 {2}자, 영문/숫자 {1}자 이하로 입력해 주세요.",
				"msg_eng_check_input_min_length": "{0} 영문 또는 숫자 {1}자 이상 입력해 주세요.",
				"msg_eng_check_input_max_length": "{0} 영문 또는 숫자 {1}자 이하로 입력해 주세요.",
				"msg_onlyeng_check_input_min_length": "{0} 영문 {1}자 이상 입력해 주세요.",
				"msg_onlyeng_check_input_max_length": "{0} 영문 {1}자 이하로 입력해 주세요.",
				"msg_onlynum_check_input_min_length": "{0} 숫자 {1}자 이상 입력해 주세요.",
				"msg_onlynum_check_input_max_length": "{0} 숫자 {1}자 이하로 입력해 주세요.",
				"msg_onlynumhyphen_check_input_min_length": "{0} 숫자 또는 - {1}자 이상 입력해 주세요.",
				"msg_onlynumhyphen_check_input_max_length": "{0} 숫자 또는 - {1}자 이하로 입력해 주세요."
			}

			this.msgByteCheck = true;
		} else {
			msg_data = {
				"msg_basic": "{0} 입력해 주세요.",
				"msg_select": "{0} 선택해 주세요.",
				"msg_checked": "{0} 선택해 주세요.",
				"msg_do_not_space": "{0} 공백을 입력할 수 없습니다.",
				"msg_do_not_start_space": "{0} 공백으로 시작할 수 없습니다.",
				"msg_check_mail_exp": "정확한 메일 형식이 아닙니다.\n{0} 다시 입력해 주세요.",
				"msg_check_mail_hanmail": "{0} 한메일은 사용하실 수 없습니다.\n다른 이메일 주소를 입력해 주세요.",
				"msg_check_specialword": "{0} 특수문자는 사용하실 수 없습니다.",
				"msg_check_number": "{0} 숫자만 입력하실 수 있습니다.",
				"msg_check_number_hyphen": "{0} 숫자와 - 만 입력하실 수 있습니다.",
				"msg_kor_check_input_min_length": "{0} 한글 {1}자 이상 입력해 주세요.",
				"msg_kor_check_input_max_length": "{0} 한글 {1}자 이하로 입력해 주세요.",
				"msg_eng_check_input_min_length": "{0} 영문 {1}자 이상 입력해 주세요.",
				"msg_eng_check_input_max_length": "{0} 영문 {1}자 이하로 입력해 주세요.",
				"msg_onlyeng_check_input_min_length": "{0} 영문 {1}자 이상 입력해 주세요.",
				"msg_onlyeng_check_input_max_length": "{0} 영문 {1}자 이하로 입력해 주세요.",
				"msg_onlynum_check_input_min_length": "{0} 숫자 {1}자 이상 입력해 주세요.",
				"msg_onlynum_check_input_max_length": "{0} 숫자 {1}자 이하로 입력해 주세요.",
				"msg_onlynumhyphen_check_input_min_length": "{0} 숫자 또는 - {1}자 이상 입력해 주세요.",
				"msg_onlynumhyphen_check_input_max_length": "{0} 숫자 또는 - {1}자 이하로 입력해 주세요."
			}

			this.msgByteCheck = false;
		}
	}
	this.setMsgByteCheck(true);

	//object 반환
	this.returnObject = function(obj_name) {
		if (typeof obj_name == "object") {
			return obj_name;
		} else {
			return document.getElementById(obj_name);
		}
	}

	//object 값 반환
	this.objVal = function(obj_name) {
		var this_val;
		if (typeof obj_name == "object") {
			this_val = obj_name.val();
		} else {
			this_val = $("#"+obj_name).val();
		}

		return this_val;
	}

	//입력값 체크
	this.checkVal = function(obj_name, msg, space) {
		obj = this.returnObject(obj_name);
		if (!this.checkNull(obj.value, msg)) {
			this.focusObj(obj);
			return false;
		}

		if (space == "Y") {
			if (!this.checkSpace(obj.value, msg)) {
				obj.value = "";
				this.focusObj(obj);
				return false;
			}
		}

		return true;
	}

	//셀렉트 체크
	this.checkSelect = function(obj_name, msg) {
		obj = this.returnObject(obj_name);
		if (!this.checkNullSelect(obj.value, msg)) {
			this.focusObj(obj);
			return false;
		}

		return true;
	}

	//라디오 체크
	this.checkRadiobox = function(obj_name, msg) {
		obj = $(":radio[name="+obj_name+"]");
		if ($(":radio[name="+obj_name+"]:checked").size() == 0) {
			if (language == "kor"){
				if (this.returnMsgType == "layer") {
					this.returnMsg = formatingMsg(msg_data.msg_checked, postpositionalWord(msg, "을", "를"));
				} else {
					alert(formatingMsg(msg_data.msg_checked, postpositionalWord(msg, "을", "를")));
				}
			} else if (language == "eng"){
				if (this.returnMsgType == "layer") {
					this.returnMsg = msg;
				} else {
					alert(msg);
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			obj.eq(0).focus();
			return false;
		}

		return true;
	}

	//체크박스 체크
	this.checkCheckbox = function(obj_name, msg) {
		obj = $(":checkbox[name="+obj_name+"]");
		if ($(":checkbox[name="+obj_name+"]:checked").size() == 0) {
			if (language == "kor"){
				if (this.returnMsgType == "layer") {
					this.returnMsg = formatingMsg(msg_data.msg_checked, postpositionalWord(msg, "을", "를"));
				} else {
					alert(formatingMsg(msg_data.msg_checked, postpositionalWord(msg, "을", "를")));
				}
			} else if (language == "eng"){
				if (this.returnMsgType == "layer") {
					this.returnMsg = msg;
				} else {
					alert(msg);
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			obj.eq(0).focus();
			return false;
		}

		return true;
	}

	//입력값 null or 공백체크(입력값없음)
	this.checkNull = function(val, msg) {
		if (val.indexOf(" ") == 0) {
			if (language == "kor") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = formatingMsg(msg_data.msg_do_not_start_space, postpositionalWord(msg, "은", "는"));
				} else {
					alert(formatingMsg(msg_data.msg_do_not_start_space, postpositionalWord(msg, "은", "는")));
				}
			} else if (language == "eng") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = msg + " (Can not begin with a blank character.)";
				} else {
					alert(msg + "\n\n(Can not begin with a blank character.)");
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			return false;
		}

		var tmp_val = val.replace( /^\s+|\s+$/g, "" );
		if (tmp_val == ""){
			if (language == "kor") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = formatingMsg(msg_data.msg_basic, postpositionalWord(msg, "을", "를"));
				} else {
					alert(formatingMsg(msg_data.msg_basic, postpositionalWord(msg, "을", "를")));
				}
			} else if (language == "eng") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = msg;
				} else {
					alert(msg);
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			return false;
		}

		return true;
	}

	//입력값 null or 공백체크(입력값없음) 셀렉트 박스
	this.checkNullSelect = function(val, msg) {
		var tmp_val = val.replace( /^\s+|\s+$/g, "" );
		if (tmp_val == "") {

			if (language == "kor"){
				if (this.returnMsgType == "layer") {
					this.returnMsg = formatingMsg(msg_data.msg_select, postpositionalWord(msg, "을", "를"));
				} else {
					alert(formatingMsg(msg_data.msg_select, postpositionalWord(msg, "을", "를")));
				}
			} else if (language == "eng"){
				if (this.returnMsgType == "layer") {
					this.returnMsg = msg;
				} else {
					alert(msg);
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			return false;
		}
		return true;
	}

	//입력값에 공백이 들어갔는지 체크
	this.checkSpace = function(val, msg) {
		for (var i = 0 ; i < val.length ;i++ ) {
			if (val.charAt(i) == ' ') {
				if (language == "kor") {
					if (this.returnMsgType == "layer") {
						this.returnMsg = formatingMsg(msg_data.msg_do_not_space, postpositionalWord(msg, "은", "는"));
					} else {
						alert(formatingMsg(msg_data.msg_do_not_space, postpositionalWord(msg, "은", "는")));
					}
				} else if (language == "eng") {
					if (this.returnMsgType == "layer") {
						this.returnMsg = msg + " (You can not type spaces.)";
					} else {
						alert(msg + "\n\n(You can not type spaces.)");
					}
				} else {
					alert("언어를 추가해주세요.");
				}

				return false;
			}
		}

		return true;
	}

	//입력값 길이체크
	this.checkLen = function(obj_name, nlen, mlen, msg, types) {
		obj = this.returnObject(obj_name);

		//Default : KOR
		var max_msg = msg_data.msg_kor_check_input_max_length;
		var min_msg = msg_data.msg_kor_check_input_min_length;

		if (types) {
			if (types.toUpperCase() == "KO") {
				max_msg = msg_data.msg_kor_check_input_max_length;
				min_msg = msg_data.msg_kor_check_input_min_length;
			} else if (types.toUpperCase() == "EN") {
				max_msg = msg_data.msg_eng_check_input_max_length;
				min_msg = msg_data.msg_eng_check_input_min_length;
			} else if (types.toUpperCase() == "OE") {
				max_msg = msg_data.msg_onlyeng_check_input_max_length;
				min_msg = msg_data.msg_onlyeng_check_input_min_length;
			} else if (types.toUpperCase() == "ON") {
				max_msg = msg_data.msg_onlynum_check_input_max_length;
				min_msg = msg_data.msg_onlynum_check_input_min_length;
			} else if (types.toUpperCase() == "N-") {
				max_msg = msg_data.msg_onlynumhyphen_check_input_max_length;
				min_msg = msg_data.msg_onlynumhyphen_check_input_min_length;
			}
		}

		//한글이외 체크
		if (types && types.toUpperCase() != "KO") {
			//한글입력금지체크
			if (isHangeul(obj.value)) {
				if (language == "kor") {
					if (this.returnMsgType == "layer") {
						this.returnMsg = "한글은 입력이 불가능합니다.";
					} else {
						alert("한글은 입력이 불가능합니다.");
					}
				} else if (language == "eng") {
					if (this.returnMsgType == "layer") {
						this.returnMsg = "Korean is impossible to enter";
					} else {
						alert("Korean is impossible to enter");
					}
				} else {
					alert("언어를 추가해주세요.");
				}

				obj.value = "";
				this.focusObj(obj);

				return false;
			}

			//영문만입력체크
			if (types.toUpperCase() == "OE") {
				if (!isOnlyEng(obj.value)) {
					if (language == "kor") {
						if (this.returnMsgType == "layer") {
							this.returnMsg = "영문만 입력이 가능합니다.";
						} else {
							alert("영문만 입력이 가능합니다.");
						}
					} else if (language == "eng") {
						if (this.returnMsgType == "layer") {
							this.returnMsg = "Please enter only english.";
						} else {
							alert("Please enter only english.");
						}
					} else {
						alert("언어를 추가해주세요.");
					}

					obj.value = "";
					this.focusObj(obj);

					return false;
				}
			}

			//숫자만입력체크
			if (types.toUpperCase() == "ON") {
				if (!isOnlyNum(obj.value)) {
					if (language == "kor") {
						if (this.returnMsgType == "layer") {
							this.returnMsg = "숫자만 입력이 가능합니다.";
						} else {
							alert("숫자만 입력이 가능합니다.");
						}
					} else if (language == "eng") {
						if (this.returnMsgType == "layer") {
							this.returnMsg = "Please enter only numbers.";
						} else {
							alert("Please enter only numbers.");
						}
					} else {
						alert("언어를 추가해주세요.");
					}

					obj.value = "";
					this.focusObj(obj);

					return false;
				}
			}

			//숫자,하이픈(-) 입력체크
			if (types.toUpperCase() == "N-") {
				if (!/[0-9-]/.test(obj.value) || obj.value.indexOf("-") == -1) {
					if (language == "kor") {
						if (this.returnMsgType == "layer") {
							this.returnMsg = "숫자와 하이픈(-) 조합으로 입력해주세요.";
						} else {
							alert("숫자와 하이픈(-) 조합으로 입력해주세요.");
						}
					} else if (language == "eng") {
						if (this.returnMsgType == "layer") {
							this.returnMsg = "Please enter only numbers And hyphen.";
						} else {
							alert("Please enter only numbers And hyphen.");
						}
					} else {
						alert("언어를 추가해주세요.");
					}

					obj.value = "";
					this.focusObj(obj);

					return false;
				}
			}
		}

		//자리수 체크
		var this_length = 0; //입력값의 현재 길이값
		var obj_value = obj.value;
		if (types.toUpperCase() == "ON") obj_value = String(obj_value.replace(/[^0-9]/g,''));

		for (var i = 0;i < obj_value.length; i++) {
			var tmp = obj_value.charAt(i);

			//요청에 의한변경 | 바이트상관없이 무조건 글자수 기준 | 김세화팀장 | 2019-01-07
			if (this.msgByteCheck) {
				if (escape(tmp).length > 4) {
					this_length = this_length + 2;
				} else {
					this_length++;
				}
			} else {
				this_length++;
			}
		}

		if (this_length < nlen) {
			if (language == "kor") {
				if (this.returnMsgType == "layer") {
					if (this.msgByteCheck) {
						this.returnMsg = formatingMsg(min_msg, postpositionalWord(msg, "은", "는"), nlen, parseInt(nlen/2));
					} else {
						this.returnMsg = formatingMsg(min_msg, postpositionalWord(msg, "은", "는"), nlen, nlen);
					}
				} else {
					if (this.msgByteCheck) {
						alert(formatingMsg(min_msg, postpositionalWord(msg, "은", "는"), nlen, parseInt(nlen/2)));
					} else {
						alert(formatingMsg(min_msg, postpositionalWord(msg, "은", "는"), nlen, nlen));
					}
				}
			} else if (language == "eng") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = msg + " (Please enter at least " + nlen + " characters or more.)";
				} else {
					alert(msg + "\n\n(Please enter at least " + nlen + " characters or more.)");
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			this.focusObj(obj);

			return false;
		}

		if (this_length > mlen) {
			if (language == "kor") {
				if (this.returnMsgType == "layer") {
					if (this.msgByteCheck) {
						this.returnMsg = formatingMsg(max_msg, postpositionalWord(msg, "은", "는"), mlen, parseInt(mlen/2));
					} else {
						this.returnMsg = formatingMsg(max_msg, postpositionalWord(msg, "은", "는"), mlen, mlen);
					}
				} else {
					if (this.msgByteCheck) {
						alert(formatingMsg(max_msg, postpositionalWord(msg, "은", "는"), mlen, parseInt(mlen/2)));
					} else {
						alert(formatingMsg(max_msg, postpositionalWord(msg, "은", "는"), mlen, mlen));
					}
				}
			} else if (language == "eng") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = msg + "\n\n(Please enter a maximum of " + mlen + " characters.)";
				} else {
					alert(msg + "\n\n(Please enter a maximum of " + mlen + " characters.)");
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			this.focusObj(obj);

			return false;
		}

		return true;
	}

	//입력값과 길이 동시 체크
	this.checkValNLen = function(obj_name, nlen, mlen, msg, space, types) {

		if (!this.checkVal(obj_name, msg, space)) {
			return false;
		}

		if (!this.checkLen(obj_name, nlen, mlen, msg, types)) {
			return false;
		}

		return true;
	}

	//숫자값만 입력해야하는 경우
	this.checkNumber = function(obj_name, msg) {
		obj = this.returnObject(obj_name);

		if (obj.value.match(/^-?[0-9]+$/gi) == null) {
			if (language == "kor") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = formatingMsg(msg_data.msg_check_number, postpositionalWord(msg, "은", "는"));
				} else {
					alert(formatingMsg(msg_data.msg_check_number, postpositionalWord(msg, "은", "는")));
				}
			} else if (language == "eng") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = msg + " (You can only enter numbers.)";
				} else {
					alert(msg + "\n\n(You can only enter numbers.)");
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			obj.value = "";
			this.focusObj(obj);

			return false;
		}

		return true;
	}

	//숫자값과 - 만 입력해야하는 경우 [전화번호, 사업자 번호등]
	this.checkNumHyphen = function(obj_name, msg) {
		obj = this.returnObject(obj_name);
		if (obj.value.match(/^[0-9-]+$/gi) == null) {
			if (language == "kor") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = formatingMsg(msg_data.msg_check_number_hyphen, postpositionalWord(msg, "은", "는"));
				} else {
					alert(formatingMsg(msg_data.msg_check_number_hyphen, postpositionalWord(msg, "은", "는")));
				}
			} else if (language == "eng") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = msg + " (Numbers and - you can enter only.)";
				} else {
					alert(msg + "\n\n(Numbers and - you can enter only.)");
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			obj.value = "";
			this.focusObj(obj);

			return false;
		}

		return true;
	}

	//이메일체크
	var mailExp = /^([\w-]+(?:\.[\w-]+)*)@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$/;

	//이메일 입력폼이 1개일때
	this.checkEmail = function(obj_name, msg) {

		if (!this.checkVal(obj_name, msg, "Y")) {
			return false;
		}

		obj = this.returnObject(obj_name);

		if (!mailExp.test(obj.value)) {
			if (language == "kor"){
				if (this.returnMsgType == "layer") {
					this.returnMsg = formatingMsg(msg_data.msg_check_mail_exp, postpositionalWord(msg, "을", "를"));
				} else {
					alert(formatingMsg(msg_data.msg_check_mail_exp, postpositionalWord(msg, "을", "를")));
				}
			} else if (language == "eng") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = "Invalid email address.Please enter a correct email address.";
				} else {
					alert("Invalid email address.\nPlease enter a correct email address.");
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			obj.value= "";
			this.focusObj(obj);

			return false;
		}

		return true;
	}

	//이메일값을 받을때
	this.checkEmail2 = function(obj_name, val, msg) {
		//obj_name는 이메일 값이 아닐때 포커스가 갈 input
		obj = this.returnObject(obj_name);

		if (!mailExp.test(val)) {
			if (language == "kor"){
				if (this.returnMsgType == "layer") {
					this.returnMsg = formatingMsg(msg_data.msg_check_mail_exp, postpositionalWord(msg, "을", "를"));
				} else {
					alert(formatingMsg(msg_data.msg_check_mail_exp, postpositionalWord(msg, "을", "를")));
				}
			} else if (language == "eng") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = "Invalid email address.Please enter a correct email address.";
				} else {
					alert("Invalid email address.\nPlease enter a correct email address.");
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			this.focusObj(obj);

			return false;
		}

		return true;
	}

	//아이디, 비밀번호 보안성검사 정규식
	var passwordExp = /([a-zA-Z0-9].*[!,@,#,$,%,^,&,*,?,_,~])|([!,@,#,$,%,^,&,*,?,_,~].*[a-zA-Z0-9])/;	//영문,숫자,특수문자의 조합 확인
	var regChk1 = /[a-z]/;				//적어도 한개의 a-z 확인
	var regChk2 = /[A-Z]/;				//적어도 한개의 A-Z 확인
	var regChk3 = /[0-9]/;				//적어도 한개의 0-9 확인
	var regChk4 = /[!@$%^&*_~\-]/i;	//비밀번호 특수문자 확인
	var regChk5 = /[_-]/i;				//아이디 특수문자 확인


	//아이디 보안체크
	this.idSecurityCheck = function (obj_name, msg){
		if (!this.checkVal(obj_name, msg, "Y")) {
			return false;
		}

		var obj = this.returnObject(obj_name);
		var val = obj.value;

		var passCheck1 = 0; //영소문자입력 카운트
		var passCheck2 = 0; //영문대문자입력 카운트
		var passCheck3 = 0; //숫자입력 카운트
		var passCheck5 = 0; //특수문자입력 카운트

		for (i=0; i<val.length; i++) {
			valueCharAt = val.charAt(i);

			if (regChk1.test(valueCharAt)) passCheck1++;
			if (regChk3.test(valueCharAt)) passCheck3++;

			//첫번째 글짜 영문자 체크
			if (i==0 && !regChk1.test(valueCharAt)) {
				if (language == "kor") {
					if (this.returnMsgType == "layer") {
						this.returnMsg = "첫문장은 영문자로 입력해주세요.";
					} else {
						alert("첫문장은 영문자로 입력해주세요.");
					}
				} else if (language == "eng") {
					if (this.returnMsgType == "layer") {
						this.returnMsg = "Please enter the first sentence in English.";
					} else {
						alert("Please enter the first sentence in English.");
					}
				} else {
					alert("언어를 추가해주세요.");
				}

				this.focusObj(obj);
				return false;
			}

			//특수문자 체크
			if (/[^a-z0-9]/gi.test(valueCharAt)) {
				if (!regChk5.test(valueCharAt)) {
					if (language == "kor") {
						if (this.returnMsgType == "layer") {
							this.returnMsg = "특수문자는 ｀_ -´ 만 사용 가능합니다.";
						} else {
							alert("특수문자는 ｀_ -´ 만 사용 가능합니다.");
						}
					} else if (language == "eng") {
						if (this.returnMsgType == "layer") {
							this.returnMsg = "Special character can only be used in ｀_ -´.";
						} else {
							alert("Special character can only be used in ｀_ -´.");
						}
					} else {
						alert("언어를 추가해주세요.");
					}

					this.focusObj(obj);
					return false;
				}
			}

			//영문 대문자 체크
			if (regChk2.test(valueCharAt)) {
				if (language == "kor") {
					if (this.returnMsgType == "layer") {
						this.returnMsg = "영문 소문자만 입력해주세요.";
					} else {
						alert("영문 소문자만 입력해주세요.");
					}
				} else if (language == "eng") {
					if (this.returnMsgType == "layer") {
						this.returnMsg = "Please enter lowercase English characters only.";
					} else {
						alert("Please enter lowercase English characters only.");
					}
				} else {
					alert("언어를 추가해주세요.");
				}

				this.focusObj(obj);
				return false;
			}
		}

		/*
		if (passCheck1==0 && passCheck3==0) {
			if (language == "kor") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = "영문 소문자, 숫자는 한글자 이상 입력해주세요.";
				} else {
					alert("영문 소문자, 숫자는 한글자 이상 입력해주세요.");
				}
			} else if (language == "eng") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = "Please enter at least one lowercase English character or number.";
				} else {
					alert("Please enter at least one lowercase English character or number.");
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			this.focusObj(obj);
			return false;
		}
		*/

		return true;
	}

	//비밀번호 보안체크
	this.pwdSecurityCheck = function(obj_name, msg){
		if (!this.checkVal(obj_name, msg, "Y")) {
			return false;
		}

		var obj = this.returnObject(obj_name);
		var val = obj.value;

		var passCheck1 = 0; //영소문자입력 카운트
		var passCheck2 = 0; //영문대문자입력 카운트
		var passCheck3 = 0; //숫자입력 카운트
		var passCheck4 = 0; //특수문자입력 카운트

		for (i=0; i<val.length; i++) {
			valueCharAt = val.charAt(i);

			if (regChk1.test(valueCharAt)) passCheck1++;
			if (regChk2.test(valueCharAt)) passCheck2++;
			if (regChk3.test(valueCharAt)) passCheck3++;

			//특수문자 체크
			if (/[^a-zA-Z0-9]/gi.test(valueCharAt)) {
				if (!regChk4.test(valueCharAt)) {
					if (language == "kor") {
						if (this.returnMsgType == "layer") {
							this.returnMsg = "특수문자는 ｀! @ $ % ^ & * _ ~ -´ 만 사용 가능합니다.";
						} else {
							alert("특수문자는 ｀! @ $ % ^ & * _ ~ -´ 만 사용 가능합니다.");
						}
					} else if (language == "eng") {
						if (this.returnMsgType == "layer") {
							this.returnMsg = "Special character can only be used in ｀! @ $ % ^ & * _ ~ -´.";
						} else {
							alert("Special character can only be used in ｀! @ $ % ^ & * _ ~ -´.");
						}
					} else {
						alert("언어를 추가해주세요.");
					}

					this.focusObj(obj);
					return false;
				} else {
					passCheck4++;
				}
			}
		}

		if ((passCheck1==0 && passCheck2==0) || (passCheck3==0 && passCheck4==0)) {
			if (language == "kor") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = "영문 + 숫자 또는 특수문자 조합으로 입력해주세요.";
				} else {
					alert("영문 + 숫자 또는 특수문자 조합으로 입력해주세요.");
				}
			} else if (language == "eng") {
				if (this.returnMsgType == "layer") {
					this.returnMsg = "Please enter in alphabet + number combination.";
				} else {
					alert("Please enter in alphabet + number combination.");
				}
			} else {
				alert("언어를 추가해주세요.");
			}

			this.focusObj(obj);

			return false;
		}

		return true;
	}

	//포커스 에디터등 display:none 인경우 에러가 나는걸 방지위해
	this.focusObj = function(obj) {
		try {
			if (this.returnMsgType == "layer") {
			} else {
				obj.focus();
			}
		} catch(e){
			//
		}
	}

	//메세지 생성
	function formatingMsg(msg_form)	{
		var tmp_word = ''
		if (arguments.length > 1) {
			for (var msg_i = 0; msg_i < arguments.length; msg_i++) {
				msg_form = msg_form.replace(new RegExp("\\{" + msg_i + "\\}", "gi"), arguments[msg_i + 1]);
			}
		}

		return msg_form;
	}

	//한글 조사 체크
	function postpositionalWord(msg, postpositional_word_1, postpositional_word_2) {
		if (language != "" && language != "kor") {
			return msg;
		}

		var check_word = '가갸거겨고교구규그기개걔게계과괘궈궤괴귀긔까꺄꺼껴꼬꾜꾸뀨끄끼깨꺠께꼐꽈꽤꿔꿰꾀뀌끠나냐너녀노뇨누뉴느니내냬네녜놔놰눠눼뇌뉘다댜더뎌도됴두듀드디대댸데뎨돠돼둬뒈되뒤듸따땨떠뗘또뚀뚜뜌뜨띠때떄떼뗴똬뙈뚸뛔뙤뛰띄라랴러려로료루류르리래럐레례롸뢔뤄뤠뢰뤼마먀머며모묘무뮤므미매먜메몌뫄뫠뭐뭬뫼뮈믜바뱌버벼보뵤부뷰브비배뱨베볘봐봬붜붸뵈뷔븨빠뺘뻐뼈뽀뾰뿌쀼쁘삐빼뺴뻬뼤뽜뽸뿨쀄뾔쀠사샤서셔소쇼수슈스시새섀세셰솨쇄숴쉐쇠쉬싀싸쌰써쎠쏘쑈쑤쓔쓰씨쌔썌쎄쎼쏴쐐쒀쒜쐬쒸씌아야어여오요우유으이의애얘에예와왜워웨외위자쟈저져조죠주쥬즈지재쟤제졔좌좨줘줴죄쥐즤짜쨔쩌쪄쪼쬬쭈쮸쯔찌째쨰쩨쪠쫘쫴쭤쮀쬐쮜쯰차챠처쳐초쵸추츄츠치채챼체쳬촤쵀춰췌최취카캬커켜코쿄쿠큐크키캐컈케켸콰쾌쿼퀘쾨퀴킈타탸터텨토툐투튜트티태턔테톄톼퇘퉈퉤퇴튀틔파퍄퍼펴포표푸퓨프피패퍠페폐퐈퐤풔풰푀퓌하햐허혀호효후휴흐히해햬헤혜화홰훠훼회휘희';
		var check_word_flag = true;

		for (var word_i = 0;word_i < check_word.length ; word_i++) {
			if (check_word.charAt(word_i) == msg.charAt(msg.length-1)) {
				check_word_flag = false;
				break;
			}
		}

		if (check_word_flag) {
			return msg + postpositional_word_1;
		} else {
			return msg + postpositional_word_2;
		}
	}
}