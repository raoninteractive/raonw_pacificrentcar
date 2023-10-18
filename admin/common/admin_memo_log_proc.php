<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['idx']        = chkReqRpl("idx", null, "", "POST", "INT");
	$params['section']    = chkReqRpl("section", "", 50, "POST", "STR");
	$params['gubun']      = chkReqRpl("gubun", "", 50, "POST", "STR");
	$params['content'] = chkReqRpl("admin_memo", "", "max", "POST", "STR");

    if (chkBlank($params['section'])) fnMsgJson(502, "위치 값이 유효하지 않습니다.", "");
    if (chkBlank($params['gubun'])) fnMsgJson(503, "구분 값이 유효하지 않습니다.", "");
    if (chkBlank($params['idx']) && chkBlank($params['content'])) fnMsgJson(504, "내용 값이 유효하지 않습니다.", "");

	$cls_admin_memo = new CLS_ADMIN_MEMO;

    $params['writer'] = $MEM_ADM['usr_name'];
	$params['upt_ip'] = $NOW_IP;
    $params['upt_id'] = $MEM_ADM['usr_id'];
    $params['reg_ip'] = $NOW_IP;
    $params['reg_id'] = $MEM_ADM['usr_id'];

    if (chkBlank($params['idx'])) {
        //등록
        if (!$cls_admin_memo->memo_save($params)) fnMsgJson(505, "등록 처리중 오류가 발생되었습니다.", "");
    } else {
        //삭제
        if (!$cls_admin_memo->memo_delete($params)) fnMsgJson(506, "삭제 처리중 오류가 발생되었습니다.", "");
    }
?>
{"result": 200, "message": "OK"}
