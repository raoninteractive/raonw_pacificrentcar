<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['depth']        = chkReqRpl("depth", null, "", "POST", "INT");
	$params['category_idx'] = chkReqRpl("category_idx", null, "", "POST", "INT");
	$params['parent_idx']   = chkReqRpl("parent_idx", 0, "", "POST", "INT");
	$params['name']         = chkReqRpl("name", "", 100, "POST", "STR");
	$params['sort']         = chkReqRpl("sort", 0, "", "POST", "INT");

    $params['allow_auth']   = implode(',', chkReqRpl("allow_auth", "", 100, "POST", "STR"));
    $params['open_flag']    = chkReqRpl("open_flag", "N", 1, "POST", "STR");
	$params['memo']         = chkReqRpl("memo", "", "max", "POST", "STR");

	$params['up_file']      = $_FILES['up_file'];
	$params['old_up_file']  = chkReqRpl("old_up_file", "", 500, "POST", "STR");

	$up_file_path           = "/upload/content/icon";

	if (chkBlank($params['depth'])) fnMsgJson(502, "카테고리 정보 값이 유효하지 않습니다.", "");
	//if (chkBlank($params['category_idx'])) fnMsgJson(503, "카테고리 정보 값이 유효하지 않습니다..", "");
	if ($params['depth'] > 1 && chkBlank($params['parent_idx'])) fnMsgJson(504, "카테고리 정보 값이 유효하지 않습니다.", "");
	if (chkBlank($params['name'])) fnMsgJson(505, "메뉴명 값이 유효하지 않습니다.", "");
	//if (chkBlank($params['sort'])) fnMsgJson(506, "정렬순번 값이 유효하지 않습니다.", "");
	if (chkBlank($params['allow_auth'])) fnMsgJson(507, "사용권한 값이 유효하지 않습니다.", "");
	//if (chkBlank($params['open_flag'])) fnMsgJson(508, "사용유무 값이 유효하지 않습니다.", "");
	//if (chkBlank($params['memo'])) fnMsgJson(509, "메모 값이 유효하지 않습니다.", "");

    $cls_content = new CLS_CONTENT;

    $upfile_change = false;
    if (!chkBlank($params['up_file'])) {
        $fuArray           = fileUpload("up_file", $up_file_path, 2, "IMG", "N");
        $params['up_file'] = $fuArray[0]["file_info"];
        $upfile_change     = true;
    } else {
        $params['up_file'] = $params['old_up_file'];
    }


	//카테고리 메뉴 정보 저장
	$params['upt_ip']      = $NOW_IP;
    $params['upt_id']      = $MEM_ADM['usr_id'];
    $params['reg_ip']      = $NOW_IP;
	$params['reg_id']      = $MEM_ADM['usr_id'];
	if (!$cls_content->category_save($params)) {
		if ($upfile_change) fileDelete($up_file_path, $params['up_file']);

		fnMsgJson(510, "저장 처리중 오류가 발생되었습니다.", "");
	} else {
		if ($upfile_change) fileDelete($up_file_path, $params['old_up_file']);
	}

?>
{"result": 200, "message": "OK"}
