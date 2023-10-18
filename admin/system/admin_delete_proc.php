<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$usr_idx = chkReqRpl("usr_idx", null, "", "POST", "INT");

	if (chkBlank($usr_idx)) fnMsgJson(502, "운영자 정보 값이 유효하지 않습니다.", "");

	$cls_member = new CLS_MEMBER;

	//회원정보 삭제
	$params['usr_idx']   = $usr_idx;
	$params['upt_ip']    = $NOW_IP;
	$params['upt_id']    = $MEM_ADM['usr_id'];
	if (!$cls_member->admin_delete($params)) fnMsgJson(508, "수정 처리중 오류가 발생되었습니다.", "");

?>
{"result": 200, "message": "OK"}
