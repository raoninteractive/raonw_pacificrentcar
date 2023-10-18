<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $params['booking_idx']    = chkReqRpl("booking_idx", null, "", "POST", "INT");
    $params['confirm_num']    = chkReqRpl("confirm_num", "", "20", "POST", "STR");
    $params['confirm_status'] = chkReqRpl("status", "", "10", "POST", "STR");

    if (chkBlank($params['booking_idx'])) fnMsgJson(502, "예약정보 고유번호 값이 유효하지 않습니다.", "");
    if ($params['confirm_status']=='30' && chkBlank($params['confirm_num'])) fnMsgJson(503, "확정서 번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['confirm_status'])) fnMsgJson(504, "확정서 상태 값이 유효하지 않습니다.", "");

    $cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;
    $cls_sms = new CLS_SMS;
    $cls_jwt = new CLS_JWT();
    $cls_jwt->expire_check = false;

    //예약정보 불러오기
    $booking_view = $cls_booking->booking_view($params['booking_idx']);
    if ($booking_view == false) fnMsgJson(505, "일치하는 예약정보가 없습니다.", "");

	$params['upt_ip'] = $NOW_IP;
    $params['upt_id'] = $MEM_ADM['usr_id'];
	if (!$cls_booking->confirm_status_proc($params)) fnMsgJson(506, "수정 처리중 오류가 발생되었습니다.", "");


	//확정서 토큰 생성
    $confirm_token = $cls_jwt->hashing(array(
		'booking_num'=> $booking_view['booking_num']
	));

    //단축 URL 생성
    $confirm_url = SITE_URL."/mypage/certificate.php?token=". $confirm_token;
    if (!naverShorturl($confirm_url, $short_url)) fnMsgJson(507, "단축 URL 생성간 오류가 발생되었습니다.", "");


    //카카오 알림톡 회원발송
    if ($params['confirm_status'] == '30') {
        //확정서발행완료
        $cls_sms->kakao_send('TL_9130', array(
            array(
                'name'=>$booking_view['name'],
                'phone'=>$booking_view['phone']
                )
        ), array(
            getGoodsCateName($booking_view['goods_category']),
            $booking_view['booking_num'],
            $params['confirm_num'],
            $short_url
        ));
    }
?>
{"result": 200, "message": "OK"}
