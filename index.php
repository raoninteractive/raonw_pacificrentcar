<? include("inc/config.php") ?>
<?
	$cls_pop = new CLS_SETTING_POPUP;

	$db = new DB_HELPER;

	//메인 차종 불러오기
	$sql = "
			SELECT * FROM (
				SELECT
					idx, category, title, up_file_1, day1_amt, keyword, option_1, option_2, main_sort,
					(
						SELECT COUNT(idx) FROM booking WHERE status IN ('10', '20', '30', '40') AND del_flag='N'
					) AS popular_cnt,
					(
						SELECT IFNULL(SUM(stock_cnt),0) FROM goods_stock WHERE goods_idx=a.idx AND TIMESTAMPDIFF(DAY, DATE_FORMAT(sdate,'%Y-%m-%d'), CURDATE()) <= 0 AND del_flag='N'
					) AS rest_stock_cnt
				FROM goods a
				WHERE category='C001' AND open_flag='Y' AND total_stock_cnt > 0 AND main_open_flag='Y' AND del_flag='N'
			) t
			WHERE rest_stock_cnt > 0
			ORDER BY main_sort DESC, popular_cnt DESC, idx DESC
			LIMIT 8
		";
	$goods_list1 = $db->getQuery($sql);


	//빠른 예약하기 차종 불러오기
	$sql = "
			SELECT * FROM (
				SELECT
					idx, category, title, up_file_1, day1_amt, keyword, sort,
					(
						SELECT COUNT(idx) FROM booking WHERE status IN ('10', '20', '30', '40') AND del_flag='N'
					) AS popular_cnt,
					(
						SELECT IFNULL(SUM(stock_cnt),0) FROM goods_stock WHERE goods_idx=a.idx AND TIMESTAMPDIFF(DAY, DATE_FORMAT(sdate,'%Y-%m-%d'), CURDATE()) <= 0 AND del_flag='N'
					) AS rest_stock_cnt
				FROM goods a
				WHERE category='C001' AND open_flag='Y' AND total_stock_cnt > 0 AND del_flag='N'
			) t
			ORDER BY sort DESC, popular_cnt DESC, idx DESC
		";
	$quick_list = $db->getQuery($sql);


	//팝업 목록 불러오기
	$pop_list = $cls_pop->popup_list('Y');

	$pageNum = "0000";
	$pageName = "메인";
?>
<? include "inc/top.php" ?>
<? include "inc/header.php" ?>

<link href="css/owl.carousel.min.css" rel="stylesheet">
<script src="js/owl.carousel.min.js"></script>

