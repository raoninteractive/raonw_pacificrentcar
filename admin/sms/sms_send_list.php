<?include("../inc/config.php")?>
<?
	$pageNum = "9102";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", dateAdd("m", -1, date("Y-m-d")), 10, "GET", "STR");
    $params['sch_edate']  = chkReqRpl("sch_edate", date("Y-m-d"), 10, "GET", "STR");
    $params['sch_status'] = chkReqRpl("sch_status", null, "", "GET", "INT");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$page_params = "&sch_sdate=". $params['sch_sdate'] ."&sch_edate=". $params['sch_edate'] ."&sch_status=". $params['sch_status'] ."&sch_type=". $params['sch_type'] ."&sch_word=". $params['sch_word'];

    $cls_sms = new CLS_SMS;

	//SMS 발송이력 불러오기
	$list = $cls_sms->send_list($params, $total_cnt, $total_page);
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="search_box">
				<table>
					<colgroup>
						<col width="110">
						<col >
					</colgroup>
					<tbody>
						<tr>
							<th>검색설정</th>
							<td class="com">
								<form name="searchFrm" id="searchFrm">
								<div class="box">
									<div class="input_box date">
										<input type="text" name="sch_sdate" id="sch_sdate" value="<?=$params['sch_sdate']?>" readonly placeholder="발송일" />
									</div>
									<p class="dash">~</p>
									<div class="input_box date">
										<input type="text" name="sch_edate" id="sch_edate" value="<?=$params['sch_edate']?>" readonly placeholder="발송일" />
                                    </div>
                                    <div class="c_selectbox">
										<label for=""></label>
										<select name="sch_status" id="sch_status">
											<option value="">발송상태</option>
											<option value="1" <?=chkCompare($params['sch_status'], 1, "selected")?>>대기</option>
                                            <option value="2" <?=chkCompare($params['sch_status'], 2, "selected")?>>완료</option>
                                            <option value="3" <?=chkCompare($params['sch_status'], 3, "selected")?>>취소</option>
                                            <option value="4" <?=chkCompare($params['sch_status'], 4, "selected")?>>실패</option>
										</select>
									</div>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_type" id="sch_type">
											<option value="">전체검색</option>
											<option value="1" <?=chkCompare($params['sch_type'], 1, "selected")?>>수신자 아이디</option>
                                            <option value="2" <?=chkCompare($params['sch_type'], 2, "selected")?>>수신자 이름</option>
                                            <option value="3" <?=chkCompare($params['sch_type'], 3, "selected")?>>수신자 연락처뒤4자리</option>
                                            <option value="4" <?=chkCompare($params['sch_type'], 4, "selected")?>>발송내용</option>
										</select>
									</div>
									<div class="input_box" style="width:200px">
										<input type="text" name="sch_word" id="sch_word" value="<?=$sch_word?>" maxlength="20" placeholder="검색어를 입력해주세요." />
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
                        <col width="60" />
						<col width="150" />
						<col width="120" />
						<col width="120" />
						<col width="*" />
						<col width="120" />
						<col width="90" />
					</colgroup>
					<thead>
						<tr>
                            <th>번호</th>
							<th>발송위치</th>
							<th>수신아이디</th>
							<th>수신번호</th>
							<th>발송내용</th>
							<th>발송일시</th>
							<th>발송상태</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
								<td><?=$list[$i]['section']?></td>
								<td><?=$list[$i]['usr_id']?></td>
								<td><?=$list[$i]['recipient_tel']?></td>
								<td class="left"><a href="javascript:;" class="link" onclick="smsSendView(<?=$list[$i]['idx']?>)"><?=returnToCut($list[$i]['send_msg'], 100, "…")?></a></td>
								<td><?=formatDates($list[$i]['reserve_date'], "Y.m.d H:i")?></td>
								<td>
                                    <?if ($list[$i]['status']=='0' && $list[$i]['send_gubun']=='R') {?>
                                        <?if ($list[$i]['status']=='0' && $list[$i]['send_gubun']=='R') {?>
                                            <a href="javascript:;" class="a_link fc_red" onclick="smsSendCancel(<?=$list[$i]['idx']?>)">예약발송취소</a>
                                        <?} else {?>
                                            <?=$list[$i]['status_name']?>
                                        <?}?>
                                    <?} else {?>
                                        <?if ($list[$i]['status'] > '1') {?>
                                            <strong class="fc_red"><?=$list[$i]['status_name']?></strong>
                                        <?} else {?>
                                            <?=$list[$i]['status_name']?>
                                        <?}?>
                                    <?}?>
                                </td>
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
				</nav>
			</div>
		</div>
	</div>
    <!-- //container -->

    <!-- 레이어팝업 : 문자내용상세보기 -->
	<article class="layer_popup sms_send_view_popup"></article>

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

		//문자예약발송취소
		function smsSendCancel(idx) {
			if (!confirm("선택하신 문자내역에 대해서 발송을 취소하시겠습니까?\n한번 취소시 복구는 불가능합니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

			AJ.callAjax("__sms_send_cancel_proc.php", {"idx": idx}, function(data){
				if (data.result == 200) {
					alert("발송 취소 처리가 완료되었습니다.");
					location.reload();
				} else {
					alert(data.message);
				}
			});
		}

		//문자내용상세보기
		function smsSendView(idx) {
			AJ.callAjax("sms_send_view.php", {"idx": idx}, function(data){
				$(".sms_send_view_popup").html(data);
				commonLayerOpen('sms_send_view_popup');
			}, "html");
		}
	</script>
<?include("../inc/footer.php")?>