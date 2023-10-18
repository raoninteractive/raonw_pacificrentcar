<? include "../inc/config.php" ?>
<?
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 12;
	$params['block_size'] = 10;
	$params['gubun']      = chkReqRpl("gubun", "C001", "10", "", "STR");
	$page_params          = setPageParamsValue($params, "page,list_size,block_size,gubun");

    $cls_goods = new CLS_GOODS;

    //여행상품 목록
	$goods_list = $cls_goods->goods_list($params, $total_cnt, $total_page);

	$pageNum = "0101";
	$pageName = "예약";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
    <? include "../inc/spot.php" ?>

	<div class="contents">
		<div class="inr-c">
			<div class="lst_car1">
                <?if (count($goods_list) > 0) {?>
                    <ul class="n4">
                        <?for ($i=0; $i<count($goods_list); $i++) {?>
                            <li>
                                <a href="javascript:;" class="col" onclick="goodsView(<?=$goods_list[$i]['idx']?>);">
                                    <div class="img"><img src="<?=filePathCheck("/upload/goods/thumb/".getUpfileName($goods_list[$i]['up_file_1']))?>" alt="사진"></div>
                                    <div class="txt">
                                        <p class="h1">
                                            <!-- <span>괌(GUAM)</span> -->
                                            <span class="tit f-gm"><?=$goods_list[$i]['title']?></span>
                                        </p>
                                        <div class="bat">
                                            <?if ($goods_list[$i]['keyword'] != '') {?>
                                                <span class="i-txt"><?=implode('</span><span class="i-txt">',explode(',', $goods_list[$i]['keyword']))?></span>
                                            <?} else {?>
                                                <span class="i-txt" style="background:none">&nbsp;</span>
                                            <?}?>
                                        </div>

                                        <!-- <div class="bat">
                                        <?if ($goods_list[$i]['option_1'] == 'Y') {?>
                                                <span><img src="/images/common/ico_bat_car1.png" alt="주유포함"></span>
                                            <?}?>
                                            <?if ($goods_list[$i]['option_2'] == 'Y') {?>
                                                <span><img src="/images/common/ico_bat_car2.png" alt="CDW포함"></span>
                                            <?}?>
                                            <?if ($goods_list[$i]['option_7'] == 'Y') {?>
                                                <span><img src="/images/common/ico_bat_car3.png" alt="ZDC포함"></span>
                                            <?}?>
                                            <?if ($goods_list[$i]['option_8'] == 'Y') {?>
                                                <span><img src="/images/common/ico_bat_car4.png" alt="PAI포함"></span>
                                            <?}?>
                                        </div> -->

                                        <div class="btn-pk b color f-gm"><span><strong>$ <?=$goods_list[$i]['day1_amt']?></strong> / 24시간</span></div>
                                    </div>
                                </a>
                                <?if ($goods_list[$i]['rest_stock_cnt'] >= 1) {?>
                                    <a href="/reservation/reservation.php?goods_idx=<?=$goods_list[$i]['idx']?>" class="btn-pk b orange f-gm w100p mt10"><span>예약</span></a>
                                <?} else {?>
                                    <a href="javascript:alert('예약이 불가능한 상품입니다.')" class="btn-pk b gray f-gm w100p mt10"><span>예약 불가</span></a>
                                <?}?>
                            </li>
                        <?}?>
                    </ul>
                <?} else {?>
                    <div class="ta-c pt50 pb50">등록된 상품정보가 없습니다.</div>
                <?}?>
			</div>
			<div class="pagenation">
                <? frontPaging($total_page, $params['block_size'], $params['page'], $page_params, "") ?>
			</div>
		</div>
	</div>
</div><!--//container -->

<!-- 팝업 -->
<div id="popCarview" class="layerPopup pop_carview"></div>

<script>
    function goodsView(goods_idx) {
		AJ.callAjax("rent_view.php", {"goods_idx": goods_idx}, function(data){
            $("#popCarview").html(data)
            openLayerPopup('popCarview');
		}, "html");
    }
</script>

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>