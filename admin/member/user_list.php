<?include("../inc/config.php")?>
<?
	$pageNum = "0201";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_gubun']  = chkReqRpl("sch_gubun", null, "", "", "INT");
	$params['sch_status'] = chkReqRpl("sch_status", "", 1, "", "STR");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

	//일반회원 목록 불러오기
	$list = $cls_member->user_list($params, $total_cnt, $total_page);
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
										<input type="text" name="sch_sdate" id="sch_sdate" value="<?=$params['sch_sdate']?>" readonly placeholder="가입일" />
									</div><span></span>
									<p class="dash">~</p>
									<div class="input_box date">
										<input type="text" name="sch_edate" id="sch_edate" value="<?=$params['sch_edate']?>" readonly placeholder="가입일" />
									</div><span></span>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_gubun" id="sch_gubun">
											<option value="">회원등급 전체</option>
											<?foreach ($CONST_MEMBER_GUBUN as $item) {?>
												<option value="<?=$item[0]?>" <?=chkCompare($params['sch_gubun'],$item[0],'selected')?>><?=$item[1]?></option>
											<?}?>
										</select>
									</div>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_status" id="sch_status">
											<option value="">회원상태 전체</option>
											<option value="Y" <?=chkCompare($params['sch_status'], 'Y', 'selected')?>>정상</option>
											<option value="N" <?=chkCompare($params['sch_status'], 'N', 'selected')?>>이용정지</option>
										</select>
									</div>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_type" id="sch_type">
											<option value="">전체 검색</option>
											<option value="1" <?=chkCompare($params['sch_type'], 1, 'selected')?>>아이디</option>
											<option value="2" <?=chkCompare($params['sch_type'], 2, 'selected')?>>이름</option>
											<option value="3" <?=chkCompare($params['sch_type'], 3, 'selected')?>>휴대폰번호 뒤4자리</option>
										</select>
									</div>
									<div class="input_box" style="width:200px">
										<input type="text" name="sch_word" id="sch_word" value="<?=$params['sch_word']?>" maxlength="20" placeholder="검색어를 입력해주세요." />
									</div>
									<a href="javascript:;" class="btn_search" onclick="searchGo()">검색</a>

									<a href="user_excel_down.php?page=<?=$page . $page_params?>" class="btn_30 ml10">엑셀다운로드</a>
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
						<col width="80" />
						<col width="120" />
						<col width="*" />
						<col width="90" />
						<col width="70" />
						<col width="110" />
						<col width="200" />
						<col width="70" />
						<col width="70" />
						<col width="70" />
						<col width="120" />
						<col width="120" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>회원등급</th>
							<th>아이디</th>
							<th>이름</th>
							<th>생년월일</th>
							<th>성별</th>
							<th>휴대폰번호</th>
							<th>이메일</th>
							<th>아이프리</th>
							<th>회원인증</th>
							<th>회원상태</th>
							<th>최근 접속일</th>
							<th>최초 가입일시</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
								<td><?=$list[$i]['usr_gubun_name']?></td>
								<td><?=$list[$i]['usr_id']?></td>
								<td><a href="user_view.php?usr_idx=<?=$list[$i]['usr_idx']?>&page=<?=$params['page'] . $page_params?>" class="link"><?=$list[$i]['usr_name']?></a></td>
								<td><?=$list[$i]['birthdate']?></td>
								<td><?=$list[$i]['gender_name']?></td>
								<td><?=$list[$i]['usr_phone']?></td>
								<td><?=$list[$i]['usr_email']?></td>
								<td><?=$list[$i]['eyefree_flag']?></td>
								<td><?=$list[$i]['auth_flag']?></td>
								<td><?=iif($list[$i]['status']=='Y', '정상', '<strong class="fc_red">이용정지</strong>')?></td>
								<td><?=formatDates($list[$i]['visit_last_date'], "Y.m.d H:i")?></td>
								<td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
							</tr>
						<?}?>

						<?if (count($list) == 0) {?>
							<tr>
								<td colspan="13">등록된 데이터가 없습니다.</td>
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