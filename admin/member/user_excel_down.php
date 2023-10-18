<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "BACK");
	if (!isAdmin()) fnMsgGo(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "BACK");

	$params['page']       = 1;
	$params['list_size']  = 9999999;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_gubun']  = chkReqRpl("sch_gubun", null, "", "", "INT");
	$params['sch_status'] = chkReqRpl("sch_status", "", 1, "", "STR");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 50, "", "STR");

	$cls_member = new CLS_MEMBER;

	//일반회원 목록 불러오기
	$list = $cls_member->user_list($params);


	header( "Content-type: application/vnd.ms-excel; charset=utf-8");
	header( "Content-Disposition: attachment; filename = member_".date('Ymd').".xls" );
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
		<col width="80" />
		<col width="120" />
		<col width="120" />
		<col width="120" />
		<col width="200" />
		<col width="120" />
		<col width="120" />
		<col width="80" />
		<col width="100" />
		<col width="300" />
		<col width="200" />
		<col width="150" />
		<col width="100" />
		<col width="100" />
		<col width="100" />
		<col width="100" />
		<col width="150" />
		<col width="150" />
	</colgroup>
	<thead>
		<tr>
			<th class="tlt">번호</th>
			<th class="tlt">회원등급</th>
			<th class="tlt">아이디</th>
			<th class="tlt">이름</th>
			<th class="tlt">닉네임</th>
			<th class="tlt">이메일</th>
			<th class="tlt">휴대폰번호</th>
			<th class="tlt">생년월일</th>
			<th class="tlt">성별</th>
			<th class="tlt">우편번호</th>
			<th class="tlt">주소</th>
			<th class="tlt">상세주소</th>
			<th class="tlt">장애구분</th>
			<th class="tlt">장애급수</th>
			<th class="tlt">장애경중</th>
			<th class="tlt">아이프리 회원</th>
			<th class="tlt">회원인증</th>
			<th class="tlt">최근 접속일</th>
			<th class="tlt">가입일시</th>
		</tr>
	</thead>
	<tbody>
		<?for ($i=0; $i<count($list);$i++) {?>
			<tr>
				<td class="con"><?=formatNumbers(count($list)-$i)?></td>
				<td class="con"><?=$list[$i]['usr_gubun_name']?></td>
				<td class="con"><?=$list[$i]['usr_id']?></td>
				<td class="con"><?=$list[$i]['usr_name']?></td>
				<td class="con"><?=$list[$i]['nick_name']?></td>
				<td class="con"><?=$list[$i]['usr_email']?></td>
				<td class="con"><?=$list[$i]['usr_phone']?></td>
				<td class="con"><?=$list[$i]['birthdate']?></td>
				<td class="con"><?=$list[$i]['gender_name']?></td>
				<td class="con"><?=$list[$i]['zipcode']?></td>
				<td class="con"><?=$list[$i]['addr']?></td>
				<td class="con"><?=$list[$i]['addr_detail']?></td>
				<td class="con"><?=$list[$i]['disa_gubun']?></td>
				<td class="con"><?=$list[$i]['disa_grade']?></td>
				<td class="con"><?=$list[$i]['disa_state']?></td>
				<td class="con"><?=$list[$i]['eyefree_flag']?></td>
				<td class="con"><?=$list[$i]['auth_flag']?></td>
				<td class="con"><?=formatDates($list[$i]['visit_last_date'], "Y.m.d H:i")?></td>
				<td class="con"><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
			</tr>
		<?}?>
	</tbody>
</table>