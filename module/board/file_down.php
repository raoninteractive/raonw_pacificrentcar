<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

    if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");

    $params['idx']      = chkReqRpl("idx", null, "", "", "INT");
    $params['fnum']     = chkReqRpl("fnum", null, "", "", "INT");
	$up_file_path       = "/upload/board/attach/";

    if (chkBlank($params['idx'])) fnMsgGo(501, "게시판 고유번호 값이 유효하지 않습니다.", "BACK", "");
	if (chkBlank($params['fnum'])) fnMsgGo(502, "파일번호 값이 유효하지 않습니다.", "BACK", "");


	$cls_board = new CLS_BOARD;

    //게시판 관리 정보 불러오기
    $view = $cls_board->view($params);
    if ($view == false) {
        fnMsgGo(503, "일치하는 데이터 정보가 없습니다.", "BACK", "");
    } else {
        $up_file      = getUpfileName($view["up_file_". $fnum]);
        $up_file_name = getUpfileOriName($view["up_file_". $fnum]);
    }

	if(!is_file(chkMapPath($up_file_path)."/". changeValueCharset($up_file))) fnMsgGo(504, "관련자료 파일을 찾을 수 없습니다.", "BACK", "");

	fileDown($up_file, $up_file_name, $up_file_path);
?>