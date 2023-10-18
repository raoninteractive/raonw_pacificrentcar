<?
	include "../inc/config.php";

    if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");
	if (!isAdmin()) fnMsgGo(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "BACK", "");

    $idx           = chkReqRpl("idx", null, "", "", "INT");
    $fnum          = chkReqRpl("fnum", null, "", "", "INT");
    if ($fnum == '1') {
        $up_file_path = "/upload/content/video";
    } else {
        $up_file_path = "/upload/content/attach";
    }

    if (chkBlank($idx)) fnMsgGo(502, "강의 고유번호 값이 유효하지 않습니다.", "BACK", "");
    if (chkBlank($fnum)) fnMsgGo(503, "파일번호 값이 유효하지 않습니다.", "BACK", "");

	$cls_content = new CLS_CONTENT;


    //강의 정보 불러오기
    $view = $cls_content->lecture_view($idx);
    if ($view == false) {
        fnMsgGo(504, "일치하는 강의 데이터가 없습니다.", "BACK", "");
    } else {
        $up_file      = getUpfileName($view["up_file_". $fnum]);
        $up_file_name = getUpfileOriName($view["up_file_". $fnum]);
    }

	if(!is_file(chkMapPath($up_file_path)."/". changeValueCharset($up_file))) fnMsgGo(505, "관련자료 파일을 찾을 수 없습니다.", "BACK", "");

	fileDown($up_file, $up_file_name, $up_file_path);
?>