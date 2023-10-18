<?include("../inc/config.php")?>
<?
	$params['idx']      = chkReqRpl("idx", null, "", "", "INT");
	$params['page']     = chkReqRpl("page", 1, "", "", "INT");
	$params['bbs_code'] = chkReqRpl("bbs_code", "", 10, "GET", "STR");
	$params['sch_cate'] = chkReqRpl("sch_cate", null, "", "", "INT");
	$params['sch_type'] = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word'] = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$page_params        = setPageParamsValue($params, "page,list_size,block_size");

	$cls_board = new CLS_BOARD;

	//상단 공지 사용여부
	$is_notice = $cls_board->isNotice($params['bbs_code']);

	//카테고리 사용여부
	$is_category = $cls_board->isCategory($params['bbs_code']);

	//에이터 사용여부
	$is_html = $cls_board->isHtml($params['bbs_code']);

	//게시판 목록이미지 사용 체크
	$is_list_thum = $cls_board->isListThumb($params['bbs_code']);

	//게시판 파일업로드 사용 체크
	$is_upfile = $cls_board->isUpfile($params['bbs_code']);

	//게시판 외부링크 사용 체크
	$is_link = $cls_board->isLink($params['bbs_code']);

	//게시판 댓글 사용 체크
	$is_comment = $cls_board->isComment($params['bbs_code']);

	//상세보기
	$view = $cls_board->view($params);
	if ($view == false) {
		$view['writer'] = $MEM_ADM['usr_name'];

		$view['mode'] = 'reg';
	} else {
		$view['mode'] = 'modify';

		if ($is_html) {
			$view['content'] = htmlDecode($view['content']);
		}
	}

	//카테고리 불러오기
	$category_list = $cls_board->category_list($params['bbs_code'], 1);


	$pageNum = $cls_set_menu->board_menu_code($params['bbs_code']);
	if ($pageNum == null) fnMsgGo(500, "잘못된 요청코드 정보 입니다.", "BACK", "");
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
				<div class="group">
					<form name="regFrm" id="regFrm" method="post" enctype="multipart/form-data">
					<input type="hidden" name="bbs_code" value="<?=$params['bbs_code']?>" />
					<input type="hidden" name="idx" value="<?=$params['idx']?>" />
					<input type="hidden" name="mode" value="<?=$view['mode']?>" />
					<input type="hidden" name="writer" id="writer" value="<?=$view['writer']?>" />
					<table class="g_table">
						<colgroup>
							<col width="12%">
							<col width="*">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_imp">노출상태<span></th>
								<td>
									<div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="open_flag" id="open_flag">
												<option value="Y" <?=chkCompare($view['open_flag'], 'Y', 'selected')?>>노출</option>
												<option value="N" <?=chkCompare($view['open_flag'], 'N', 'selected')?>>숨김</option>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<?if ($is_category) {?>
								<tr>
									<th><span class="t_imp">카테고리<span></th>
									<td>
										<div class="box">
											<div class="c_selectbox">
												<label for=""></label>
												<select name="category" id="category">
													<option value="">카테고리 전체</option>
													<?for ($i=0; $i<count($category_list); $i++) {?>
														<option value="<?=$category_list[$i]['category_idx']?>" <?=chkCompare($view['category'], $category_list[$i]['category_idx'], "selected")?>><?=$category_list[$i]['category_name']?></option>
													<?}?>
												</select>
											</div>
										</div>
									</td>
								</tr>
							<?}?>

							<?if ($is_notice) {?>
								<tr>
									<th><span class="t_imp">공지여부<span></th>
									<td>
										<div class="box">
											<div class="c_checkbox">
												<input type="checkbox" name="notice_flag" id="notice_flag" value="Y" <?=chkCompare($view['notice_flag'], 'Y', 'checked')?> />
												<label for="notice_flag">상단 상시 공지</label>
											</div>
										</div>
									</td>
								</tr>
							<?}?>

							<tr>
								<th><span class="t_imp">제목<span></th>
								<td>
									<div class="box">
										<div class="input_box" style="width:100%">
											<input type="text" name="title" id="title" value="<?=$view['title']?>" />
										</div>
									</div>
								</td>
							</tr>

							<tr>
								<th><span class="t_imp">내용<span></th>
								<td>
									<div class="box">
										<div class="textarea_box" style="width:100%">
											<textarea name="content" id="content" style="height:400px"><?=$view['content']?></textarea>
										</div>
									</div>
								</td>
							</tr>

							<?if ($is_list_thum) {?>
								<tr>
									<th><span class="t_h">썸네일 이미지<span></th>
									<td>
										<div class="box file">
											<div class="input_box" style="width:400px;">
												<input type="text" placeholder="이미지를 등록해주세요." readonly />
											</div>
											<input type="hidden" name="old_list_img" id="old_list_img" value="<?=$view['list_img']?>" />
											<input type="file" name="list_img" id="list_img" class="upload-hidden" upload-type="img" upload-size="5" />
											<label for="list_img" class="btn_30 gray">찾아보기</label>
											<?if (getUpfileName($view['list_img']) != '') {?>
												<p class="mt5">
													<a href="javascript:;" onclick="imgPreviwePopupOpen('/upload/board/thumb/<?=getUpfileName($view['list_img'])?>')">
														<img src="/upload/board/thumb/<?=getUpfileName($view['list_img'])?>" style="max-width:300px" />
													</a>

													<a href="javascript:;" onclick="fileDel('<?=$i?>', 'thumb')">[삭제]</a>
												</p>
											<?}?>
										</div>
									</td>
								</tr>
							<?}?>

							<?if ($is_upfile) {?>
								<?for ($i=1; $i<=2; $i++) {?>
									<tr>
										<th>
											<span class="t_h">첨부자료<?=$i?><span>
										</th>
										<td>
											<div class="box file">
												<div class="input_box" style="width:400px;">
													<input type="text" placeholder="파일을 등록해주세요." readonly />
												</div>
												<input type="hidden" name="old_up_file_<?=$i?>" id="old_up_file_<?=$i?>" value="<?=$view['up_file_'.$i]?>" />
												<input type="file" name="up_file_<?=$i?>" id="up_file_<?=$i?>" class="upload-hidden" upload-size="100">
												<label for="up_file_<?=$i?>" class="btn_30 gray">찾아보기</label>
												<?if (getUpfileName($view['up_file_'.$i]) != '') {?>
													<p class="mt5">
														<a href="__file_down.php?bbs_code=<?=$view['bbs_code']?>&idx=<?=$view['idx']?>&fnum=<?=$i?>"><?=getUpfileOriName($view['up_file_'.$i])?></a>
														<a href="javascript:;" onclick="fileDel('<?=$i?>', 'attach')">[삭제]</a>
													</p>
												<?}?>
											</div>
										</td>
									</tr>
								<?}?>
							<?}?>

							<?if ($is_link) {?>
								<?for ($i=1; $i<=2; $i++) {?>
									<tr>
										<th><span class="t_h">링크<?=$i?><span></th>
										<td>
											<div class="box">
												<div class="input_box" style="width:100%">
													<input type="text" name="link<?=$i?>" id="link<?=$i?>" value="<?=$view['link'.$i]?>" />
												</div>
											</div>
										</td>
									</tr>
								<?}?>
							<?}?>
						</tbody>
					</table>
					</form>
				</div>
				<div class="page_btn_a center">
					<a href="list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
					<a href="javascript:;" class="btn_40 white" onclick="regGo();"><?=iif($view['idx'] == '', '등록하기', '수정하기')?></a>
					<?if ($view['idx'] != '') {?>
						<a href="javascript:;" class="btn_40 gray" onclick="deleteGo();">삭제하기</a>
					<?}?>
				</div>

				<?if ($params['idx'] != '' && $is_comment) {?>
					<div class="mt50 mb20">
						<h3 class="g_title">댓글 관리</h3>
						<table class="g_table">
							<tbody>
								<tr>
									<td style="border-bottom:0">
										<div class="box">
											<div class="textarea_box">
												<textarea name="comment" id="comment"></textarea>
											</div>
											<div style="text-align:right; margin:5px 0">
												<a href="javascript:;" class="btn_30" onclick="commentRegGo()">댓글등록</a>
											</div>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						<div class="comment_list"></div>

						<script type="text/javascript">
							function commentRegGo() {
								var h = new clsJsHelper();

								if (!h.checkValNLen("comment", 1, 4000, "댓글 내용", "N", "KO")) return false;

								AJ.callAjax("__comment_proc.php", {
									"bbs_idx": "<?=$params['idx']?>",
									"comment": h.objVal("comment")
								}, function(data){
									if (data.result == 200) {
										alert("댓글이 등록 되었습니다.");
										location.reload();
									} else {
										alert(data.message);
									}
								});
							}

							function commentDelGo(idx) {
								if (!confirm("선택하신 내역을 삭제하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

								AJ.callAjax("__comment_del_proc.php", {
									"bbs_idx": "<?=$params['idx']?>",
									"idx": idx
								}, function(data){
									if (data.result == 200) {
										alert("댓글이 삭제 되었습니다.");
										location.reload();
									} else {
										alert(data.message);
									}
								});
							}

							function commentList(page) {
								AJ.callAjax("__comment_list.php", {"bbs_idx": "<?=$params['idx']?>", "page": page}, function(data){
									$(".comment_list").html(data);
								}, "html", "get");
							}

							$(function(){
								commentList(1);
							})
						</script>
					</div>
				<?}?>
			</div>
		</div>
	</div>
	<!-- //container -->

	<?if ($is_html) {?>
		<script type="text/javascript" src="/module/ckeditor/ckeditor.js"></script>
	<?}?>
	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			<?if ($is_html) {?>
			CKEDITOR.replace('content',{
				height:400
			});
			<?}?>
		})

		//게시글 폼체크
		function regGo() {
			<?if ($is_category) {?>
				if (!h.checkSelect("category", "카테고리")) return false;
			<?}?>

			if (!h.checkValNLen("title", 2, 100, "제목", "N", "KO")) return false;

			<?if ($is_html) {?>
				CKEDITOR.instances.content.updateElement();

				if (!h.checkVal("content", "내용", "N", "KO")) {
					CKEDITOR.instances.content.focus();

					return false;
				}
			<?} else {?>
				if (!h.checkVal("content", "내용", "N", "KO")) return false;
			<?}?>

			AJ.ajaxForm($("#regFrm"), "write_proc.php", function(data) {
				if (data.result == 200) {
					alert("처리 되었습니다.");

					<?if ($view['idx'] == '') {?>
						location.replace("list.php?page=<?=$page_params?>");
					<?} else {?>
						location.reload();
					<?}?>
				} else {
					alert(data.message);
				}
			});
		}

		//삭제 처리
		function deleteGo() {
			if (!confirm("선택한 게시글을 삭제 하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.callAjax("delete_proc.php", {"idx":"<?=$params['idx']?>"}, function(data){
				if (data.result == 200) {
					alert("처리 되었습니다.");
					location.replace("list.php?page=<?=$page_params?>");
				} else {
					alert(data.message);
				}
			});
		}

		//첨부파일 삭제
		function fileDel(fnum, gubun) {
			if (!confirm("첨부파일을 삭제하시겠습니까?\n삭제 후 파일은 복구가 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.callAjax("file_delete_proc.php", {"idx":"<?=$params['idx']?>", "fnum": fnum, "gubun": gubun}, function(data){
				if (data.result == 200) {
					alert("파일이 삭제되었습니다.");

					location.reload();
				} else {
					alert(data.message);
				}
			});
		}

		//카테고리 목록 불러오기
		function categoryList(depth, category) {
			var max_depth = 3;

			for (i=depth; i<=max_depth; i++) {
				$("#category"+i).html("<option value=''>"+ i +"차 카테고리</option>");
			}
			if (category != "") {
				AJ.callAjax("__category_list.php", {"depth":depth, "category": category}, function(data){
					if (data.result == 200) {
						$.each(data.list, function(i, item){
							$("#category"+depth).append("<option value='"+ item.category_idx +"'>"+ item.category_name +"</option>")
						})
					} else {
						alert(data.message);
					}
				},"json","get");
			}

			selectboxInit();
		}
	</script>
<?include("../inc/footer.php")?>