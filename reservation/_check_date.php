<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

    $params['goods_idx'] = chkReqRpl("goods_idx", null, "", "POST", "INT");
    $params['sch_sdate'] = date('Y-m-d');
    $params['sch_stock'] = 'Y';

	if (chkBlank($params['goods_idx'])) fnMsgJson(501, "잘못된 요청 정보 입니다.", "");

	$cls_goods = new CLS_GOODS;

    //상품정보 불러오기
	$goods_view = $cls_goods->goods_view($params['goods_idx']);
	if ($goods_view == false) fnMsgJson(502, "일치하는 상품정보가 없습니다.", "");

    //상품 재고 정보 불러오기
    $stock_list = $cls_goods->stock_list($params);

    $list = array();
    for ($i=0; $i<count($stock_list); $i++) {
		array_push($list, array(
            "date" => $stock_list[$i]['sdate']
        ));
    }


	$result["result"] = 200;
	$result["message"] = "OK";
	$result["list"] = $list;

	echo json_encode($result, JSON_UNESCAPED_UNICODE);
?>