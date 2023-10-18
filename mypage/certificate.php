<?include("../inc/config.php")?>
<?
	$token = chkReqRpl("token", "", "max", "", "STR");
	if (chkBlank($token)) fnMsgGo(500, "잘못된 접근 입니다.", "WCLOSE", "");

	$cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;
	$cls_jwt = new CLS_JWT();
    $cls_jwt->expire_check = false;

	//토큰정보 확인
	$token_data = $cls_jwt->dehashing($token);
	if (chkBlank($token_data)) fnMsgGo(501, "잘못된 요청정보 입니다.", "WCLOSE", "");

    //예약접수 정보 불러오기(예약번호+휴대번호 조회)
    $booking_view = $cls_booking->booking_view('', $token_data['booking_num'], '', '', '');
	if ($booking_view == false) fnMsgGo(502, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "WCLOSE", "");


    //여행안내사항
	$guide_notice = getBookingSettingInfoView($booking_view['goods_category'])['guide_notice'];


    //정책적용 시작일
    $apply_policy_221101 = iif(datediff('day', '2022-11-01', date('Y-m-d')) >= 0, true, false); //2022.11.01 정책적용


	$pageNum = "0003";
	$pageName = "확정서메인";
?>
<? include "../inc/top.php" ?>
	<div class="wrap_print">
		<div id="print" class="paper">
			<div class="inner">
				<div class="in">
					<div class="tit">
						<h1 class="logo"><span class="ico"><img src="../images/common/logo.png" alt="퍼시픽"></span><span class="t">퍼시픽렌터카 예약 확정서</span></h1>
					</div>

					<h2 class="stit1">예약 정보</h2>
					<div class="tbl_basic ty2 mb50">
						<table class="list">
							<caption>예약자명</caption>
							<colgroup>
								<col style="width: 20%;">
								<col style="width: 20%;">
								<col style="width: 20%;">
								<col style="width: 20%;">
								<col>
							</colgroup>
							<tbody>
								<tr>
									<th>고객명<br><span class="fz-s2 c-gray">(NAME)</span></th>
									<td colspan="4" style="text-align: left;"><?=$booking_view['name']?> (<?=$booking_view['eng_name1']?> <?=$booking_view['eng_name2']?>)</td>
								</tr>
								<tr>
									<th>수령일시<br><span class="fz-s2 c-gray">(PICK UP DATE)</span></th>
									<th>인수/픽업 장소<br><span class="fz-s2 c-gray">(PICK UP PLACE)</span></th>
									<th>반납일<br><span class="fz-s2 c-gray">(RETURN DATE)</span></th>
									<th>반납장소<br><span class="fz-s2 c-gray">(RETURN PLACE)</span></th>
									<th>차종<br><span class="fz-s2 c-gray">(MODEL)</span></th>
								</tr>
								<tr>
									<td><?=formatDates($booking_view['rental_sdate'], 'Y.m.d')?> <?=$booking_view['rental_time']?></td>
									<td><?=$booking_view['pickup_area']?></td>
									<td><?=formatDates($booking_view['rental_edate'], 'Y.m.d')?></td>
									<td><?=$booking_view['return_area']?></td>
						            <td>
                                        <?=$booking_view['goods_title']?>
                                        <?if ($booking_view['goods_options'] != '') {?>
                                            (<?=$booking_view['goods_options']?>)
                                        <?}?>
                                    </td>
								</tr>
								<tr>
									<th>이용기간<br><span class="fz-s2 c-gray">(PERIOD)</span></th>
									<th colspan="3">렌트비용<br><span class="fz-s2 c-gray">(TOTAL FEE)</span></th>
									<th>컨펌번호<br><span class="fz-s2 c-gray">(CONFIRM NR)</span></th>
								</tr>
								<tr>
									<td><?=$booking_view['rental_day']?> DAY(S)</td>
									<td colspan="3">
                                        <strong class="c-orange">$<?=formatNumbers($booking_view['total_rental_amt']+$booking_view['total_add_amt'])?></strong>
                                        <p style="font-size:0.8em;">
                                                (렌트비 : $<?=formatNumbers($booking_view['rental_amt'])?> +
                                                아동보조시트 : $<?=formatNumbers($booking_view['total_seat_amt'])?> +
                                                추가선택사항 : $<?=formatNumbers($booking_view['add_option_1_amt'] + $booking_view['add_option_2_amt'])?> +
                                                공항픽업 : $<?=formatNumbers(iif($booking_view['airport_meeting']=="Y", $booking_view['airport_meeting_amt'], 0))?>
                                                <?if ($apply_policy_221101) {?>
                                                    <?if ($booking_view['airport_car_return_flag'] == 'Y') {?>
                                                        + 공항파킹 : $<?=formatNumbers($booking_view['airport_car_return_amt'])?>
                                                    <?}?>
                                                <?}?>
                                                <?if ($booking_view['total_add_amt'] != 0) {?>
                                                    + 추가・할인 : <?=iif($booking_view['total_add_amt']<0, '-$'. formatNumbers(abs($booking_view['total_add_amt'])), '$'.formatNumbers($booking_view['total_add_amt']))?>
                                                <?}?>)
                                        </span>
                                    </td>
									<td><?=$booking_view['confirm_num']?></td>
								</tr>
							</tbody>
						</table>
					</div>


					<div class="tbl_bind mb50">
						<div class="tbl_basic ty2">
							<table class="list">
								<caption>선택사항</caption>
								<colgroup>
									<col style="width: 40%;">
									<col>
								</colgroup>
								<tbody>
									<tr>
										<th colspan="2">추가 선택사항</th>
									</tr>
									<tr>
										<th>아이스박스</th>
                                        <td>
                                            <?if ($booking_view['add_option_1_flag'] == 'Y') {?>
                                                <?=iif($booking_view['add_option_1'] == 'Y', iif($booking_view['add_option_1_amt']>0, '$'.formatNumbers($booking_view['add_option_1_amt']), '무료'), '선택없음')?>
                                            <?} else {?>
                                                선택없음
                                            <?}?>
                                        </td>
									</tr>
									<tr>
										<th>네비게이션</th>
                                        <td>
                                            <?if ($booking_view['add_option_2_flag'] == 'Y') {?>
                                                <?=iif($booking_view['add_option_2'] == 'Y', iif($booking_view['add_option_2_amt']>0, '$'.formatNumbers($booking_view['add_option_2_amt']), '무료'), '선택없음')?>)
                                            <?} else {?>
                                                선택없음
                                            <?}?>
                                        </td>
									</tr>
									<tr>
										<th>공항픽업</th>
                                        <td>
                                            <?if ($booking_view['airport_meeting_flag'] == 'Y') {?>
                                                <?=iif($booking_view['airport_meeting'] == 'Y', iif($booking_view['airport_meeting_amt']>0, '$'.formatNumbers($booking_view['airport_meeting_amt']), '무료'), '선택안함')?>
                                            <?} else {?>
                                                <?$booking_view['airport_meeting_amt'] = 0;?>
                                                <?=iif($booking_view['airport_meeting'] == 'Y', '공항픽업 (예약불가)', '선택안함')?>
                                            <?}?>
                                        </td>
									</tr>
									<tr>
										<th>유아 보조시트<br>(~12개월)</th>
                                        <td>
                                            <?if ($booking_view['infant_seat_cnt'] > 0) {?>
                                                <?=$booking_view['infant_seat_cnt']?>개 (<?=iif($booking_view['infant_seat_amt']>0, '$'.formatNumbers($booking_view['infant_seat_amt']), '무료')?>)
                                            <?} else {?>
                                                선택없음
                                            <?}?>
                                        </td>
									</tr>
									<tr>
										<th>어린이 보조시트<br>(12~24개월)</th>
                                        <td>
                                            <?if ($booking_view['child_seat_cnt'] > 0) {?>
                                                <?=$booking_view['child_seat_cnt']?>개 (<?=iif($booking_view['child_seat_amt']>0, '$'.formatNumbers($booking_view['child_seat_amt']), '무료')?>)
                                            <?} else {?>
                                                선택없음
                                            <?}?>
                                        </td>
									</tr>
									<tr>
										<th>부스터 시트<br>(24개월~)</th>
                                        <td>
                                            <?if ($booking_view['booster_seat_cnt'] > 0) {?>
                                                <?=$booking_view['booster_seat_cnt']?>개 (<?=iif($booking_view['booster_seat_amt']>0, '$'.formatNumbers($booking_view['booster_seat_amt']), '무료')?>)
                                            <?} else {?>
                                                선택없음
                                            <?}?>
                                        </td>
									</tr>
								</tbody>
							</table>
                            <p class="fz-s2 c-gray mt5">
                                유아나 어린이 동반시에는 카시트, 부스터를 꼭 장착하여야 합니다.(현지교통법)<br>
								표시가격이 있어도 <strong class="c-orange"><?=$booking_view['seat_free_cnt']?>대까지 무료지원</strong> 됩니다.
                            </p>
						</div>

						<div class="tbl_basic ty2">
							<table class="list">
								<caption>현장 지불금액</caption>
								<colgroup>
									<col style="width: 40%;">
									<col>
								</colgroup>
								<tbody>
									<tr>
										<th colspan="2">현장 지불금액</th>
									</tr>
									<tr>
										<td colspan="2" class="he">
                                            <strong class="c-orange">$<?=formatNumbers(($booking_view['total_rental_amt']+$booking_view['total_add_amt']))?></strong>
                                            <!-- <p style="font-size:14px;">
                                                (렌트비용: $<?=formatNumbers($booking_view['total_rental_amt']+$booking_view['total_add_amt'])?> - 온라인 예약 대행 수수료: $<?=formatNumbers($booking_view['booking_agency_fee'])?>)
                                            </p> -->
                                        </td>
									</tr>
								</tbody>
							</table>
						</div>
					</div>

					<h2 class="stit1">여행안내사항</h2>
					<div class="box">
                        <?=htmlDecode($guide_notice)?>
					</div>
				</div>
			</div>
		</div>

		<button type="button" class="btn-pk b orange w100p f-gm"><span>프린트</span></button>
	</div>

 <link rel="stylesheet" type="text/css" href="/css/print.css" media="all" />
<script type="text/javascript" src="/js/printThis.js"></script>
<script type="text/javascript">
$(function(){
	$(".btn-pk").on("click", function(){
		$("#print").printThis({
			debug : false,
			importCSS : true,
			printContainer : true,
			loadCss: "/css/print.css",
			pageTitle: "",
			removeInline : false
		});
	});
});
</script>



<?include("../inc/bottom.php")?>