<div id="container" class="container">
	<div class="bxMain">
		<div class="owl-carousel">
			<div class="item"><img src="images/main/img_slider1.jpg" alt=""></span></div>
			<div class="item"><img src="images/main/img_slider2.jpg" alt=""></span></div>
		</div>

		<div class="inr-c">
			<div class="bat_kakao">
				<div class="t">
					<p class="h1 f-gm">카카오톡 문의상담</p>
					<p class="t1"><span>ID : pacificrent</span></p>
				</div>
				<p class="t2 f-gm">친구추가하고 간편하게 1:1 상담 받으세요!</p>
			</div>
		</div>
	</div><!--//bxMain -->

	<section class="area_main1">
		<div class="inr-c">
			<header class="hd_tit1">
				<h2 class="h">퍼시픽 렌트카의 <span class="c-color">다양한 혜택정보</span></h2>
			</header>

			<div class="lst_main1">
                <ul class="ty1">
					<li>
						<div class="icon"><span class="i-set m1_6"></span></div>
						<div>
							<p class="h1">공항 픽업 및 반납</p>
							<p class="t1">24시간 괌 국제 공항 주차장에서 차량 픽업 및 반납이 가능 합니다.</p>
						</div>
					</li>
					<li>
						<div class="icon"><span class="i-set m1_7"></span></div>
						<div>
							<p class="h1">호텔 픽업 및 반납</p>
							<p class="t1">차량 픽업 시 호텔로비에서 직원 미팅 후 사무실로 이동( 약 5~7분) 후 차량 인수 (결제 진행) <br>
							호텔반납은 선택 하신 호텔 주차장에 직접 주차 반납이 가능 (별도 직원 미팅 없습니다) </p>
						</div>
					</li>
				</ul>
				<ul class="ty2">
					<li>
						<div class="icon"><span class="i-set m1_1"></span></div>
						<p class="t1">차량사고 본인부담금 <br>한도보험(CDW)포함</p>
					</li>
					<li>
						<div class="icon"><span class="i-set m1_2"></span></div>
						<p class="t1">차량사고 시 <br>개인상해보험(PAI)포함</p>
					</li>
					<li>
						<div class="icon"><span class="i-set m1_3"></span></div>
						<p class="t1">운전자 1명 <br>추가기능</p>
					</li>
					<li>
						<div class="icon"><span class="i-set m1_4"></span></div>
						<p class="t1">카시트 2개 <br>무료제공</p>
					</li>
					<li>
						<div class="icon"><span class="i-set m1_5"></span></div>
						<p class="t1">아이스박스 <br>무료제공</p>
					</li>
				</ul>
			</div>
		</div>

		<div class="area_rent">
			<div class="inr-c">
				<h2 class="tit f-gm"><span><em>빠른 <br>예약하기</em><i></i></span></h2>
                <form name="quickFrm" id="quickFrm" method="get" action="/reservation/reservation.php">
				<div class="lst">
					<div class="n1">
						<label for="sel1">렌트날짜</label>
						<input type="text" name="rental_date" id="quick_rental_date" class="inp_txt calender datepicker2" placeholder="날짜선택" readonly>
						<select name="rental_hour" id="quick_rental_hour" class="select1">
							<option value="">시간선택</option>
                            <?for ($i=0; $i<=23; $i++) {?>
                                <option value="<?=addZero($i)?>"><?=addZero($i)?>시</option>
                            <?}?>
						</select>
					</div>
					<div class="n2">
						<label for="sel2">차종선택</label>
						<select name="goods_idx" id="quick_goods" class="select1 w100p">
							<option value="">차종을 선택해 주세요.</option>
                            <?for ($i=0; $i<count($quick_list); $i++) {?>
                                <option value="<?=$quick_list[$i]['idx']?>"><?=$quick_list[$i]['title']?></option>
                            <?}?>
						</select>
					</div>
					<div class="n2">
						<label for="sel2">대여기간</label>
						<select name="rental_day" id="quick_rental_day" class="select1 w100p">
                            <option value="">대여기간 선택</option>
                            <?for ($i=1; $i<=30; $i++) {?>
                                <option value="<?=addZero($i)?>"><?=addZero($i)?>일</option>
                            <?}?>
						</select>
					</div>
				</div>
                </form>
				<button type="button" class="btn-pk orange b f-gm" onclick="quickBookingGo()"><span>빠른 <br>예약신청</span></button>
			</div>
		</div>
	</section>

	<section class="area_main2">
		<div class="inr-c">
			<div class="lst_car1">
				<ul class="n4">
                    <?for ($i=0; $i<count($goods_list1); $i++) {?>
                        <li>
                            <a href="javascript:;" onclick="goodsView(<?=$goods_list1[$i]['idx']?>);">
                                <div class="img"><img src="<?=filePathCheck("/upload/goods/thumb/".getUpfileName($goods_list1[$i]['up_file_1']))?>" alt="사진"></div>
                                <div class="txt">
                                    <p class="h1">
                                        <!-- <span>괌(GUAM)</span> -->
                                        <span class="tit f-gm"><?=$goods_list1[$i]['title']?></span>
                                    </p>
                                    <div class="bat">
                                        <?if ($goods_list1[$i]['keyword'] != '') {?>
                                            <span class="i-txt"><?=implode('</span><span class="i-txt">',explode(',', $goods_list1[$i]['keyword']))?></span>
                                        <?} else {?>
                                            <span class="i-txt" style="background:none">&nbsp;</span>
                                        <?}?>
                                    </div>

                                    <!-- <div class="bat">
                                        <?if ($goods_list1[$i]['option_1'] == 'Y') {?>
                                            <span><img src="/images/common/ico_bat_car1.png" alt="주유포함"></span>
                                        <?}?>
                                        <?if ($goods_list1[$i]['option_2'] == 'Y') {?>
                                            <span><img src="/images/common/ico_bat_car2.png" alt="CDW포함"></span>
                                        <?}?>
                                        <?if ($goods_list1[$i]['option_7'] == 'Y') {?>
                                            <span><img src="/images/common/ico_bat_car3.png" alt="ZDC포함"></span>
                                        <?}?>
                                        <?if ($goods_list1[$i]['option_8'] == 'Y') {?>
                                            <span><img src="/images/common/ico_bat_car4.png" alt="PAI포함"></span>
                                        <?}?>
                                    </div> -->

                                    <button type="button" class="btn-pk b color f-gm"><span><strong>$ <?=$goods_list1[$i]['day1_amt']?></strong> / 24시간</span></button>
                                </div>
                            </a>
                            <?if ($goods_list1[$i]['rest_stock_cnt'] >= 1) {?>
                                <a href="/reservation/reservation.php?goods_idx=<?=$goods_list1[$i]['idx']?>" class="btn-pk b orange f-gm w100p mt10"><span>예약</span></a>
                            <?} else {?>
                                <a href="javascript:alert('예약이 불가능한 상품입니다.')" class="btn-pk b gray f-gm w100p mt10"><span>예약 불가</span></a>
                            <?}?>
                        </li>
                    <?}?>
				</ul>
			</div>
		</div>
	</section>

	<section class="area_main3">
		<div class="inr-c">
			<div class="tbl">
				<div class="img"><span><img src="images/main/img_area4.png" alt="사진"></span></div>
				<div class="txt">
					<p class="t1"><strong>PACIFIC RENTALS</strong>는</p>
					<p class="t2">외국 해외 체인의 괌 렌터카 지사이며, <strong class="c-red">렌터카 300대를 준비 하여</strong> 한국 관광객들의 편의를 제공하고자
						한국인 관광객 전용 렌터카 회사의 별도 법인을 설립한 렌터카 전문 회사 입니다.
						기존 다양한 나라의 여행객분들에게 다양하고 합리적인 안전한 차량을 제공을 하며
						성장하고 있으며, 한국인 관광객분들을 위한 전용 회사 설립을 통해
						한국인 관광객분들에게 다양한 서비스로 보답을 드리고자 합니다.
						<br>
						<br>다량의 렌터카를 확보하고 있으며 한국인분들을 위해 괌 현지에서
						한국인 직원과 24시간 상담이 가능하며 그 외 다양한 서비스로
						최고의 안전한 괌 여행이 될 수 있도록 노력하겠습니다
						<br>
						<br>감사합니다
					</p>
				</div>
			</div>
		</div>
	</section>

	<section class="area_main4">
		<div class="inr-c">
			<div class="lst_main4">
				<ul>
					<!-- <li>
						<div class="icon"><span class="i-set m4_1"></span></div>
						<p class="h1 f-gm">공항 픽업 및 반납</p>
						<p class="t1">괌 국제 공항 주차장에서 <br>차량 픽업 및 반납이 가능 합니다. </p>
					</li>
					<li>
						<div class="icon"><span class="i-set m4_2"></span></div>
						<p class="h1 f-gm">호텔 픽업 및 반납</p>
						<p class="t1">별도 렌터카 사무실 이동 없이 숙박 하시는 호텔로 차를 직접 가지고 가며,  반납 또한 숙박 하시는 호텔 주차장에 주차로 반납이 가능 합니다 .</p>
					</li> -->
					<li>
						<div class="icon"><span class="i-set m4_3"></span></div>
						<p class="h1 f-gm">다양한 차량 확보</p>
						<p class="t1">고객분들은 다양한 차량을 경험해 보실 수 있으며 타사 보다 많은 차량으로 안전하게 원하시는 날짜에 차량 예약이 가능 합니다.</p>
					</li>
					<li>
						<div class="icon"><span class="i-set m4_4"></span></div>
						<p class="h1 f-gm">차량 정기 관리 및 소독</p>
						<p class="t1">렌터카 내에 전문 관리팀의 차량 관리 및 주기적인 차량 내부 방역으로 안전하게 이용이 가능 합니다.</p>
					</li>
					<li>
						<div class="icon"><span class="i-set m4_5"></span></div>
						<p class="h1 f-gm">무료 혜택</p>
						<p class="t1">아이스 박스 제공, 추가 운전자 무료 등 다양한 혜택을 드립니다.</p>
					</li>
					<li>
						<div class="icon"><span class="i-set m4_6"></span></div>
						<p class="h1 f-gm">괌 현지 도착 후 <br>비상 연락처 한국인 상담</p>
						<p class="t1">차량이용, 사고 등으로 렌터카 사용 상담이 필요 할 시 한국인 전담 직원과 카톡 등을 통한 24시간 연락으로 빠른 서비스를 받으실 수 있습니다. </p>
					</li>
				</ul>
			</div>
		</div>
	</section>
