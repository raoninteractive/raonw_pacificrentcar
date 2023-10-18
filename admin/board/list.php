<?include("../inc/config.php")?>
<?
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['bbs_code']   = chkReqRpl("bbs_code", "", 10, "GET", "STR");
	$params['sch_cate']   = chkReqRpl("sch_cate", null, "", "", "INT");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

	$cls_board = new CLS_BOARD;

	//카테고리 사용여부
	$is_category = $cls_board->isCategory($params['bbs_code']);

	//게시판 목록이미지 사용 체크
	$is_list_thum = $cls_board->isListThumb($params['bbs_code']);

	//카테고리 불러오기
	$category_list = $cls_board->category_list($params['bbs_code'], 1);

	//게시판 댓글 사용 체크
	$is_comment = $cls_board->isComment($params['bbs_code']);


	//공지사항 목록 불러오기
	$notice_list = $cls_board->notice_list($params);

	//목록 불러오기
	$list = $cls_board->list($params, $total_cnt, $total_page);

	$pageNum = $cls_set_menu->board_menu_code($params['bbs_code']);
	if ($pageNum == null) fnMsgGo(500, "잘못된 요청코드 정보 입니다.", "BACK", "");
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);
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
								<input type="hidden" name="bbs_code" value="<?=$params['bbs_code']?>" />
								<div class="box">
									<?if ($is_category) {?>
										<div class="c_selectbox">
											<label for=""></label>
											<select name="sch_cate" id="sch_cate">
												<option value="">카테고리 전체</option>
												<?for ($i=0; $i<count($category_list); $i++) {?>
													<option value="<?=$category_list[$i]['category_idx']?>" <?=chkCompare($params['sch_cate'], $category_list[$i]['category_idx'], "selected")?>><?=$category_list[$i]['category_name']?></option>
												<?}?>
											</select>
										</div>
									<?}?>

									<div class="c_selectbox" style="width:100px">
										<label for=""></label>
										<select name="sch_type" id="sch_type">
											<option value="">전체</option>
											<option value="1" <?=chkCompare($params['sch_type'], 1, "selected")?>>제목</option>

											<?if ($params['bbs_code'] != "compare" && $params['bbs_code'] != "exercise" && $params['bbs_code'] != "youtube") {?>
												<option value="2" <?=chkCompare($params['sch_type'], 2, "selected")?>>내용</option>
											<?}?>
										</select>
									</div>
									<div class="input_box" style="width:20%">
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
						<dd><?=formatNumbers($total_cnt + count($notice_list))?></dd>
					</dl>
				</div>

				<?
					if ($is_list_thum) {
						include("list_img.php");
					} else {
						include("list_normal.php");
					}
				?>

				<nav class="page_nate">
					<? adminPaging($total_page, $params['block_size'], $params['page'], $page_params, "") ?>

					<a href="write.php?page=page=<?=$params['page'] . $page_params?>" class="btn_etc">글등록</a>
				</nav>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			$("#search_word").keyup(function(){
				enters(function(){ searchGo(); });
			})
		})

		//검색
		function searchGo() {
			if (h.objVal("sch_word")) {
				if (!h.checkValNLen("sch_word", 2, 20, "검색어", "N", "KO")) return false;
			}

			$("#searchFrm").submit();
		}

		//삭제 처리
		function deleteGo(idx) {
			if (!confirm("선택한 게시글을 삭제 하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.callAjax("delete_proc.php", {"idx":idx}, function(data){
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