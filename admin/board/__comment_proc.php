<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['bbs_idx'] = chkReqRpl("bbs_idx", null, "", "POST", "INT");
	$params['comment'] = chkReqRpl("comment", "", "max", "POST", "STR");

    if (chkBlank($params['bbs_idx'])) fnMsgJson(502, "위치 값이 유효하지 않습니다.", "");
    if (chkBlank($params['comment'])) fnMsgJson(503, "댓글 내용 값이 유효하지 않습니다.", "");

	$cls_board = new CLS_BOARD;

    $params['reg_ip'] = $NOW_IP;
    $params['reg_id'] = $MEM_ADM['usr_id'];

    if (!$cls_board->comment_save_proc($params)) fnMsgJson(504, "댓글 등록 처리중 오류가 발생되었습니다.", "");
?>
{"result": 200, "message": "OK"}
