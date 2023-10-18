<?
    require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

    if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

    $params['token'] = chkReqRpl("token", "", "max", "POST", "STR");

    if (chkBlank($params['token'])) fnMsgJson(501, "예약정보 값이 유효하지 않습니다.", "");

	$cls_booking = new CLS_BOOKING;
	$cls_jwt = new CLS_JWT();

	//토큰정보 확인
	$token_data = $cls_jwt->dehashing($token);
	if (chkBlank($token_data)) fnMsgJson(502, "잘못된 요청정보 입니다.", "");

    //예약접수 정보 불러오기(예약번호+휴대번호 조회)
    $booking_view = $cls_booking->booking_view('', $token_data['booking_num'], '', $token_data['booker_phone'], '');
	if ($booking_view == false) fnMsgJson(503, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "");


    $params['booking_idx'] = $booking_view['idx'];
	$params['upt_ip']      = $NOW_IP;
    $params['upt_id']      = $MEM_USR['usr_id'];
	if (!$cls_booking->cancel_req_proc($params)) fnMsgJson(504, "예약취소요청 처리중 오류가 발생되었습니다.", "");
?>
{"result": 200, "message": "OK"}