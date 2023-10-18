<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['usr_idx'] = chkReqRpl("usr_idx", null, "", "POST", "INT");

	if (chkBlank($params['usr_idx'])) fnMsgJson(502, "회원 정보 값이 유효하지 않습니다.", "");

	$cls_member = new CLS_MEMBER;

	//비밀번호 초기화
	$params['upt_ip']    = $NOW_IP;
	$params['upt_id']    = $MEM_ADM['usr_id'];
	if (!$cls_member->passwd_reset_save($params)) fnMsgJson(503, "일치하는 회원정보가 없거나, 변경된 내역이 없습니다.", "");

?>
{"result": 200, "message": "OK"}
