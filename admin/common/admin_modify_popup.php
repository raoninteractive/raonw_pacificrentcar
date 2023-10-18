<?include("../inc/config.php")?>
<?
	if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "../common/logout.php", "");
?>
<div class="dim"></div>
<div class="contents" style="width:800px;margin-left:-400px;">
	<div class="layer_header">
		<h2>기본정보 관리</h2>
		<button type="button" title="레이어 팝업 닫기" class="btn_close_layer" onclick="commonLayerClose('admin_modify_popup')"></button>
	</div>
	<div class="cont">
		<div class="explain">
			현재 <strong>＇<?=$adm_view['usr_name']?>＇</strong>님의 기본등록 정보 입니다.<br>
			허용된 변경사항 외 수정은 시스템관리자에게 문의주세요.
		</div>
		<div class="common_form">
			<form name="popAdminModifyFrm" id="popAdminModifyFrm" method="post">
			<table class="g_table">
				<colgroup>
					<col style="width:15%;">
					<col style="width:35%">
					<col style="width:15%;">
					<col style="width:35%">
				</colgroup>
				<tbody>
					<tr>
						<th><span class="t_imp">아이디</span></th>
						<td>
							<div class="box">
								<div class="input_box" style="width:100%">
									<input type="text" name="usr_id" id="pop_usr_id" value="<?=$adm_view['usr_id']?>" />
								</div>
							</div>
						</td>
						<th><span class="t_imp">이름</span></th>
						<td>
							<div class="box">
								<div class="input_box" style="width:100%">
									<input type="text" name="usr_name" id="pop_usr_name" value="<?=$adm_view['usr_name']?>" />
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th><span class="t_h">이메일</span></th>
						<td>
							<div class="box">
								<div class="input_box" style="width:100%">
									<input type="text" name="usr_email" id="pop_usr_email" value="<?=$adm_view['usr_email']?>" maxlength="50" placeholder="예: <?=SITE_EMAIL?>" />
								</div>
							</div>
						</td>
						<th><span class="t_h">휴대폰번호</span></th>
						<td>
							<div class="box">
								<div class="input_box" style="width:100%">
									<input type="text" name="usr_phone" id="pop_usr_phone" value="<?=$adm_view['usr_phone']?>" class="onlyNumHyphen" placeholder="예: 010-1234-5678" />
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th><span class="t_imp">비밀번호</span></th>
						<td>
							<div class="box">
								<div class="input_box" style="width:100%">
									<input type="password" name="passwd" id="pop_passwd" maxlength="20" placeholder="현재/변경 비밀번호 (영문,숫자 포함 8자 이상)" />
								</div>
							</div>
						</td>
						<th><span class="t_imp">비밀번호 확인</span></th>
						<td>
							<div class="box">
								<div class="input_box" style="width:100%">
									<input type="password" name="passwd_re" id="pop_passwd_re" maxlength="20" placeholder="현재/변경 비밀번호 확인" />
								</div>
							</div>
						</td>
					</tr>
					<tr>
						<th><span class="t_h">로그인 수 /<br>최종 접속일</span></th>
						<td colspan="3"><?=formatNumbers($adm_view['visit_cnt'], 0)?>회 / <?=formatDates($adm_view['visit_last_date'], "Y-m-d H:i:s")?></td>
					</tr>
					<tr>
						<th><span class="t_h">마지막 비밀번호 변경일</span></th>
						<td colspan="3"><?=formatDates($adm_view['pwd_last_date'], "Y-m-d H:i:s")?></td>
					</tr>
				</tbody>
			</table>
			</form>

			<div class="btn_area two">
				<a href="javascript:;" class="btn gray" onclick="commonLayerClose('admin_modify_popup')">닫기</a>
				<a href="javascript:;" class="btn blue" onclick="adminModifyPopGo()">저장</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	//관리자 폼체크
	function adminModifyPopGo() {
		if (!h.checkValNLen("pop_usr_id", 5, 20, "아이디", "Y", "EN")) return false;
		if (!h.checkValNLen("pop_usr_name", 4, 50, "이름", "N", "KO")) return false;
		if (h.objVal("pop_usr_email")) {
			if (!h.checkValNLen("pop_usr_email", 10, 50, "이메일", "Y", "EN")) return false;
			if (!h.checkEmail("pop_usr_email", "이메일")) return false;
		}
		if (h.objVal("pop_usr_phone")) {
			if (!h.checkValNLen("pop_usr_phone", 12, 13, "휴대폰번호", "Y", "N-")) return false;
			if (!phoneRegExpCheck(h.objVal("pop_usr_phone"), "휴대폰번호", "-")) return false;
		}
		if (!h.checkValNLen("pop_passwd", 8, 20, "비밀번호", "Y", "EN")) return false;
		if (!h.pwdSecurityCheck("pop_passwd", "비밀번호")) return false;
		if (!h.checkValNLen("pop_passwd_re", 8, 20, "비밀번호 확인", "Y", "EN")) return false;


		AJ.ajaxForm($("#popAdminModifyFrm"), "../common/admin_modify_proc.php", function(data) {
			if (data.result == 200) {
				alert("처리 되었습니다.");
				commonLayerClose('admin_modify_popup');
			} else {
				alert(data.message);
			}
		});
	}
</script>