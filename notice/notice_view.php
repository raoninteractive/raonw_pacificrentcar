<? include "../inc/config.php" ?>
<?
	$params['idx']        = chkReqRpl("idx", null, "", "", "INT");
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['bbs_code']   = 'notice';
	$params['sch_cate']   = chkReqRpl("sch_cate", null, "", "", "INT");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "GET", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 20, "GET", "STR");
    $params['open_flag']  = "Y";
	$page_params          = setPageParamsValue($params, "page,list_size,block_size,bbs_code,open_flag");

	$cls_board = new CLS_BOARD;

	$view = $cls_board->view($params);
	if ($view == false) fnMsgGo(501, "일치하는 게시글 정보가 없습니다.", "BACK", "");

	//에이터 사용여부
	$is_html = $cls_board->isHtml($params['bbs_code']);

	if ($is_html) {
		$view['content'] = htmlDecode($view['content']);
	} else {
		$view['content'] = textareaDecode($view['content']);
	}

	//조회수 업데이트
	$cls_board->view_check($params['idx']);

	//이전글
	$view_prev = $cls_board->view_prev_next($params, 'prev', $view['notice_flag']);

	//다음글
	$view_next = $cls_board->view_prev_next($params, 'next', $view['notice_flag']);

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

			<div class="bbs_view">
				<div class="tit">
					<p class="h1"><span><?=$view['title']?></span></p>
					<p class="t1"><span>등록일: <?=formatDates($view['reg_date'], "Y.m.d")?></span><span>조회수: <?=formatNumbers($view['view_cnt'])?></span></p>
				</div>
				<div class="cont">
					<div class="t"><?=$view['content']?></div>

                    <?if ($view['up_file_1'] != "" || $view['up_file_2'] != "") {?>
                        <div class="botm">
                            <?for ($i=1; $i<=2; $i++) {?>
                                <?if ($view['up_file_'.$i] != '') {?>
                                    <p class="t1 <?if ($i>1){?>mt10<?}?>">
                                        <span>첨부파일</span> <a href="/module/board/file_down.php?idx=<?=$params['idx']?>&fnum=<?=$i?>" class="c-color"><?=getUpfileOriName($view['up_file_'.$i])?></a>
                                    </p>
                                <?}?>
                            <?}?>
                        </div>
                    <?}?>
				</div>
			</div>

			<div class="btn-bot ta-c">
				<a href="notice.php?page=<?=$params['page'] . $page_params?>" class="btn-pk b orange f-gm mw100p">목록으로</a>
			</div>
		</div>
	</div>
</div><!--//container -->

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>