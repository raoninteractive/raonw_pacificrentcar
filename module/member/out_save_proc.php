<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

    if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
    if (!isUser()) fnMsgJson(501, "로그인 후 이용하실 수 있습니다.", "");

    $params['usr_pwd']    = encryption(chkReqRpl("out_pwd", "", 20, "POST", "STR"));
	$params['out_reason'] = chkReqRpl("out_reason", "", 4000, "POST", "STR");

	if (chkBlank($params['usr_pwd'])) fnMsgJson(502, "비밀번호 값이 유효하지 않습니다.", "");
	if (chkBlank($params['out_reason'])) fnMsgJson(503, "탈퇴 사유 값이 유효하지 않습니다.", "");

	$cls_member = new CLS_MEMBER;

    //회원 탈퇴 저장
    $params['upt_ip'] = $NOW_IP;
    $params['upt_id'] = $MEM_USR['usr_id'];
    if (!$cls_member->out_save($params)) fnMsgJson(504, "일치하는 회원정보가 없거나, 변경된 내역이 없습니다.", "");
?>
{"result": 200, "message": "OK"}