</div><!--//container -->

<!-- 팝업 -->
<div id="popCarview" class="layerPopup pop_carview"></div>

<div class="wrap_mainPopup">
	<?for ($i=0; $i<count($pop_list); $i++) {?>
		<?
			$img_pc = $pop_list[$i]['up_file_1'];
			$img_mo = $pop_list[$i]['up_file_2'];
			if (chkBlank($img_mo)) $img_mo = $img_pc;

			$link_pc = $pop_list[$i]['link_pc'];
			$link_mo = $pop_list[$i]['link_mobile'];
			if (chkBlank($link_mo)) $link_mo = $link_pc;

			$target_pc = $pop_list[$i]['target_pc'];
			$target_mo = $pop_list[$i]['target_mobile'];
			if (chkBlank($target_mo)) $target_mo = $target_pc;

			$img    = iif(returnMobileCheck() == false, $img_pc, $img_mo);
			$link   = iif(returnMobileCheck() == false, $link_pc, $link_mo);
			$target = iif(returnMobileCheck() == false, $target_pc, $target_mo);
		?>
		<div id="mainPopup<?=$i?>" class="mainPopup">
			<div class="info">
				<?if ($link != '') {?>
					<a href="<?=$link?>" target="<?=$target?>"><img src="/upload/popup/<?=getUpfileName($img)?>"></a>
				<?} else {?>
					<img src="/upload/popup/<?=getUpfileName($img)?>">
				<?}?>
			</div>
			<div class="botm">
				<label class="inp_checkbox"><input type="checkbox" id="todayPopupHide<?=$i?>"><span>오늘 하루 안 보기</span></label>
				<button type="button" onclick="closePopup(this);">닫기</button>
			</div>
		</div>
	<?}?>
