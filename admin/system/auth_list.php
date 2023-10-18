<?include("../inc/config.php")?>
<?
	$pageNum = "9902";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	//권한설정 목록 불러오기
	$list = $cls_set_menu->admin_auth_list();
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_list">
				<table>
					<colgroup>
						<col width="80" />
						<col width="150" />
						<col />
						<col width="80" />
						<col width="120" />
						<col width="80" />
					</colgroup>
					<thead>
						<tr>
							<th>등급코드</th>
							<th>권한등급명</th>
							<th>권한설명</th>
							<th>상태</th>
							<th>수정일</th>
							<th>관리</th>
						</tr>
					</thead>
					<tbody>
						<?for ($i=0; $i<count($list);$i++) {?>
							<tr>
								<td><?=$list[$i]['gubun']?></td>
								<td><?=$list[$i]['title']?></td>
								<td class="left"><?=$list[$i]['description']?></td>
								<td><?=$list[$i]['status_name']?></td>
								<td><?=formatDates($list[$i]['upt_date'], "Y.m.d H:i")?></td>
								<td>
									<div class="btn">
										<a href="auth_write.php?gubun=<?=$list[$i]['gubun']?>" class="btn_26 white">수정</a>
									</div>
								</td>
							</tr>
						<?}?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		//검색
		function searchGo() {
			if (h.objVal("sch_word")) {
				if (!h.checkValNLen("sch_word", 2, 20, "검색어", "N", "KO")) return false;
			}

			$("#searchFrm").submit();
		}
	</script>
<?include("../inc/footer.php")?>