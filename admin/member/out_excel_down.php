<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "BACK");
	if (!isAdmin()) fnMsgGo(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "BACK");

	$params['page']       = 1;
	$params['list_size']  = 9999999;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "GET", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "GET", "STR");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");

	$cls_member = new CLS_MEMBER;

	//탈퇴회원 목록 불러오기
	$list = $cls_member->out_list($params);


	header( "Content-type: application/vnd.ms-excel; charset=utf-8");
	header( "Content-Disposition: attachment; filename = member_out_".date('Ymd').".xls" );
	header( "Content-Description: PHP4 Generated Data" );
?>
<style type="text/css">
	table {
		mso-displayed-decimal-separator:"\.";
		mso-displayed-thousand-separator:"\,";
		border-collapse: collapse
	}
	table, th, td {font-size:12px; font-family:gulim, Verdana, sans-serif;}

	th, td {border:.5pt solid windowtext}
	.subject {height:34.5pt; font-size:13px; text-align:center; font-weight:bold;}
	.tlt {text-align:center; background:#f7f7f7; height:30px; font-weight:bold;}
	.con {text-align:center; mso-number-format:'\@'; height:30px;}

	br{mso-data-placement:same-cell;}
</style>
<table>
	<colgroup>
		<col width="70" />
		<col width="150" />
		<col width="150" />
		<col width="400" />
		<col width="150" />
		<col width="150" />
	</colgroup>
	<thead>
		<tr>
			<th class="tlt">번호</th>
			<th class="tlt">아이디</th>
			<th class="tlt">이름</th>
			<th class="tlt">탈퇴사유</th>
			<th class="tlt">가입일시</th>
			<th class="tlt">탈퇴일시</th>
		</tr>
	</thead>
	<tbody>
		<?for ($i=0; $i<count($list);$i++) {?>
			<tr>
				<td class="con"><?=formatNumbers(count($list)-$i)?></td>
				<td class="con"><?=$list[$i]['usr_id']?></td>
				<td class="con"><?=$list[$i]['usr_name']?></td>
				<td class="con"><?=$list[$i]['out_reason']?></td>
				<td class="con"><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
				<td class="con"><?=formatDates($list[$i]['out_date'], "Y.m.d H:i")?></td>
			</tr>
		<?}?>
	</tbody>
</table>