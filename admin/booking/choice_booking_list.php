<?include("../inc/config.php")?>
<?
	$pageNum = "0502";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']          = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']     = 20;
	$params['block_size']    = 10;
	$params['sch_sdate']     = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']     = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_status']    = chkReqRpl("sch_status", "", 10, "", "STR");
    $params['sch_type']      = chkReqRpl("sch_type", null, "", "", "INT");
	$params['sch_word']      = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

    $cls_booking = new CLS_BOOKING;


    //예약자 목록
    $list = $cls_booking->choice_tour_booking_list($params, $total_cnt, $total_page);

    //예약상태 상태목록
    $status_list = getResvStatusList();
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
									<p class="normal mr5">접수일 검색</p>
									<div class="input_box date">
										<input type="text" name="sch_sdate" id="sch_sdate" value="<?=$params['sch_sdate']?>" readonly placeholder="접수일" />
									</div><span class="mr0"></span>
									<p class="dash">~</p>
									<div class="input_box date">
										<input type="text" name="sch_edate" id="sch_edate" value="<?=$params['sch_edate']?>" readonly placeholder="접수일" />
									</div><span></span>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_status" id="sch_status">
											<option value="">상태 전체</option>
                                            <?for ($i=0; $i<count($status_list); $i++) {?>
                                                <?if ($status_list[$i]['code']!='11' && $status_list[$i]['code']!='21' && $status_list[$i]['code']!='31' && $status_list[$i]['code']!='41') {?>
                                                    <option value="<?=$status_list[$i]['code']?>" <?=chkCompare($params['sch_status'],$status_list[$i]['code'],'selected')?>><?=$status_list[$i]['name2']?></option>
                                                <?}?>
											<?}?>
										</select>
                                    </div>
                                    <div class="c_selectbox">
										<label for=""></label>
										<select name="sch_type" id="sch_type">
											<option value="">전체</option>
											<option value="1" <?=chkCompare($params['sch_type'],1,'selected')?>>예약번호</option>
											<option value="2" <?=chkCompare($params['sch_type'],2,'selected')?>>예약자 이름</option>
                                            <option value="3" <?=chkCompare($params['sch_type'],3,'selected')?>>예약자 연락처</option>
                                            <option value="4" <?=chkCompare($params['sch_type'],4,'selected')?>>예약자 이메일</option>
										</select>
                                    </div>
									<div class="input_box" style="width:200px">
										<input type="text" name="sch_word" id="sch_word" value="<?=$params['sch_word']?>" maxlength="20" placeholder="상품명을 입력해주세요." />
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
						<col width="130" />
                        <col width="*" />
                        <col width="100" />
                        <col width="100" />
                        <col width="120" />
						<col width="100" />
						<col width="100" />
						<col width="100" />
						<col width="140" />
                        <col width="120" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
                            <th>예약번호</th>
                            <th>상품명</th>
							<th>예약자</th>
							<th>예약자 연락처</th>
                            <th>투어 일시</th>
							<th>투어 인원</th>
							<th>현장지불금액</th>
							<th>예약 대행 수수료</th>
							<th>예약상태</th>
							<th>접수일</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
                                <td><a href="choice_booking_view.php?idx=<?=$list[$i]['idx']?>&page=<?=$params['page'] . $page_params?>" class="link"><?=$list[$i]['booking_num']?></a></td>
                                <td><?=$list[$i]['goods_title']?></td>
								<td><?=$list[$i]['name']?></td>
								<td><?=$list[$i]['phone']?></td>
								<td><?=$list[$i]['tour_date']?> <?=$list[$i]['tour_time']?></td>
								<td>
                                    <?=formatNumbers($list[$i]['adult_cnt']+$list[$i]['child_cnt']+$list[$i]['infant_cnt'])?>명
                                </td>
								<td>$<?=(formatNumbers($list[$i]['total_tour_amt']+$list[$i]['total_add_amt']))?></td>
								<td>￦<?=formatNumbers($list[$i]['booking_agency_fee'])?></td>
								<td><?=getResvStatusName($list[$i]['status'], 'name2')?></td>
								<td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
							</tr>
						<?}?>

						<?if (count($list) == 0) {?>
							<tr>
								<td colspan="11">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>

				<nav class="page_nate">
					<? adminPaging($total_page, $params['block_size'], $params['page'], $page_params, "") ?>
				</nav>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			$("#sch_sdate, #sch_edate, #sch_picksdate, #sch_pickedate").datepicker();
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