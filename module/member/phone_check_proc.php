<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

	$params['usr_phone'] = chkReqRpl("usr_phone", "", 20, "POST", "STR");
	$params['old_phone'] = chkReqRpl("old_phone", "", 20, "POST", "STR");

	if (!isDataCheck($params['usr_phone'], "phone")) fnMsgJson(501, "휴대폰번호 값이 유효하지 않습니다.", "");
	if ($params['old_phone'] != '' && !isDataCheck($params['old_phone'], "phone")) fnMsgJson(502, "이전 휴대폰번호 값이 유효하지 않습니다.", "");

	$cls_member = new CLS_MEMBER;

	if ($cls_member->is_phone_check($params['usr_phone'], $params['old_phone'])) fnMsgJson(502, "이미 사용중인 휴대폰번호가 있습니다.", "");
?>
{"result": 200, "message": "OK"}