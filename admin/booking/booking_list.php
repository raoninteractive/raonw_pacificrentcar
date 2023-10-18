<?include("../inc/config.php")?>
<?
	$pageNum = "0501";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']          = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']     = 20;
	$params['block_size']    = 10;
	$params['sch_sdate']     = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']     = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_picksdate'] = chkReqRpl("sch_picksdate", "", 10, "", "STR");
	$params['sch_pickedate'] = chkReqRpl("sch_pickedate", "", 10, "", "STR");
	$params['sch_cate']      = chkReqRpl("sch_cate", "", 10, "", "STR");
	$params['sch_status']    = chkReqRpl("sch_status", "", 10, "", "STR");
    $params['sch_type']      = chkReqRpl("sch_type", null, "", "", "INT");
	$params['sch_word']      = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

    $cls_booking = new CLS_BOOKING;


    //예약자 목록
    $list = $cls_booking->booking_list($params, $total_cnt, $total_page);

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

									<p class="normal ml10 mr5">픽업일 검색</p>
									<div class="input_box date">
										<input type="text" name="sch_picksdate" id="sch_picksdate" value="<?=$params['sch_picksdate']?>" readonly placeholder="픽업일" />
									</div><span class="mr0"></span>
									<p class="dash">~</p>
									<div class="input_box date">
										<input type="text" name="sch_pickedate" id="sch_pickedate" value="<?=$params['sch_pickedate']?>" readonly placeholder="픽업일" />
									</div><span></span>
								</div>
								<div class="box mt5">
                                    <div class="c_selectbox">
										<label for=""></label>
										<select name="sch_cate" id="sch_cate">
											<option value="">구분 전체</option>
											<option value="C001" <?=chkCompare($params['sch_cate'],'C001','selected')?>>괌(GUAM)</option>
										</select>
                                    </div>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_status" id="sch_status">
											<option value="">상태 전체</option>
                                            <?for ($i=0; $i<count($status_list); $i++) {?>
                                                <?if (strpos('20,23,22,30,32,40,42,43,44,50,52', $status_list[$i]['code']) !== false) {?>
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
											<option value="2" <?=chkCompare($params['sch_type'],2,'selected')?>>확정서 번호</option>
											<option value="3" <?=chkCompare($params['sch_type'],3,'selected')?>>예약자 이름</option>
                                            <option value="4" <?=chkCompare($params['sch_type'],4,'selected')?>>예약자 연락처</option>
                                            <option value="5" <?=chkCompare($params['sch_type'],5,'selected')?>>예약자 이메일</option>
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
						<col width="70" />
						<col width="130" />
                        <col width="*" />
                        <col width="80" />
                        <col width="100" />
						<col width="120" />
						<col width="70" />
						<col width="80" />
						<col width="*" />
						<col width="*" />
						<col width="80" />
						<col width="140" />
                        <col width="80" />
                        <col width="120" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>구분</th>
                            <th>예약번호</th>
                            <th>상품명</th>
							<th>예약자명</th>
							<th>연락처</th>
							<th>인수/픽업일</th>
							<th>렌트기간</th>
							<th>출발항공편</th>
							<th>인수/픽업 위치</th>
							<th>차량반납 위치</th>
							<th>확정서 번호</th>
							<th>예약상태</th>
							<th>현지메일발송</th>
							<th>접수일</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
								<td><?=iif($list[$i]['goods_category']=='C001', '괌', '사이판')?></td>
                                <td><a href="booking_view.php?idx=<?=$list[$i]['idx']?>&page=<?=$params['page'] . $page_params?>" class="link"><?=$list[$i]['booking_num']?></a></td>
                                <td><?=$list[$i]['goods_title']?></td>
								<td><?=$list[$i]['name']?></td>
								<td><?=$list[$i]['phone']?></td>
								<td><?=formatDates($list[$i]['rental_sdate'],'Y.m.d')?> <?=$list[$i]['rental_time']?></td>
								<td><?=$list[$i]['rental_day']?>일</td>
								<td><?=$list[$i]['out_airline']?></td>
								<td><?=$list[$i]['pickup_area']?></td>
								<td><?=$list[$i]['return_area']?></td>
                                <td>
									<?
										if ($list[$i]['confirm_status'] == '30') {
											echo $list[$i]['confirm_num'];
										} else {
											echo getConfirmCateName($list[$i]['confirm_status']);
										}
									?>
								</td>
								<td>
                                    <?
                                        $status_name = getResvStatusName($list[$i]['status'], 'name2');
                                        if (count(explode("(", $status_name)) > 1) {
                                            echo explode("(", $status_name)[0];
                                            echo "<br>(". explode("(", $status_name)[1];
                                        } else {
                                            echo $status_name;
                                        }
                                    ?>
                                </td>
                                <td><?=iif($list[$i]['local_send_email_flag']=='Y', '<strong class="fc_red">발송</strong>', '미발송')?></td>
								<td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
							</tr>
						<?}?>

						<?if (count($list) == 0) {?>
							<tr>
								<td colspan="15">등록된 데이터가 없습니다.</td>
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