<?
    $admin_memo_list = $cls_admin_memo->memo_list($admin_memo_section, $admin_memo_gubun);
?>
<div class="mt50 mb20">
	<h3 class="g_title">관리자 메모</h3>
	<table class="g_table">
		<tbody>
			<tr>
				<td style="border-bottom:0">
					<div class="box">
						<div class="textarea_box">
							<textarea name="admin_memo" id="admin_memo" placeholder="관리자 메모는 등록 후 수정이 불가능합니다."></textarea>
						</div>
						<div style="text-align:right; margin:5px 0">
							<a href="javascript:;" class="btn_30" onclick="adminiMemoLogRegGo()">메모등록</a>
						</div>
					</div>
				</td>
			</tr>
		</tbody>
	</table>
	<div style="max-height:300px; overflow:auto;">
		<div class="common_list">
			<table style="border:1px solid #c3c3c3">
				<colgroup>
					<col width="60" />
					<col width="*" />
					<col width="120" />
					<col width="130" />
					<col width="130" />
					<col width="80" />
				</colgroup>
				<thead>
					<tr>
						<th>번호</th>
						<th>내용</th>
						<th>아이피</th>
						<th>등록자</th>
						<th>등록일</th>
						<th>관리</th>
					</tr>
				</thead>
				<tbody>
					<?for ($i=0; $i<count($admin_memo_list);$i++) {?>
						<tr>
							<td><?=count($admin_memo_list) - $i?></td>
							<td class="left"><?=textareaDecode($admin_memo_list[$i]['content'])?></td>
							<td><?=$admin_memo_list[$i]['reg_ip']?></td>
							<td><?=$admin_memo_list[$i]['writer']?></td>
							<td><?=formatDates($admin_memo_list[$i]['reg_date'], "Y.m.d H:i")?></td>
							<td>
								<?if ($admin_memo_list[$i]['reg_id'] == 'system') {?>
									<span class="fc_red">시스템</span>
								<?} else {?>
									<?if ($admin_memo_list[$i]['reg_id'] == $MEM_ADM['usr_id'] || $MEM_ADM['usr_gubun']==99) {?>
										<a href="javascript:;" class="btn_26 gray" onclick="adminMemoLogDelGo(<?=$admin_memo_list[$i]['idx']?>)">삭제</a>
									<?} else {?>
										-
									<?}?>
								<?}?>
							</td>
						</tr>
					<?}?>

					<?if (count($admin_memo_list) == 0) {?>
						<tr>
							<td colspan="6">등록된 로그내역이 없습니다.</td>
						</tr>
					<?}?>
				</tbody>
			</table>
		</div>
	</div>

	<script type="text/javascript">
		function adminiMemoLogRegGo() {
			var h = new clsJsHelper();

			if (!h.checkValNLen("admin_memo", 1, 500, "관리자메모", "N", "KO")) return false;

			AJ.callAjax("../common/admin_memo_log_proc.php", {
				"section":"<?=$admin_memo_section?>",
				"gubun": "<?=$admin_memo_gubun?>",
				"admin_memo": h.objVal("admin_memo")
			}, function(data){
				if (data.result == 200) {
					alert("메모가 등록 되었습니다.");
					location.reload();
				} else {
					alert(data.message);
				}
			});
		}

		function adminMemoLogDelGo(idx) {
			if (!confirm("선택하신 내역을 삭제하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

			AJ.callAjax("../common/admin_memo_log_proc.php", {
				"section":"<?=$admin_memo_section?>",
				"gubun": "<?=$admin_memo_gubun?>",
				"idx": idx
			}, function(data){
				if (data.result == 200) {
					alert("메모가 삭제 되었습니다.");
					location.reload();
				} else {
					alert(data.message);
				}
			});
		}
	</script>
</div>