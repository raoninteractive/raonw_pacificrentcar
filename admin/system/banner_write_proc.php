<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['idx']           = chkReqRpl("idx", null, "", "POST", "INT");
	$params['section']       = chkReqRpl("section", null, "", "POST", "INT");
	$params['title']         = chkReqRpl("title", "", 100, "POST", "STR");
	$params['sub_title1']    = chkReqRpl("sub_title1", "", 100, "POST", "STR");
	$params['sub_title2']    = chkReqRpl("sub_title2", "", 100, "POST", "STR");
	$params['sdate']         = chkReqRpl("sdate", "", 10, "POST", "STR");
	$params['edate']         = chkReqRpl("edate", "", 10, "POST", "STR");
	$params['content']       = chkReqRpl("content", "", "max", "POST", "STR");
	$params['up_file_1']     = $_FILES['up_file_1'];
	$params['up_file_2']     = $_FILES['up_file_2'];
	$params['target_pc']     = chkReqRpl("target_pc", "", 10, "POST", "STR");
	$params['link_pc']       = chkReqRpl("link_pc", "", 500, "POST", "STR");
	$params['target_mobile'] = chkReqRpl("target_mobile", "", 10, "POST", "STR");
	$params['link_mobile']   = chkReqRpl("link_mobile", "", 500, "POST", "STR");
	$params['open_flag']     = chkReqRpl("open_flag", "N", 1, "POST", "STR");
	$up_file_path            = "/upload/banner/";

	if (chkBlank($params['section'])) fnMsgJson(502, "배너위치 값이 유효하지 않습니다.", "");
	if (chkBlank($params['title'])) fnMsgJson(503, "제목 값이 유효하지 않습니다.", "");
	//if (chkBlank($params['content'])) fnMsgJson(504, "내용 값이 유효하지 않습니다.", "");
	//if (chkBlank($params['sdate'])) fnMsgJson(505, "시작기간 값이 유효하지 않습니다.", "");
	//if (chkBlank($params['edate'])) fnMsgJson(506, "종료기간 값이 유효하지 않습니다.", "");
	if (chkBlank($params['idx']) && chkBlank($params['up_file_1'])) fnMsgJson(507, "PC 이미지 값이 유효하지 않습니다.", "");

	$cls_banner = new CLS_SETTING_BANNER;

	//배너위치 체크
	if ($params['section'] > 1) {
		if ($cls_banner->banner_area_check($params['idx'], $params['section']) == false) fnMsgJson(508, "이미 등록된 위치가 있습니다. 같은 위치에 등록이 불가능합니다.", "");
	}

	//배너 상세정보
	$pop_view = $cls_banner->banner_view($params['idx']);
	$params['old_up_file_1'] = $pop_view['up_file_1'];
	$params['old_up_file_2'] = $pop_view['up_file_2'];

	//첨부파일
    for ($i=1; $i<=2; $i++) {
        ${'upfile_change'.$i} = false;
        if (!chkBlank($params['up_file_'.$i])) {
            $fuArray               = fileUpload("up_file_".$i, $up_file_path, 10, "IMG", "N");
            $params['up_file_'.$i] = $fuArray[0]["file_name"];
            ${'upfile_change'.$i}  = true;
        } else {
			$params['up_file_'.$i] = $params['old_up_file_'.$i];
        }
    }

	//배너 등록 및 수정
	$params['upt_ip'] = $NOW_IP;
	$params['upt_id'] = $MEM_ADM['usr_id'];
	$params['reg_ip'] = $NOW_IP;
	$params['reg_id'] = $MEM_ADM['usr_id'];
	if ($cls_banner->banner_save($params) == 0) {
		if ($upfile_change1) fileDelete($up_file_path, $params['up_file_1']);
		if ($upfile_change2) fileDelete($up_file_path, $params['up_file_2']);

		fnMsgJson(509, "배너 저장 처리중 오류가 발생되었습니다.", "");
	} else {
		if ($upfile_change1) fileDelete($up_file_path, $params['old_up_file_1']);
		if ($upfile_change2) fileDelete($up_file_path, $params['old_up_file_2']);
	}
?>
{"result": 200, "message": "OK"}
