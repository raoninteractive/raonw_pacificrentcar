<?include("../inc/config.php")?>
<?
	$pageNum = "9001";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$page         = chkReqRpl("page", 1, "", "", "INT");
	$list_size    = 20;
	$block_size   = 10;
	$search_sdate = chkReqRpl("search_sdate", "", 10, "GET", "STR");
	$search_edate = chkReqRpl("search_edate", "", 10, "GET", "STR");
	$page_params  = "&search_sdate=$search_sdate&search_edate=$search_edate";

	if (chkBlank($search_edate)) $search_edate = date("Y-m-d");
	if (chkBlank($search_sdate)) $search_sdate = dateAdd("m", -1, $search_edate);

	$db = new DB_HELPER;
	$sql = "
			SELECT
				DAYOFWEEK(reg_date) AS week,
				CASE DAYOFWEEK(reg_date)
					WHEN '1' THEN '일요일'
					WHEN '2' THEN '월요일'
					WHEN '3' THEN '화요일'
					WHEN '4' THEN '수요일'
					WHEN '5' THEN '목요일'
					WHEN '6' THEN '금요일'
					WHEN '7' THEN '토요일'
				END AS week_name,
				COUNT(*) AS total_cnt
			FROM statistics
			WHERE DATEDIFF(reg_date, '$search_sdate')>=0 AND DATEDIFF(reg_date, '$search_edate')<=0
			GROUP BY DAYOFWEEK(reg_date)
			ORDER BY week ASC
		";
	$rows = $db->getQuery($sql);

	$total_cnt = 0;
	for ($i=0; $i<count($rows);$i++) {
		$total_cnt += $rows[$i]['total_cnt'];
	}
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="search_box">
				<table>
					<colgroup>
						<col style="width:110px;">
						<col style="width:*;">
					</colgroup>
					<tbody>
						<tr>
							<th>검색설정</th>
							<td class="com">
								<form name="searchFrm" id="searchFrm">
								<div class="box">
									<div class="input_box date">
										<input type="text" name="search_sdate" id="search_sdate" value="<?=$search_sdate?>" readonly placeholder="통계기간 검색" />
									</div>
									<p class="dash">~</p>
									<div class="input_box date">
										<input type="text" name="search_edate" id="search_edate" value="<?=$search_edate?>" readonly placeholder="통계기간 검색" />
									</div>
									<a href="javascript:;" class="btn_search" onclick="searchGo()">검색</a>
								</div>
								</form>
							</td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="common_list">
				<nav class="list_category five">
					<ul>
						<li><span><a href="statistics_visit_day.php?page=<?=$page . $page_params?>">일별 방문자수</a></span></li>
						<li><span><a href="statistics_visit_hour.php?page=<?=$page . $page_params?>"><span>시간대별 방문자수</span></a></li>
						<li class="curr"><span><a href="statistics_visit_week.php?page=<?=$page . $page_params?>"><span>요일별 방문자수</span></a></li>
						<li><span><a href="statistics_visit_route.php?page=<?=$page . $page_params?>"><span>방문경로</span></a></li>
						<li><span><a href="statistics_visit_page.php?page=<?=$page . $page_params?>"><span>방문 리스트</span></a></li>
					</ul>
				</nav>
				<div class="list_header">
					<dl class="cnt">
						<dt>통계기간내 총 방문수</dt>
						<dd><?=formatNumbers($total_cnt)?>명</dd>
					</dl>
				</div>
				<table>
					<colgroup>
						<col width="15%" />
						<col width="*" />
					</colgroup>
					<thead>
						<tr>
							<th>요일</th>
							<th>방문자수</th>
						</tr>
					</thead>
					<tbody>
						<?
							for ($i=0; $i<count($rows);$i++) {
								if ($rows[$i]['total_cnt'] == 0) {
									$percent = 0;
									$width = 0;
								} else {
									$percent = number_format($rows[$i]['total_cnt'] / $total_cnt, 2) * 100;
									$width = 80 * ($percent / 100);
								}
						?>
								<tr>
									<td><?=$rows[$i]['week_name']?></td>
									<td class="left">
										<a href="javascript:;" class="btn_26 white ta_l" style="min-width:55px;width:<?=$width?>%"><?=formatNumbers($rows[$i]['total_cnt'])?>명 (<?=$percent?>%)</a>
									</td>
								</tr>
						<?
							}
						?>

						<?if (count($rows) == 0) {?>
							<tr>
								<td colspan="2">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			$("#search_sdate, #search_edate").datepicker();
		})

		//검색
		function searchGo() {
			$("#searchFrm").submit();
		}
	</script>
<?include("../inc/footer.php")?>