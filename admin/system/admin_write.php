<?include("../inc/config.php")?>
<?
	$pageNum = "9901";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['usr_idx']    = chkReqRpl("usr_idx", null, "", "", "INT");
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 20;
	$params['block_size'] = 10;
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$page_params = "&sch_type=". $params['sch_type'] ."&sch_word=". $params['sch_word'];

	//상세정보 불러오기
	$view = $cls_member->admin_view($params['usr_idx']);

	//권한등급 목록
	$auth_list = $cls_set_menu->admin_auth_list();
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
				<div class="group">
					<form name="regFrm" id="regFrm" method="post">
					<input type="hidden" name="usr_idx" value="<?=$params['usr_idx']?>" />
					<h3 class="g_title">기본정보</h3>
					<table class="g_table">
						<colgroup>
							<col width="12%">
							<col width="*">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_imp">아이디</span></th>
								<td>
									<div class="box">
										<div class="input_box" style="width:20%">
											<input type="text" name="usr_id" id="usr_id" value="<?=$view['usr_id']?>" />
										</div>
										<p class="normal fc_blue" style="margin-left:10px">4~20자의 영문 소문자, 숫자와 특수기호(_),(-)만 사용 가능합니다.</p>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_h"></span>비밀번호</th>
								<td>
									<div class="box">
										<?if ($view == false) {?>
											<p class="normal fc_red">※ 비밀번호는 처음 등록시 <strong>＂<?=CONST_RESET_PWD?> + 휴대폰번호 뒤 4자리＂</strong>로 자동 생성됩니다.</p>
										<?} else {?>
											<a href="javascript:;" class="btn_30" onclick="passwordReset()">비밀번호 초기화</a>
											<p class="normal fc_blue" style="margin-left:10px">마지막 비밀번호 변경일은 <strong>"<?=formatDates($view['pwd_last_date'],"Y.m.d H:i:s")?>"</strong> 입니다.</p>
										<?}?>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">이름<span></th>
								<td>
									<div class="box">
										<div class="input_box" style="width:20%">
											<input type="text" name="usr_name" id="usr_name" value="<?=$view['usr_name']?>" maxlength="10" />
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">이메일</span></th>
								<td>
									<div class="box">
										<div class="input_box" style="width:20%">
											<input type="text" name="usr_email" id="usr_email" value="<?=$view['usr_email']?>" />
										</div>
										<p class="normal fc_blue">이메일은 예제와 같이 입력해주세요. 예) 아이디@서비스도메인</p>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">휴대폰번호</span></th>
								<td>
									<div class="box">
										<div class="input_box" style="width:20%">
											<input type="text" name="usr_phone" id="usr_phone" value="<?=$view['usr_phone']?>"  />
										</div>
										<p class="normal fc_blue">휴대폰번호는 예제와 같이 (-) 포함해서 입력해주세요. 예) 010-1234-5678</p>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">상태</span></th>
								<td>
									<div class="box">
										<div class="c_selectbox" style="width:20%">
											<label for=""></label>
											<select name="status" id="status">
												<option value="Y" <?=chkCompare($view['status'],'Y','selected')?>>이용중</option>
												<option value="N" <?=chkCompare($view['status'],'N','selected')?>>이용중지</option>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">권한등급</span></th>
								<td>
									<div class="box">
										<div class="c_selectbox" style="width:20%">
											<label for=""></label>
											<select name="usr_gubun" id="usr_gubun">
												<?if ($view && $view['usr_gubun'] == "99") {?>
													<option value="99" <?=chkCompare($view['usr_gubun'], '99', 'selected')?>><?=$view['usr_gubun_name']?></option>
												<?}?>
												<?if ($view['usr_gubun'] != '99') {?>
													<?for ($i=0; $i<count($auth_list); $i++) {?>
														<?if ($auth_list[$i]['gubun'] != 99 && $auth_list[$i]['status']=='Y') {?>
															<option value="<?=$auth_list[$i]['gubun']?>" <?=chkCompare($view['usr_gubun'], $auth_list[$i]['gubun'], 'selected')?>><?=$auth_list[$i]['title']?></option>
														<?}?>
													<?}?>
												<?}?>
											</select>
										</div>
										<?
											if ($view && $view['usr_gubun'] != "99") {
												$k=0;
												for ($i=0; $i<count($auth_list); $i++) {
													if (($view['usr_gubun'] == $auth_list[$i]['gubun']) && $auth_list[$i]['status']=='Y') $k++;
												}

												if ($k == 0) {
													?><p class="normal fc_red">※권한등급 <strong>[<?=$view['usr_gubun_name']?>]</strong>은 이용이 불가능합니다, 권한등급을 필히 변경해주세요.</p><?
												}
											}
										?>
									</div>
								</td>
							</tr>
							<?if ($view) {?>
								<tr>
									<th><span class="t_h">최근 로그인정보</span></th>
									<td>
										<?if ($view['visit_last_date'] != '') {?>
											<?=formatDates($view['visit_last_date'], "Y.m.d H:i:s")?> / <?=$view['visit_cnt']?>회 로그인
										<?} else {?>
											로그인 기록이 없습니다.
										<?}?>
									</td>
								</tr>
								<tr>
									<th><span class="t_h">최근 수정일</span></th>
									<td><?=formatDates($view['upt_date'], "Y.m.d H:i:s")?></td>
								</tr>
								<tr>
									<th><span class="t_h">최초 등록일</span></th>
									<td><?=formatDates($view['reg_date'], "Y.m.d H:i:s")?></td>
								</tr>
							<?}?>
						</tbody>
					</table>
					</form>
				</div>
				<div class="page_btn_a center">
					<a href="admin_list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
					<a href="javascript:;" class="btn_40 white" onclick="regGo();"><?=iif($view==false, "등록하기", "수정하기")?></a>
					<?if ($view) {?>
						<a href="javascript:;" class="btn_40 gray" onclick="deleteGo();">삭제하기</a>
					<?}?>
				</div>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		//운영자 폼체크
		function regGo() {
			if (!h.checkValNLen("usr_id", 4, 20, "아이디", "Y", "EN")) return false;
			if (!h.checkValNLen("usr_name", 2, 50, "이름", "Y", "KO")) return false;
			if (!h.checkValNLen("usr_email", 10, 50, "이메일", "Y", "EN")) return false;
			if (!h.checkEmail("usr_email", "이메일")) return false;
			if (!h.checkValNLen("usr_phone", 9, 20, "휴대폰번호", "Y", "N-")) return false;
			if (!phoneRegExpCheck(h.objVal("usr_phone"), "휴대폰번호", "-")) return false;

			AJ.ajaxForm($("#regFrm"), "admin_write_proc.php", function(data) {
				if (data.result == 200) {
					alert("처리 되었습니다.");

					<?if ($view == false) {?>
						location.replace("admin_list.php?page=1<?=$page_params?>");
					<?} else {?>
						location.reload();
					<?}?>
				} else {
					alert(data.message);
				}
			});
		}

		//비밀번호 초기화
		function passwordReset() {
			if (!confirm("비밀번호를 초기화 하시겠습니까?\n비밀번호는＂<?=CONST_RESET_PWD?> + 휴대폰번호 뒤 4자리＂로 초기화 됩니다.\n\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

			AJ.callAjax("__password_reset_proc.php", {"usr_idx": "<?=$params['usr_idx']?>"}, function(data){
				if (data.result == 200) {
					alert("비밀번호가 초기화 되었습니다.");

					location.reload();
				} else {
					alert(data.message);
				}
			});
		}

		//운영자 삭제
		function deleteGo() {
			if (!confirm("운영자를 삭제하시겠습니까?\n삭제시 운영자는 더이상 접속이 불가능합니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

			AJ.callAjax("admin_delete_proc.php", {"usr_idx": "<?=$params['usr_idx']?>"}, function(data){
				if (data.result == 200) {
					alert("운영자가 삭제 되었습니다.");

					location.replace("admin_list.php");
				} else {
					alert(data.message);
				}
			});
		}
	</script>
<?include("../inc/footer.php")?>