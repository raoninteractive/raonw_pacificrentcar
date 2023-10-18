<?include("../inc/config.php")?>
<?
	$db = new DB_HELPER;

	//신규 가입(금일)
	$sql = "SELECT COUNT(*) AS total_cnt FROM member WHERE usr_gubun <= 80 AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), CURDATE()) = 0";
	$new_mem_cnt = $db->getQueryValue($sql)['total_cnt'];

	//총 회원 수(누적)
	$sql = "SELECT COUNT(*) AS total_cnt FROM member WHERE usr_gubun < 80";
	$total_mem_cnt = $db->getQueryValue($sql)['total_cnt'];

	//차단 회원(누적)
	$sql = "SELECT COUNT(*) AS total_cnt FROM member WHERE usr_gubun < 80 AND status='N'";
	$block_mem_cnt = $db->getQueryValue($sql)['total_cnt'];

	//탈퇴 회원(누적)
	$sql = "SELECT COUNT(*) AS total_cnt FROM member WHERE usr_gubun = 80";
	$out_mem_cnt = $db->getQueryValue($sql)['total_cnt'];

	//최근 게시물(7일)
	$sql = "SELECT COUNT(*) AS total_cnt FROM board WHERE del_flag='N' AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), CURDATE()) <= 7";
	$latest_board_cnt = $db->getQueryValue($sql)['total_cnt'];

	//수강신청(금일)
	$sql = "SELECT COUNT(*) AS total_cnt FROM content_class_apply WHERE status=1 AND del_flag='N' AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), CURDATE()) = 0";
	$class_apply_cnt = $db->getQueryValue($sql)['total_cnt'];

	//금일 신규가입 목록 불러오기
	$sql = "
			SELECT
				usr_idx, usr_id, usr_name, eyefree_flag, reg_date
			FROM member
			WHERE usr_gubun < 80 AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), CURDATE()) = 0
			ORDER BY reg_date DESC
			LIMIT 5
		";
	$new_mem_list = $db->getQuery($sql);

	//금일 강의시청 목록 불러오기
	$sql = "
			SELECT
				d.usr_id,
				d.usr_name,
				c1.title AS class_title,
				c2.title AS lec_title,
				a.reg_date
			FROM content_class_apply_lecture a
				INNER JOIN content_class_apply b ON a.apply_idx=b.idx AND b.del_flag='N'
				INNER JOIN content_class c1 ON b.class_idx=c1.idx AND c1.del_flag='N'
				INNER JOIN content_class_lecture c2 ON a.lecture_idx=c2.idx AND c2.del_flag='N'
				INNER JOIN member d ON a.reg_id=d.usr_id
			WHERE a.status=1 AND a.del_flag='N' AND TIMESTAMPDIFF(DAY, DATE_FORMAT(a.reg_date,'%Y-%m-%d'), CURDATE()) >= 0
			ORDER BY a.reg_date DESC
			LIMIT 5
		";
	$lecture_list = $db->getQuery($sql);

	//최근 게시물 목록 불러오기
	$sql = "
			SELECT
				idx, bbs_code, title, writer, reg_date,
				CASE
					WHEN bbs_code='notice' THEN '공지사항'
					WHEN bbs_code='it' THEN 'IT뉴스 및 정보'
					WHEN bbs_code='data' THEN '자료실'
					WHEN bbs_code='qna' THEN 'Q&A 게시판'
				END AS bbs_code_name
			FROM board
			WHERE del_flag='N' AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), CURDATE()) <= 7
			ORDER BY reg_date DESC
			LIMIT 5
		";
	$board_list = $db->getQuery($sql);

	$pageNum = "0101";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container main">
		<div class="status_a">
			<table>
				<colgroup>
					<col width="16.66%" />
					<col width="16.66%" />
					<col width="16.66%" />
					<col width="16.66%" />
					<col width="16.66%" />
					<col width="16.66%" />
				</colgroup>
				<thead>
					<tr>
						<th>신규 가입<br>(금일)</th>
						<th>총 회원 수<br>(누적)</th>
                        <th>차단 회원<br>(누적)</th>
                        <th>탈퇴 회원<br>(누적)</th>
						<th>최근 게시물<br>(7일)</th>
						<th>수강 신청<br>(금일)</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><?=formatNumbers($new_mem_cnt)?></td>
						<td><?=formatNumbers($total_mem_cnt)?></td>
						<td><?=formatNumbers($block_mem_cnt)?></td>
						<td><?=formatNumbers($out_mem_cnt)?></td>
						<td><?=formatNumbers($latest_board_cnt)?></td>
						<td><?=formatNumbers($class_apply_cnt)?></td>
					</tr>
				</tbody>
			</table>
		</div>
		<div class="group bottom">
			<div class="box left_a">
				<div class="title_a">
					<h2 class="b_title">금일 신규가입</h2>
					<a href="../member/user_list.php?sch_sdate=<?=date('Y-m-d')?>&sch_edate=<?=date('Y-m-d')?>" class="btn_more"><img src="../images/btn_more.gif" alt="더보기" /></a>
				</div>
				<table class="list_tbl">
					<colgroup>
						<col width="25%">
                        <col width="25%">
                        <col width="25%">
                        <col width="25%">
					</colgroup>
					<thead>
						<tr>
							<th>아이디</th>
                            <th>이름</th>
                            <th>아이프리</th>
                            <th>가입일시</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($new_mem_list); $i++) {?>
							<tr>
								<td><a href="../member/user_view.php?usr_idx=<?=$new_mem_list[$i]['usr_idx']?>" class="a_link"><?=$new_mem_list[$i]['usr_id']?></a></td>
								<td><?=$new_mem_list[$i]['usr_name']?></td>
								<td><?=$new_mem_list[$i]['eyefree_flag']?></td>
								<td><?=formatDates($new_mem_list[$i]['reg_date'], "Y.m.d H:i")?></td>
							</tr>
						<?}?>

						<?if (count($new_mem_list) == 0) {?>
							<tr>
								<td colspan="4">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>
			</div>
			<div class="box right_a">
				<div class="title_a">
					<h2 class="b_title">금일 강의현황</h2>
					<a href="javascript:;" class="btn_more"><img src="../images/btn_more.gif" alt="더보기" /></a>
				</div>
				<table class="list_tbl">
					<colgroup>
						<col width="16%">
                        <col width="33%">
                        <col width="33%">
                        <col width="18%">
					</colgroup>
					<thead>
						<tr>
							<th>이름</th>
							<th>강좌명</th>
                            <th>강의명</th>
                            <th>시작일시</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($lecture_list); $i++) {?>
							<tr>
								<td><?=$lecture_list[$i]['usr_name']?></td>
								<td class="ta_l"><?=$lecture_list[$i]['class_title']?></td>
								<td class="ta_l"><?=$lecture_list[$i]['lec_title']?></td>
								<td><?=formatDates($lecture_list[$i]['reg_date'], "Y.m.d H:i")?></td>
							</tr>
						<?}?>

						<?if (count($lecture_list) == 0) {?>
							<tr>
								<td colspan="4">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>
			</div>
        </div>

		<div class="group bottom">
			<div class="box">
				<div class="title_a">
					<h2 class="b_title">최근 게시물 (7일)</h2>
					<!-- <a href="javascript:;" class="btn_more"><img src="../images/btn_more.gif" alt="더보기" /></a> -->
				</div>
				<table class="list_tbl">
					<colgroup>
						<col width="5%">
						<col width="13%">
						<col width="*">
						<col width="10%">
						<col width="10%">
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>게시판/강좌</th>
                            <th>제목</th>
                            <th>작성자</th>
                            <th>등록일</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($board_list); $i++) {?>
							<tr>
								<td><?=count($board_list)-$i?></td>
								<td><?=$board_list[$i]['bbs_code_name']?></td>
								<td class="ta_l"><a href="../board/write.php?bbs_code=<?=$board_list[$i]['bbs_code']?>&idx=<?=$board_list[$i]['idx']?>" class="a_link"><?=$board_list[$i]['title']?></a></td>
								<td><?=$board_list[$i]['writer']?></td>
								<td><?=formatDates($board_list[$i]['reg_date'], "Y.m.d H:i")?></td>
							</tr>
						<?}?>

						<?if (count($board_list) == 0) {?>
							<tr>
								<td colspan="5">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- //container -->
<?include("../inc/footer.php")?>