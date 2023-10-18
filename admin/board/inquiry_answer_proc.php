<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

    $params['idx']               = chkReqRpl("idx", null, "", "POST", "INT");
    $params['answer_content']    = chkReqRpl("answer_content", "", "max", "POST", "STR");
    $params['answer_email_send'] = chkReqRpl("answer_email_send", "N", "1", "POST", "STR");
    $params['answer_email']      = chkReqRpl("answer_email", "", "50", "POST", "STR");


    if (chkBlank($params['idx'])) fnMsgJson(502, "게시글 고유번호 값이 유효하지 않습니다.", "");
    if (chkBlank($params['answer_content'])) fnMsgJson(503, "답변내용 값이 유효하지 않습니다.", "");
    if ($params['answer_email_send']=='Y' && chkBlank($params['answer_email'])) fnMsgJson(504, "이메일 값이 유효하지 않습니다.", "");

	$cls_board = new CLS_BOARD;
    $cls_sms = new CLS_SMS;

    //상세보기
    $view = $cls_board->inquiry_view($params['idx']);
    if ($view == false) fnMsgJson(505, "일치하는 게시글 정보가 없습니다.", "");

	$params['upt_ip'] = $NOW_IP;
	$params['upt_id'] = $MEM_ADM['usr_id'];

    //문의 답변 처리
    if ($cls_board->inquiry_answer_save_proc($params) == 0) fnMsgJson(506, "저장 처리중 오류가 발생되었습니다.", "");

    //메일 발송
    if ($params['answer_email_send'] == 'Y') {
        $subject = "[". SITE_NAME ."] 안녕하세요. 접수하신 문의에 대한 답변입니다.";
        $content = getEmailSendFile("/module/email/qna.answer.send.html");
        $content = str_replace('{{site_url}}', SITE_URL, $content);
        $content = str_replace('{{content}}', textareaDecode($view['content']), $content);
        $content = str_replace('{{answer_content}}', textareaDecode($params['answer_content']), $content);

        sendEmail($subject, $params['answer_email'], "", SITE_EMAIL, SITE_NAME, $content);

        /*
        //카카오 알림톡 회원발송
        $cls_sms->kakao_send('TI_2076', array(
            array(
                'name'=>$view['name'],
                'phone'=>$view['phone']
            )
        ), array(
            '괌',
            $view['email']
        ));
        */
    }

    $memo = "답변 내용이 저장되었습니다.". iif($params['answer_email_send']=='Y', ' (답변 이메일 발송 - '. $params['answer_email'] .')', '') ."\n";
    $memo .= "--------------------------------------------------\n";
    $memo .= $params['answer_content'];


    adminMemoSystemSave('board_inquiry', $params['idx'], $memo, $MEM_ADM['usr_name']);
?>
{"result": 200, "message": "OK"}
