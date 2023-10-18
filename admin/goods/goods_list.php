<?include("../inc/config.php")?>
<?
	$pageNum = "0401";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 20;
	$params['block_size'] = 10;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_cate']   = chkReqRpl("sch_cate", "", 10, "", "STR");
    $params['sch_open']   = chkReqRpl("sch_open", "", 1, "", "STR");
	$params['sch_word']   = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params          = setPageParamsValue($params, "page,list_size,block_size");

    $cls_goods = new CLS_GOODS;

    //상품 관리자 목록
	$list = $cls_goods->goods_list_admin($params, $total_cnt, $total_page);

	$cate_list = getGoodsCateList();
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="search_box">
				<table>
					<colgroup>
						<col style="width:110px;">
						<col style="width:*;">
					</colgroup>
					<tbody>
						<tr>
							<th>검색설정</th>
							<td class="com">
								<form name="searchFrm" id="searchFrm">
								<div class="box">
									<div class="input_box date">
										<input type="text" name="sch_sdate" id="sch_sdate" value="<?=$params['sch_sdate']?>" readonly placeholder="출발일" />
									</div><span></span>
									<p class="dash">~</p>
									<div class="input_box date">
										<input type="text" name="sch_edate" id="sch_edate" value="<?=$params['sch_edate']?>" readonly placeholder="도착일" />
									</div><span></span>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_cate" id="sch_cate">
											<option value="">상품분류 전체</option>
											<?for ($i=0; $i<count($cate_list); $i++) {?>
												<option value="<?=$cate_list[$i]['code']?>" <?=chkCompare($params['sch_cate'], $cate_list[$i]['code'],'selected')?>><?=$cate_list[$i]['name']?></option>
											<?}?>
										</select>
                                    </div>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_open" id="sch_open">
											<option value="">노출상태 전체</option>
											<option value="Y" <?=chkCompare($params['sch_open'], 'Y','selected')?>>노출</option>
											<option value="N" <?=chkCompare($params['sch_open'], 'N','selected')?>>숨김</option>
										</select>
                                    </div>
									<div class="input_box" style="width:300px">
										<input type="text" name="sch_word" id="sch_word" value="<?=$params['sch_word']?>" maxlength="20" placeholder="상품명/특징/키워드 검색어를 입력해주세요." />
									</div>
									<a href="javascript:;" class="btn_search" onclick="searchGo()">검색</a>
								</div>
								</form>
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
				</div>
				<table>
					<colgroup>
						<col width="70" />
						<col width="100" />
						<col width="*" />
						<col width="250" />
						<col width="150" />
						<col width="90" />
						<col width="80" />
                        <col width="120" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>상품분류</th>
							<th>상품명</th>
							<th>상품가격</th>
							<th>상품옵션</th>
							<th>상품재고</th>
                            <th>노출상태</th>
							<th>등록일</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
								<td><?=getGoodsCateName($list[$i]['category'])?></td>
								<td class="left"><a href="goods_write.php?idx=<?=$list[$i]['idx']?>&page=<?=$params['page'] . $page_params?>" class="link"><?=$list[$i]['title']?></a></td>
								<td>
									1일: $<?=formatNumbers($list[$i]['total_stock_cnt'])?>,
									7일: $<?=formatNumbers($list[$i]['total_stock_cnt'])?>,
									30일: $<?=formatNumbers($list[$i]['total_stock_cnt'])?>,
								</td>
								<td>
									<?
										$option_txt = "";
										if ($list[$i]['option_1'] == 'Y') {
											$option_txt .= "주유포함";
										}

										if ($list[$i]['option_2'] == 'Y') {
											if ($option_txt != "") $option_txt .= ", ";
											$option_txt .= "CDW포함";
										}

										if ($list[$i]['option_7'] == 'Y') {
											if ($option_txt != "") $option_txt .= ", ";
											$option_txt .= "ZDC포함";
										}

										if ($list[$i]['option_8'] == 'Y') {
											if ($option_txt != "") $option_txt .= ", ";
											$option_txt .= "PAI포함";
										}

										echo $option_txt;
									?>
								</td>
								<td><?=formatNumbers($list[$i]['total_stock_cnt'])?>개</td>
								<td><?=$list[$i]['open_flag']?></td>
								<td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
							</tr>
						<?}?>

						<?if (count($list) == 0) {?>
							<tr>
								<td colspan="8">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>

				<nav class="page_nate">
					<? adminPaging($total_page, $params['block_size'], $params['page'], $page_params, "") ?>

                    <a href="goods_write.php?page=page=<?=$params['page'] . $page_params?>" class="btn_etc">상품 등록</a>
				</nav>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			$("#sch_sdate, #sch_edate").datepicker();
		})

		//검색
		function searchGo() {
			if (h.objVal("sch_word")) {
				if (!h.checkValNLen("sch_word", 2, 20, "검색어", "N", "KO")) return false;
			}

			$("#searchFrm").submit();
		}
	</script>
<?include("../inc/footer.php")?>