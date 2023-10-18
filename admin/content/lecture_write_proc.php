<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['class_idx']     = chkReqRpl("class_idx", null, "", "POST", "INT");
	$params['idx']           = chkReqRpl("idx", null, "", "POST", "INT");
    $params['title']         = chkReqRpl("title", "", 100, "POST", "STR");
    $params['sample_flag']   = chkReqRpl("sample_flag", "N", 1, "POST", "STR");
    $params['controll_flag'] = chkReqRpl("controll_flag", "N", 1, "POST", "STR");
    $params['up_file_1']     = $_FILES['up_file_1'];
    $params['old_up_file_1'] = chkReqRpl("old_up_file_1", "", 500, "POST", "STR");
    $params['up_file_2']     = $_FILES['up_file_2'];
    $params['old_up_file_2'] = chkReqRpl("old_up_file_2", "", 500, "POST", "STR");
    $params['up_file_3']     = $_FILES['up_file_3'];
    $params['old_up_file_3'] = chkReqRpl("old_up_file_3", "", 500, "POST", "STR");
	$params['sort']          = chkReqRpl("sort", 0, "", "POST", "INT");
    $params['open_flag']     = chkReqRpl("open_flag", "Y", 1, "POST", "STR");
    $up_file_path1            = "/upload/content/video";
    $up_file_path2            = "/upload/content/attach";

	if (chkBlank($params['class_idx'])) fnMsgJson(502, "강좌 정보 값이 유효하지 않습니다.", "");
	if (chkBlank($params['title'])) fnMsgJson(503, "강의명 정보 값이 유효하지 않습니다.", "");

    $cls_content = new CLS_CONTENT;

    //강좌 정보 불러오기
    $class_view = $cls_content->class_view($params['class_idx']);
    if ($class_view == false) fnMsgJson(504, "일치하는 강좌 정보가 없습니다.", "");

    $upfile_change1 = false;
    if (!chkBlank($params['up_file_1']['name'])) {
        $fuArray             = fileUpload("up_file_1", $up_file_path1, 500, "FILE", "N", "mp3,mp4");
        $params['up_file_1'] = $fuArray[0]["file_info"];
        $upfile_change1      = true;
    } else {
        $params['up_file_1'] = $params['old_up_file_1'];
    }


    $upfile_change2 = false;
    if (!chkBlank($params['up_file_2']['name'])) {
        $fuArray             = fileUpload("up_file_2", $up_file_path2, 100, "FILE", "N");
        $params['up_file_2'] = $fuArray[0]["file_info"];
        $upfile_change2      = true;
    } else {
        $params['up_file_2'] = $params['old_up_file_2'];
    }

    $upfile_change3 = false;
    if (!chkBlank($params['up_file_3']['name'])) {
        $fuArray             = fileUpload("up_file_3", $up_file_path2, 100, "FILE", "N");
        $params['up_file_3'] = $fuArray[0]["file_info"];
        $upfile_change3      = true;
    } else {
        $params['up_file_3'] = $params['old_up_file_3'];
    }

	//강좌 강의 정보 저장
	$params['upt_ip']      = $NOW_IP;
    $params['upt_id']      = $MEM_ADM['usr_id'];
    $params['reg_ip']      = $NOW_IP;
	$params['reg_id']      = $MEM_ADM['usr_id'];
	if (!$cls_content->lecture_save($params)) {
		if ($upfile_change1) fileDelete($up_file_path1, $params['up_file_1']);
        if ($upfile_change2) fileDelete($up_file_path2, $params['up_file_2']);
        if ($upfile_change3) fileDelete($up_file_path2, $params['up_file_3']);

        fnMsgJson(505, "저장 처리중 오류가 발생되었습니다.", "");
    } else {
        //if ($upfile_change1) fileDelete($up_file_path1, $params['old_up_file_1']);
        if ($upfile_change2) fileDelete($up_file_path2, $params['old_up_file_2']);
        if ($upfile_change3) fileDelete($up_file_path2, $params['old_up_file_3']);
    }

?>
{"result": 200, "message": "OK"}
