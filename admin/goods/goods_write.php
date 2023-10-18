<?include("../inc/config.php")?>
<?
	$pageNum = "0401";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

    $params['idx']        = chkReqRpl("idx", null, "", "", "INT");
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_cate']   = chkReqRpl("sch_cate", "", 10, "", "STR");
    $params['sch_open']   = chkReqRpl("sch_open", "", 1, "", "STR");
	$params['sch_word']   = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params          = setPageParamsValue($params, "page,list_size,block_size");

    $cls_goods = new CLS_GOODS;

    $view = $cls_goods->goods_view($params['idx'], 'Y');
    if (!chkBlank($params['idx']) && $view == false) fnMsgGo(503, "일치하는 데이터가 없습니다.", "BACK", "");
    if ($view == false) {
        $view['main_open_flag'] = 'N';
        $view['main_sort'] = 0;
        $view['sort'] = 0;
    } else {
    }

    $goods_cate = getGoodsCateList();
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
                <form name="regFrm" id="regFrm" method="post">
                <input type="hidden" name="idx" value="<?=$params['idx']?>" />
				<div class="group">
                    <h3 class="g_title">상품 정보</h3>
					<table class="g_table">
						<colgroup>
                            <col width="12%">
                            <col width="38%">
                            <col width="12%">
                            <col width="38%">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_imp">상품명<span></th>
								<td>
                                    <div class="box">
										<div class="input_box" style="width:100%">
											<input type="text" name="title" id="title" value="<?=$view['title']?>" />
										</div>
									</div>
                                </td>
                                <th><span class="t_imp">노출 상태<span></th>
								<td>
                                    <div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="open_flag" id="open_flag">
												<option value="Y" <?=chkCompare($view['open_flag'], 'Y', 'selected')?>>노출</option>
												<option value="N" <?=chkCompare($view['open_flag'], 'N', 'selected')?>>숨김</option>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">상품분류<span></th>
								<td>
									<div class="box">
                                        <div class="c_selectbox">
											<label for=""></label>
											<select name="category" id="category">
                                                <option value="">선택</option>
                                                <?for ($i=0; $i<count($goods_cate); $i++) {?>
                                                    <option value="<?=$goods_cate[$i]['code']?>" <?=chkCompare($view['category'], $goods_cate[$i]['code'], "selected")?>><?=$goods_cate[$i]['name']?></option>
                                                <?}?>
											</select>
										</div>
                                    </div>
								</td>
								<th><span class="t_h">상품옵션<span></th>
								<td>
                                    <div class="box">
                                        <div class="c_checkbox">
                                            <input type="checkbox" name="option_1" id="option_1" value="Y" <?=chkCompare($view['option_1'], 'Y', 'checked')?> />
                                            <label for="option_1">주유포함</label>
                                        </div>

                                        <div class="c_checkbox">
                                            <input type="checkbox" name="option_2" id="option_2" value="Y" <?=chkCompare($view['option_2'], 'Y', 'checked')?> />
                                            <label for="option_2">CDW 포함</label>
                                        </div>

                                        <div class="c_checkbox">
                                            <input type="checkbox" name="option_7" id="option_7" value="Y" <?=chkCompare($view['option_7'], 'Y', 'checked')?> />
                                            <label for="option_7">ZDC 포함</label>
                                        </div>

                                        <div class="c_checkbox">
                                            <input type="checkbox" name="option_8" id="option_8" value="Y" <?=chkCompare($view['option_8'], 'Y', 'checked')?> />
                                            <label for="option_8">PAI 포함</label>
                                        </div>
                                    </div>
                                </td>
                            </tr>
							<tr>
								<th><span class="t_imp">상품가격<span></th>
								<td>
									<div class="box">
                                        <p class="normal mr10"><strong>1일</strong></p>
                                        <p class="normal mr5">$</p>
                                        <div class="input_box mr15" style="width: 50px">
                                            <input type="text" name="day1_amt" id="day1_amt" value="<?=$view['day1_amt']?>" class="onlyNum"  maxlength="4" />
                                        </div>

                                        <p class="normal fc_red">※ <?=$GOODS_DC_PERIOD_DAY?>일 이후 부터 1일 상품금액의 <?=$GOODS_DC_RATE * 100?>% 적용</p>

                                        <!-- <p class="normal mr10"><strong>7일</strong></p>
                                        <p class="normal mr5">$</p>
                                        <div class="input_box mr15" style="width: 50px">
                                            <input type="text" name="day7_amt" id="day7_amt" value="<?=$view['day7_amt']?>" class="onlyNum" maxlength="4" />
                                        </div>

                                        <p class="normal mr10"><strong>30일</strong></p>
                                        <p class="normal mr5">$</p>
                                        <div class="input_box mr15" style="width: 50px">
                                            <input type="text" name="day30_amt" id="day30_amt" value="<?=$view['day30_amt']?>" class="onlyNum" maxlength="4" />
                                        </div> -->
                                    </div>
                                </td>
								<th><span class="t_imp">예약 대행 수수료<span></th>
								<td>
									<div class="box">
                                        <div class="input_box" style="width: 100px">
                                            <input type="text" name="agency_fee" id="agency_fee" value="<?=$view['agency_fee']?>" class="onlyNum"  maxlength="9" />
                                        </div>
                                        <p class="normal">원</p>
                                    </div>
								</td>
                            </tr>
                            <tr>
								<th><span class="t_imp">상품가격<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="c_checkbox mr5">
                                            <input type="checkbox" name="option_3" id="option_3" value="Y" <?=chkCompare($view['option_3'], 'Y', 'checked')?> />
                                            <label for="option_3">아이스박스</label>
                                        </div>
                                        <p class="normal mr5">$</p>
                                        <div class="input_box mr20" style="width: 50px">
                                            <input type="text" name="option_3_amt" id="option_3_amt" value="<?=$view['option_3_amt']?>" class="onlyNum"  maxlength="4" />
                                        </div>

                                        <div class="c_checkbox mr5">
                                            <input type="checkbox" name="option_4" id="option_4" value="Y" <?=chkCompare($view['option_4'], 'Y', 'checked')?> />
                                            <label for="option_4">공항픽업</label>
                                        </div>
                                        <p class="normal mr5">$</p>
                                        <div class="input_box mr20" style="width: 50px">
                                            <input type="text" name="option_4_amt" id="option_4_amt" value="<?=$view['option_4_amt']?>" class="onlyNum"  maxlength="4" />
                                        </div>

                                        <div class="c_checkbox mr5">
                                            <input type="checkbox" name="option_5" id="option_5" value="Y" <?=chkCompare($view['option_5'], 'Y', 'checked')?> />
                                            <label for="option_5">네이게이션</label>
                                        </div>
                                        <p class="normal mr5">$</p>
                                        <div class="input_box mr20" style="width: 50px">
                                            <input type="text" name="option_5_amt" id="option_5_amt" value="<?=$view['option_5_amt']?>" class="onlyNum"  maxlength="4" />
                                        </div>

                                        <div class="c_checkbox mr5">
                                            <input type="checkbox" name="option_6" id="option_6" value="Y" <?=chkCompare($view['option_6'], 'Y', 'checked')?> />
                                            <label for="option_6">카시트</label>
                                        </div>
                                        <p class="normal mr5">$</p>
                                        <div class="input_box mr20" style="width: 60px">
                                            <input type="text" name="option_6_amt" id="option_6_amt" value="<?=$view['option_6_amt']?>" class="onlyNumDot"  maxlength="5" />
                                        </div>

                                        <p class="normal fc_red">※ 0원 입력시 무료 입니다.</p>
                                    </div>
                                </td>
                            </tr>
							<tr>
								<th><span class="t_imp">특징<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="textarea_box" style="width:467px">
											<textarea name="content" id="content" style="height:100px"><?=$view['content']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
                            <?for ($i=1; $i<=1; $i++) {?>
                                <tr>
                                    <th><span class="<?=iif($i==1, 't_imp', 't_h')?>">상품이미지<?=$i?><span></th>
                                    <td colspan="3">
                                        <div class="box file">
                                            <div class="input_box" style="width:400px;">
                                                <input type="text" placeholder="이미지 파일을 등록해주세요. (2MB / png,jpg,gif,jpeg)" readonly />
                                            </div>
                                            <input type="hidden" name="old_up_file_<?=$i?>" id="old_up_file_<?=$i?>" value="<?=$view['up_file_'.$i]?>" />
                                            <input type="file" name="up_file_<?=$i?>" id="up_file_<?=$i?>" class="upload-hidden" upload-type="img" upload-size="2" upload-ext="png,jpg,gif,jpeg" >
                                            <label for="up_file_<?=$i?>" class="btn_30 gray">찾아보기</label>
                                            <?if (getUpfileName($view['up_file_'.$i]) != '') {?>
												<p class="mt5">
													<a href="javascript:;" onclick="imgPreviwePopupOpen('/upload/goods/thumb/<?=getUpfileName($view['up_file_'.$i])?>')">
														<img src="/upload/goods/thumb/<?=getUpfileName($view['up_file_'.$i])?>" style="max-width:200px" />
													</a>

                                                    <?if ($i > 1) {?>
													    <a href="javascript:;" onclick="upfileDelGo('<?=$view['idx']?>', '<?=$i?>', 'thumb')">[삭제]</a>
                                                    <?}?>
												</p>
											<?}?>
                                        </div>
                                    </td>
                                </tr>
                            <?}?>
							<tr>
								<th><span class="t_h">상품키워드<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="input_box" style="width:467px">
											<input type="text" name="keyword" id="keyword" value="<?=$view['keyword']?>" />
                                        </div>
                                        <p class="normal fc_red">※ 키워드는 콤마(,)로 구분하여 등록해야되며 최대3개까지 등록가능합니다.</p>
                                    </div>
								</td>
                            </tr>
                            <tr>
								<th><span class="t_imp">상품정렬순번<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="input_box mr20" style="width: 50px">
                                            <input type="text" name="sort" id="sort" value="<?=$view['sort']?>" class="onlyNum"  maxlength="3" placeholder="순번" />
                                        </div>
                                        <p class="normal fc_red">※ 순번은 정렬순번 높은순으로 정렬됩니다.</p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
								<th><span class="t_imp">메인노출<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="c_checkbox mr5">
                                            <input type="checkbox" name="main_open_flag" id="main_open_flag" value="Y" <?=chkCompare($view['main_open_flag'], 'Y', 'checked')?> />
                                            <label for="main_open_flag">메인노출</label>
                                        </div>
                                        <div class="input_box mr20" style="width: 50px">
                                            <input type="text" name="main_sort" id="main_sort" value="<?=$view['main_sort']?>" class="onlyNum"  maxlength="1" placeholder="순번" />
                                        </div>
                                        <p class="normal fc_red">※ 순번은 정렬순번 높은순으로 정렬됩니다.</p>
                                    </div>
                                </td>
                            </tr>
						</tbody>
					</table>
                </div>

                <!-- 탭 영역 -->
                <div class="tabs_btn">
                    <h3 class="g_title">상품 재고 정보</h3>
                    <nav class="tabs">
                        <ul class="tab1">
                            <li class="curr" data-id="tab1"><a href="javascript:;">일별 재고관리</a></li>
                        </ul>
                    </nav>
                </div>
                <div class="tabs_cont tab1"></div>
                </form>

				<div class="page_btn_a center mt30">
					<a href="goods_list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
					<a href="javascript:;" class="btn_40 white" onclick="regGo();"><?=iif($params['idx'] == '', '등록하기', '수정하기')?></a>
                </div>

                <!-- 관리자 메모영역 -->
                <?if ($params['idx'] != '') {?>
                    <?
                        $admin_memo_section = "goods_write";
                        $admin_memo_gubun = $params['idx'];
                    ?>
                    <?include("../common/admin_memo_log_include.php")?>
                <?}?>
			</div>
		</div>
	</div>
    <!-- //container -->

    <!-- 레이어팝업 : 상품가격 추가 -->
    <article class="layer_popup goods_stock_popup"></article>

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
            $(".tabs_cont.tab1").load("_tabs_cont_1.php?goods_idx=<?=$params['idx']?>");

            $("#option_3").on("click dblclick", function(){
                $("#option_3_amt").prop("disabled", !this.checked)
            })
            $("#option_3").trigger("dblclick");

            $("#option_4").on("click dblclick", function(){
                $("#option_4_amt").prop("disabled", !this.checked)
            })
            $("#option_4").trigger("dblclick");

            $("#option_5").on("click dblclick", function(){
                $("#option_5_amt").prop("disabled", !this.checked)
            })
            $("#option_5").trigger("dblclick");

            $("#option_6").on("click dblclick", function(){
                $("#option_6_amt").prop("disabled", !this.checked)
            })
            $("#option_6").trigger("dblclick");
		})

		//폼체크
		function regGo() {
            if (!h.checkValNLen("title", 2, 200, "상품명", "N", "KO")) return false;
            if (!h.checkSelect("category", "상품분류")) return false;

            if (!h.checkValNLen("day1_amt", 1, 4, "상품가격(1일)", "Y", "ON")) return false;
            //if (!h.checkValNLen("day7_amt", 1, 4, "상품가격(7일)", "Y", "ON")) return false;
            //if (!h.checkValNLen("day30_amt", 1, 4, "상품가격(30일)", "Y", "ON")) return false;
            if (!h.checkValNLen("agency_fee", 1, 9, "예약 대행 수수료", "Y", "ON")) return false;

            if (!h.checkValNLen("content", 1, 1000, "특징", "N", "KO")) return false;

            if (h.objVal("old_up_file_1")=='' && h.objVal("up_file_1")=='') {
                alert("상품이미지1을 선택해주세요.");
                return false;
            }

            if (h.objVal("keyword")) {
                if (!h.checkValNLen("keyword", 1, 100, "상품키워드", "Y", "KO")) return false;

                var keyword = h.objVal("keyword");
                var tmp_arr = keyword.split(",");

                if (tmp_arr.length > 3) {
                    alert("키워드는 최대 3개까지 등록가능합니다.");
                    return false;
                }

                for (i=0; i<tmp_arr.length; i++) {
                    if (tmp_arr[i] == "") {
                        alert((i+1)+"번째 키워드를 입력해주세요.");
                        return false;
                    } else if (tmp_arr[i].length > 10) {
                        alert((i+1)+"번째 키워드는 최대 10글자 까지 등록가능합니다.");
                        return false;
                    }
                }
            }

			AJ.ajaxForm($("#regFrm"), "goods_write_proc.php", function(data) {
				if (data.result == 200) {
                    alert("처리 되었습니다.");

                    <?if (chkBlank($params['idx'])) {?>
                        location.replace("goods_write.php?page=<?=$params['page'] . $page_params?>&idx="+data.goods_idx);
                    <?} else {?>
                        location.reload();
                    <?}?>
				} else {
					alert(data.message);
				}
			});
        }
	</script>
<?include("../inc/footer.php")?>