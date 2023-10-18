<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $params['goods_idx'] = chkReqRpl("goods_idx", null, "", "POST", "INT");
	$params['stock_idx'] = chkReqRpl("stock_idx", null, "", "POST", "INT");

    if (chkBlank($params['goods_idx'])) fnMsgJson(502, "상품 고유번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['stock_idx'])) fnMsgJson(503, "상품재고 고유번호 값이 유효하지 않습니다.", "");

    $cls_goods = new CLS_GOODS;

    $goods_view = $cls_goods->goods_view($params['goods_idx']);
    if ($goods_view == false) fnMsgJson(504, "일치하는 상품 정보가 없습니다.", "");

    $stock_view = $cls_goods->stock_view($params['stock_idx']);
    if ($stock_view == false) fnMsgJson(505, "일치하는 상품재고 정보가 없습니다.", "");

	$params['upt_ip'] = $NOW_IP;
	$params['upt_id'] = $MEM_ADM['usr_id'];

    //상품가격 삭제
    if ($cls_goods->stock_delete_proc($params) == 0) fnMsgJson(504, "삭제 처리중 오류가 발생되었습니다.", "");
?>
{"result": 200, "message": "OK"}
