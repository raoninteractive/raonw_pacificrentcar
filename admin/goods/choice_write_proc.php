<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['idx']           = chkReqRpl("idx", null, "", "POST", "INT");
	$params['title']         = chkReqRpl("title", "", "200", "POST", "STR");
	$params['tour_time']     = chkReqRpl("tour_time", "", "200", "POST", "STR");
    $params['introduction']  = chkReqRpl("introduction", "", "max", "POST", "STR");
	$params['adult_amt']     = chkReqRpl("adult_amt", 0, "", "POST", "INT");
	$params['child_amt']     = chkReqRpl("child_amt", 0, "", "POST", "INT");
    $params['infant_amt']    = chkReqRpl("infant_amt", 0, "", "POST", "INT");
    $params['agency_fee']    = chkReqRpl("agency_fee", 0, "", "POST", "INT");
    $params['content']       = chkReqRpl("content", "", "max", "POST", "STR");
    $params['notice']        = chkReqRpl("notice", "", "max", "POST", "STR");
    $params['sdate']         = chkReqRpl("sdate", "", "10", "POST", "STR");
    $params['edate']         = chkReqRpl("edate", "", "10", "POST", "STR");
	$params['open_flag']     = chkReqRpl("open_flag", "N", "1", "POST", "STR");
    $params['sort']          = chkReqRpl("sort", 0, "", "POST", "INT");
    $up_file_path1           = "/upload/choice_tour/list";
    $up_file_path2           = "/upload/choice_tour/view";

    for ($i=1; $i<=2; $i++) {
        $params['up_file_'.$i]     = $_FILES['up_file_'.$i];
        $params['old_up_file_'.$i] = chkReqRpl("old_up_file_".$i, "", "500", "POST", "STR");
    }

    $cls_goods = new CLS_GOODS;

    if (chkBlank($params['title'])) fnMsgJson(502, "상품명 값이 유효하지 않습니다.", "");
    if (chkBlank($params['tour_time'])) fnMsgJson(503, "출항시간 값이 유효하지 않습니다.", "");
    if (chkBlank($params['introduction'])) fnMsgJson(504, "목록 상품설명 값이 유효하지 않습니다.", "");
    if (chkBlank($params['up_file_1']) && chkBlank($params['old_up_file_1'])) fnMsgJson(506, "목록 이미지 값이 유효하지 않습니다.", "");
    //if (chkBlank($params['up_file_2']) && chkBlank($params['old_up_file_2'])) fnMsgJson(507, "상세 이미지 값이 유효하지 않습니다.", "");
    if (chkBlank($params['content'])) fnMsgJson(508, "내용 값이 유효하지 않습니다.", "");
    if (chkBlank($params['notice'])) fnMsgJson(509, "유의사항 값이 유효하지 않습니다.", "");
    if (chkBlank($params['sdate'])) fnMsgJson(510, "상품 시작 기간 값이 유효하지 않습니다.", "");
    if (chkBlank($params['edate'])) fnMsgJson(511, "상품 종료 기간 값이 유효하지 않습니다.", "");

    for ($i=1; $i<=1; $i++) {
        ${'upfile_change'.$i} = false;
        if (!chkBlank($params['up_file_'.$i])) {
            $fuArray               = fileUpload("up_file_".$i, ${'up_file_path'.$i}, 2, "IMG", "N");
            $params['up_file_'.$i] = $fuArray[0]["file_info"];

            makeThumbnail(${'up_file_path'.$i}, $fuArray[0]["file_name"], ${'up_file_path'.$i}, 1200, 9999, true);

            ${'upfile_change'.$i}  = true;
        } else {
            $params['up_file_'.$i] = $params['old_up_file_'.$i];
        }
    }

	//게시글 등록 및 수정
	$params['upt_ip'] = $NOW_IP;
	$params['upt_id'] = $MEM_ADM['usr_id'];
	$params['reg_ip'] = $NOW_IP;
	$params['reg_id'] = $MEM_ADM['usr_id'];
	if (!$cls_goods->choice_tour_save_proc($params, $goods_idx)) {
		for ($i=1; $i<=2; $i++) {
            if (${'upfile_change'.$i}) fileDelete(${'up_file_path'.$i}, getUpfileName($params['up_file_'.$i]));
        }

		fnMsgJson(512, "저장 처리중 오류가 발생되었습니다.", "");
	} else {
		for ($i=1; $i<=2; $i++) {
            if (${'upfile_change'.$i}) fileDelete(${'up_file_path'.$i}, getUpfileName($params['old_up_file_'.$i]));
        }
	}
?>
{"result": 200, "message": "OK", "goods_idx": "<?=$goods_idx?>"}
