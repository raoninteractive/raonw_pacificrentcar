<?
    require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

    if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

    $params['booking_num'] = chkReqRpl("booking_num", "", "15", "POST", "STR");
    $params['booker_phone']  = chkReqRpl("booker_phone", "", "12", "POST", "STR");

    if (chkBlank($params['booking_num'])) fnMsgJson(501, "예약번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['booker_phone']) || !isDataCheck($params['booker_phone'], 'phone2')) fnMsgJson(502, "휴대폰번호 값이 유효하지 않습니다.", "");

    $cls_booking = new CLS_BOOKING;
    $cls_jwt = new CLS_JWT();

    //예약접수 정보 불러오기(예약번호+휴대번호 조회)
    $booking_view = $cls_booking->booking_check($params['booking_num'], $params['booker_phone']);
    if ($booking_view == false) fnMsgJson(503, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "");

    $cls_jwt->session_check=true;
    $token = $cls_jwt->hashing(array(
            'booking_num'=> $params['booking_num'],
            'booker_phone'=> $params['booker_phone']
        ));
?>
{"result": 200, "message": "OK", "token": "<?=$token?>", "page_url": "<?=iif($booking_view['gubun']=='rental', 'reservation.php', 'reservation_tour.php')?>"}