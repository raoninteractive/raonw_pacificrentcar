<?include("../inc/config.php")?>
<?
    if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "RELOAD", "");
    if (!isAdmin()) fnMsgGo(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "RELOAD", "");

    $params['goods_idx']   = chkReqRpl("goods_idx", null, "", "", "INT");
    $params['gubun']       = chkReqRpl("gubun", "", 10, "", "STR");
    $params['stock_idx']   = chkReqRpl("stock_idx", null, "", "", "INT");
    $params['sch_sdate']   = chkReqRpl("sch_sdate", date('Y-m-d'), 10, "", "STR");
    $params['sch_edate']   = chkReqRpl("sch_edate", date('Y-m-d'), 10, "", "STR");

    $cls_goods = new CLS_GOODS;

    $goods_view = $cls_goods->goods_view($params['goods_idx']);
    if ($goods_view == false) fnMsgGo(502, "일치하는 상품 정보가 없습니다.", "RELOAD", "");

    $view = $cls_goods->stock_view($params['stock_idx']);
    if ($view == false) {
        $view['stock_cnt'] = 0;
    } else {

    }
?>
<div class="dim"></div>
<div class="contents" style="width:800px;margin-left:-400px;">
	<div class="layer_header">
		<h2>상품 재고 정보 <?=iif($params['stock_idx']!='', '수정', iif($params['gubun']=='reg', '일괄등록', '일괄수정'))?> 관리</h2>
		<button type="button" title="레이어 팝업 닫기" class="btn_close_layer" onclick="commonLayerClose('goods_stock_popup')"></button>
	</div>
	<div class="cont">
        <div class="explain pl10 ta_l">
            1. 출발일 범위 지정으로 일괄 등록 및 수정이 가능합니다.<br>
            2. 출발일이 모두 중복된 경우에만 수정처리 됩니다.<br>
            3. 수정시 이미 예약 접수건에 대해서는 반영이 안됩니다.<br>
		</div>
		<div class="common_form">
			<form name="popGoodsStockFrm" id="popGoodsStockFrm" method="post">
            <input type="hidden" name="goods_idx" value="<?=$params['goods_idx']?>">
            <input type="hidden" name="gubun" value="<?=$params['gubun']?>">
            <input type="hidden" name="stock_idx" value="<?=$params['stock_idx']?>">
			<table class="g_table">
				<colgroup>
					<col width="15%">
                    <col width="35%">
                    <col width="15%">
                    <col width="35%">
				</colgroup>
				<tbody>
                    <tr>
                        <th><span class="t_imp">출발일 범위</span></th>
                        <td colspan="3">
                            <?if (chkBlank($params['stock_idx'])) {?>
                                <div class="box">
                                    <div class="input_box" style="width:130px;margin-right:0">
                                        <input type="text" name="sdate" id="pop_sdate" value="<?=$params['sch_sdate']?>" readonly />
                                    </div>
                                    <p class="normal" style="margin:0 8px;">~</p>
                                    <div class="input_box" style="width:130px;">
                                        <input type="text" name="edate" id="pop_edate" value="<?=$params['sch_edate']?>" readonly />
                                    </div>
                                </div>
                            <?} else {?>
                                <input type="hidden" name="sdate" id="pop_sdate" value="<?=$view['sdate']?>">
                                <input type="hidden" name="edate" id="pop_edate" value="<?=$view['sdate']?>">
                                <?=$view['sdate']?> ~ <?=$view['sdate']?>
                            <?}?>
                        </td>
                    </tr>
                    <tr>
						<th><span class="t_imp">재고</span></th>
						<td colspan="3">
							<div class="box">
                                <div class="input_box mr5" style="width: 50px">
                                    <input type="text" name="stock_cnt" id="pop_stock_cnt" class="onlyNum" value="<?=$view['stock_cnt']?>" maxlength="3" />
                                </div>
                                <p class="normal">개</p>
							</div>
                        </td>
                    </tr>
				</tbody>
			</table>
			</form>

			<div class="btn_area two">
				<a href="javascript:;" class="btn gray" onclick="commonLayerClose('goods_stock_popup')">닫기</a>
				<a href="javascript:;" class="btn blue" onclick="priceSaveGo()">저장</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    $(function(){
        <?if (chkBlank($params['stock_idx'])) {?>
            $("#pop_sdate, #pop_edate").datepicker();
        <?}?>
    })

	function priceSaveGo() {
        var h = new clsJsHelper();

		AJ.ajaxForm($("#popGoodsStockFrm"), "goods_stock_write_proc.php", function(data) {
			if (data.result == 200) {
                alert("처리 되었습니다.");

                getStockList(1);
                commonLayerClose('goods_stock_popup')
			} else {
				alert(data.message);
			}
		});
	}
</script>