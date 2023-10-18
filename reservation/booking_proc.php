<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

    $params['booking_num']      = getCreateOrderNum();
	$params['goods_idx']        = chkReqRpl("goods_idx", null, "", "POST", "INT");
	$params['out_date']         = chkReqRpl("out_date", "", "10", "POST", "STR");
    $params['out_airline']      = chkReqRpl("out_airline", "", "50", "POST", "STR");
    $params['in_date']          = chkReqRpl("in_date", "", "10", "POST", "STR");
    $params['in_airline']       = chkReqRpl("in_airline", "", "50", "POST", "STR");
    $params['hotel']            = chkReqRpl("hotel", "", "50", "POST", "STR");
    $params['rental_sdate']      = chkReqRpl("rental_date", "", "10", "POST", "STR");
    $params['rental_hour']      = chkReqRpl("rental_hour", "", "2", "POST", "STR");
    $params['rental_minute']    = chkReqRpl("rental_minute", "", "2", "POST", "STR");
    $params['rental_day']       = chkReqRpl("rental_day", null, "", "POST", "INT");
    $params['pickup_area']      = chkReqRpl("pickup_area", "", "200", "POST", "STR");
    $params['return_area']      = chkReqRpl("return_area", "", "200", "POST", "STR");
    $params['name']             = chkReqRpl("name_kor", "", "20", "POST", "STR");
    $params['eng_name1']        = chkReqRpl("name_eng1", "", "10", "POST", "STR");
    $params['eng_name2']        = chkReqRpl("name_eng2", "", "30", "POST", "STR");
    $params['phone']            = chkReqRpl("booking_phone", "", "30", "POST", "STR");
    $params['email']            = chkReqRpl("booking_email", "", "30", "POST", "STR");
    $params['adult_cnt']        = chkReqRpl("adult_cnt", 0, "", "POST", "INT");
    $params['child_cnt']        = chkReqRpl("child_cnt", 0, "", "POST", "INT");
    $params['infant_cnt']       = chkReqRpl("infant_cnt", 0, "", "POST", "INT");
    $params['infant_seat_cnt']  = chkReqRpl("infant_seat_cnt", 0, "", "POST", "INT");
    $params['child_seat_cnt']   = chkReqRpl("child_seat_cnt", 0, "", "POST", "INT");
    $params['booster_seat_cnt'] = chkReqRpl("booster_seat_cnt", 0, "", "POST", "INT");
    $params['add_option_1']     = chkReqRpl("add_option_1", "N", "1", "POST", "STR");
    $params['add_option_2']     = chkReqRpl("add_option_2", "N", "1", "POST", "STR");
    $params['airport_meeting']  = chkReqRpl("airport_meeting", "N", "1", "POST", "STR");
    $params['booking_memo']     = chkReqRpl("booking_memo", "", "2000", "POST", "STR");

    if (chkBlank($params['goods_idx'])) fnMsgJson(501, "여행상품 고유번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['out_date']) || !isDate($params['out_date'])) fnMsgJson(502, "한국 출발일 값이 유효하지 않습니다.", "");
    if (chkBlank($params['out_airline'])) fnMsgJson(503, "한국 출발 항공편 값이 유효하지 않습니다.", "");
    //if (chkBlank($params['in_date']) || !isDate($params['in_date'])) fnMsgJson(504, "귀국일 값이 유효하지 않습니다.", "");
    //if (chkBlank($params['in_airline'])) fnMsgJson(505, "귀국일 항공사 값이 유효하지 않습니다.", "");
    if (chkBlank($params['hotel'])) fnMsgJson(506, "투숙 호텔 값이 유효하지 않습니다.", "");
    if (chkBlank($params['rental_sdate']) || !isDate($params['rental_sdate'])) fnMsgJson(507, "렌트날짜 값이 유효하지 않습니다.", "");
    if (chkBlank($params['rental_hour'])) fnMsgJson(508, "렌트날짜(시간) 값이 유효하지 않습니다.", "");
    if (chkBlank($params['rental_minute'])) fnMsgJson(509, "렌트날짜(분) 값이 유효하지 않습니다.", "");
    if (chkBlank($params['rental_day'])) fnMsgJson(510, "렌트기간 값이 유효하지 않습니다.", "");
    if (chkBlank($params['pickup_area'])) fnMsgJson(511, "인수/픽업 위치 값이 유효하지 않습니다.", "");
    if (chkBlank($params['return_area'])) fnMsgJson(512, "차량반납 위치 값이 유효하지 않습니다.", "");
    if (chkBlank($params['name'])) fnMsgJson(513, "예약자 이름 값이 유효하지 않습니다.", "");
    if (chkBlank($params['eng_name1'])) fnMsgJson(514, "예약자 영문 이름(성) 값이 유효하지 않습니다.", "");
    if (chkBlank($params['eng_name2'])) fnMsgJson(515, "예약자 영문 이름(이름) 값이 유효하지 않습니다.", "");
    if (chkBlank($params['phone']) || !isDataCheck($params['phone'], 'phone2')) fnMsgJson(516, "예약자 휴대번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['email']) || !isDataCheck($params['email'], 'email')) fnMsgJson(517, "예약자 이메일 값이 유효하지 않습니다.", "");
    //if ($params['adult_cnt'] == 0) fnMsgJson(515, "여행인원(성인) 값이 유효하지 않습니다.", "");


    /* 20211108 | 추후사용예정
    for ($i=1; $i<=2; $i++) {
        $params['driver_name'.$i]                = chkReqRpl("driver_name_kor".$i, "", "20", "POST", "STR");
        $params['driver_name_eng1'.$i]           = chkReqRpl("driver_name_eng1".$i, "", "10", "POST", "STR");
        $params['driver_name_eng2'.$i]           = chkReqRpl("driver_name_eng2".$i, "", "30", "POST", "STR");
        $params['driver_name_eng'.$i]            = $params['driver_name_eng2'.$i] ." ". $params['driver_name_eng1'.$i];
        $params['driver_zipcode'.$i]             = chkReqRpl("driver_zipcode".$i, "", "6", "POST", "STR");
        $params['driver_addr'.$i]                = chkReqRpl("driver_addr".$i, "", "100", "POST", "STR");
        $params['driver_addr_detail'.$i]         = chkReqRpl("driver_addr_detail".$i, "", "100", "POST", "STR");
        $params['driver_local_addr'.$i]          = chkReqRpl("driver_local_addr".$i, "", "200", "POST", "STR");
        $params['driver_phone'.$i]               = chkReqRpl("driver_phone".$i, "", "20", "POST", "STR");
        $params['driver_birthdate'.$i]           = chkReqRpl("driver_birthdate".$i, "", "10", "POST", "STR");
        $params['driver_license'.$i]             = chkReqRpl("driver_license".$i, "", "50", "POST", "STR");
        $params['driver_license_expiry_date'.$i] = chkReqRpl("driver_license_expiry_date".$i, "", "10", "POST", "STR");

        if ($params['driver_addr'.$i] != "" || $params['driver_addr_detail'.$i] != "") {
            $params['driver_home_addr'.$i] = "[". $params['driver_zipcode'.$i] ."] ". $params['driver_addr'.$i] . iif($params['driver_addr_detail'.$i]!="", " ".$params['driver_addr_detail'.$i], "");
        }
        if ($params['driver_birthdate'.$i] != "") {
            $params['driver_birthdate'.$i] = formatDates($params['driver_birthdate'.$i], 'Y-m-d');
        }
        if ($params['driver_license_expiry_date'.$i] != "") {
            $params['driver_license_expiry_date'.$i] = formatDates($params['driver_license_expiry_date'.$i], 'Y-m-d');
        }

        if ($i==1 || $params['driver_name'.$i]!='' || $params['driver_name_eng1'.$i]!='' || $params['driver_name_eng2'.$i]!=''
            || $params['driver_zipcode'.$i]!='' || $params['driver_addr'.$i]!='' || $params['driver_addr_detail'.$i]!=''
            || $params['driver_local_addr'.$i]!='' || $params['driver_phone'.$i]!='' || $params['driver_birthdate'.$i]!=''
            || $params['driver_license'.$i]!='' || $params['driver_license_expiry_date'.$i]!=''
        ) {
            if (chkBlank($params['driver_name'.$i])) fnMsgJson(517, "운전자$i 의 이름 값이 유효하지 않습니다.", "");
            if (chkBlank($params['driver_name_eng1'.$i])) fnMsgJson(518, "운전자$i 의 영문 이름(성) 값이 유효하지 않습니다.", "");
            if (chkBlank($params['driver_name_eng2'.$i])) fnMsgJson(519, "운전자$i 의 영문 이름(이름) 값이 유효하지 않습니다.", "");
            if (chkBlank($params['driver_addr'.$i])) fnMsgJson(520, "운전자$i 의 한국주소 값이 유효하지 않습니다.", "");
            if (chkBlank($params['driver_local_addr'.$i])) fnMsgJson(521, "운전자$i 의 현지주소 값이 유효하지 않습니다.", "");
            if (chkBlank($params['driver_phone'.$i]) || !isDataCheck($params['driver_phone'.$i], 'phone2')) fnMsgJson(522, "운전자$i 의 휴대번호 값이 유효하지 않습니다.", "");
            if (chkBlank($params['driver_birthdate'.$i]) || !isDate($params['driver_birthdate'.$i])) fnMsgJson(523, "운전자$i 의 생년월일 값이 유효하지 않습니다.", "");
            if (chkBlank($params['driver_license'.$i])) fnMsgJson(524, "운전자$i의 운전면허증 번호 값이 유효하지 않습니다.", "");
            if (chkBlank($params['driver_license_expiry_date'.$i]) || !isDate($params['driver_license_expiry_date'.$i])) fnMsgJson(525, "운전자$i 의 운전면허증 만료일 값이 유효하지 않습니다.", "");
        }
    }
    */

    //정책적용 시작일
    $apply_policy_221101 = iif(datediff('day', '2022-11-01', date('Y-m-d')) >= 0, true, false); //2022.11.01 정책적용
    if ($DEV_IP) {
        $apply_policy_221101 = true;
    }

    $cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;
    $cls_jwt = new CLS_JWT();
    $cls_sms = new CLS_SMS;


	//여행상품 정보 불러오기
	$goods_view = $cls_goods->goods_view($params['goods_idx'], 'Y');
    if ($goods_view == false) fnMsgJson(526, "일치하는 여행상품 정보가 없습니다.", "");

	//예약가능일 불러오기
	$stock_params['goods_idx'] = $params['goods_idx'];
    $stock_params['sch_sdate'] = date('Y-m-d');
    $stock_params['sch_stock'] = 'Y';
	$stock_list = $cls_goods->stock_list($stock_params);
	if (count($stock_list) == 0) fnMsgJson(527, "예약 가능한 차량이 없습니다. 다른 차량은 이용해주세요.", "");

    //예약가능일 체크
    $check_cnt = 0;
    for ($i=0; $i<count($stock_list); $i++) {
        if ($params['rental_sdate'] == $stock_list[$i]['sdate'] && $stock_list[$i]['stock_cnt'] > 0) {
            $check_cnt++;
        }
    }
    if ($check_cnt == 0) fnMsgJson(528, "예약 가능한 차량이 없습니다. 다른 차량은 이용해주세요.", "");


    //예약상품 정보
    $params['goods_category'] = $goods_view['category'];
    $params['goods_title']    = $goods_view['title'];
    $params['goods_options']  = "";

    $params['goods_rent_day1_amt']  = $goods_view['day1_amt'];      //렌트 1일 단가
    $params['goods_rent_day7_amt']  = $goods_view['day7_amt'];      //렌트 7일 단가
    $params['goods_rent_day30_amt'] = $goods_view['day30_amt'];     //렌트 30일 단가
    $params['goods_car_seat_amt']   = $goods_view['option_6_amt'];  //카시트 단가

    if ($goods_view['option_1'] == 'Y') {
        $params['goods_options'] .= "주유포함";
    }

    if ($goods_view['option_2'] == 'Y') {
        if ($params['goods_options'] != "") $params['goods_options'] .= ", ";
        $params['goods_options'] .= "CDW포함";
    }

    if ($goods_view['option_7'] == 'Y') {
        if ($params['goods_options'] != "") $params['goods_options'] .= ", ";
        $params['goods_options'] .= "ZDC포함";
    }

    if ($goods_view['option_8'] == 'Y') {
        if ($params['goods_options'] != "") $params['goods_options'] .= ", ";
        $params['goods_options'] .= "PAI포함";
    }

    if ($params['goods_options'] == "") $params['goods_options'] = "없음";


    //렌트시작일 체크
    if (dateDiff("d", $params['out_date'], $params['rental_sdate']) < 0) fnMsgJson(529, "렌트 시작 날짜는 출국일 보다 작을 수 없습니다.\n렌트 시작 날짜를 다시 확인해주세요.", "");

    //렌트종료일 체크
    $params['rental_edate'] = dateAdd("d", $params['rental_day'], $params['rental_sdate']);
    //if (dateDiff("d", $params['in_date'], $params['rental_edate']) > 0) fnMsgJson(530, "귀국일은 렌트 종료 날짜(". $params['rental_edate'] .") 보다 작을 수 없습니다.\n귀국일을 다시 확인해주세요.", "");

    //렌트시작시간
    $params['rental_time'] = $params['rental_hour'] .":". $params['rental_minute'];

    //예약가능 인원 체크
    //if ($params['adult_cnt'] + $params['child_cnt'] + $params['infant_cnt'] >= 10) fnMsgJson(531, "여행인원은 최대 10명까지 선택 가능합니다.", "");

    //보조시트 체크 및 가격
    $params['total_seat_amt']   = 0;
    $params['infant_seat_amt']  = 0;
    $params['child_seat_amt']   = 0;
    $params['booster_seat_amt'] = 0;
    $params['seat_free_cnt']    = $CONST_CAR_SEAT_FREE;
    if ($goods_view['option_6']=='Y') {
        $total_seat_cnt = $params['infant_seat_cnt'] + $params['child_seat_cnt'] + $params['booster_seat_cnt'];

        //if ($total_seat_cnt > 0 && $params['child_cnt'] + $params['infant_cnt'] < $total_seat_cnt) fnMsgJson(532, "소아/유아의 인원수가 보조시트 신청 개수 보다 작습니다.\n소아/유아의 인원수를 선택한 보조시트 개수 보다 같거나 크게 선택해 주세요.", "");

        //보조시트 가격
        $params['infant_seat_amt']  = $params['infant_seat_cnt'] * $goods_view['option_6_amt'];
        $params['child_seat_amt']   = $params['child_seat_cnt'] * $goods_view['option_6_amt'];
        $params['booster_seat_amt'] = $params['booster_seat_cnt'] * $goods_view['option_6_amt'];

        //보조시트 합산 가격
        if ($total_seat_cnt > $CONST_CAR_SEAT_FREE) {
            $params['total_seat_amt'] = ($params['infant_seat_amt']+$params['child_seat_amt']+$params['booster_seat_amt']) - ($goods_view['option_6_amt'] * $CONST_CAR_SEAT_FREE);
        }

        $params['total_seat_amt'] = $params['total_seat_amt'] * $params['rental_day'];
    }

    //렌트가격
    $params['rental_amt'] = 0;
    /*for ($i=1; $i<=30; $i++) {
        if ($i % 7 == 0) {
            $params['rental_amt'] = $goods_view['day7_amt'] * ($i/7);
        } else if ($i % 30 == 0) {
            $params['rental_amt'] = $goods_view['day30_amt'] * ($i/30);
        } else {
            $params['rental_amt'] += $goods_view['day1_amt'];
        }

        if ($params['rental_day'] == $i) break;
    }
    */
    for ($i=1; $i<=30; $i++) {
        if (strpos("10019, 10020", (string)$params['goods_idx']) !== false) {
            $params['rental_amt'] = $goods_view['day1_amt'] * $i;
        } else {
            if ($i < $GOODS_DC_PERIOD_DAY) {
                $params['rental_amt'] = $goods_view['day1_amt'] * $i;
            } else {
                $params['rental_amt'] = round(($goods_view['day1_amt'] - ($goods_view['day1_amt'] * $GOODS_DC_RATE)) * $i);
            }
        }

        if ($params['rental_day'] == $i) break;
    }

    //추가선택사항 가격
    $params['add_option_1_amt']        = 0;
    $params['add_option_1_flag']       = 'N';
    $params['add_option_2_amt']        = 0;
    $params['add_option_2_flag']       = 'N';
    $params['airport_meeting_amt']     = $goods_view['option_4_amt'];
    $params['airport_meeting_amt_chk'] = 0;
    $params['airport_meeting_flag']    = 'N';
    if ($goods_view['option_3']=='Y') {
        //아이스박스
        if ($params['add_option_1'] == 'Y') {
            $params['add_option_1_amt']  = $goods_view['option_3_amt'];
        }
        $params['add_option_1_flag'] = 'Y';
    }
    if ($goods_view['option_4']=='Y') {
        //공항픽업
        if ($params['airport_meeting'] == 'Y') {
            $params['airport_meeting_amt_chk']  = $goods_view['option_4_amt'];
        }
        $params['airport_meeting_flag'] = 'Y';
    }
    if ($goods_view['option_5']=='Y') {
        //네비게이션
        if ($params['add_option_1'] == 'Y') {
            $params['add_option_2_amt'] = $goods_view['option_5_amt'];
        }
        $params['add_option_2_flag'] = 'Y';
    }

    //차량 공항반납 추가금액
    $params['airport_car_return_amt'] = 0;
    $params['airport_car_return_flag'] = 'N';
    if ($apply_policy_221101) {
        if (strpos($params['return_area'], "AIRPORT PARKING") !== false) {
            $params['airport_car_return_amt'] = preg_replace("/[^0-9]*/s", "", explode("(", $params['return_area'])[1]);
            $params['airport_car_return_flag'] = 'Y';
        }
    }


    //총 렌트 비용
    $params['total_rental_amt'] = $params['rental_amt'] + $params['total_seat_amt'] + $params['airport_meeting_amt_chk'] + $params['add_option_1_amt'] + $params['add_option_2_amt'] + $params['airport_car_return_amt'];

    //예약 대행 수수료
    $params['booking_agency_fee'] = $goods_view['agency_fee'];


    //예약 등록 및 수정
	$params['upt_ip'] = $NOW_IP;
	$params['upt_id'] = $MEM_USR['usr_id'];
	$params['reg_ip'] = $NOW_IP;
	$params['reg_id'] = $MEM_USR['usr_id'];
	if (!$cls_booking->booking_save_proc($params)) fnMsgJson(533, "저장 처리중 오류가 발생되었습니다.", "");

    $cls_jwt->session_check=true;
    $token = $cls_jwt->hashing(array(
            'booking_num'=> $params['booking_num'],
            'booker_phone'=> $params['phone']
        ));


    //카카오 알림톡 회원발송
    $cls_sms->kakao_send('TJ_5940', array(
        array(
            'name'=>$params['name'],
            'phone'=>$params['phone']
        )
    ), array(
        getGoodsCateName($params['goods_category']),
        $params['booking_num']
    ));
?>
{"result": 200, "message": "OK", "token": "<?=$token?>"}
