<? include "../inc/config.php" ?>
<?
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['bbs_code']   = 'faq';
	$params['sch_cate']   = chkReqRpl("sch_cate", null, "", "", "INT");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
	$params['open_flag']  = "Y";
	$page_params = setPageParamsValue($params, "page,list_size,block_size,open_flag");

	$cls_board = new CLS_BOARD;

	//에이터 사용여부
	$is_html = $cls_board->isHtml($params['bbs_code']);

	//목록 불러오기
	$list = $cls_board->list($params, $total_cnt, $total_page);

	//카테고리 불러오기
	$category_list = $cls_board->category_list($params['bbs_code'], 1);

	$pageNum = "0402";
	$pageName = "고객센터";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
    <? include "../inc/spot.php" ?>

	<div class="contents wrap_notice">
		<div class="inr-c">
			<div class="tab ty1 bd">
				<ul>
					<li><a href="notice.php">공지사항</a></li>
					<li class="on"><a href="faq.php">자주하는 질문</a></li>
					<li><a href="inquiry.php">문의하기</a></li>
				</ul>
			</div>

			<div class="tab ty2">
				<ul>
					<li <?if (chkBlank($params['sch_cate'])) {?>class="on"<?}?>><a href="?sch_cate=">전체</a></li>
                    <?for ($i=0; $i<count($category_list); $i++) {?>
					    <li <?if ($params['sch_cate']==$category_list[$i]['category_idx']) {?>class="on"<?}?>><a href="?sch_cate=<?=$category_list[$i]['category_idx']?>"><?=$category_list[$i]['category_name']?></a></li>
					<?}?>
				</ul>
			</div>
			<div class="tbl_basic ty2 tbl_faq">
				<table class="list">
					<colgroup>
						<col class="trq">
						<col>
					</colgroup>
					<tbody>
                        <?for ($i=0; $i<count($list);$i++) {?>
                            <tr class="tr_q">
                                <td><span class="q f-gm">Q</span></td>
                                <td class="subject"><?=$list[$i]['title']?></td>
                            </tr>
                            <tr class="tr_a">
                                <td><span class="a f-gm">A</span></td>
                                <td class="ta-l">
                                    <?
										if ($is_html) {
											echo htmlDecode($list[$i]['content']);
										} else {
											echo textareaDecode($list[$i]['content']);
										}
									?>
                                </td>
                            </tr>
                        <?}?>

						<?if (count($list) == 0) {?>
							<tr>
								<td colspan="2">등록된 데이터가 없습니다.</td>
							</tr>
						<?}?>
					</tbody>
				</table>
			</div>

            <form name="searchFrm" id="searchFrm" method="get">
			<div class="tbl_sch">
                <select name="sch_type" id="sch_type" class="select1">
                    <option value="1" <?=chkCompare($params['sch_type'], 1, "selected")?>>제목</option>
                    <option value="2" <?=chkCompare($params['sch_type'], 2, "selected")?>>내용</option>
                </select>
				<input type="text" name="sch_word" id="sch_word" value="<?=$params['sch_word']?>" class="inp_txt" placeholder="검색">
				<button type="button" class="btn-pk n orange" onclick="searchGo()"><span>검색</span></button>
			</div>
            </form>

			<div class="pagenation">
                <? frontPaging($total_page, $params['block_size'], $params['page'], $page_params, "") ?>
			</div>
		</div>
	</div>
</div><!--//container -->

<script>
    tab(".tab.ty2",1);
</script>

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>