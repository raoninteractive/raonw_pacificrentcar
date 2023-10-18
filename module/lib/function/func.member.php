<?php
	function isUser() {
		global $MEM_USR;

		if ((is_null(getSession("user_view")) || empty(getSession("user_view"))) && strlen(getSession("user_view")) == 0) {
			$MEM_USR = array();
			$MEM_USR['usr_gubun'] = '00';

			return false;
		} else {
			$MEM_USR = getSession("user_view");

			return true;
		}
	}
	isUser();

	function isAdmin() {
		global $MEM_ADM;

		if ((is_null(getSession("admin_view")) || empty(getSession("admin_view"))) && strlen(getSession("admin_view")) == 0) {
			$MEM_ADM = array();
			$MEM_ADM['usr_gubun'] = '00';

			return false;
		} else {
			$MEM_ADM = getSession("admin_view");

			return true;
		}
	}
	isAdmin();


	//회원 구분 이름
	function getUserGubunName($gubun) {
		global $CONST_MEMBER_GUBUN;

		$result = "";
		foreach($CONST_MEMBER_GUBUN as $item) {
			if ($item[0] == $gubun) {
				$result = $item[1];
				break;
			}
		}

		return $result;
	}