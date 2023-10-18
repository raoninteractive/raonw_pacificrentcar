<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$usr_idx   = chkReqRpl("usr_idx", null, "", "POST", "INT");
	$usr_id    = chkReqRpl("usr_id", "", 20, "POST", "STR");
	$usr_name  = chkReqRpl("usr_name", "", 50, "POST", "STR");
	$usr_email = chkReqRpl("usr_email", "", 50, "POST", "STR");
	$usr_phone = chkReqRpl("usr_phone", "", 20, "POST", "STR");
	$status    = chkReqRpl("status", "N", 1, "POST", "STR");
	$usr_gubun = chkReqRpl("usr_gubun", null, "", "POST", "INT");

	if (chkBlank($usr_id)) fnMsgJson(502, "아이디 값이 유효하지 않습니다.", "");
	if (chkBlank($usr_name)) fnMsgJson(503, "이름 값이 유효하지 않습니다..", "");
	if ($usr_email != "" && !isDataCheck($usr_email, "email")) fnMsgJson(504, "이메일 값이 유효하지 않습니다.", "");
	if ($usr_phone != "" && !isDataCheck($usr_phone, "phone")) fnMsgJson(505, "휴대폰번호 값이 유효하지 않습니다.", "");
	if (chkBlank($usr_gubun)) fnMsgJson(506, "권한등급 값이 유효하지 않습니다..", "");

	if (chkBlank($usr_idx)) {
		$passwd = encryption(CONST_RESET_PWD . right($usr_phone,4));
	} else {
		$passwd = "";
	}


	$cls_member = new CLS_MEMBER;
	$admin_view = $cls_member->admin_view($usr_idx);

	//아이디 중복 체크
	if ($cls_member->is_id_check($usr_id, $admin_view['usr_id'])) fnMsgJson(507, "이미 사용중인 아이디가 있습니다.", "");


	//회원정보 수정
	$params['usr_idx']   = $usr_idx;
	$params['usr_id']    = $usr_id;
	$params['usr_pwd']   = $passwd;
	$params['usr_name']  = $usr_name;
	$params['usr_email'] = $usr_email;
	$params['usr_phone'] = $usr_phone;
	$params['status']    = $status;
	$params['usr_gubun'] = $usr_gubun;
	$params['upt_ip']    = $NOW_IP;
	$params['upt_id']    = $MEM_ADM['usr_id'];
	$params['reg_ip']    = $NOW_IP;
	$params['reg_id']    = $MEM_ADM['usr_id'];
	if (!$cls_member->admin_save($params)) fnMsgJson(508, "수정 처리중 오류가 발생되었습니다.", "");

?>
{"result": 200, "message": "OK"}
