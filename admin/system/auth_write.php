<?include("../inc/config.php")?>
<?
	$pageNum = "9902";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$gubun    = chkReqRpl("gubun", null, "", "", "INT");

	//상세정보 불러오기
	$view = $cls_set_menu->admin_auth_view($gubun);
	if ($view == false) fnMsgGo(500, "일치하는 데이터가 없습니다.", "BACK", "");
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
				<div class="group">
					<form name="regFrm" id="regFrm" method="post">
					<input type="hidden" name="gubun" value="<?=$gubun?>" />
					<h3 class="g_title">기본정보</h3>
					<table class="g_table">
						<colgroup>
							<col width="150">
							<col width="*">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_imp">권한등급명<span></th>
								<td>
									<div class="box">
										<div class="input_box">
											<input type="text" name="title" id="title" value="<?=$view['title']?>" />
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">권한설명<span></th>
								<td>
									<div class="box">
										<div class="input_box" style="width:100%">
											<input type="text" name="description" id="description" value="<?=$view['description']?>" />
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">상태</span></th>
								<td>
									<div class="box">
										<?if (strpos("90,99", $view['gubun']) !== false) {?>
											사용
											<span class="normal fc_red" style="margin-left:10px">※기본생성된 권한등급은 상태값을 변경 할 수 없습니다.</span>
											<input type="hidden" name="status" id="status" value="Y" old-status="<?=$view['status']?>" />
										<?} else {?>
											<div class="c_selectbox">
												<label for=""></label>
												<select name="status" id="status" old-status="<?=$view['status']?>">
													<option value="Y" <?=chkCompare($view['status'],'Y','selected')?>>사용</option>
													<option value="N" <?=chkCompare($view['status'],'N','selected')?>>사용중지</option>
												</select>
											</div>
										<?}?>
									</div>
								</td>
							</tr>
						</tbody>
					</table>

					<h3 class="g_title">권한설정</h3>
					<?
						$menu_list = $cls_set_menu->menu_list("", "Y");

						for ($i=0; $i<count($menu_list); $i++) {
							$sub_list = $cls_set_menu->menu_list($menu_list[$i]['code'], "Y");
					?>
							<table class="g_table <?if ($i >0){?>mt5<?}?>">
								<colgroup>
									<col width="150">
								</colgroup>
								<tbody>
									<tr class="center">
										<th rowspan="2"><?=$menu_list[$i]['code_name']?></th>
										<?for ($j=0; $j<count($sub_list); $j++) {?>
											<th><?=$sub_list[$j]['code_name']?></th>
										<?}?>
										<?for ($k=$j; $k<$cls_set_menu->max_menu_count(); $k++) {?>
											<th></th>
										<?}?>
									</tr>
									<tr class="center">
										<?for ($j=0; $j<count($sub_list); $j++) {?>
											<td>
												<div class="box">
													<div class="c_checkbox none">
														<input type="checkbox" name="menu_access_auth[]" id="menu_<?=$sub_list[$j]['code']?>" value="<?=$sub_list[$j]['code']?>" <?=chkCompare($view['menu_auth'], $sub_list[$j]['code'], 'checked')?>/>
														<label for="menu_<?=$sub_list[$j]['code']?>"></label>
													</div>
												</div>
											</td>
										<?}?>
										<?for ($k=$j; $k<$cls_set_menu->max_menu_count(); $k++) {?>
											<td></td>
										<?}?>
									</tr>
								</tbody>
							</table>
					<?
						}
					?>
					</form>
				</div>
				<div class="page_btn_a center">
					<a href="auth_list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
					<a href="javascript:;" class="btn_40 white" onclick="regGo();">수정하기</a>
				</div>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		//운영자 폼체크
		function regGo() {
			if (!h.checkValNLen("title", 2, 20, "권한등급명", "Y", "KO")) return false;
			if (!h.checkValNLen("description", 1, 200, "권한설명", "N", "KO")) return false;
			if ($(":checkbox[name='menu_access_auth[]']:checked").size() == 0) {
				alert("권한은 최소 1개이상 선택해야 합니다.\n권한을 선택해주세요.");
				return false;
			}

			<?if ($view['gubun'] == '99') {?>
			if ($(":checkbox[name='menu_access_auth[]']").size() != $(":checkbox[name='menu_access_auth[]']:checked").size()) {
				alert("기본생성된 마스터 권한등급은 권한설정 값을 변경 할 수 없습니다.");
				$(":checkbox[name='menu_access_auth[]']").prop("checked", true);
				return false;
			}
			<?}?>

			if ($("#status").attr("old-status") != $("#status").val()) {
				if (!confirm("상태값을 변경하시겠습니까?\n이미 관리자계정에 할당된 권한등급이 있으면 관리자계정도 동일하게 상태값이 적용됩니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;
			}

			AJ.ajaxForm($("#regFrm"), "auth_write_proc.php", function(data) {
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