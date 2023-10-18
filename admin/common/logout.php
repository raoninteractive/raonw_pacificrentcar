<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	$tp = chkReqRpl("tp", "", 20, "", "STR");

	delSession("admin_view");

	$MEM_ADM = array();

	//로그아웃 버튼 클릭시만 자동로그인 해제
	if ($tp == "btn") {
		setcookie("ADMIN_SAVE_LOGIN", "", time() - (86400 * 365), "/");
	}

	var_dump(session_destroy());
	header("Location: ../");
?>
