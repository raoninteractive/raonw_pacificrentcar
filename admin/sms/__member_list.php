<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "../", "");
    if (!isAdmin()) fnMsgGo(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "../", "");

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_gubun']  = chkReqRpl("sch_gubun", "", "", "", "STR");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "", "INT");
    $params['sch_word']   = chkReqRpl("sch_word", "", 20, "", "STR");
    $page_params = setPageParamsValue($params, "page,list_size,block_size");

    $cls_sms = new CLS_SMS;

	//일반회원 목록 불러오기
	$list = $cls_sms->member_list($params, $total_cnt, $total_page);
?>
<div class="list_header">
	<dl class="cnt">
		<dt>Total</dt>
		<dd><?=formatNumbers($total_cnt)?></dd>
	</dl>

    <div class="category">
        <a href="javascript:;" class="curr" style="font-size:13px" onclick="addAllListGo()">[전체추가]</a>
    </div>
</div>
<table>
    <colgroup>
        <col width="70">
        <col width="70" />
        <col width="*" />
        <col width="180" />
        <col width="120" />
        <col width="100" />
        <col width="100" />
        <col width="120" />
    </colgroup>
    <thead>
        <tr>
            <th>선택</th>
            <th>번호</th>
            <th>아이디</th>
            <th>이름</th>
            <th>이메일</th>
            <th>휴대폰번호</th>
            <th>회원권한</th>
            <th>가입일</th>
        </tr>
    </thead>
    <tbody>
        <?for ($i=0; $i<count($list);$i++) {?>
            <tr>
                <td><a href="javascript:;" class="btn_26 white" onclick="recipientListAdd('<?=$list[$i]['usr_id']?>|<?=$list[$i]['usr_name']?>|<?=$list[$i]['usr_phone']?>')">선택</a></td>
                <td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
                <td><?=$list[$i]['usr_id']?></td>
                <td><?=$list[$i]['usr_name']?></td>
                <td><?=$list[$i]['usr_email']?></td>
                <td><?=$list[$i]['usr_phone']?></td>
                <td><?=$list[$i]['usr_gubun_name']?></td>
                <td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
            </tr>
        <?}?>

        <?if (count($list) == 0) {?>
            <tr>
                <td colspan="8">등록된 데이터가 없습니다.</td>
            </tr>
        <?}?>
    </tbody>
</table>

<nav class="page_nate">
    <? adminScriptPaging($total_page, $params['block_size'], $params['page'], "searchGo({page})") ?>
</nav>