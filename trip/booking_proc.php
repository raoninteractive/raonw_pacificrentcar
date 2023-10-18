<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

    $params['booking_num']  = getCreateOrderNum();
	$params['goods_idx']    = chkReqRpl("goods_idx", null, "", "POST", "INT");
    $params['tour_date']    = chkReqRpl("tour_date", "", 10, "POST", "STS");
    $params['adult_cnt']    = chkReqRpl("adult_cnt", 0, "", "POST", "INT");
    $params['child_cnt']    = chkReqRpl("child_cnt", 0, "", "POST", "INT");
    $params['infant_cnt']   = chkReqRpl("infant_cnt", 0, "", "POST", "INT");
    $params['pickup_area']  = chkReqRpl("pickup_area", "", 50, "POST", "STS");
    $params['tour_time']    = chkReqRpl("tour_time", "", 10, "POST", "STS");
    $params['name']         = chkReqRpl("name_kor", "", "20", "POST", "STR");
    $params['eng_name1']    = chkReqRpl("name_eng1", "", "10", "POST", "STR");
    $params['eng_name2']    = chkReqRpl("name_eng2", "", "30", "POST", "STR");
    $params['phone']        = chkReqRpl("booking_phone", "", "30", "POST", "STR");
    $params['email']        = chkReqRpl("booking_email", "", "30", "POST", "STR");
    $params['booking_memo'] = chkReqRpl("booking_memo", "", "2000", "POST", "STR");

    if (chkBlank($params['goods_idx'])) fnMsgJson(501, "상품 고유번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['tour_date'])) fnMsgJson(502, "투어 날짜 값이 유효하지 않습니다.", "");
    if (chkBlank($params['pickup_area'])) fnMsgJson(503, "픽업 장소 고유번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['tour_time'])) fnMsgJson(504, "시간 고유번호 값이 유효하지 않습니다.", "");
    if (($params['adult_cnt']+$params['child_cnt']) == 0) fnMsgJson(505, "투어 인원 값이 유효하지 않습니다.", "");
    if (chkBlank($params['name'])) fnMsgJson(506, "예약자 이름 값이 유효하지 않습니다.", "");
    if (chkBlank($params['eng_name1'])) fnMsgJson(507, "예약자 영문 이름(성) 값이 유효하지 않습니다.", "");
    if (chkBlank($params['eng_name2'])) fnMsgJson(508, "예약자 영문 이름(이름) 값이 유효하지 않습니다.", "");
    if (chkBlank($params['phone']) || !isDataCheck($params['phone'], 'phone2')) fnMsgJson(509, "예약자 휴대번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['email']) || !isDataCheck($params['email'], 'email')) fnMsgJson(510, "예약자 이메일 값이 유효하지 않습니다.", "");


    $cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;
    $cls_jwt = new CLS_JWT();


	//여행상품 정보 불러오기
	$goods_view = $cls_goods->choice_tour_view($params['goods_idx'], 'Y');
    if ($goods_view == false) fnMsgJson(511, "일치하는 상품 정보가 없습니다.", "");


    //예약상품 정보
    $params['goods_title'] = $goods_view['title'];
    $params['adult_amt']   = $goods_view['adult_amt'];
    $params['child_amt']   = $goods_view['child_amt'];
    $params['infant_amt']  = $goods_view['infant_amt'];


    $params['total_tour_amt']     = ($goods_view['adult_amt']*$params['adult_cnt']) + ($goods_view['child_amt']*$params['child_cnt']) + ($goods_view['infant_amt']*$params['infant_cnt']);
    $params['booking_agency_fee'] = ($params['adult_cnt'] + $params['child_cnt']) * $goods_view['agency_fee'];

    //투어 가능 날짜 체크
    if (dateDiff('day', $goods_view['sdate'], $params['tour_date']) < 0) fnMsgJson(512, "투어 가능 날짜가 아닙니다. 투어 날짜를 다시 확인해주세요.", "");
    if (dateDiff('day', $goods_view['edate'], $params['tour_date']) > 0) fnMsgJson(513, "투어 가능 날짜가 아닙니다. 투어 날짜를 다시 확인해주세요.", "");


    //예약 등록 및 수정
	$params['upt_ip'] = $NOW_IP;
	$params['upt_id'] = $MEM_USR['usr_id'];
	$params['reg_ip'] = $NOW_IP;
	$params['reg_id'] = $MEM_USR['usr_id'];
	if (!$cls_booking->choice_tour_booking_save_proc($params)) fnMsgJson(533, "저장 처리중 오류가 발생되었습니다.", "");

    $cls_jwt->session_check=true;
    $token = $cls_jwt->hashing(array(
            'booking_num'=> $params['booking_num'],
            'booker_phone'=> $params['phone']
        ));
?>
{"result": 200, "message": "OK", "token": "<?=$token?>"}
