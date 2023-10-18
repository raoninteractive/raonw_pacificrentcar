<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['idx']            = chkReqRpl("idx", null, "", "POST", "INT");
	$params['title']          = chkReqRpl("title", "", "200", "POST", "STR");
	$params['open_flag']      = chkReqRpl("open_flag", "N", "1", "POST", "STR");
	$params['category']       = chkReqRpl("category", "", "10", "POST", "STR");
	$params['option_1']       = chkReqRpl("option_1", "N", "1", "POST", "STR");
    $params['option_2']       = chkReqRpl("option_2", "N", "1", "POST", "STR");
    $params['option_3']       = chkReqRpl("option_3", "N", "1", "POST", "STR");
    $params['option_4']       = chkReqRpl("option_4", "N", "1", "POST", "STR");
    $params['option_5']       = chkReqRpl("option_5", "N", "1", "POST", "STR");
    $params['option_6']       = chkReqRpl("option_6", "N", "1", "POST", "STR");
    $params['option_7']       = chkReqRpl("option_7", "N", "1", "POST", "STR");
    $params['option_8']       = chkReqRpl("option_8", "N", "1", "POST", "STR");
    $params['option_3_amt']   = chkReqRpl("option_3_amt", 0, "", "POST", "INT");
    $params['option_4_amt']   = chkReqRpl("option_4_amt", 0, "", "POST", "INT");
    $params['option_5_amt']   = chkReqRpl("option_5_amt", 0, "", "POST", "INT");
    $params['option_6_amt']   = chkReqRpl("option_6_amt", 0, "", "POST", "INT");
	$params['day1_amt']       = chkReqRpl("day1_amt", 0, "", "POST", "INT");
	$params['day7_amt']       = chkReqRpl("day7_amt", 0, "", "POST", "INT");
    $params['day30_amt']      = chkReqRpl("day30_amt", 0, "", "POST", "INT");
    $params['agency_fee']     = chkReqRpl("agency_fee", 0, "", "POST", "INT");
    $params['content']        = chkReqRpl("content", "", "max", "POST", "STR");
    $params['keyword']        = chkReqRpl("keyword", "", "100", "POST", "STR");
    $params['main_open_flag'] = chkReqRpl("main_open_flag", "N", "1", "POST", "STR");
    $params['main_sort']      = chkReqRpl("main_sort", 0, "", "POST", "INT");
    $params['sort']           = chkReqRpl("sort", 0, "", "POST", "INT");
    $up_file_path1            = "/upload/goods/thumb";

    for ($i=1; $i<=1; $i++) {
        $params['up_file_'.$i]     = $_FILES['up_file_'.$i];
        $params['old_up_file_'.$i] = chkReqRpl("old_up_file_".$i, "", "500", "POST", "STR");
    }

    $cls_goods = new CLS_GOODS;

    if (chkBlank($params['title'])) fnMsgJson(502, "상품명 값이 유효하지 않습니다.", "");
    if (chkBlank($params['category'])) fnMsgJson(503, "상품분류 값이 유효하지 않습니다.", "");
    if (chkBlank($params['content'])) fnMsgJson(504, "특징 값이 유효하지 않습니다.", "");
    if (chkBlank($params['up_file_1']) && chkBlank($params['old_up_file_1'])) fnMsgJson(509, "상품이미지1 값이 유효하지 않습니다.", "");


    for ($i=1; $i<=1; $i++) {
        ${'upfile_change'.$i} = false;
        if (!chkBlank($params['up_file_'.$i])) {
            $fuArray               = fileUpload("up_file_".$i, $up_file_path1, 2, "IMG", "N");
            $params['up_file_'.$i] = $fuArray[0]["file_info"];

            makeThumbnail($up_file_path1, $fuArray[0]["file_name"], $up_file_path1, 1200, 9999, true);

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
	if (!$cls_goods->goods_save_proc($params, $goods_idx)) {
		for ($i=1; $i<=1; $i++) {
            if (${'upfile_change'.$i}) fileDelete($up_file_path1, getUpfileName($params['up_file_'.$i]));
        }

		fnMsgJson(510, "저장 처리중 오류가 발생되었습니다.", "");
	} else {
		for ($i=1; $i<=1; $i++) {
            if (${'upfile_change'.$i}) fileDelete($up_file_path1, getUpfileName($params['old_up_file_'.$i]));
        }
	}
?>
{"result": 200, "message": "OK", "goods_idx": "<?=$goods_idx?>"}
