<?include("../inc/config.php")?>
<?
	$pageNum = "9902";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "GET", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "GET", "STR");
	$params['sch_open']   = chkReqRpl("sch_open", "", 1, "GET", "STR");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$page_params = "&sch_sdate=". $params['sch_sdate'] ."&sch_edate=". $params['sch_edate'] ."&sch_open=". $params['sch_open'] ."&sch_word=". $params['sch_word'];

	$cls_banner = new CLS_SETTING_BANNER;

	//배너 목록 불러오기
	$list = $cls_banner->banner_admin_list($params, $total_cnt, $total_page);
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
								<form name="searchFrm" id="searchFrm" method="get">
								<div class="box">
									<!--<div class="input_box date">
										<input type="text" name="sch_sdate" id="sch_sdate" value="<?=$params['sch_sdate']?>" readonly placeholder="이벤트기간" />
									</div><span></span>
									<p class="dash">~</p>
									<div class="input_box date">
										<input type="text" name="sch_edate" id="sch_edate" value="<?=$params['sch_edate']?>" readonly placeholder="이벤트기간" />
									</div><span></span>-->
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_open" id="sch_open">
											<option value="">노출상태</option>
											<option value="Y" <?=chkCompare($params['sch_open'], 'Y', "selected")?>>노출</option>
											<option value="N" <?=chkCompare($params['sch_open'], 'N', "selected")?>>숨김</option>
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
						<col width="120" />
						<col width="70" />
						<col width="200" />
						<col width="*" />
						<col width="120" />
						<col width="140" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>배너위치</th>
							<th>노출상태</th>
							<th>이미지(PC)</th>
							<th>제목</th>
							<th>등록일</th>
							<th>관리</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
								<td><?=$CONST_BANNER_AREA[$list[$i]['section']]?></td>
								<td><?=$list[$i]['open_flag_name']?></td>
								<td><p class="img"><img src="/upload/banner/<?=$list[$i]['up_file_1']?>" style="max-width:100%" /></p></td>
								<td class="left"><?=$list[$i]['title']?></td>
								<td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
								<td>
									<div class="btn">
										<a href="banner_write.php?page=<?=$params['page'] . $page_params?>&idx=<?=$list[$i]['idx']?>" class="btn_26 white">수정</a>
										<a href="javascript:;" class="btn_26 gray" onclick="deleteGo('<?=$list[$i]['idx']?>')">삭제</a>
									</div>
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

					<a href="banner_write.php?page=<?=$params['page'] . $page_params?>" class="btn_etc">배너 추가</a>
				</nav>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			//$("#sch_sdate, #sch_edate").datepicker();
		})

		//검색
		function searchGo() {
			if (h.objVal("sch_word")) {
				if (!h.checkValNLen("sch_word", 2, 20, "검색어", "N", "KO")) return false;
			}

			$("#searchFrm").submit();
		}

		//배너 삭제
		function deleteGo(idx) {
			if (!confirm("배너을 삭제하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

			AJ.callAjax("banner_delete_proc.php", {"idx": idx}, function(data){
				if (data.result == 200) {
					alert("처리 되었습니다.");

					location.replace("banner_list.php");
				} else {
					alert(data.message);
				}
			});
		}
	</script>
<?include("../inc/footer.php")?>