<?include("../inc/config.php")?>
<?
	$pageNum = "0305";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['idx']      = chkReqRpl("idx", null, "", "", "INT");
	$params['page']     = chkReqRpl("page", 1, "", "", "INT");
	$params['sch_type'] = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word'] = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$page_params        = setPageParamsValue($params, "page,list_size,block_size");

	$cls_board = new CLS_BOARD;

	//상세보기
	$view = $cls_board->inquiry_view($params['idx']);
	if ($view == false) fnMsgGo(500, "일치하는 게시글 정보가 없습니다.", "BACK", "");
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
				<div class="group">
					<form name="saveFrm" id="saveFrm" method="post" enctype="multipart/form-data">
					<input type="hidden" name="idx" value="<?=$params['idx']?>" />
					<table class="g_table">
						<colgroup>
							<col width="12%">
							<col width="*">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_h">이름<span></th>
								<td><?=$view['name']?></td>
							</tr>
							<tr>
								<th><span class="t_h">휴대폰번호<span></th>
								<td><?=$view['phone']?></td>
                            </tr>
							<tr>
								<th><span class="t_h">이메일<span></th>
								<td><?=$view['email']?></td>
                            </tr>
							<tr>
								<th><span class="t_h">문의내용<span></th>
								<td><?=textareaDecode($view['content'])?></td>
                            </tr>
							<tr>
								<th><span class="t_h">답변<span></th>
								<td>
									<div class="box">
										<div class="textarea_box" style="width:100%">
											<textarea name="answer_content" id="answer_content" style="height:100px"><?=$view['answer_content']?></textarea>
										</div>
									</div>

									<div class="box mt5">
										<div class="c_checkbox">
											<input type="checkbox" name="answer_email_send" id="answer_email_send" value="Y" />
											<label for="answer_email_send">답변 후 안내 이메일발송</label>
										</div>
										<div class="input_box">
											<input type="text" name="answer_email" id="answer_email" value="<?=$view['email']?>" />
										</div>
										<p class="normal fc_red">※ 메일 수신이 안될시 수기로 변경 후 발송가능합니다.</p>
									</div>
								</td>
                            </tr>
                            <?if ($view['answer_date'] != '') {?>
                                <tr>
                                    <th><span class="t_h">답변일<span></th>
                                    <td><?=formatDates($view['answer_date'], "Y.m.d H:i:s")?></td>
                                </tr>
                            <?}?>
                            <tr>
                                <th><span class="t_h">등록일<span></th>
                                <td><?=formatDates($view['reg_date'], "Y.m.d H:i:s")?></td>
                            </tr>
						</tbody>
					</table>
					</form>
				</div>
				<div class="page_btn_a center">
                    <a href="inquiry_list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
                    <a href="javascript:;" class="btn_40 white" onclick="answerGo();">답변저장</a>
					<a href="javascript:;" class="btn_40 gray" onclick="deleteGo();">삭제하기</a>
				</div>

				<?
					$admin_memo_section = "board_inquiry";
					$admin_memo_gubun = $params['idx'];
				?>
				<?include("../common/admin_memo_log_include.php")?>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
		})

		//답변처리
		function answerGo() {
            if (!h.checkValNLen("answer_content", 2, 4000, "답변", "N", "KO")) return false;

            if (!confirm("답변을 등록하시겠습니까?\n답변은 등록 후 수정이 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.ajaxForm($("#saveFrm"), "inquiry_answer_proc.php", function(data) {
				if (data.result == 200) {
					alert("답변 처리 되었습니다.");

                    location.reload();
				} else {
					alert(data.message);
				}
			});
		}

		//삭제 처리
		function deleteGo() {
			if (!confirm("선택한 게시글을 삭제 하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.callAjax("inquiry_delete_proc.php", {"idx":"<?=$params['idx']?>"}, function(data){
				if (data.result == 200) {
					alert("처리 되었습니다.");
					location.replace("inquiry_list.php?page=<?=$page_params?>");
				} else {
					alert(data.message);
				}
			});
		}
	</script>
<?include("../inc/footer.php")?>