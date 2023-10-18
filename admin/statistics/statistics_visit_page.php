<?include("../inc/config.php")?>
<?
	$pageNum = "9001";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 20;
	$params['block_size'] = 10;
	$params['sch_sdate']  = chkReqRpl("sch_sdate", dateAdd("m", -1, date("Y-m-d")), 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", date("Y-m-d"), 10, "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

	$db = new DB_HELPER;
	$sql = "
			SELECT
				*
			FROM statistics
			WHERE TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_sdate'] ."') <= 0 AND TIMESTAMPDIFF(DAY, DATE_FORMAT(reg_date,'%Y-%m-%d'), '". $params['sch_edate'] ."') >= 0
			ORDER BY idx DESC
		";
	$rows = $db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
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
										<input type="text" name="sch_sdate" id="sch_sdate" value="<?=$params['sch_sdate']?>" readonly placeholder="통계기간 검색" />
									</div>
									<p class="dash">~</p>
									<div class="input_box date">
										<input type="text" name="sch_edate" id="sch_edate" value="<?=$params['sch_edate']?>" readonly placeholder="통계기간 검색" />
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
						<li><span><a href="statistics_visit_day.php?page=1<?=$page_params?>">일별 방문자수</a></span></li>
						<li><span><a href="statistics_visit_hour.php?page=1<?=$page_params?>"><span>시간대별 방문자수</span></a></li>
						<li><span><a href="statistics_visit_week.php?page=1<?=$page_params?>"><span>요일별 방문자수</span></a></li>
						<li><span><a href="statistics_visit_route.php?page=1<?=$page_params?>"><span>방문경로</span></a></li>
						<li class="curr"><span><a href="statistics_visit_page.php?page=1<?=$page_params?>"><span>방문 리스트</span></a></li>
					</ul>
				</nav>
				<div class="list_header">
					<dl class="cnt">
						<dt>Total</dt>
						<dd><?=formatNumbers($total_cnt)?></dd>
					</dl>
				</div>
				<table>
					<colgroup>
						<col width="70" />
						<col width="150" />
						<col width="*" />
						<col width="150" />
						<col width="150" />
					</colgroup>
					<thead>
						<tr>
							<th>번호</th>
							<th>접속아이피</th>
							<th>접속한 페이지</th>
							<th>사용 브라우저</th>
							<th>시간</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($rows);$i++) {?>
							<tr>
								<td><?=formatNumbers($total_cnt-(($page-1)*$list_size)-$i)?></td>
								<td><?=$rows[$i]['ip']?></td>
								<td class="left"><?=$rows[$i]['page_url']?></td>
								<td>
									<?
										$agent = $rows[$i]['agent'];
										$agent_name = "";
										if (strrpos(strtolower($agent),"edge")) {
											$agent_name = "EDGE";
										} else if (strrpos(strtolower($agent),"rv:11")) {
											$agent_name = "익스플로우 11.0";
										} else if (strrpos(strtolower($agent),"msie 10")) {
											$agent_name = "익스플로우 10.0";
										} else if (strrpos(strtolower($agent),"msie 9")) {
											$agent_name = "익스플로우 9.0";
										} else if (strrpos(strtolower($agent),"msie 8")) {
											$agent_name = "익스플로우 8.0";
										} else if (
											strrpos(strtolower($agent),"android") ||
											strrpos(strtolower($agent),"iphone") ||
											strrpos(strtolower($agent),"ipod") ||
											strrpos(strtolower($agent),"blackBerry") ||
											strrpos(strtolower($agent),"windows ce") ||
											strrpos(strtolower($agent),"lg") ||
											strrpos(strtolower($agent),"mot") ||
											strrpos(strtolower($agent),"samsung") ||
											strrpos(strtolower($agent),"sonyericsson")
										) {
											$agent_name = "모바일";
										} else if (strrpos(strtolower($agent),"chrome")) {
											$agent_name = "크 롬";
										} else if (strrpos(strtolower($agent),"firefox")) {
											$agent_name = "사파리";
										} else {
											$agent_name = "기타";
										}

										echo $agent_name;
									?>
								</td>
								<td><?=formatDates($rows[$i]['reg_date'],"Y.m.d H:i:s")?></td>
							</tr>
						<?}?>

						<?if (count($rows) == 0) {?>
							<tr>
								<td colspan="5">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>

				<nav class="page_nate">
					<? adminPaging($total_page, $params['block_size'], $params['page'], $page_params, "") ?>
				</nav>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			$("#sch_sdate, #sch_edate").datepicker();
		})

		//검색
		function searchGo() {
			$("#searchFrm").submit();
		}
	</script>
<?include("../inc/footer.php")?>