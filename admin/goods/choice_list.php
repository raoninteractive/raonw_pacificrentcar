<?include("../inc/config.php")?>
<?
	$pageNum = "0402";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 20;
	$params['block_size'] = 10;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "", "STR");
    $params['sch_open']   = chkReqRpl("sch_open", "", 1, "", "STR");
	$params['sch_word']   = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params          = setPageParamsValue($params, "page,list_size,block_size");

    $cls_goods = new CLS_GOODS;

    //상품 관리자 목록
	$list = $cls_goods->choice_tour_list_admin($params, $total_cnt, $total_page);

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
										<select name="sch_open" id="sch_open">
											<option value="">노출상태 전체</option>
											<option value="Y" <?=chkCompare($params['sch_open'], 'Y','selected')?>>노출</option>
											<option value="N" <?=chkCompare($params['sch_open'], 'N','selected')?>>숨김</option>
										</select>
                                    </div>
									<div class="input_box" style="width:300px">
										<input type="text" name="sch_word" id="sch_word" value="<?=$params['sch_word']?>" maxlength="20" placeholder="상품명/소개 검색어를 입력해주세요." />
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
						<col width="*" />
						<col width="250" />
						<col width="150" />
						<col width="200" />
						<col width="80" />
                        <col width="120" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>상품명</th>
							<th>상품가격</th>
							<th>예약수수료</th>
							<th>상품기간</th>
                            <th>노출상태</th>
							<th>등록일</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
								<td class="left"><a href="choice_write.php?idx=<?=$list[$i]['idx']?>&page=<?=$params['page'] . $page_params?>" class="link"><?=$list[$i]['title']?></a></td>
								<td>
									성인: $<?=formatNumbers($list[$i]['adult_amt'])?>,
									아동: $<?=formatNumbers($list[$i]['child_amt'])?>,
									유아: $<?=formatNumbers($list[$i]['infant_amt'])?>,
								</td>
                                <td><?=formatNumbers($list[$i]['agency_fee'])?>원</td>
								<td><?=formatDates($list[$i]['sdate'], 'Y.m.d')?> ~ <?=formatDates($list[$i]['edate'], 'Y.m.d')?></td>
								<td><?=$list[$i]['open_flag']?></td>
								<td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
							</tr>
						<?}?>

						<?if (count($list) == 0) {?>
							<tr>
								<td colspan="7">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>

				<nav class="page_nate">
					<? adminPaging($total_page, $params['block_size'], $params['page'], $page_params, "") ?>

                    <a href="choice_write.php?page=page=<?=$params['page'] . $page_params?>" class="btn_etc">상품 등록</a>
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