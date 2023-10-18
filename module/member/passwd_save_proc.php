<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

    if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
    if (isUser()) fnMsgJson(501, "로그인 사용자는 접근이 제한되어 있습니다.", "");

    $params['token']   = chkReqRpl("token", "", "max", "POST", "STR");
	$params['new_pwd'] = encryption(chkReqRpl("new_pwd", "", 20, "POST", "STR"));
	$params['chk_pwd'] = encryption(chkReqRpl("chk_pwd", "", 20, "POST", "STR"));

    if (chkBlank($params['token'])) fnMsgJson(502, "회원정보 값이 유효하지 않습니다.", "");
	if (chkBlank($params['new_pwd'])) fnMsgJson(503, "비밀번호 값이 유효하지 않습니다.", "");
	if (chkBlank($params['chk_pwd'])) fnMsgJson(505, "비밀번호 재확인 값이 유효하지 않습니다.", "");
    if ($params['new_pwd'] != $params['chk_pwd']) fnMsgJson(504, "비밀번호가 일치하지 않습니다. 비밀번호를 확인해주세요.", "");

    $tmp_token                = explode('|@|', decryption($params['token']));
    $params['gubun']          = "passwd";
    $params['find_id']        = $tmp_token[0];
    $params['find_name']      = $tmp_token[1];
    $params['find_birthdate'] = $tmp_token[2];
    $params['find_phone']     = $tmp_token[3];
    $params['find_email']     = $tmp_token[4];

    $cls_member = new CLS_MEMBER;

    //회원 검색
	$find_view = $cls_member->find_id_passwd($params);
    if ($find_view == false) fnMsgJson(505, "일치하는 회원정보가 없습니다.", "");

	//탈퇴 확인
    if ($find_view['usr_gubun'] == '80') fnMsgJson(506, "탈퇴 처리된 회원 입니다.\n탈퇴 회원은 비밀번호 변경이 불가능합니다.", "");


    //비밀번호 변경
    $params['usr_id']  = $find_view['usr_id'];
    $params['usr_pwd'] = $params['new_pwd'];
    $params['upt_ip']  = $NOW_IP;
    $params['upt_id']  = $find_view['usr_id'];
    if (!$cls_member->user_passwd_save($params)) fnMsgJson(507, "변경된 내용이 없거나 일치하는 데이터가 없습니다.", "");
?>
{"result": 200, "message": "OK"}
