<? include "../inc/config.php" ?>
<?
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 12;
	$params['block_size'] = 10;
	$page_params          = setPageParamsValue($params, "page,list_size,block_size,gubun");

    $cls_goods = new CLS_GOODS;

    //선택상품 목록
	$goods_list = $cls_goods->choice_tour_list($params, $total_cnt, $total_page);

	$pageNum = "0301";
	$pageName = "선택관광";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
    <? include "../inc/spot.php" ?>

	<div class="contents wrap_trip">
		<div class="inr-c">

			<div class="lst_trip">
                <?if (count($goods_list) > 0) {?>
                    <ul>
                        <?for ($i=0; $i<count($goods_list); $i++) {?>
                        <li><a href="trip_view.php?goods_idx=<?=$goods_list[$i]['idx']?>">
                            <div class="img"><span style="background-image: url('/upload/choice_tour/list/<?=getUpfileName($goods_list[$i]['up_file_1'])?>');"></span></div>
                            <div class="cont">
                                <p class="h1 t-dot"><?=$goods_list[$i]['title']?></p>
                                <p class="t1 t-dot"><?=textareaDecode($goods_list[$i]['introduction'])?></p>
                                <p class="c1"><strong class="c-orange f-gm">$<?=$goods_list[$i]['adult_amt']?></strong><span> / 성인 1인</span></p>
                            </div>
                        </a></li>
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

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>