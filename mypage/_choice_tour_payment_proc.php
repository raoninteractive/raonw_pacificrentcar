<?
    require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

    if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

    $params['payment_method'] = chkReqRpl("gubun", "", "10", "POST", "STR");
    $params['booking_num']    = chkReqRpl("booking_num", "", "15", "POST", "STR");
    $params['payment_tid']    = chkReqRpl("tid", "", "100", "POST", "STR");

    if (chkBlank($params['payment_method'])) fnMsgJson(501, "구분 값이 유효하지 않습니다.", "");
    if (chkBlank($params['booking_num'])) fnMsgJson(502, "예약번호 값이 유효하지 않습니다.", "");
    if ($params['payment_method'] !='BANK' && chkBlank($params['payment_tid'])) fnMsgJson(503, "결제번호 값이 유효하지 않습니다.", "");

    $cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;
    $cls_jwt = new CLS_JWT();

    //예약접수 정보 불러오기(예약번호 조회)
    $booking_view = $cls_booking->choice_tour_booking_view('', $params['booking_num'], '', '', '');
    if ($booking_view == false) fnMsgJson(504, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "");

	//상품정보 불러오기
	$goods_view = $cls_goods->goods_view($booking_view['goods_idx']);
	if ($goods_view == false) fnMsgJson(505, "일치하는 상품정보가 없습니다.", "");

    //결제확인
    if ($params['payment_method'] != 'BANK') {
        if (!chkBlank($booking_view['payment_status'])) fnMsgJson(506, "이미 결제 처리된 예약정보 입니다.", "");
    }

    $params['booking_idx'] = $booking_view['idx'];
	$params['upt_ip']      = $NOW_IP;
    $params['upt_id']      = $MEM_USR['usr_id'];
	if (!$cls_booking->choice_tour_payment_proc($params)) fnMsgJson(507, "결제완료 처리중 오류가 발생되었습니다.", "");


    $cls_jwt->session_check=true;
    $token = $cls_jwt->hashing(array(
            'booking_num'=> $params['booking_num']
        ));
?>
{"result": 200, "message": "OK", "token": "<?=$token?>"}