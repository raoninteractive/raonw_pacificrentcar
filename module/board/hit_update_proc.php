<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

	$params['idx']  = chkReqRpl("idx", null, "", "", "INT");

	if (chkBlank($params['idx'])) fnMsgJson(502, "게시글 고유번호 값이 유효하지 않습니다.", "");

    $cls_board = new CLS_BOARD;

	//게시글 상세
	$view = $cls_board->view($params);
    if ($view == false) fnMsgJson(502, "일치하는 게시글 정보가 없습니다.", "");

	//조회수 업데이트
	$cls_board->view_check($params['idx']);

?>
{"result": 200, "message": "OK"}