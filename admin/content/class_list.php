<?include("../inc/config.php")?>
<?
	$pageNum = "0302";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']         = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']    = 15;
	$params['block_size']   = 10;
	$params['sch_sdate']    = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']    = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_mgubun1']  = chkReqRpl("sch_mgubun1", null, "", "", "INT");
	$params['sch_mgubun2']  = chkReqRpl("sch_mgubun2", null, "", "", "INT");
	$params['sch_mgubun3']  = chkReqRpl("sch_mgubun3", null, "", "", "INT");
	$params['sch_mgubun4']  = chkReqRpl("sch_mgubun4", null, "", "", "INT");
	$params['sch_mgubun5']  = chkReqRpl("sch_mgubun5", null, "", "", "INT");
	$params['sch_mgubun6']  = chkReqRpl("sch_mgubun6", null, "", "", "INT");
	$params['sch_apply']    = chkReqRpl("sch_apply", "", 1, "", "STR");
	$params['sch_status']   = chkReqRpl("sch_status", "", 1, "", "STR");
	$params['sch_type']     = chkReqRpl("sch_type", null, "", "", "INT");
	$params['sch_word']     = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

	$cls_content = new CLS_CONTENT;

	//강좌 목록 불러오기
	$list = $cls_content->class_list_admin($params, $total_cnt, $total_page);

?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="search_box">
				<table>
					<colgroup>
						<col width="12%">
						<col width="*">
					</colgroup>
					<tbody>
						<tr>
							<th>검색설정</th>
							<td class="com">
								<form name="searchFrm" id="searchFrm">
								<div class="box">
									<div class="input_box date">
										<input type="text" name="sch_sdate" id="sch_sdate" value="<?=$params['sch_sdate']?>" readonly placeholder="등록일" />
									</div><span></span>
									<p class="dash">~</p>
									<div class="input_box date">
										<input type="text" name="sch_edate" id="sch_edate" value="<?=$params['sch_edate']?>" readonly placeholder="등록일" />
									</div><span></span>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_apply" id="sch_apply">
											<option value="">수강신청 전체</option>
											<option value="Y" <?=chkCompare($params['sch_apply'], 'Y', 'selected')?>>사용</option>
											<option value="N" <?=chkCompare($params['sch_apply'], 'N', 'selected')?>>미사용</option>
										</select>
									</div>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_status" id="sch_status">
											<option value="">수강상태 전체</option>
											<option value="Y" <?=chkCompare($params['sch_status'], 'Y', 'selected')?>>가능</option>
											<option value="N" <?=chkCompare($params['sch_status'], 'N', 'selected')?>>불가능</option>
										</select>
									</div>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_type" id="sch_type">
											<option value="">전체 검색</option>
											<option value="1" <?=chkCompare($params['sch_type'], 1, 'selected')?>>강좌명</option>
											<option value="2" <?=chkCompare($params['sch_type'], 2, 'selected')?>>강사명</option>
											<option value="3" <?=chkCompare($params['sch_type'], 3, 'selected')?>>강좌내용</option>
										</select>
									</div>
									<div class="input_box" style="width:200px">
										<input type="text" name="sch_word" id="sch_word" value="<?=$params['sch_word']?>" maxlength="20" placeholder="검색어를 입력해주세요." />
									</div>
									<a href="javascript:;" class="btn_search" onclick="searchGo()">검색</a>
								</div>
								<div class="box mt5">
									<p class="normal mr5">회원구분</p>
									<div class="c_checkbox">
										<input type="checkbox" name="sch_mgubun1" id="sch_mgubun1" value="00" <?=chkCompare($params['sch_mgubun1'], '00', 'checked')?> />
										<label for="sch_mgubun1">비회원</label>
									</div>
									<?
										$i=2;
										foreach($CONST_MEMBER_GUBUN as $item) {
									?>
											<div class="c_checkbox">
												<input type="checkbox" name="sch_mgubun<?=$i?>" id="sch_mgubun<?=$i?>" value="<?=$item[0]?>" <?=chkCompare($params['sch_mgubun'.$i], $item[0], 'checked')?> />
												<label for="sch_mgubun<?=$i?>"><?=$item[1]?></label>
											</div>
									<?
											$i++;
										}
									?>
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
						<col width="*" />
						<col width="70" />
						<col width="70" />
						<col width="180" />
						<col width="120" />
						<col width="70" />
						<col width="70" />
						<col width="120" />
						<col width="70" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>순번</th>
							<th>강좌명</th>
							<th>수강기간</th>
							<th>수강신청<br>제한여부</th>
							<th>이용 회원등급</th>
							<th>강사명</th>
							<th>등록<br>강의수</th>
							<th>강좌상태</th>
							<th>등록일시</th>
							<th>관리</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
								<td><?=$list[$i]['sort']?></td>
								<td class="left">
									<p class="mb5"><?=$cls_content->parent_category_path($list[$i]['category'])?></p>
									<a href="class_write.php?page=<?=$params['page'] . $page_params?>&idx=<?=$list[$i]['idx']?>" class="a_link"><?=$list[$i]['title']?></a>
								</td>
								<td>
									<?
										if ($list[$i]['period_flag'] == 'Y') {
											echo $list[$i]['period'] ."일";
										} else {
											echo "없음";
										}
									?>
								</td>
								<td><?=iif($list[$i]['limit_flag']=='Y', '신청제한', '제한없음')?></td>
								<td><?=$cls_content->allow_auth_name($list[$i]['allow_auth'])?></td>
								<td>
									<?=$list[$i]['inst_name']?>
									<?if ($list[$i]['inst_id'] != '') {?>
										<br>(<?=$list[$i]['inst_id']?>)
									<?}?>
								</td>
								<td><?=formatNumbers($list[$i]['lecture_cnt'])?></td>
								<td><?=iif($list[$i]['class_status']=='Y', '수강가능', '종료')?></td>
								<td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
								<td>
									<a href="javascript:;" class="btn_26 red" onclick="deleteGo(<?=$list[$i]['idx']?>);">삭제</a>
								</td>
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

					<a href="class_write.php?page=<?=$params['page'] . $page_params?>" class="btn_etc">강좌등록</a>
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

		//삭제
		function deleteGo(idx) {
			if (!confirm("선택한 강좌를 삭제 하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.callAjax("class_delete_proc.php", {"idx": idx}, function(data){
				if (data.result == 200) {
					alert("처리 되었습니다.");

					location.reload();
				} else {
					alert(data.message);
				}
			});
		}
	</script>
<?include("../inc/footer.php")?>