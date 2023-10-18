<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $params['booking_idx'] = chkReqRpl("booking_idx", null, "", "POST", "INT");

    if (chkBlank($params['booking_idx'])) fnMsgJson(502, "예약정보 고유번호 값이 유효하지 않습니다.", "");

    $cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;

    //예약정보 불러오기
    $booking_view = $cls_booking->choice_tour_booking_view($params['booking_idx']);
    if ($booking_view == false) fnMsgJson(503, "일치하는 예약정보가 없습니다.", "");

    //옵션
    $goods_options = $booking_view['goods_options'];
    $goods_options = str_replace("주유포함", "GAS", $goods_options);
    $goods_options = str_replace("포함", "", $goods_options);

    $rental_sdate = formatDates($booking_view['rental_sdate'], 'Y.m.d') .' '. $booking_view['rental_time'];
    $rental_edate = formatDates($booking_view['rental_edate'], 'Y.m.d') .' ('. $booking_view['rental_day'].iif($booking_view['rental_day']>1, 'days', 'day') .')';

    $people  = '';
    $people .= 'Adult: '. $booking_view['adult_cnt'] . iif($booking_view['adult_cnt']>1,'people','person') .'<br>';
    $people .= 'Child: '. $booking_view['child_cnt'] . iif($booking_view['child_cnt']>1,'people','person') .'<br>';
    $people .= 'Infant: '. $booking_view['infant_cnt'] . iif($booking_view['infant_cnt']>1,'people','person');

    $sheet  = '';
    $sheet .= 'Infant Assistance Sheet(~12months): '. $booking_view['infant_seat_cnt'] .'ea<br>';
    $sheet .= 'Child Assistance Sheet(12 to 24 months): '. $booking_view['child_seat_cnt'] .'ea<br>';
    $sheet .= 'Booster Sheet(24 months ~): '. $booking_view['booster_seat_cnt'].'ea';

    $add_option = '';
    if ($booking_view['add_option_1_flag'] == 'Y') {
        $add_option .= 'Ice Box: ' .iif($booking_view['add_option_1'] == 'Y', 'Yes', 'No') .'<br>';
    } else {
        $add_option .= 'Ice Box: No<br>';
    }
    if ($booking_view['add_option_2_flag'] == 'Y') {
        $add_option .= 'Navigation: ' .iif($booking_view['add_option_2'] == 'Y', 'Yes', 'No') .'<br>';;
    } else {
        $add_option .= 'Navigation: No<br>';
    }

    $airport_meeting = '';
    if ($booking_view['airport_meeting_flag'] == 'Y') {
        $airport_meeting .= iif($booking_view['airport_meeting'] == 'Y', 'Yes', 'No, Individual Movements');
    } else {
        $airport_meeting .= 'No, Individual Movements';
    }
    if ($airport_meeting!='') {
        $add_option .= 'Airport Pickup: '. $airport_meeting;
    }


    $payment  = '';
    $payment .= '<strong>$'. formatNumbers(($booking_view['total_rental_amt']+$booking_view['total_add_amt'])) .'</strong>';
    $payment .= '<span style="color:#ff7053;"> (';
    $payment .= 'Rental Amount: $'. formatNumbers($booking_view['rental_amt']) . ' / ';
    $payment .= 'Assistance Sheet: $'. formatNumbers($booking_view['total_seat_amt']). ' / ';
    $payment .= 'Additional Options: $'. formatNumbers($booking_view['add_option_1_amt'] + $booking_view['add_option_2_amt']) . ' / ';
    $payment .= 'Airport Pickup: $'. formatNumbers($booking_view['airport_meeting_amt']);
    if ($booking_view['total_add_amt'] != 0) {
        if ($booking_view['total_add_amt'] > 0) {
            $payment .= ' / Options D.C: $'. formatNumbers($booking_view['total_add_amt']);
        } else {
            $payment .= ' / Options D.C: -$'. formatNumbers(abs($booking_view['total_add_amt']));
        }
    }
    $payment .= ')</span>';


    $subject = "[". SITE_NAME ."] Hello. Please check the reservation. <". $rental_sdate .", ". $booking_view['name'] .", ". $booking_view['eng_name1'] .' '. $booking_view['eng_name2'] .">";
    $content = getEmailSendFile("/module/email/booking.send.html");
    $content = str_replace('{{site_url}}', SITE_URL, $content);
    $content = str_replace('{{상품}}', $booking_view['goods_title'], $content);
    $content = str_replace('{{옵션}}', $goods_options, $content);
    $content = str_replace('{{한국 출발 항공편}}', formatDates($booking_view['out_date'], 'Y.m.d') .' ('. $booking_view['out_airline'] .')', $content);
    $content = str_replace('{{귀국정보}}', formatDates($booking_view['in_date'], 'Y.m.d') .' ('. $booking_view['in_airline'] .')', $content);
    $content = str_replace('{{투숙호텔}}', $booking_view['hotel'], $content);
    $content = str_replace('{{픽업날짜}}', $rental_sdate, $content);
    $content = str_replace('{{반납일}}', $rental_edate, $content);
    $content = str_replace('{{픽업위치}}', $booking_view['pickup_area'], $content);
    $content = str_replace('{{반납위치}}', $booking_view['return_area'], $content);
    $content = str_replace('{{영문이름}}', $booking_view['eng_name2'] .' '. $booking_view['eng_name1'], $content);
    $content = str_replace('{{한글이름}}', $booking_view['name'], $content);
    $content = str_replace('{{휴대번호}}', $booking_view['phone'], $content);
    $content = str_replace('{{이메일}}', $booking_view['email'], $content);
    $content = str_replace('{{여행인원}}', $people, $content);
    $content = str_replace('{{아동보조시트}}', $sheet, $content);
    $content = str_replace('{{추가선택사항}}', $add_option, $content);
    //$content = str_replace('{{공항픽업}}', $airport_meeting, $content);
    $content = str_replace('{{지불금액}}', $payment, $content);
    //$content = str_replace('{{예약수수료}}', '￦'. formatNumbers($booking_view['booking_agency_fee']), $content);

    for ($i=1; $i<=2; $i++) {
        $content = str_replace('{{운전자명'.$i.'}}', $booking_view['driver_name'.$i] .' ('. $booking_view['driver_name_eng'.$i] .')', $content);
        $content = str_replace('{{한국주소'.$i.'}}', $booking_view['driver_home_addr'.$i], $content);
        $content = str_replace('{{현지주소'.$i.'}}', $booking_view['driver_local_addr'.$i], $content);
        $content = str_replace('{{핸드폰번호'.$i.'}}', $booking_view['driver_phone'.$i], $content);
        $content = str_replace('{{생년월일'.$i.'}}', $booking_view['driver_birthdate'.$i], $content);
        $content = str_replace('{{운전면허증번호'.$i.'}}', $booking_view['driver_license'.$i], $content);
        $content = str_replace('{{운전면허증만료일'.$i.'}}', $booking_view['driver_license_expiry_date'.$i], $content);
    }

    if ($booking_view['goods_category'] == 'C001') {
        //괌발송
        sendEmail($subject, $CONST_LOCAL_CONTACT_EMAIL1, "", SITE_EMAIL, SITE_NAME, $content);
    } else {
        //사이판발송
        //sendEmail($subject, $CONST_LOCAL_CONTACT_EMAIL2, "", SITE_EMAIL, SITE_NAME, $content);
    }

    adminMemoSystemSave('choice_tour_booking_view', $params['booking_idx'], '현지 이메일 발송을 했습니다.', $MEM_ADM['usr_name']);
?>
{"result": 200, "message": "OK"}
