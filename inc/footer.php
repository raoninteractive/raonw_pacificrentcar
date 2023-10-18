<?
	$db = new DB_HELPER;

	//공지사항 목록
	$sql = "SELECT idx, title, reg_date FROM board WHERE bbs_code='notice' AND open_flag='Y' AND del_flag='N' ORDER BY groups DESC, sort ASC LIMIT 5";
	$notice_list = $db->getQuery($sql);
?>
<footer id="footer" class="footer">
	<div class="foo_top">
		<div class="inr-c clearfix">
			<div class="lft">
				<!-- <h2 class="h2 f-gm">고객 <br>상담문의</h2>
				<div class="col">
					<p class="t">한국에서 전화하실 때 <br><em>(통화가능시간 : 오전 8시 ~ 오후 7시)</em><br><strong>070)7838-0130</strong></p>
					<p class="t">해외에서 전화하실 때 <br><em>(통화가능시간 : 오전 8시 ~ 오후 7시)</em><br><strong>070)747-0060</strong></p>
				</div> -->
				<h2 class="h2 f-gm mb0">선택관광</h2>
				<div class="icon1"><a href="/trip/trip.php"><img src="/images/common/ico_foo.png" alt=""></a></div>
				<div class="bat_kakao">
					<div class="t">
						<p class="h1 f-gm">카카오톡 문의상담</p>
						<p class="t1"><span>ID : pacificrent</span></p>
					</div>
					<p class="t2 f-gm">친구추가하고 간편하게 1:1 상담 받으세요!</p>
				</div>
			</div>
			<div class="rgh">
				<h2 class="h2 f-gm">예약대행 수수료 <br>입금전용 계좌</h2>
				<p class="t1"><span class="i-aft i_bank">아이피씨코리아</span></p>
				<p class="t2"><strong>110-485-440315</strong></p>
			</div>
		</div>
	</div>
	<div class="foo_notice">
		<div class="inr-c">
			<p class="h f-gm">공지사항</p>
			<div class="lst">
				<ul>
                    <?for ($i=0; $i<count($notice_list); $i++) {?>
					    <li><a href="/notice/notice_view.php?page=1&idx=<?=$notice_list[$i]['idx']?>"><span class="t"><?=$notice_list[$i]['title']?></span><span class="d"><?=formatDates($notice_list[$i]['reg_date'], 'Y.m.d')?></span></a></li>
                    <?}?>
				</ul>
			</div>
		</div>
	</div>
	<div class="foo_cont f-gm">
		<div class="inr-c">
			<div class="link">
				<ul class="">
					<li><a href="/customer/terms.php">이용약관</a></li>
					<li><a href="/customer/privacy.php">개인정보처리방침</a></li>
					<li><a href="/used/used.php">이용안내</a></li>
					<li><a href="/notice/notice.php">공지사항</a></li>
					<li><a href="/notice/faq.php">자주하는 질문</a></li>
				</ul>
			</div>
			<div class="txt">
				<p><strong>아이피씨코리아(IPC KOREA)</strong></p>
				<p><span>대표자 원종식</span><span>사업자등록번호 : 132-75-00061</span><span>통신판매업신고 : 제2021-서울서대문-1148호</span></p>
				<p><span>공제영업보증보험: 제100-000-2021-0371-6103호</span><span>개인정보관리책임자 : 원종식</span></p>
				<p><span>주소 : 서울시 서대문구 서소문로27, 3층 (충정로3가. 충정리시온)</span><span>TEL : 02-745-8163</span></p>
				<p class="copy">Copyright &copy; IPC KOREA All Right Reserved</p>
			</div>
		</div>
	</div>
	<button type="button" class="scroll_top"><span><img src="/images/common/ico_scrolltop.png" alt="맨위로"></span></button>
</footer>