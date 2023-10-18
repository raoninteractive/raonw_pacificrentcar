<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['usr_idx']         = chkReqRpl("usr_idx", null, "", "POST", "INT");
	$params['recv_email_flag'] = chkReqRpl("recv_email_flag", "N", 1, "POST", "STR");
	$params['recv_sms_flag']   = chkReqRpl("recv_sms_flag", "N", 1, "POST", "STR");
	$params['up_file_1']       = $_FILES['up_file_1'];
	$params['usr_gubun']       = chkReqRpl("usr_gubun", null, "", "POST", "INT");
	$params['status']          = chkReqRpl("status", "N", 1, "POST", "STR");
    $params['eyefree_flag']    = chkReqRpl("eyefree_flag", "N", 1, "POST", "STR");
	$params['auth_flag']       = chkReqRpl("auth_flag", "N", 1, "POST", "STR");
    $params['movie_autoplay']  = chkReqRpl("movie_autoplay", "N", 1, "POST", "STR");
	$params['movie_continue']  = chkReqRpl("movie_continue", "N", 1, "POST", "STR");
	$up_file_path              = "/upload/member";

	if (chkBlank($params['usr_idx'])) fnMsgJson(502, "회원 고유번호 값이 유효하지 않습니다.", "");

	$cls_member = new CLS_MEMBER;


	$view = $cls_member->user_view($params['usr_idx']);
	if ($view == false) fnMsgJson(504, "일치하는 데이터가 없습니다.", "");


	$upfile_change = false;
	if (!chkBlank($params['up_file_1']['name'])) {
		$fuArray             = fileUpload("up_file_1", $up_file_path, 10, "IMG", "N");
		$params['up_file_1'] = $fuArray[0]["file_info"];
		$upfile_change       = true;

		makeThumbnail($up_file_path, $fuArray[0]["file_name"], $up_file_path, 1200, 1200, true);
	} else {
		$params['up_file_1'] = $view['up_file_1'];
	}



	//$params['usr_gubun']           = $view['usr_gubun'];
	$params['usr_pwd']             = $view['usr_pwd'];
	$params['usr_name']            = $view['usr_name'];
	$params['usr_email']           = $view['usr_email'];
	$params['usr_phone']           = $view['usr_phone'];
	$params['birthdate']           = $view['birthdate'];
	$params['gender']              = $view['gender'];
	//$params['recv_email_flag']     = $view['recv_email_flag'];
	$params['old_recv_email_flag'] = $view['recv_email_flag'];
	//$params['recv_sms_flag']       = $view['recv_sms_flag'];
	$params['old_recv_sms_flag']   = $view['recv_sms_flag'];
	//$params['status']              = $view['status'];
	$params['zipcode']             = $view['zipcode'];
	$params['addr']                = $view['addr'];
	$params['addr_detail']         = $view['addr_detail'];
	$params['disa_gubun']          = $view['disa_gubun'];
	$params['disa_grade']          = $view['disa_grade'];
	$params['disa_state']          = $view['disa_state'];
	//$params['up_file_1']        = $view['up_file_1'];
	//$params['eyefree_flag']        = $view['eyefree_flag'];
	//$params['auth_flag']           = $view['auth_flag'];
	//$params['movie_autoplay']      = $view['movie_autoplay'];
	//$params['movie_continue']      = $view['movie_continue'];
	$params['preference_edu']      = $view['preference_edu'];
	$params['upt_ip']              = $NOW_IP;
	$params['upt_id']              = $MEM_ADM['usr_id'];
	if (!$cls_member->user_save($params)) {
		if ($upfile_change) fileDelete($up_file_path, getUpfileName($params['up_file_1']));

		fnMsgJson(505, "일치하는 회원정보가 없거나, 변경된 내역이 없습니다.", "");
	} else {
		if ($upfile_change) fileDelete($up_file_path, getUpfileName($view['up_file_1']));
	}

?>
{"result": 200, "message": "OK"}
