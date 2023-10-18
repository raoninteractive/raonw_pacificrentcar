<header id="header" class="header">
	<div class="inr-c">
		<h1 class="logo"><a href="/"><img src="/images/common/logo.png" alt="퍼시픽렌터카" ></a></h1>

		<button type="button" class="btn_gnb"><span>모바일 메뉴 열기</span></button>
		<div class="rgh">
			<a href="/mypage/mypage.php" class="btn-pk nb green"><span>나의 예약정보</span></a>
		</div>
		<div class="gnbbox">
			<nav id="gnb" class="gnb">
				<ul class="menu">
					<li class="g1 <?if (left($pageNum,2) == '01') {?>on<?}?>">
						<a href="/reservation/rent.php"><span>전체차량 및 예약</span></a>
					</li>
					<li class="g2 <?if (left($pageNum,2) == '02') {?>on<?}?>">
						<a href="/used/used.php"><span>이용안내</span></a>
					</li>
					<li class="g3 <?if (left($pageNum,2) == '03') {?>on<?}?>">
						<a href="/trip/trip.php"><span>선택관광</span></a>
					</li>
					<li class="g4 <?if (left($pageNum,2) == '04') {?>on<?}?>">
						<a href="/notice/notice.php"><span>고객센터</span></a>
					</li>
				</ul>
			</nav>
		</div>
	</div>
</header><!-- //header -->