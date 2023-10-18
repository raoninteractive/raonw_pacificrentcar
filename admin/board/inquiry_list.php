<?include("../inc/config.php")?>
<?
	$pageNum = "0305";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

	$cls_board = new CLS_BOARD;

	//목록 불러오기
	$list = $cls_board->inquiry_list($params, $total_cnt, $total_page);
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
									<div class="c_selectbox" style="width:100px">
										<label for=""></label>
										<select name="sch_type" id="sch_type">
											<option value="">전체</option>
                                            <option value="1" <?=chkCompare($params['sch_type'], 1, "selected")?>>이름</option>
                                            <option value="2" <?=chkCompare($params['sch_type'], 2, "selected")?>>핸드폰번호</option>
                                            <option value="3" <?=chkCompare($params['sch_type'], 3, "selected")?>>이메일</option>
                                            <option value="4" <?=chkCompare($params['sch_type'], 4, "selected")?>>내용</option>
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

                <table>
                    <colgroup>
                        <col width="70" />
                        <col width="*" />
                        <col width="120" />
                        <col width="250" />
                        <col width="80" />
                        <col width="120" />
                        <col width="120" />
                        <col width="80" />
                    </colgroup>
                    <thead>
                        <tr>
                            <th>번호</th>
                            <th>이름</th>
                            <th>휴대폰번호</th>
                            <th>이메일</th>
                            <th>확인여부</th>
                            <th>답변일</th>
                            <th>등록일</th>
                            <th>관리</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?for ($i=0; $i<count($list);$i++) {?>
                            <tr>
                                <td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
                                <td>
                                    <a href="inquiry_view.php?page=<?=$params['page'] . $page_params?>&idx=<?=$list[$i]['idx']?>" class="a_link"><?=$list[$i]['name']?></a>
                                </td>
                                <td><?=$list[$i]['phone']?></td>
                                <td><?=$list[$i]['email']?></td>
                                <td><?=$list[$i]['view_flag']?></td>
                                <td><?=formatDates($list[$i]['answer_date'], "Y.m.d H:i")?></td>
                                <td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
                                <td>
                                    <a href="javascript:;" class="btn_26 red" onclick="deleteGo(<?=$list[$i]['idx']?>);">삭제</a>
                                </td>
                            </tr>
                        <?}?>

                        <?if (count($list) == 0) {?>
                            <tr>
                                <td colspan="8">등록된 데이터가 없습니다.</td>
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

			AJ.callAjax("inquiry_delete_proc.php", {"idx":idx}, function(data){
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