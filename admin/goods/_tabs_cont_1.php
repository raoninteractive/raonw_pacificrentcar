<?
    require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

    if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");
	if (!isAdmin()) fnMsgGo(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "BACK", "");

    $params['goods_idx']  = chkReqRpl("goods_idx", null, "", "GET", "INT");
	$params['page']       = chkReqRpl("page", 1, "", "GET", "INT");
	$params['list_size']  = 20;
	$params['block_size'] = 10;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "", "STR");
    $page_params          = setPageParamsValue($params, "page,list_size,block_size");

    $cls_goods = new CLS_GOODS;

    //상품 재고 목록 불러오기
    $stock_list = $cls_goods->stock_list($params, $total_cnt, $total_page);
?>

<?if (chkBlank($params['goods_idx'])) {?>
    <div class="mt30 mb30 ta_c fz-s6">상품 정보 등록 후 등록 가능합니다.</div>
<?} else {?>
    <div class="search_box">
        <table>
            <colgroup>
                <col width="12%">
                <col >
            </colgroup>
            <tbody>
                <tr>
                    <th>검색설정</th>
                    <td>
                        <div class="box">
                            <div class="input_box date">
                                <input type="text" name="sch_sdate" id="sch_sdate" value="<?=$params['sch_sdate']?>" readonly placeholder="출발일검색" />
                            </div><span></span>
                            <p class="dash">~</p>
                            <div class="input_box date">
                                <input type="text" name="sch_edate" id="sch_edate" value="<?=$params['sch_edate']?>" readonly placeholder="출발일검색" />
                            </div><span></span>
                            <a href="javascript:;" class="btn_search" onclick="stockListSearchGo()">검색</a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="common_list">
        <div class="list_header">
            <dl class="cnt">
                <dt>Total</dt>
                <dd><?=formatNumbers($total_cnt)?></dd>
            </dl>
            <div class="ft_r">
                <a href="javascript:;" class="btn_30" onclick="stockWritePopupGo('reg', '')">일괄 등록</a>
                <a href="javascript:;" class="btn_30" onclick="stockWritePopupGo('modify', '')">일괄 수정</a>
            </div>
        </div>
        <table>
            <colgroup>
                <col width="70" />
                <col width="120" />
                <col width="" />
                <col width="" />
                <col width="" />
                <col width="120" />
            </colgroup>
            <thead>
                <tr>
                    <th>번호</th>
                    <th>출발일</th>
                    <th>총 재고 수</th>
                    <th>예약자 수</th>
                    <th>남은 재고 수</th>
                    <th>관리</th>
                </tr>
            </thead>
            <tbody class="stock_list">
                <?for ($i=0; $i<count($stock_list); $i++) {?>
                    <tr>
                        <td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
                        <td><?=$stock_list[$i]['sdate']?></td>
                        <td><?=formatNumbers($stock_list[$i]['stock_cnt'] + $stock_list[$i]['booking_cnt'])?>개</td>
                        <td><?=formatNumbers($stock_list[$i]['booking_cnt'])?>개</td>
                        <td><?=formatNumbers($stock_list[$i]['stock_cnt'])?>개</td>
                        <td>
                            <a href="javascript:;" class="btn_26 white" onclick="stockWritePopupGo('reg', <?=$stock_list[$i]['stock_idx']?>)">수정</a>
                            <a href="javascript:;" class="btn_26 gray" onclick="stockDeleteGo(<?=$stock_list[$i]['stock_idx']?>)">삭제</a>
                        </td>
                    </tr>
                <?}?>

                <?if (count($stock_list) == 0) {?>
                    <tr class="no-data">
                        <td colspan="6">등록된 데이터가 없습니다.</td>
                    </tr>
                <?}?>
            </tbody>
        </table>

        <nav class="page_nate">
            <? adminScriptPaging($total_page, $params['block_size'], $params['page'], "getStockList({page})") ?>
        </nav>
    </div>

    <script type="text/javascript">
        $(function(){
            $("#sch_sdate, #sch_edate").datepicker();

            selectboxInit();
        })

        //상품가격 목록
        function getStockList(page) {
            var $params = {
                "goods_idx": "<?=$params['goods_idx']?>",
                "sch_sdate": $("#sch_sdate").val(),
                "sch_edate": $("#sch_edate").val()
            }

            $(".tabs_cont.tab1").load("_tabs_cont_1.php?page="+ page +"&"+ $.param($params));
        }

        //검색
        function stockListSearchGo() {
            var $params = {
                "goods_idx": "<?=$params['goods_idx']?>",
                "sch_sdate": $("#sch_sdate").val(),
                "sch_edate": $("#sch_edate").val()
            }

            $(".tabs_cont.tab1").load("_tabs_cont_1.php?"+$.param($params));
        }

        //등록,수정 팝업
        function stockWritePopupGo(gubun, stock_idx) {
            AJ.callAjax("goods_stock_write.php", {"goods_idx": "<?=$params['goods_idx']?>", "gubun": gubun, "stock_idx": stock_idx,
                    "sch_sdate": $("#sch_sdate").val(), "sch_edate": $("#sch_edate").val()}, function(data){
                $(".goods_stock_popup").html(data);
                commonLayerOpen('goods_stock_popup');
            }, "html");
        }

        //삭제
        function stockDeleteGo(stock_idx) {
            if (!confirm("선택한 상품가격을 삭제하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

            AJ.callAjax("goods_stock_delete_proc.php", {"goods_idx": "<?=$params['goods_idx']?>", "stock_idx": stock_idx}, function(data){
                if (data.result == 200) {
                    alert("삭제 되었습니다.");

                    getStockList(1);
                } else {
                    alert(data.message);
                }
            });
        }
    </script>
<?}?>