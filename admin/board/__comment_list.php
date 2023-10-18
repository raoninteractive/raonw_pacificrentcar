<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	$params['bbs_idx']    = chkReqRpl("bbs_idx", null, "", "", "INT");
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;


	$cls_board = new CLS_BOARD;

	$comment_list = $cls_board->comment_list($params, $total_cnt, $total_page);
?>
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
			<?for ($i=0; $i<count($comment_list);$i++) {?>
				<tr>
					<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
					<td class="left"><?=textareaDecode($comment_list[$i]['comment'])?></td>
					<td><?=$comment_list[$i]['reg_ip']?></td>
					<td><?=$comment_list[$i]['reg_name']?></td>
					<td><?=formatDates($comment_list[$i]['reg_date'], "Y.m.d H:i:s")?></td>
					<td>
						<a href="javascript:;" class="btn_26 gray" onclick="commentDelGo(<?=$comment_list[$i]['idx']?>)">삭제</a>
					</td>
				</tr>
			<?}?>

			<?if (count($comment_list) == 0) {?>
				<tr>
					<td colspan="6">등록된 댓글이 없습니다.</td>
				</tr>
			<?}?>
		</tbody>
	</table>
</div>

<nav class="page_nate">
	<? adminScriptPaging($total_page, $params['block_size'], $params['page'], "commentList({page})") ?>
</nav>