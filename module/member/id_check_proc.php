<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

	$params['usr_id'] = chkReqRpl("usr_id", "", 20, "POST", "STR");

	if (chkBlank($params['usr_id'])) fnMsgJson(502, "아이디 값이 유효하지 않습니다.", "");

	$cls_member = new CLS_MEMBER;

	if ($cls_member->is_id_check($params['usr_id'])) fnMsgJson(503, "사용할 수 없는 아아이디 입니다.", "");
?>
{"result": 200, "message": "OK"}
