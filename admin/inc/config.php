<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	$cls_admin_memo = new CLS_ADMIN_MEMO;
	$cls_member = new CLS_MEMBER;
	$cls_set_menu = new CLS_SETTING_MENU_AUTH;

	if (!isAdmin()) {
		if (strpos(NOW_URL, "/common/login.php") === false) {
			fnMsgGo(500, "해당정보에 접근할 수 있는 권한이 없습니다.", "../common/logout.php", "");
		}
	} else {
		if (strpos(NOW_URL, "/common/login.php")) {
			header("Location: ". CLS_SETTING_MENU_AUTH::menu_first_page( $MEM_ADM['usr_gubun'] ));
		} else {
			$adm_view = $cls_member->admin_view($MEM_ADM['usr_id']);

			//관리자 정보 불러오기
			if ($adm_view == false) fnMsgGo(501, "일치하는 관리자 정보가 없습니다.", "../common/logout.php", "");

			//상태 확인
			if ($adm_view['status'] != 'Y') fnMsgGo(502, "이용이 제한된 관리자 입니다.\n담당자에게 문의 주세요.", "../common/logout.php", "");

			//권한 확인
			if ($adm_view['usr_gubun_status'] != 'Y') fnMsgGo(503, "이용 권한이 없는 관리자 입니다.\n담당자에게 문의 주세요.", "../common/logout.php", "");
		}
	}