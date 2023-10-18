<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['page']       = 1;
	$params['list_size']  = 999999;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "GET", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "GET", "STR");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");

    $cls_sms = new CLS_SMS;

	//일반회원 목록 불러오기
    $list = $cls_sms->member_list($params, $total_cnt, $total_page);

    $json = array();
    $json['result'] = 200;
    $json['message'] = "OK";
    $json['list'] = array();

    for ($i=0; $i<count($list);$i++) {
        $json['list'][$i]['member_info'] = $list[$i]['usr_id'] ."|". $list[$i]['usr_name'] ."|". $list[$i]['usr_phone'];
    }

    echo json_encode($json, JSON_UNESCAPED_UNICODE);