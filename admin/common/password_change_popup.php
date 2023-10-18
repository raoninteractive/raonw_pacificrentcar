<?include("../inc/config.php")?>
<?
	if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "../common/logout.php", "");

	//상세정보 불러오기
	$view = $cls_member->admin_view($MEM_ADM['usr_id']);
?>
<div class="dim"></div>
<div class="contents" style="width:400px;margin-left:-200px;">
	<div class="layer_header">
		<h2>비밀번호 변경 안내</h2>
	</div>
	<div class="cont">
		<div class="explain">
			<?if (encryption(CONST_RESET_PWD . right($view['usr_phone'],4)) == $view['usr_pwd']) {?>
				<strong><span class="fc_red">기본 비밀번호</span>는 비밀번호 변경이 필요합니다.<br>비밀번호를 신규로 수정해주세요.</strong>
			<?} else {?>
				<strong class="spot"><?=dateDiff("d", left($view['pwd_last_date'],10), date("Y-m-d"))?>일 동안 비밀번호를 변경하지 않으셨습니다.</strong><br><br>
				<?=$view['usr_name']?>님은 장기간 비밀번호를 변경하지 않고,<br>동일한 비밀번호를 사용중입니다.<br>
				안전한 개인정보 보호를 위해 지금 비밀번호를 변경 해주세요.
			<?}?>
		</div>
		<div class="common_form">
			<div class="box">
				<div class="input_box" style="width:100%">
					<input type="password" id="pop_pwdchange_old" class="pop_pwdchange" placeholder="이전비밀번호" maxlength="20" />
				</div>
			</div>
			<div class="box">
				<div class="input_box" style="width:100%">
					<input type="password" id="pop_pwdchange_new" class="pop_pwdchange" placeholder="신규비밀번호 (영문,숫자 포함 8자 이상)" maxlength="20" />
				</div>
			</div>
			<div class="box">
				<div class="input_box" style="width:100%">
					<input type="password" id="pop_pwdchange_re" class="pop_pwdchange" placeholder="신규비밀번호 확인" maxlength="20" />
				</div>
			</div>
			<div class="btn_area two">
				<a href="../common/logout.php" class="btn gray">로그아웃</a>
				<a href="javascript:;" class="btn blue" onclick="popupPasswordChangeGo()">저장</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	//비밀번호 변경공통
	function popupPasswordChangeGo() {
		var h = new clsJsHelper();

		if (!h.checkValNLen("pop_pwdchange_old", 4, 20, "이전 비밀번호", "Y", "EN")) return false;
		if (!h.checkValNLen("pop_pwdchange_new", 8, 20, "신규 비밀번호", "Y", "EN")) return false;
		if (!h.pwdSecurityCheck("pop_pwdchange_new", "신규 비밀번호")) return false;
		if (!h.checkValNLen("pop_pwdchange_re", 8, 20, "신규 비밀번호 확인", "Y", "EN")) return false;

		if (h.objVal("pop_pwdchange_old") == h.objVal("pop_pwdchange_new")) {
			alert("이전 비밀번호와 동일합니다. 비밀번호를 다시 확인해주세요.");
			return false;
		}

		if (h.objVal("pop_pwdchange_new") != h.objVal("pop_pwdchange_re")) {
			alert("비밀번호가 일치하지 않습니다. 비밀번호를 다시 확인해주세요.");
			return false;
		}

		if (!confirm("비밀번호를 변경하시겠습니까?\n계속 진행사히려면 확인을 눌러주세요.")) return false;

		var params = {
			"old_pwd": h.objVal("pop_pwdchange_old"),
			"new_pwd": h.objVal("pop_pwdchange_new"),
			"new_pwd2": h.objVal("pop_pwdchange_re")
		}

		AJ.callAjax("../common/password_change_proc.php", params, function(data){
			if (data.result == 200) {
				alert("비밀번호가 변경 되었습니다.");
				location.reload();
			} else {
				alert(data.message);
			}
		});
	}
</script>