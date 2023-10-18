<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");

	$params['group_code'] = chkReqRpl("group_code", "", 20, "POST", "STR");

	if (chkBlank($params['group_code'])) fnMsgJson(502, "닉네임 값이 유효하지 않습니다.", "");

    $cls_member = new CLS_MEMBER;

    $view = $cls_member->group_view($params['group_code'], 'Y');
    if (!($view['group_code'] == $params['group_code'])) {
        $view['group_name'] = "";
    }
?>
{"result": 200, "message": "OK", "group_name": "<?=$view['group_name']?>"}
