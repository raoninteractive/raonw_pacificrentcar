<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['goods_idx'] = chkReqRpl("goods_idx", null, "", "POST", "INT");
	$params['gubun']     = chkReqRpl("gubun", "", "10", "POST", "STR");
	$params['stock_idx'] = chkReqRpl("stock_idx", null, "", "POST", "INT");
	$params['sdate']     = chkReqRpl("sdate", "", "10", "POST", "STR");
	$params['edate']     = chkReqRpl("edate", "", "10", "POST", "STR");
	$params['stock_cnt'] = chkReqRpl("stock_cnt", 0, "", "POST", "INT");

    $cls_goods = new CLS_GOODS;

    $goods_view = $cls_goods->goods_view($params['goods_idx']);
    if ($goods_view == false) fnMsgJson(502, "일치하는 상품 정보가 없습니다.", "");

    if ($params['stock_idx'] != '') {
        $stock_view = $cls_goods->stock_view($params['stock_idx']);
        if ($stock_view == false) fnMsgJson(503, "일치하는 상품재고 정보가 없습니다.", "");
    }

    if (dateDiff("d", $params['sdate'], $params['edate']) < 0) fnMsgJson(504, "출발일 값이 유효하지 않습니다. 종료날짜가 시작날짜보다 작을 수 없습니다.", "");

	//등록 및 수정
	$params['upt_ip'] = $NOW_IP;
	$params['upt_id'] = $MEM_ADM['usr_id'];
	$params['reg_ip'] = $NOW_IP;
	$params['reg_id'] = $MEM_ADM['usr_id'];
	if (!$cls_goods->stock_save_proc($params, $err_msg)) fnMsgJson(510, "저장 처리중 오류가 발생되었습니다. [". $err_msg ."]", "");
?>
{"result": 200, "message": "OK"}
