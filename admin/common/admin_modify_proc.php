<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$usr_id    = chkReqRpl("usr_id", "", 20, "POST", "STR");
	$usr_name  = chkReqRpl("usr_name", "", 50, "POST", "STR");
	$usr_email = chkReqRpl("usr_email", "", 50, "POST", "STR");
	$usr_phone = chkReqRpl("usr_phone", "", 20, "POST", "STR");
	$passwd    = chkReqRpl("passwd", "", 20, "POST", "STR");
	$passwd_re = chkReqRpl("passwd_re", "", 20, "POST", "STR");

	if (chkBlank($usr_id)) fnMsgJson(502, "아이디 값이 유효하지 않습니다.", "");
	if (chkBlank($usr_name)) fnMsgJson(503, "이름 값이 유효하지 않습니다..", "");
	if ($usr_email != "" && !isDataCheck($usr_email, "email")) fnMsgJson(504, "이메일 값이 유효하지 않습니다.", "");
	if ($usr_phone != "" && !isDataCheck($usr_phone, "phone")) fnMsgJson(505, "휴대폰번호 값이 유효하지 않습니다.", "");
	if (chkBlank($passwd)) fnMsgJson(506, "비밀번호 값이 유효하지 않습니다.", "");
	if (chkBlank($passwd_re)) fnMsgJson(507, "비밀번호 확인 값이 유효하지 않습니다.", "");
	if (!isDataCheck($passwd, "password")) fnMsgJson(508, "비밀번호는 영문+숫자 조합으로 입력해주세요.", "");
	if (!isDataCheck($passwd, "specialword")) fnMsgJson(509, "허용되지 않은 특수문자가 포함되어있습니다.\n특수문자는 ｀! @ $ % ^ & * _ ~ -´ 만 사용 가능합니다.", "");
	if ($passwd != $passwd_re) fnMsgJson(510, "비밀번호가 일치하지 않습니다. 비밀번호를 다시 확인해주세요.", "");

	$passwd = encryption($passwd);

	$cls_member = new CLS_MEMBER;
	$adm_view = $cls_member->admin_view($MEM_ADM['usr_id']);

	if ($adm_view == false) fnMsgJson(511, "일치하는 관리자 정보가 없습니다.", "");

	//아이디 중복 체크
	if ($cls_member->is_id_check($usr_id, $adm_view['usr_id'])) fnMsgJson(512, "이미 사용중인 아이디가 있습니다.", "");

	//회원정보 수정
	$params['usr_idx']     = $MEM_ADM['usr_idx'];
	$params['usr_id']      = $usr_id;
	$params['usr_pwd']     = $passwd;
	$params['usr_pwd_old'] = $adm_view['usr_pwd'];
	$params['usr_name']    = $usr_name;
	$params['usr_email']   = $usr_email;
	$params['usr_phone']   = $usr_phone;
	$params['upt_ip']      = $NOW_IP;
	$params['upt_id']      = $MEM_ADM['usr_id'];
	if (!$cls_member->amdin_modify_save($params)) fnMsgJson(513, "수정 처리중 오류가 발생되었습니다.", "");

?>
{"result": 200, "message": "OK"}
