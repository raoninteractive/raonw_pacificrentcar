<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['idx'] = chkReqRpl("idx", null, "", "POST", "INT");

	if (chkBlank($params['idx'])) fnMsgJson(502, "SMS 발송정보 값이 유효하지 않습니다.", "");

    $cls_sms = new CLS_SMS;

	//예약발송취소
	$params['upt_ip'] = $NOW_IP;
	$params['upt_id'] = $MEM_ADM['usr_id'];
	if ($cls_sms->send_cancel_svae($params) == false) fnMsgJson(503, "변경된 내역이 없습니다.", "");

?>
{"result": 200, "message": "OK"}
