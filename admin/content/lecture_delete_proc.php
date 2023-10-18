<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");


    $params['class_idx'] = chkReqRpl("class_idx", null, "", "POST", "INT");
	$params['idx']       = chkReqRpl("idx", null, "", "POST", "INT");

    if (chkBlank($params['class_idx'])) fnMsgJson(502, "강좌 고유번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['idx'])) fnMsgJson(503, "강의 고유번호 값이 유효하지 않습니다.", "");

	$cls_content = new CLS_CONTENT;

	//강좌 수강신청 내역중 강의 대기,시청중 수 체크
	if ($cls_content->class_apply_lecture_count($params['idx']) > 0) fnMsgJson(504, "현재 강의를 수강 시청중인 회원이 있습니다.\n시수강 시청중인 강의는 삭제가 불가능합니다.", "");

	//강의 삭제
	$params['upt_ip'] = $NOW_IP;
	$params['upt_id'] = $MEM_ADM['usr_id'];
	if ($cls_content->lecture_delete($params) == 0) fnMsgJson(505, "삭제 처리중 오류가 발생되었습니다.", "");
?>
{"result": 200, "message": "OK"}
