<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $params['booking_idx'] = chkReqRpl("booking_idx", null, "", "POST", "INT");
    $params['notice']      = chkReqRpl("notice", "", "max", "POST", "STR");

    if (chkBlank($params['booking_idx'])) fnMsgJson(502, "예약정보 고유번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['notice'])) fnMsgJson(503, "담당자 안내문 값이 유효하지 않습니다.", "");

    $cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;

    //예약정보 불러오기
    $booking_view = $cls_booking->booking_view($params['booking_idx']);
    if ($booking_view == false) fnMsgJson(504, "일치하는 예약정보가 없습니다.", "");

	$params['upt_ip'] = $NOW_IP;
    $params['upt_id'] = $MEM_ADM['usr_id'];
	if (!$cls_booking->notice_save_proc($params)) fnMsgJson(505, "수정 처리중 오류가 발생되었습니다.", "");
?>
{"result": 200, "message": "OK"}
