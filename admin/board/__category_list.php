<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$depth    = chkReqRpl("depth", null, "", "", "INT");
	$category = chkReqRpl("category", null, "", "", "INT");

	if (chkBlank($depth)) fnMsgJson(502, "카테고리 정보 값이 유효하지 않습니다.", "");
	if (chkBlank($category)) fnMsgJson(503, "카테고리 정보 값이 유효하지 않습니다.", "");

	$cls_board = new CLS_BOARD;

	$list = $cls_board->category_list('treatment', $depth, $category);

	$category_arr = array();
	for ($i=0; $i<count($list); $i++) {
		array_push($category_arr, array(
						"category_idx" => $list[$i]['category_idx'],
						"category_name" => $list[$i]['category_name']
					));
	}

	$result = array();

	$result["result"] = 200;
	$result["message"] = "OK";
	$result["list"] = $category_arr;

	echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>