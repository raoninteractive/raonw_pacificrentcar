<table>
	<colgroup>
		<col width="70" />
		<?if ($is_category) {?>
			<col width="200" />
		<?}?>
		<col width="*" />
		<?if ($is_comment) {?>
			<col width="80" />
		<?}?>
		<col width="80" />
		<col width="120" />
		<col width="120" />
		<col width="80" />
	</colgroup>
	<thead>
		<tr>
			<th>번호</th>
			<?if ($is_category) {?>
				<th>카테고리</th>
			<?}?>
			<th>제목</th>
			<?if ($is_comment) {?>
				<th>댓글수</th>
			<?}?>
			<th>조회수</th>
			<th>작성자</th>
			<th>등록일</th>
			<th>관리</th>
		</tr>
	</thead>
	<tbody>
		<?for ($i=0; $i<count($notice_list);$i++) {?>
			<tr>
				<td>공지</td>
				<?if ($is_category) {?>
					<td><?=$cls_board->category_name($notice_list[$i]['category'])?></td>
				<?}?>
				<td class="left"><a href="write.php?page=<?=$params['page'] . $page_params?>&idx=<?=$notice_list[$i]['idx']?>" class="a_link"><?=$notice_list[$i]['title']?></a></td>
				<?if ($is_comment) {?>
					<td><?=formatNumbers($notice_list[$i]['comment_cnt'])?></td>
				<?}?>
				<td><?=formatNumbers($notice_list[$i]['view_cnt'])?></td>
				<td><?=$notice_list[$i]['writer']?></td>
				<td><?=formatDates($notice_list[$i]['reg_date'], "Y.m.d H:i")?></td>
				<td>
					<a href="javascript:;" class="btn_26 red" onclick="deleteGo(<?=$notice_list[$i]['idx']?>);">삭제</a>
				</td>
			</tr>
		<?}?>

		<?for ($i=0; $i<count($list);$i++) {?>
			<tr>
				<td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
				<?if ($is_category) {?>
					<td>
						<?
							$tmp_cate = $cls_board->parent_category_list($list[$i]['category']);
							for ($k=0; $k<count($tmp_cate); $k++) {
								if ($k > 0) echo " &gt ";
								echo $tmp_cate[$k]['category_name'];
							}
						?>
					</td>
				<?}?>
				<td class="left">
					<a href="write.php?page=<?=$params['page'] . $page_params?>&idx=<?=$list[$i]['idx']?>" class="a_link"><?=$list[$i]['title']?></a>
				</td>
				<?if ($is_comment) {?>
					<td><?=formatNumbers($list[$i]['comment_cnt'])?></td>
				<?}?>
				<td><?=formatNumbers($list[$i]['view_cnt'])?></td>
				<td><?=$list[$i]['writer']?></td>
				<td><?=formatDates($list[$i]['reg_date'], "Y.m.d H:i")?></td>
				<td>
					<a href="javascript:;" class="btn_26 red" onclick="deleteGo(<?=$list[$i]['idx']?>);">삭제</a>
				</td>
			</tr>
		<?}?>

		<?if (count($list) == 0) {?>
			<tr>
				<td colspan="<?=iif($is_category, iif($is_comment, 8, 7), iif($is_comment, 7, 6))?>">등록된 데이터가 없습니다.</td>
			</tr>
		<?}?>
	</tbody>
</table>