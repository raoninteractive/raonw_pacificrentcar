<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $fnum = chkReqRpl("fnum", null, "", "POST", "INT");
	$usr_idx = chkReqRpl("usr_idx", null, "", "POST", "INT");

    if (chkBlank($fnum)) fnMsgJson(502, "파일번호 값이 유효하지 않습니다.", "");
    if (chkBlank($usr_idx)) fnMsgJson(503, "회원 정보 값이 유효하지 않습니다.", "");

	$cls_member = new CLS_MEMBER;

	//파일 삭제 처리
	if (!$cls_member->user_file_del($fnum, $usr_idx)) fnMsgJson(504, "일치하는 파일정보가 없거나, 변경된 내역이 없습니다.", "");

?>
{"result": 200, "message": "OK"}
