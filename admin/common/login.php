<?include("../inc/config.php")?>
<?
	$save_id    = $_COOKIE["ADMIN_SAVE_ID"];
	$save_login = $_COOKIE["ADMIN_SAVE_LOGIN"];

	if ($save_id != "") $save_id = decryption($save_id);
	if ($save_login != "") {
		$save_login = decryption($save_login);

		//관리자 정보 불러오기
		$adm_view = $cls_member->admin_view($save_login);

		if ($adm_view == false) {
			fnMsgGo(500, "일치하는 사용자 정보가 없습니다.", "logout.php", "");
		} else {
			//마지막 방문일 쿠키저장
			if ($adm_view['visit_last_date'] != "") {
				setcookie("ADMIN_VISIT_LAST_DATE", $adm_view['visit_last_date'], time() + (86400 * 365));
			} else {
				setcookie("ADMIN_VISIT_LAST_DATE", date("Y-m-d H:i:s"), time() + (86400 * 365));
			}

			//로그인 회수 저장 및 마지막 로그인 접수일 업데이트
			$cls_member->last_visit_update($save_login);

			//세션저장
			setSession("admin_view", $adm_view);

			fnMsgGo(501, "", CLS_SETTING_MENU_AUTH::menu_first_page( $adm_view['usr_gubun'] ), "");
		}
	}
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?=SITE_NAME?>">
	<meta property="og:site_name" content="<?=SITE_NAME?>" />
	<meta property="og:title" content="<?=SITE_NAME?>" />
	<meta property="og:description" content="<?=SITE_NAME?>" />
	<title>로그인 | <?=SITE_NAME?></title>
	<link rel="icon" href="/images/common/favicon_kor.ico">
	<link rel="stylesheet" href="../css/reset.css">
	<link rel="stylesheet" href="../css/common.css">
	<script src="../js/jquery-1.10.1.min.js"></script>
	<script src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="../js/html5shiv.js"></script>
	<script src="../js/prefixfree.min.js"></script>
	<script src="../js/amcharts.js"></script>
	<script src="../js/serial.js"></script>
	<script src="../js/common.js"></script>
	<script type="text/javascript" src="/module/js/class.helper.js"></script>
	<script type="text/javascript" src="/module/js/fn.user.define.js"></script>


	<script type="text/javascript">
		var ajaxStatus = false; //Ajax중보처리상태 플래그

		$(function(){
			$("#login_id, #login_pwd").keyup(function(){
				enters(function(){ loginGo() });
			})
		})

		function loginGo(){
			var h = new clsJsHelper();

			if (!h.checkValNLen("login_id", 4, 20, "아이디", "Y", "EN")) return;
			if (!h.checkValNLen("login_pwd", 4, 20, "비밀번호", "Y", "EN")) return;

			AJ.ajaxForm($("#loginFrm"), "login_proc.php", function(data) {
				if (data.result == 200) {
					location = data.page_link;
				} else {
					alert(data.message);
				}
			});
		}
	</script>
</head>
<body>
	<div class="login_wrap">
		<div class="box">
			<form name="loginFrm" id="loginFrm" method="post">
			<input type="hidden" name="referer_page" value="<?=URL_REFERER?>" />
			<div class="logo"><img src="../images/img_login_top.png" /></div>
			<div class="form_box">
				<div class="input_row">
					<div class="input">
						<div class="input_box">
							<input type="text" name="login_id" id="login_id" value="<?=$save_id?>" placeholder="아이디를 입력해주세요." />
						</div>
						<div class="input_box">
							<input type="password" name="login_pwd" id="login_pwd" placeholder="비밀번호를 입력해주세요." />
						</div>
					</div>
					<a href="javascript:;" class="btn_login" onclick="loginGo()">로그인</a>
				</div>
				<div class="etc_row">
					<div class="check">
						<input type="checkbox" name="save_id" id="save_id" value="Y" <?=iif($save_id!="", "checked", "")?> />
						<label for="save_id">아이디 저장</label>
						<input type="checkbox" name="save_login" id="save_login" value="Y" <?=iif($save_login!="", "checked", "")?> />
						<label for="save_login">로그인 유지</label>
					</div>
				</div>
			</div>
			</form>
			<div class="info">
				<p class="ie">본 시스템은 Chrome, 인터넷 익스플로어(IE 11 이상)에 최적화되었습니다.</p>
				<p class="copyright">copyright©<span class="mark"><?=SITE_NAME?></span> All Rights Reserved.</p>
			</div>
			<img src="../images/img_login_box_bottom.png" alt="" class="box_bottom" />
		</div>
	</div>

	<script type="text/javascript" src="/module/js/jquery.form.js"></script>
	<script type="text/javascript" src="/module/js/jquery.tmpl.js"></script>
	<script type="text/javascript" src="/module/js/fn.util.js"></script>
	<script type="text/javascript" src="/module/js/fn.check.field.js"></script>
</body>
</html>