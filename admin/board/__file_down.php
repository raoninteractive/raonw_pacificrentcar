<?
	include "../inc/config.php";

    if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");
	if (!isAdmin()) fnMsgGo(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "BACK", "");

    $params['bbs_code'] = chkReqRpl("bbs_code", "", 10, "", "STR");
    $params['idx']      = chkReqRpl("idx", null, "", "", "INT");
    $params['fnum']     = chkReqRpl("fnum", null, "", "", "INT");
	$up_file_path       = "/upload/board/attach/";

    if (chkBlank($params['bbs_code'])) fnMsgGo(502, "게시판 코드 값이 유효하지 않습니다.", "BACK", "");
    if (chkBlank($params['idx'])) fnMsgGo(503, "게시판 고유번호 값이 유효하지 않습니다.", "BACK", "");
	if (chkBlank($params['fnum'])) fnMsgGo(504, "파일번호 값이 유효하지 않습니다.", "BACK", "");


	$cls_board = new CLS_BOARD;

    //게시판 관리 정보 불러오기
    $view = $cls_board->view($params);
    if ($view == false) {
        fnMsgGo(505, "일치하는 데이터 정보가 없습니다.", "BACK", "");
    } else {
        $up_file      = getUpfileName($view["up_file_". $fnum]);
        $up_file_name = getUpfileOriName($view["up_file_". $fnum]);
    }

	if(!is_file(chkMapPath($up_file_path)."/". changeValueCharset($up_file))) fnMsgGo(506, "관련자료 파일을 찾을 수 없습니다.", "BACK", "");

	fileDown($up_file, $up_file_name, $up_file_path);
?>