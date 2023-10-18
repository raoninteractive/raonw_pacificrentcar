<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$old_pwd  = chkReqRpl("old_pwd", "", 20, "POST", "STR");
	$new_pwd  = chkReqRpl("new_pwd", "", 20, "POST", "STR");
	$new_pwd2 = chkReqRpl("new_pwd2", "", 20, "POST", "STR");

	if (chkBlank($old_pwd)) fnMsgJson(502, "이전 비밀번호 값이 유효하지 않습니다.", "");
	if (chkBlank($new_pwd)) fnMsgJson(503, "신규 비밀번호 값이 유효하지 않습니다.", "");
	if (chkBlank($new_pwd2)) fnMsgJson(504, "신규 비밀번호 확인 값이 유효하지 않습니다.", "");

	if (!isDataCheck($new_pwd, "password")) fnMsgJson(505, "비밀번호는 영문+숫자 조합으로 입력해주세요.", "");
	if (!isDataCheck($new_pwd, "specialword")) fnMsgJson(506, "허용되지 않은 특수문자가 포함되어있습니다.\n특수문자는 ｀! @ $ % ^ & * _ ~ -´ 만 사용 가능합니다.", "");
	if ($old_pwd == $new_pwd) fnMsgJson(507, "이전 비밀번호와 동일합니다. 비밀번호를 다시 확인해주세요.", "");
	if ($new_pwd != $new_pwd2) fnMsgJson(508, "비밀번호가 일치하지 않습니다. 비밀번호를 다시 확인해주세요.", "");

	if (!$db = connect_mysql()) fnMsgJson(509, "DB연결에 실패하였습니다. 관리자에게 문의주세요.", "");

	$cls_member = new CLS_MEMBER;
	$adm_view = $cls_member->admin_view($MEM_ADM['usr_id']);

	if ($adm_view == false) fnMsgJson(510, "일치하는 관리자 정보가 없습니다.", "");

	//이전 비밀번호 체크
	if ($adm_view['usr_pwd'] != encryption($old_pwd)) fnMsgJson(511, "이전 비밀번호가 일치하지 않습니다. 비밀번호를 다시 확인해주세요.", "");

	//비밀번호 변경
	$params['usr_idx'] = $MEM_ADM['usr_idx'];
	$params['usr_pwd'] = encryption($new_pwd);;
	$params['upt_ip']  = $NOW_IP;
	$params['upt_id']  = $MEM_ADM['usr_id'];;
	if (!$cls_member->admin_passwd_save($params)) fnMsgJson(512, "비밀번호 변경 처리중 오류가 발생되었습니다.", "");
?>
{"result": 200, "messgae": "OK"}