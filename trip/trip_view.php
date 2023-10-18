<? include "../inc/config.php" ?>
<?
    $params['goods_idx']  = chkReqRpl("goods_idx", null, "", "", "INT");

    $cls_goods = new CLS_GOODS;

    $view = $cls_goods->choice_tour_view($params['goods_idx'], 'Y');
    if ($view == false) fnMsgGo(501, "일치하는 상품정보가 없습니다.", "BACK", "");

	//예약설정 정보 불러오기
	$setview = getBookingSettingInfoView('C001');

	//픽업 장소 목록
	$pickup_area_list = $setview['choice_pickup'];

	$pageNum = "0302";
	$pageName = "선택관광";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
    <? include "../inc/spot.php" ?>

	<div class="contents wrap_trip">
		<div class="inr-c">
			<div class="hd_tit1">
				<h2 class="h2"><?=$view['title']?></h2>
                <span class="t c-gray">(<?=formatDates($view['sdate'], 'Y.m.d')?> ~ <?=formatDates($view['edate'], 'Y.m.d')?>)</span>
			</div>

			<div class="bbs_view">
                <form name="tourFrm" id="tourFrm" method="post" action="reservation.php">
                <input type="hidden" name="goods_idx" value="<?=$params['goods_idx']?>" />
				<div class="top pr-mb1">
					<div class="img"><span style="background-image: url('/upload/choice_tour/list/<?=getUpfileName($view['up_file_1'])?>');"></span></div>
					<div class="tbl">
						<div class="tbl_basic">
							<table class="write">
								<caption>상세</caption>
								<tbody>
									<tr>
										<th><span class="c-orange">*</span>투어 날짜</th>
										<td><input type="text" name="tour_date" id="tour_date" class="inp_txt calender wid1" readonly></td>
									</tr>
									<tr>
										<th><span class="c-orange">*</span>투어 인원</th>
										<td>
											<div class="inp_bind">
												<div class="col">
													<span>성인</span>
													<select name="adult_cnt" id="adult_cnt" class="select1" data-amount="<?=$view['adult_amt']?>">
                                                        <option value="0">선택</option>
                                                        <?for ($i=1; $i<=10; $i++) {?>
														    <option value="<?=$i?>"><?=$i?>명</option>
                                                        <?}?>
													</select>
												</div>
												<div class="col">
													<span>아동</span>
													<select name="child_cnt" id="child_cnt" class="select1" data-amount="<?=$view['child_amt']?>">
                                                        <option value="0">선택</option>
                                                        <?for ($i=1; $i<=10; $i++) {?>
														    <option value="<?=$i?>"><?=$i?>명</option>
                                                        <?}?>
													</select>
												</div>
												<div class="col">
													<span>유아</span>
													<select name="infant_cnt" id="infant_cnt" class="select1" data-amount="<?=$view['infant_amt']?>">
                                                        <option value="0">선택</option>
                                                        <?for ($i=1; $i<=10; $i++) {?>
														    <option value="<?=$i?>"><?=$i?>명</option>
                                                        <?}?>
													</select>
												</div>
											</div>
										</td>
									</tr>
									<tr>
										<th><span class="c-orange">*</span>픽업 장소</th>
										<td>
											<select name="pickup_area" id="pickup_area" class="select1 w100p">
												<option value="">장소 선택</option>
                                                <?for ($i=0; $i<count($pickup_area_list); $i++) {?>
                                                    <option value="<?=$pickup_area_list[$i]?>"><?=$pickup_area_list[$i]?></option>
                                                <?}?>
											</select>
										</td>
									</tr>
									<tr>
										<th><span class="c-orange">*</span>시간</th>
										<td>
											<select name="tour_time" id="tour_time" class="select1 w100p">
												<option value="">선택</option>
												<?foreach(explode(",", $view['tour_time']) as $item) {?>
                                                    <option value="<?=trim($item)?>"><?=trim($item)?></option>
                                                <?}?>
											</select>
										</td>
									</tr>
								</tbody>
							</table>
						</div>

						<div class="btm">
							<div class="box_total">
								<p class="t1">현지지불금액 : <span class="total_tour_amt">$0</span></strong></p>
								<p class="tt c-orange"><span>총 결제금액</span><strong class="f-gm"><span class="total_agency_fee">￦0</span></strong></p>
							</div>
							<a href="javascript:;" class="btn-pk b orange w100p f-gm" onclick="tourGo()"><span>예약하기</span></a>
						</div>
					</div>
				</div>
                </form>

				<div class="tab ty1">
					<ul>
						<li class="on"><a href="#tab1">상품안내</a></li>
						<li><a href="#tab2">유의사항</a></li>
					</ul>
				</div>

				<div id="tab1" class="tabcont">
					<!-- <div class="tab_img"><img src="/upload/choice_tour/view/<?=getUpfileName($view['up_file_2'])?>" alt=""></div> -->
                    <div class="tab_con"><?=htmlDecode($view['content'])?></div>
				</div>

				<div id="tab2" class="tabcont">
					<div class="tab_con"><?=htmlDecode($view['notice'])?></div>
				</div>
			</div>
		</div>
	</div>
</div><!--//container -->

<script>
    tab(".tab.ty1",1);

    $(function(){
		$("#tour_date").datepicker({
			minDate: "<?=iif(dateDiff('day', $view['sdate'], date('Y-m-d')) > 0, date('Y-m-d'), $view['sdate'])?>",
			maxDate: "<?=$view['edate']?>",
			currDate: "<?=date('Y-m-d')?>",
			dateFormat: "yy-mm-dd",
			showOn: "both",
			buttonImage: "/images/common/ico_calender.png"
		});

        $("#adult_cnt, #child_cnt, #infant_cnt").change(function(){
            var adult_amt  = $("#adult_cnt").data("amount");
            var child_amt  = $("#child_cnt").data("amount");
            var infant_amt = $("#infant_cnt").data("amount");

            var adult_cnt  = $("#adult_cnt").val().toInt();
            var child_cnt  = $("#child_cnt").val().toInt();
            var infant_cnt = $("#infant_cnt").val().toInt();

            var agency_fee = <?=$view['agency_fee']?>;

            var total_tour_amt   = (adult_amt*adult_cnt) + (child_amt*child_cnt) + (infant_amt*infant_cnt);
            var total_agency_fee = (adult_cnt + child_cnt) * agency_fee;

            $(".total_tour_amt").html("$" + total_tour_amt.addComma());
            $(".total_agency_fee").html("￦" + total_agency_fee.addComma());
        })
    })

    function tourGo() {
        var adult_cnt  = $("#adult_cnt").val().toInt();
        var child_cnt  = $("#child_cnt").val().toInt();
        var infant_cnt = $("#infant_cnt").val().toInt();

        var h = new clsJsHelper();

        if (!h.checkSelect("tour_date", "투어 날짜")) return false;
        if ((adult_cnt + child_cnt) == 0) {
            alert("투어 인원은 '성인' 또는 '아동' 1명 이상 선택은 필수 입니다.");
            return false;
        }
        if (!h.checkSelect("pickup_area", "픽업 장소")) return false;
        if (!h.checkSelect("tour_time", "시간")) return false;

        $("#tourFrm").submit();
    }
</script>

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>