<?include("../inc/config.php")?>
<?
	$pageNum = "9901";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$page_params = "&sch_type=". $params['sch_type'] ."&sch_word=". $params['sch_word'];

	//운영자 목록 불러오기
	$list = $cls_member->admin_list($params, $total_cnt, $total_page);
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
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_type" id="sch_type">
											<option value="">전체검색</option>
											<option value="1" <?=chkCompare($params['sch_type'], 1, "selected")?>>아이디</option>
											<option value="2" <?=chkCompare($params['sch_type'], 2, "selected")?>>이름</option>
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
						<col width="70" />
						<col width="200" />
						<col width="120" />
						<col width="*" />
						<col width="120" />
						<col width="200" />
						<col width="90" />
						<col width="150" />
						<col width="150" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>등급</th>
							<th>담당자명</th>
							<th>아이디</th>
							<th>연락처</th>
							<th>이메일</th>
							<th>상태</th>
							<th>최종방문일</th>
							<th>최종수정일</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
								<td><?=$list[$i]['usr_gubun_name']?></td>
								<td><a href="admin_write.php?usr_idx=<?=$list[$i]['usr_idx']?>&page=<?=$params['page'] . $page_params?>" class="link"><?=$list[$i]['usr_name']?></a></td>
								<td><?=$list[$i]['usr_id']?></td>
								<td><?=$list[$i]['usr_phone']?></td>
								<td><?=$list[$i]['usr_email']?></td>
								<td><?=$list[$i]['status_name']?></td>
								<td><?=formatDates($list[$i]['visit_last_date'], "Y.m.d H:i")?></td>
								<td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
							</tr>
						<?}?>

						<?if (count($list) == 0) {?>
							<tr>
								<td colspan="9">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>

				<nav class="page_nate">
					<? adminPaging($total_page, $params['block_size'], $params['page'], $page_params, "") ?>

					<a href="admin_write.php?page=<?=$params['page'] . $page_params?>" class="btn_etc">운영자 추가</a>
				</nav>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		//검색
		function searchGo() {
			if (h.objVal("sch_word")) {
				if (!h.checkValNLen("sch_word", 2, 20, "검색어", "N", "KO")) return false;
			}

			$("#searchFrm").submit();
		}
	</script>
<?include("../inc/footer.php")?>