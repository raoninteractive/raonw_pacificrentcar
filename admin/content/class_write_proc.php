<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $params['idx']               = chkReqRpl("idx", null, "", "POST", "INT");
    $params['sort']              = chkReqRpl("sort", 0, "", "POST", "INT");
	$params['category_1']        = chkReqRpl("category_1", null, "", "POST", "INT");
	$params['category_2']        = chkReqRpl("category_2", 0, "", "POST", "INT");
	$params['category_3']        = chkReqRpl("category_3", 0, "", "POST", "INT");
	$params['category_4']        = chkReqRpl("category_4", 0, "", "POST", "INT");
    $params['title']             = chkReqRpl("title", "", 100, "POST", "STR");
    $params['types']             = chkReqRpl("types", "10", 2, "POST", "STR");
    $params['period']            = chkReqRpl("period", 0, "", "POST", "INT");
    $params['period_flag']       = chkReqRpl("period_flag", "Y", 1, "POST", "STR");
    $params['inst_name']         = chkReqRpl("inst_name", "", 50, "POST", "STR");
    $params['inst_id']           = chkReqRpl("inst_id", "", 50, "POST", "STR");
    $params['allow_auth']        = implode(',', chkReqRpl("allow_auth", "", 100, "POST", "STR"));
    $params['apply_status']      = chkReqRpl("apply_status", "Y", 1, "POST", "STR");
    $params['limit_cnt']         = chkReqRpl("limit_cnt", 0, "", "POST", "INT");
    $params['limit_flag']        = chkReqRpl("limit_flag", "Y", 1, "POST", "STR");
    $params['class_status']      = chkReqRpl("class_status", "Y", 1, "POST", "STR");
    $params['open_flag']         = chkReqRpl("open_flag", "N", 1, "POST", "STR");
    $params['class_content']     = chkReqRpl("class_content", "", "max", "POST", "STR");
    $params['total_lecture_cnt'] = chkReqRpl("total_lecture_cnt", 0, "", "POST", "INT");


    if ($params['period_flag'] == 'N') $params['period'] = 0;
    if ($params['apply_status'] == 'N' || $params['limit_flag'] == 'N') $params['limit_cnt'] = 0;
    $params['category'] = '';
    for ($i=1; $i<=4; $i++) {
        if ($params['category_'.$i] != "") {
            $params['category'] = $params['category_'.$i];
        }
    }

	if (chkBlank($params['category_1'])) fnMsgJson(502, "1차 메뉴 정보 값이 유효하지 않습니다.", "");
	if (chkBlank($params['title'])) fnMsgJson(503, "강좌명 값이 유효하지 않습니다.", "");
	if ($params['period_flag']=='Y' && $params['period']==0) fnMsgJson(504, "수강기간은 0일 이상 입력해주세요.", "");
    if (chkBlank($params['inst_name'])) fnMsgJson(505, "강사명 값이 유효하지 않습니다.", "");
    if (chkBlank($params['allow_auth'])) fnMsgJson(506, "이용 회원등급 값이 유효하지 않습니다.", "");
	if ($params['apply_status']=='Y' && $params['limit_flag']=='Y' && $params['limit_cnt']==0) fnMsgJson(507, "수강신청 제한 인원은 0명 이상 입력해주세요.", "");
	if (chkBlank($params['class_content'])) fnMsgJson(508, "강좌내용 값이 유효하지 않습니다.", "");

    $cls_content = new CLS_CONTENT;

    $view = $cls_content->class_view($params['idx']);
    if ($view != false) {
        if (!$cls_content->class_category_check($view['idx'])) {
            //$params['category_1'] = $view['category_1'];
            //$params['category_2'] = $view['category_2'];
            //$params['category_3'] = $view['category_3'];
            //$params['category_4'] = $view['category_4'];
        }

        //강좌 수강신청 건수 체크
        if ($params['total_lecture_cnt'] != $view['total_lecture_cnt']) {
            if ($cls_content->class_apply_count($params['idx']) > 0) fnMsgJson(509, "수강 신청된 회원이 있습니다. 총 강의 수는 변경이 불가능 합니다.", "");
        }
    }


	//강좌 정보 저장
	$params['upt_ip']      = $NOW_IP;
    $params['upt_id']      = $MEM_ADM['usr_id'];
    $params['reg_ip']      = $NOW_IP;
	$params['reg_id']      = $MEM_ADM['usr_id'];
	if (!$cls_content->class_save($params, $class_idx)) fnMsgJson(510, "저장 처리중 오류가 발생되었습니다.", "");

?>
{"result": 200, "message": "OK", "class_idx": "<?=$class_idx?>"}
