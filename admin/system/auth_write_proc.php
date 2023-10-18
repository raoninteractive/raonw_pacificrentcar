<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$gubun            = chkReqRpl("gubun", null, "", "POST", "INT");
	$title            = chkReqRpl("title", "", 50, "POST", "STR");
	$description      = chkReqRpl("description", "", 500, "POST", "STR");
	$status           = chkReqRpl("status", "N", 1, "POST", "STR");
	$menu_access_auth = chkReqRpl("menu_access_auth", "", 500, "POST", "STR");

	if (chkBlank($gubun)) fnMsgJson(502, "등급코드 값이 유효하지 않습니다.", "");
	if (chkBlank($title)) fnMsgJson(503, "권한등급명 값이 유효하지 않습니다.", "");
	if (chkBlank($description)) fnMsgJson(504, "권한설명 값이 유효하지 않습니다.", "");
	if (chkBlank($menu_access_auth)) fnMsgJson(505, "권한설정 값이 유효하지 않습니다.", "");

	$cls_set_menu = new CLS_SETTING_MENU_AUTH;

	//상세정보 불러오기
	$view = $cls_set_menu->admin_auth_view($gubun);
	if ($view == false) fnMsgJson(506, "일치하는 데이터가 없습니다.", "");

	//회원정보 수정
	$params['gubun']       = $gubun;
	$params['title']       = $title;
	$params['description'] = $description;
	$params['status']      = $status;
	$params['menu_auth']   = implode(',', $menu_access_auth);
	$params['upt_ip']      = $NOW_IP;
	$params['upt_id']      = $MEM_ADM['usr_id'];
	if ($cls_set_menu->admin_auth_save($params) == 0) fnMsgJson(507, "수정 처리중 오류가 발생되었습니다.", "");

?>
{"result": 200, "message": "OK"}
