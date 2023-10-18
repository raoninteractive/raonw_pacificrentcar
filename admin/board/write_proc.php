<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['mode']        = chkReqRpl("mode", "", "20", "POST", "STR");
	$params['idx']         = chkReqRpl("idx", null, "", "POST", "INT");
	$params['bbs_code']    = chkReqRpl("bbs_code", "", "20", "POST", "STR");
	$params['category']    = chkReqRpl("category", null, "", "POST", "INT");
	$params['writer']      = chkReqRpl("writer", "", "50", "POST", "STR");
	$params['passwd']      = chkReqRpl("passwd", "", "20", "POST", "STR");
	$params['title']       = chkReqRpl("title", "", "100", "POST", "STR");
	$params['content']     = chkReqRpl("content", "", "max", "POST", "STR");

	$params['list_img']    = $_FILES['list_img'];
	$params['up_file_1']   = $_FILES['up_file_1'];
	$params['up_file_2']   = $_FILES['up_file_2'];
	$params['up_file_3']   = $_FILES['up_file_3'];
	$params['notice_flag'] = chkReqRpl("notice_flag", "N", "1", "POST", "STR");
	$params['secret_flag'] = chkReqRpl("secret_flag", "N", "1", "POST", "STR");
	$params['open_flag']   = chkReqRpl("open_flag", "N", "1", "POST", "STR");
	$params['link1']       = chkReqRpl("link1", "", "255", "POST", "STR");
	$params['link2']       = chkReqRpl("link2", "", "255", "POST", "STR");
    $up_img_path           = "/upload/board/thumb/";
    $up_file_path          = "/upload/board/attach/";

	$cls_board = new CLS_BOARD;

	//카테고리 사용여부
	$is_category = $cls_board->isCategory($params['bbs_code']);

	if (chkBlank($params['bbs_code'])) fnMsgJson(502, "게시판 코드 값이 유효하지 않습니다.", "");

	if ($is_category) {
		if (chkBlank($params['category'])) fnMsgJson(503, "카테고리 값이 유효하지 않습니다.", "");
	}

	if (chkBlank($params['title'])) fnMsgJson(504, "제목 값이 유효하지 않습니다.", "");

	if (isStrpos("maxim", $params['bbs_code']) == false) {
		if (chkBlank($params['content'])) fnMsgJson(505, "내용 값이 유효하지 않습니다.", "");
	}

	//게시판코드 확인
	if (!$cls_board->bbs_code_check($params['bbs_code'])) fnMsgJson(506, "게시판 코드 값이 유효하지 않습니다.", "");

	//게시글 상세정보
	$view = $cls_board->view($params);
	$params['old_list_img']  = $view['list_img'];
	$params['old_up_file_1'] = $view['up_file_1'];
	$params['old_up_file_2'] = $view['up_file_2'];
	$params['old_up_file_3'] = $view['up_file_3'];

    $list_img_change = false;
    if (!chkBlank($params['list_img'])) {
        $fuArray            = fileUpload("list_img", $up_img_path, 5, "IMG", "N");
		$params['list_img'] = $fuArray[0]["file_info"];
        $list_img_change    = true;

		makeThumbnail($up_img_path, $fuArray[0]["file_name"], $up_img_path, 600, 600, true);
    } else {
        $params['list_img'] = $params['old_list_img'];
    }

    for ($i=1; $i<=3; $i++) {
        ${'upfile_change'.$i} = false;
        if (!chkBlank($params['up_file_'.$i])) {
            $fuArray               = fileUpload("up_file_".$i, $up_file_path, 100, "FILE", "N");
            $params['up_file_'.$i] = $fuArray[0]["file_info"];
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
	if (!$cls_board->save_proc($params)) {
		if ($list_img_change) fileDelete($up_img_path, getUpfileName($params['list_img']));
		if ($upfile_change1) fileDelete($up_file_path, getUpfileName($params['up_file_1']));
		if ($upfile_change2) fileDelete($up_file_path, getUpfileName($params['up_file_2']));
		if ($upfile_change3) fileDelete($up_file_path, getUpfileName($params['up_file_3']));

		fnMsgJson(507, "게시글 저장 처리중 오류가 발생되었습니다.", "");
	} else {
		if ($list_img_change) fileDelete($up_img_path, getUpfileName($params['old_list_img']));
		if ($upfile_change1) fileDelete($up_file_path, getUpfileName($params['old_up_file_1']));
		if ($upfile_change2) fileDelete($up_file_path, getUpfileName($params['old_up_file_2']));
		if ($upfile_change3) fileDelete($up_file_path, getUpfileName($params['old_up_file_3']));
	}
?>
{"result": 200, "message": "OK"}
