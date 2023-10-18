<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
    if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $params['section']          = chkReqRpl("section", "", 50, "POST", "STR");
	$params['send_msg']         = chkReqRpl("send_msg", "", "max", "POST", "STR");
	$params['up_file']          = chkReqRpl("up_file", "", 50, "POST", "STR");
	$params['send_gubun']       = chkReqRpl("send_gubun", "I", 1, "POST", "STR");
	$params['send_date']        = chkReqRpl("send_date", "", 10, "POST", "STR");
	$params['send_date_hour']   = chkReqRpl("send_date_hour", "", 2, "POST", "STR");
	$params['send_date_minute'] = chkReqRpl("send_date_minute", "", 2, "POST", "STR");
    $params['sender_tel']       = chkReqRpl("sender_tel", $CONST_SMS_SENDER[0], 20, "POST", "STR");
    $params['recipient_info']   = chkReqRpl("recipient_info", "", "max", "POST", "STR");


    if (chkBlank($params['section'])) fnMsgJson(502, "문자발송구역 값이 유효하지 않습니다.", "");
	if (chkBlank($params['send_msg'])) fnMsgJson(503, "문자내용 값이 유효하지 않습니다.", "");
    if (chkBlank($params['send_gubun'])) fnMsgJson(504, "발송방법 값이 유효하지 않습니다.", "");
    if ($params['send_gubun'] == "R") {
        if (chkBlank($params['send_date'])) fnMsgJson(505, "예약발송일 값이 유효하지 않습니다.", "");
        if (chkBlank($params['send_date_hour'])) fnMsgJson(506, "예약시간 값이 유효하지 않습니다.", "");
        if (chkBlank($params['send_date_minute'])) fnMsgJson(507, "예약시간 값이 유효하지 않습니다.", "");

        $params['reserve_date'] = $params['send_date'] ." ". $params['send_date_hour'] .":". $params['send_date_minute'] .":00";
    } else if ($params['send_gubun'] == "I") {
        $params['reserve_date'] = "";
    } else {
        fnMsgJson(508, "발송방법 값이 유효하지 않습니다.", "");
    }
    if (chkBlank($params['recipient_info'])) fnMsgJson(509, "담당자 연락처 값이 유효하지 않습니다.", "");

    $cls_sms = new CLS_SMS;

    $recipient_info = explode(",", $params['recipient_info']);
    for ($i=0; $i<count($recipient_info); $i++) {
        $item = explode("|", trim($recipient_info[$i]));

        $params['usr_id']      = $item[0];
        $params['rec_name']    = $item[1];
        $params['rec_tel']     = $item[2];
        $params['sender_name'] = SITE_NAME;
        $params['reg_ip']      = $NOW_IP;
        $params['reg_id']      = $MEM_ADM['usr_id'];

        if ($cls_sms->send_save($params) == false) fnMsgJson(510, "문자 발송 처리중 오류가 발생되었습니다.", "");
    }
?>
{"result": 200, "message": "OK"}
