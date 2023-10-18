<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $params['guam_pickup_area']     = chkReqRpl("guam_pickup_area", "", "max", "POST", "STR");
	$params['guam_return_area']     = chkReqRpl("guam_return_area", "", "max", "POST", "STR");
	$params['guam_out_airline']     = chkReqRpl("guam_out_airline", "", "max", "POST", "STR");
	$params['guam_in_airline']      = chkReqRpl("guam_in_airline", "", "max", "POST", "STR");
	$params['guam_hotel']           = chkReqRpl("guam_hotel", "", "max", "POST", "STR");
    $params['guam_guide_notice']    = chkReqRpl("guam_guide_notice", "", "max", "POST", "STR");
    $params['guam_choice_pickup']   = chkReqRpl("guam_choice_pickup", "", "max", "POST", "STR");

    $params['saipan_pickup_area']   = chkReqRpl("saipan_pickup_area", "", "max", "POST", "STR");
	$params['saipan_return_area']   = chkReqRpl("saipan_return_area", "", "max", "POST", "STR");
	$params['saipan_out_airline']   = chkReqRpl("saipan_out_airline", "", "max", "POST", "STR");
	$params['saipan_in_airline']    = chkReqRpl("saipan_in_airline", "", "max", "POST", "STR");
	$params['saipan_hotel']         = chkReqRpl("saipan_hotel", "", "max", "POST", "STR");
    $params['saipan_guide_notice']  = chkReqRpl("saipan_guide_notice", "", "max", "POST", "STR");
    $params['saipan_choice_pickup'] = chkReqRpl("saipan_choice_pickup", "", "max", "POST", "STR");

    if (chkBlank($params['guam_pickup_area'])) fnMsgJson(502, "괌 인수/픽업 장소 값이 유효하지 않습니다.", "");
    if (chkBlank($params['guam_return_area'])) fnMsgJson(503, "괌 반납장소 값이 유효하지 않습니다.", "");
    if (chkBlank($params['guam_out_airline'])) fnMsgJson(504, "괌 출발 항공사 값이 유효하지 않습니다.", "");
    if (chkBlank($params['guam_in_airline'])) fnMsgJson(505, "괌 도착 항공사 값이 유효하지 않습니다.", "");
    if (chkBlank($params['guam_hotel'])) fnMsgJson(506, "괌 투숙 호텔 값이 유효하지 않습니다.", "");
    if (chkBlank($params['guam_guide_notice'])) fnMsgJson(507, "괌 확정서 안내 사항 값이 유효하지 않습니다.", "");
    if (chkBlank($params['guam_choice_pickup'])) fnMsgJson(508, "괌 선택 광광 픽업 장소 값이 유효하지 않습니다.", "");

    /*
    if (chkBlank($params['saipan_pickup_area'])) fnMsgJson(509, "사이판 인수/픽업 장소 값이 유효하지 않습니다.", "");
    if (chkBlank($params['saipan_return_area'])) fnMsgJson(510, "사이판 반납장소 값이 유효하지 않습니다.", "");
    if (chkBlank($params['saipan_out_airline'])) fnMsgJson(511, "사이판 출발 항공사 값이 유효하지 않습니다.", "");
    if (chkBlank($params['saipan_in_airline'])) fnMsgJson(512, "사이판 도착 항공사 값이 유효하지 않습니다.", "");
    if (chkBlank($params['saipan_hotel'])) fnMsgJson(513, "사이판 투숙 호텔 값이 유효하지 않습니다.", "");
    if (chkBlank($params['saipan_guide_notice'])) fnMsgJson(514, "사이판 확정서 안내 사항 값이 유효하지 않습니다.", "");
    if (chkBlank($params['saipan_guide_notice'])) fnMsgJson(515, "사이판 선택 광광 픽업 장소 값이 유효하지 않습니다.", "");
    */


    $db = new DB_HELPER;

    $sql = "
            UPDATE setting_info SET
                guam_pickup_area = '". $params['guam_pickup_area'] ."',
                guam_return_area = '". $params['guam_return_area'] ."',
                guam_hotel = '". $params['guam_hotel'] ."',
                guam_out_airline = '". $params['guam_out_airline'] ."',
                guam_in_airline = '". $params['guam_in_airline'] ."',
                guam_guide_notice = '". $params['guam_guide_notice'] ."',
                guam_choice_pickup = '". $params['guam_choice_pickup'] ."',
                saipan_pickup_area = '". $params['saipan_pickup_area'] ."',
                saipan_return_area = '". $params['saipan_return_area'] ."',
                saipan_hotel = '". $params['saipan_hotel'] ."',
                saipan_out_airline = '". $params['saipan_out_airline'] ."',
                saipan_in_airline = '". $params['saipan_in_airline'] ."',
                saipan_guide_notice = '". $params['saipan_guide_notice'] ."',
                saipan_choice_pickup = '". $params['saipan_choice_pickup'] ."'
        ";
    $db->update($sql);
?>
{"result": 200, "message": "OK"}
