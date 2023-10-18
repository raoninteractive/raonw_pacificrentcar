<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

	$params['nick_name'] = chkReqRpl("nick_name", "", 20, "POST", "STR");

	if (chkBlank($params['nick_name'])) fnMsgJson(502, "닉네임 값이 유효하지 않습니다.", "");

	$cls_member = new CLS_MEMBER;

	if ($cls_member->is_nick_check($params['nick_name'])) fnMsgJson(503, "이미 사용중인 닉네임이 있습니다.", "");
?>
{"result": 200, "message": "OK"}
