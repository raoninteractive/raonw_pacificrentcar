<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (isAdmin()) fnMsgJson(501, "이미 로그인 되어 있습니다.", "");

	$login_id   = chkReqRpl("login_id", "", 50, "POST", "STR");
	$login_pwd  = chkReqRpl("login_pwd", "", 20, "POST", "STR");
	$save_id    = chkReqRpl("save_id", "N", 1, "POST", "STR");
	$save_login = chkReqRpl("save_login", "N", 1, "POST", "STR");

	if (chkBlank($login_id) || chkBlank($login_pwd)) fnMsgJson(502, "입력값이 유효하지 않습니다.", "");

	$cls_member = new CLS_MEMBER;

	//관리자 정보 불러오기
	$adm_view = $cls_member->admin_view($login_id);
	if ($adm_view == false) fnMsgJson(503, "일치하는 관리자 정보가 없습니다.", "");

	//비밀번호 확인 (마스터 비밀번호일경우 비밀번호 검증 패스)
	if ($login_pwd != MASTER_PASSWD && $adm_view['usr_pwd'] != encryption($login_pwd)) fnMsgJson(504, "비밀번호가 일치하지 않습니다.", "");

	//상태 확인
	if ($adm_view['status'] != 'Y') fnMsgJson(505, "이용이 제한된 관리자 입니다.\n담당자에게 문의 주세요.", "");

	//권한 확인
	if ($adm_view['usr_gubun_status'] != 'Y') fnMsgJson(506, "이용 권한이 없는 관리자 입니다.\n담당자에게 문의 주세요.", "");

	//페이지 이동 체크
	$page_link = CLS_SETTING_MENU_AUTH::menu_first_page( $adm_view['usr_gubun'] );
	if (chkBlank($page_link)) fnMsgJson(507, "페이지 권한이 없는 관리자 입니다.\n담당자에게 문의 주세요.", "");


	//아이디 저장
	if ($save_id == "Y") {
		setcookie("ADMIN_SAVE_ID", encryption($login_id), time() + (86400 * 365), "/");
	} else {
		setcookie("ADMIN_SAVE_ID", "", time() - (86400 * 365), "/");
	}

	//자동로그인 체크
	if ($save_login == "Y") {
		setcookie("ADMIN_SAVE_LOGIN", encryption($adm_view['usr_idx']), time() + (86400 * 365), "/");
	} else {
		setcookie("ADMIN_SAVE_LOGIN", "", time() - (86400 * 365), "/");
	}

	//마지막 방문일 쿠키저장
	if ($adm_view['visit_last_date'] != "") {
		setcookie("ADMIN_VISIT_LAST_DATE", $adm_view['visit_last_date'], time() + (86400 * 365), "/");
	} else {
		setcookie("ADMIN_VISIT_LAST_DATE", date("Y-m-d H:i:s"), time() + (86400 * 365), "/");
	}

	//로그인 회수 저장 및 마지막 로그인 접수일 업데이트
	$cls_member->last_visit_update($login_id);

	//세션저장
	setSession("admin_view",      $adm_view);
?>
{"result": 200, "message": "OK", "page_link": "<?=$page_link?>"}