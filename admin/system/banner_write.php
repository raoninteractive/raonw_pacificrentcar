<?include("../inc/config.php")?>
<?
	$pageNum = "9902";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['idx']        = chkReqRpl("idx", null, "", "", "INT");
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "GET", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "GET", "STR");
	$params['sch_status'] = chkReqRpl("sch_status", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$page_params = "&sch_sdate=". $params['sch_sdate'] ."&sch_edate=". $params['sch_edate'] ."&sch_status=". $params['sch_status'] ."&sch_word=". $params['sch_word'];

	$cls_banner = new CLS_SETTING_BANNER;

	$view = $cls_banner->banner_view($params['idx']);
	if ($view == false) {
		$view['sdate'] = date("Y-m-d");
		$view['edate'] = dateAdd('year', 1, $view['sdate']);
	}
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
				<div class="group">
					<form name="regFrm" id="regFrm" method="post" enctype="multipart/form-data">
					<input type="hidden" name="idx" value="<?=$params['idx']?>" />
					<table class="g_table">
						<colgroup>
							<col width="12%">
							<col width="*">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_imp">배너위치<span></th>
								<td>
									<div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="section" id="section">
												<?for ($i=1; $i<count($CONST_BANNER_AREA); $i++) {?>
													<option value="<?=$i?>" <?=chkCompare($view['section'], $i, 'selected')?>><?=$CONST_BANNER_AREA[$i]?></option>
												<?}?>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">상태<span></th>
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
							<tr>
								<th><span class="t_imp">제목<span></th>
								<td>
									<div class="box">
										<div class="input_box" style="width:286px">
											<input type="text" name="title" id="title" value="<?=$view['title']?>" />
										</div>
									</div>
								</td>
							</tr>
							<!-- <tr>
								<th><span class="t_imp">기간<span></th>
								<td>
									<div class="box">
										<div class="input_box" style="width:130px;margin-right:0">
											<input type="text" name="sdate" id="sdate" value="<?=$view['sdate']?>" readonly />
										</div>
										<p class="normal" style="margin:0 8px;">~</p>
										<div class="input_box" style="width:130px;margin-right:12px">
											<input type="text" name="edate" id="edate" value="<?=$view['edate']?>" readonly />
										</div>
										<p class="normal fc_blue">※기간이 지난 배너은 자동으로 상태가 숨김처리 됩니다.</p>
									</div>
								</td>
							</tr> -->
							<tr>
								<th><span class="t_imp">PC 이미지<span></th>
								<td>
									<div class="box file">
										<div class="input_box" style="width:518px;">
											<input type="text" placeholder="이미지를 등록해주세요." readonly />
										</div>
										<input type="file" name="up_file_1" id="up_file_1" class="upload-hidden" upload-type="img" upload-size="2" />
										<label for="up_file_1" class="btn_30 gray">찾아보기</label>
										<?if ($view['up_file_1'] != '') {?>
											<p class="mt5">
												<a href="javascript:;" onclick="imgPreviwePopupOpen('/upload/banner/<?=$view['up_file_1']?>')">
													<img src="/upload/banner/<?=$view['up_file_1']?>" style="max-width:300px" />
												</a>
											</p>
										<?}?>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_h">PC 링크<span></th>
								<td>
									<div class="box">
										<div class="c_selectbox" style="margin-right:5px">
											<label for=""></label>
											<select name="target_pc" id="target_pc">
												<option value="_blank" <?=chkCompare($view['target_pc'], '_blank', 'selected')?>>새창</option>
												<option value="_self" <?=chkCompare($view['target_pc'], '_self', 'selected')?>>현재창</option>
											</select>
										</div>
										<div class="input_box" style="width:497px">
											<input type="text" name="link_pc" id="link_pc" value="<?=$view['link_pc']?>" placeholder="링크가 없을경우 빈칸으로 남겨주세요." />
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_h">MOBILE 이미지<span></th>
								<td>
									<div class="box file">
										<div class="input_box" style="width:518px;">
											<input type="text"placeholder="MOBILE 이미지 미등록시 PC 이미지가 우선순위 됩니다." readonly />
										</div>
										<input type="file" name="up_file_2" id="up_file_2" class="upload-hidden" upload-type="img" upload-size="2" />
										<label for="up_file_2" class="btn_30 gray">찾아보기</label>
										<?if ($view['up_file_2'] != '') {?>
											<p class="mt5">
												<a href="javascript:;" onclick="imgPreviwePopupOpen('/upload/banner/<?=$view['up_file_2']?>')">
													<img src="/upload/banner/<?=$view['up_file_2']?>" style="max-width:300px" />
												</a>

												<a href="javascript:;" class="btn_delete" onclick="popupFileDel(<?=$view['idx']?>, 2)"><img src="../images/btn_to_delete.gif" alt="삭제"></a>
											</p>
										<?}?>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_h">MOBILE 링크<span></th>
								<td>
									<div class="box">
										<div class="c_selectbox" style="margin-right:5px">
											<label for=""></label>
											<select name="target_mobile" id="target_mobile">
												<option value="_blank" <?=chkCompare($view['target_mobile'], '_blank', 'selected')?>>새창</option>
												<option value="_self" <?=chkCompare($view['target_mobile'], '_self', 'selected')?>>현재창</option>
											</select>
										</div>
										<div class="input_box" style="width:497px">
											<input type="text" name="link_mobile" id="link_mobile" value="<?=$view['link_mobile']?>" placeholder="MOBILE링크 미등록시 PC링크가 우선순위 됩니다." />
										</div>
									</div>
								</td>
							</tr>
						</tbody>
					</table>
					</form>
				</div>
				<div class="page_btn_a center">
					<a href="banner_list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
					<a href="javascript:;" class="btn_40 white" onclick="regGo();"><?=iif($view['idx'] == '', '등록하기', '수정하기')?></a>
					<?if ($view['idx'] != '') {?>
						<a href="javascript:;" class="btn_40 gray" onclick="deleteGo();">삭제하기</a>
					<?}?>
				</div>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			//배너기간
			$("#sdate").datepicker({
				maxDate: "<?=$params['edate']?>",
				onClose: function(selectedDate) {
					$("#edate").datepicker("option", "minDate", selectedDate);
				}
			});
			$("#edate").datepicker({
				minDate: "<?=$params['sdate']?>",
				onClose: function(selectedDate) {
					$("#sdate").datepicker("option", "maxDate", selectedDate);
				}
			});
		})

		//배너등록 폼체크
		function regGo() {
			if (!h.checkValNLen("title", 2, 100, "배너 제목", "N", "KO")) return false;
			<?if ($view['idx'] == '') {?>
				if (!h.checkSelect("up_file_1", "PC 이미지")) return false;
			<?}?>

			if (h.objVal("link_pc")) {
				if (!h.checkValNLen("link_pc", 10, 255, "PC 링크", "Y", "KO")) return false;
			}
			if (h.objVal("link_mobile")) {
				if (!h.checkValNLen("link_mobile", 10, 255, "MOBILE 링크", "Y", "KO")) return false;
			}

			AJ.ajaxForm($("#regFrm"), "banner_write_proc.php", function(data) {
				if (data.result == 200) {
					alert("처리 되었습니다.");

					<?if ($view['idx'] == '') {?>
						location.replace("banner_list.php?page=1<?=$page_params?>");
					<?} else {?>
						location.reload();
					<?}?>
				} else {
					alert(data.message);
				}
			});
		}

		//배너 첨부파일 삭제
		function popupFileDel(idx, fnum) {
			if (!confirm("첨부파일을 삭제하시겠습니까?\n삭제 후 파일은 복구가 불가능합니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

			AJ.callAjax("banner_file_delete_proc.php", {"idx": idx, "fnum": fnum}, function(data){
				if (data.result == 200) {
					alert("파일이 삭제되었습니다.");

					location.reload();
				} else {
					alert(data.message);
				}
			});
		}

		//배너 삭제
		function deleteGo() {
			if (!confirm("배너을 삭제하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

			AJ.callAjax("banner_delete_proc.php", {"idx": "<?=$params['idx']?>"}, function(data){
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