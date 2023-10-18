<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['booking_idx'] = chkReqRpl("booking_idx", null, "", "POST", "INT");
    $params['status']      = chkReqRpl("status", "", "10", "POST", "STR");

    if (chkBlank($params['booking_idx'])) fnMsgJson(502, "예약정보 고유번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['status'])) fnMsgJson(503, "상태 값이 유효하지 않습니다.", "");

    $cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;

    //예약정보 불러오기
    $booking_view = $cls_booking->choice_tour_booking_view($params['booking_idx']);
    if ($booking_view == false) fnMsgJson(504, "일치하는 예약정보가 없습니다.", "");

	//상품정보 불러오기
	$goods_view = $cls_goods->goods_view($booking_view['goods_idx']);
	if ($goods_view == false) fnMsgJson(505, "일치하는 상품정보가 없습니다.", "");


    $params['goods_idx']      = $booking_view['goods_idx'];
    $params['rental_sdate']   = $booking_view['rental_sdate'];
    $params['payment_method'] = 'ADM';                              // 관리자 결제완료 처리시만 사용
	$params['upt_ip']         = $NOW_IP;
    $params['upt_id']         = $MEM_ADM['usr_id'];
	if (!$cls_booking->choice_tour_booking_status_proc($params)) fnMsgJson(506, "수정 처리중 오류가 발생되었습니다.", "");
?>
{"result": 200, "message": "OK"}
