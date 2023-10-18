<? include "../inc/config.php" ?>
<?
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['bbs_code']   = 'notice';
	$params['sch_cate']   = chkReqRpl("sch_cate", null, "", "", "INT");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
    $params['open_flag']  = "Y";
	$page_params = setPageParamsValue($params, "page,list_size,block_size,bbs_code,open_flag");

	$cls_board = new CLS_BOARD;

	//공지사항 목록 불러오기
	$notice_list = $cls_board->notice_list($params);

	//목록 불러오기
	$list = $cls_board->list($params, $total_cnt, $total_page);

	$pageNum = "0401";
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
					<li class="on"><a href="notice.php">공지사항</a></li>
					<li><a href="faq.php">자주하는 질문</a></li>
					<li><a href="inquiry.php">문의하기</a></li>
				</ul>
			</div>

			<div class="tbl_basic ty2">
				<table class="list">
					<caption>목록</caption>
					<colgroup>
						<col class="num hide-m">
						<col>
						<col class="count hide-m">
						<col class="day">
						<col class="count hide-m">
					</colgroup>
					<thead>
						<tr>
							<th class="hide-m">No</th>
							<th>제목</th>
							<th class="hide-m">파일</th>
							<th>날짜</th>
							<th class="hide-m">조회</th>
						</tr>
					</thead>
					<tbody>
                        <?for ($i=0; $i<count($notice_list);$i++) {?>
                            <tr>
                                <td class="hide-m"><span class="i-txt notice">공지</span></td>
                                <td class="subject"><a href="notice_view.php?page=<?=$params['page'] . $page_params?>&idx=<?=$notice_list[$i]['idx']?>"><span class="i-txt view-m notice">공지</span><?=$notice_list[$i]['title']?></a></td>
                                <td class="hide-m">
                                    <?if ($notice_list[$i]['up_file_1'] != "" || $notice_list[$i]['up_file_2'] != "") {?>
										<span class="file"><img src="../images/common/ico_file.png" alt="파일"></span>
									<?}?>
                                </td>
                                <td><?=formatDates($notice_list[$i]['reg_date'], "Y.m.d")?></td>
                                <td class="hide-m"><?=formatNumbers($notice_list[$i]['view_cnt'])?></td>
                            </tr>
                        <?}?>

                        <?for ($i=0; $i<count($list);$i++) {?>
                            <tr>
                                <td class="hide-m"><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
                                <td class="subject"><a href="notice_view.php?page=<?=$params['page'] . $page_params?>&idx=<?=$list[$i]['idx']?>"><?=$list[$i]['title']?></a></td>
                                <td class="hide-m">
                                    <?if ($list[$i]['up_file_1'] != "" || $list[$i]['up_file_2'] != "") {?>
										<span class="file"><img src="../images/common/ico_file.png" alt="파일"></span>
									<?}?>
                                </td>
                                <td><?=formatDates($list[$i]['reg_date'], "Y.m.d")?></td>
                                <td class="hide-m"><?=formatNumbers($list[$i]['view_cnt'])?></td>
                            </tr>
                        <?}?>

                        <?if (count($list) == 0) {?>
							<tr class="view-m">
								<td colspan="2">등록된 데이터가 없습니다.</td>
							</tr>
							<tr class="hide-m">
								<td colspan="5">등록된 데이터가 없습니다.</td>
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
	$(function(){
		$("#sch_word").keyup(function(){
			enters(function(){ searchGo(); });
		})
	})

	function searchGo() {
		var h = new clsJsHelper();

		if (h.objVal("sch_word")) {
			if (!h.checkValNLen("sch_word", 2, 20, "검색어", "N", "KO")) return false;
		}

		$("#searchFrm").submit();
	}
</script>

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>