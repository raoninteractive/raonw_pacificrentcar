<?include("../inc/config.php")?>
<?
	$pageNum = "0390";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_gubun']  = chkReqRpl("sch_gubun", "", 10, "", "STR");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 50, "", "STR");
    $page_params          = setPageParamsValue($params, "page,list_size,block_size");

    $cls_board = new CLS_BOARD;

	//댓글 전체 목록 불러오기
	$list = $cls_board->comment_list_all($params, $total_cnt, $total_page);
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
										<input type="text" name="sch_sdate" id="sch_sdate" value="<?=$params['sch_sdate']?>" readonly placeholder="등록일" />
									</div><span></span>
									<p class="dash">~</p>
									<div class="input_box date">
										<input type="text" name="sch_edate" id="sch_edate" value="<?=$params['sch_edate']?>" readonly placeholder="등록일" />
									</div><span></span>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_gubun" id="sch_gubun">
											<option value="">구분 전체</option>
											<option value="notice" <?=chkCompare($params['sch_gubun'], 'notice', 'selected')?>>공지사항</option>
											<option value="it" <?=chkCompare($params['sch_gubun'], 'it', 'selected')?>>IT뉴스 및 정보</option>
											<option value="data" <?=chkCompare($params['sch_gubun'], 'data', 'selected')?>>자료실</option>
											<option value="qna" <?=chkCompare($params['sch_gubun'], 'qna', 'selected')?>>Q&A 게시판</option>
											<option value="content" <?=chkCompare($params['sch_gubun'], 'content', 'selected')?>>콘텐츠 강의</option>
										</select>
									</div>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="sch_type" id="sch_type">
											<option value="">전체 검색</option>
											<option value="1" <?=chkCompare($params['sch_type'], 1, 'selected')?>>아이디</option>
											<option value="2" <?=chkCompare($params['sch_type'], 2, 'selected')?>>작성자</option>
											<option value="3" <?=chkCompare($params['sch_type'], 3, 'selected')?>>댓글내용</option>
										</select>
									</div>
									<div class="input_box" style="width:200px">
										<input type="text" name="sch_word" id="sch_word" value="<?=$params['sch_word']?>" maxlength="20" placeholder="검색어를 입력해주세요." />
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
						<col width="150" />
						<col width="*" />
                        <col width="120" />
                        <col width="120" />
                        <col width="120" />
                        <col width="80" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>구분</th>
							<th>댓글내용</th>
							<th>아이디</th>
							<th>작성자</th>
							<th>등록일</th>
							<th>관리</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
								<td><?=$list[$i]['gubun_name']?></td>
								<td class="left"><?=textareaDecode($list[$i]['comment'])?></td>
								<td><?=$list[$i]['reg_id']?></td>
								<td><?=$list[$i]['reg_name']?></td>
                                <td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
                                <td>
                                    <a href="javascript:;" class="btn_26 red" onclick="deleteGo('<?=$list[$i]['gubun']?>', '<?=$list[$i]['idx']?>');">삭제</a>
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

		//삭제 처리
		function deleteGo(gubun, idx) {
			if (!confirm("선택한 댓글을 삭제 하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.callAjax("comment_delete_proc.php", {"gubun": gubun, "idx":idx}, function(data){
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