</div>

<script src="/js/popup.js"></script>
<script>
    $(function(){
		var pop_cnt = <?=count($pop_list)?>

		//팝업쿠키
		for(i=0; i<pop_cnt; i++){
			if(getCookie("notToday"+i)!="Y"){
				$("#mainPopup"+i).css("display","inline-block");
			} else {
				$("#mainPopup"+i).css("display","none");
			}
		}

        //슬라이드
        var mainSlider = $(".bxMain .owl-carousel");
        mainSlider.owlCarousel({
            loop:true,
            margin:0,
            nav:false,
            dots:false,
            items:1,
            //dotsContainer : ".owl-dot-botm .owl-dots",
            smartSpeed:1500,
            autoplay:true,
            autoplayTimeout:3000,
            autoplayHoverPause:false,
            mouseDrag: false
        });

		$('.datepicker2').datepicker("destroy");
		$('.datepicker2').datepicker({
            minDate: "<?=dateAdd("day", 1, date('Y-m-d'))?>",
            maxDate: "<?=dateAdd("yyyy", 1, date('Y-m-d'))?>",
			dateFormat: "yy-mm-dd",
			showOn: "both",
			buttonImage: "/images/common/ico_select.png"
		});
    });

	//빠른예약
	function quickBookingGo() {
		var h = new clsJsHelper();

		if (!h.checkSelect("quick_rental_date", "렌트날짜")) return false;
		if (!h.checkSelect("quick_rental_hour", "렌트시간")) return false;
		if (!h.checkSelect("quick_goods", "차종")) return false;
		if (!h.checkSelect("quick_rental_day", "대여기간")) return false;


		$("#quickFrm").submit();
	}

    function goodsView(goods_idx) {
		AJ.callAjax("/reservation/rent_view.php", {"goods_idx": goods_idx}, function(data){
            $("#popCarview").html(data)
            openLayerPopup('popCarview');
		}, "html");
    }
</script>

<?include("inc/footer.php")?>
<?include("inc/bottom.php")?>