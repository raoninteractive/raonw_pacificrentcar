<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");


	$params['qna_name']    = chkReqRpl("qna_name", "", "20", "POST", "STR");
	$params['qna_phone']   = chkReqRpl("qna_phone", "", "20", "POST", "STR");
	$params['qna_email']   = chkReqRpl("qna_email", "", "50", "POST", "STR");
    $params['qna_content'] = chkReqRpl("qna_content", "", "2000", "POST", "STR");


    if (chkBlank($params['qna_name'])) fnMsgJson(501, "이름 값이 유효하지 않습니다.", "");
    if (chkBlank($params['qna_phone']) || !isDataCheck($params['qna_phone'], 'phone2')) fnMsgJson(502, "휴대폰번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['qna_email']) || !isDataCheck($params['qna_email'], 'email')) fnMsgJson(503, "이메일 값이 유효하지 않습니다.", "");
    if (chkBlank($params['qna_content'])) fnMsgJson(504, "문의내용 값이 유효하지 않습니다.", "");

    $db = new DB_HELPER;

    $params['reg_ip'] = $NOW_IP;
    $params['reg_id'] = $MEM_USR['usr_id'];
    $sql = "
            INSERT INTO board_inquiry (
                gubun, name, phone, email, content,
                del_flag, reg_ip, reg_id, reg_date
            ) VALUES (
                '". $params['qna_gubun'] ."', '". $params['qna_name'] ."', '". $params['qna_phone'] ."', '". $params['qna_email'] ."', '". $params['qna_content'] ."',
                'N', '". $params['reg_ip'] ."', '". $params['reg_id'] ."', NOW()
            )
        ";
    $db->insert($sql);

?>
{"result": 200, "message": "OK"}
