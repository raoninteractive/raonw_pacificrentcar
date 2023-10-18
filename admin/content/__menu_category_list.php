<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $depth        = chkReqRpl("depth", null, "", "", "INT");
    $category_idx = chkReqRpl("category_idx", null, "", "", "INT");

	if (chkBlank($depth)) fnMsgJson(502, "카테고리 정보 값이 유효하지 않습니다.", "");
    if ($depth>1 && chkBlank($category_idx)) fnMsgJson(503, "카테고리 정보 값이 유효하지 않습니다.", "");

    $cls_content = new CLS_CONTENT;

	//메뉴 목록 불러오기
    $ctg_list = $cls_content->category_list($depth, $category_idx);

    $result = array();
    $result['result'] = '200';
    for ($i=0; $i<count($ctg_list); $i++) {
        $result['list'][] = array(
                'category_idx' => $ctg_list[$i]['category_idx'],
                'parent_idx' => $ctg_list[$i]['parent_idx'],
                'depth' => $ctg_list[$i]['depth'],
                'sort' => $ctg_list[$i]['sort'],
                'name' => $ctg_list[$i]['name'],
                'allow_auth' => $ctg_list[$i]['allow_auth'],
                'bbs_code' => $ctg_list[$i]['bbs_code'],
                'open_flag' => $ctg_list[$i]['open_flag']
            );
    }

    echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>