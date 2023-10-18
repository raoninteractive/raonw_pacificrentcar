<? include "../inc/config.php" ?>
<?
	$token = chkReqRpl("token", "", "max", "", "STR");
	if (chkBlank($token)) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");

	$cls_booking = new CLS_BOOKING;
	$cls_jwt = new CLS_JWT();

	//토큰정보 확인
	$token_data = $cls_jwt->dehashing($token);
	if (chkBlank($token_data)) fnMsgGo(501, "잘못된 요청정보 입니다.", "BACK", "");

    //예약접수 정보 불러오기(예약번호+휴대번호 조회)
    $booking_view = $cls_booking->booking_view('', $token_data['booking_num'], '', '', '');
	if ($booking_view == false) fnMsgGo(502, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "BACK", "");


    //추가・할인 내역 목록 불러오기
    $add_amt_list = $cls_booking->booking_add_amount_list($params['idx']);

	$pageNum = "0503";
	$pageName = "나의 예약정보";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
    <? include "../inc/spot.php" ?>

	<div class="contents wrap_mypage">
		<div class="inr-c">
			<div class="hd_tit1">
				<h2 class="h2"><?=iif($booking_view['payment_method']=='BANK', '결제 확인 요청이 완료되었습니다.', '결제가 완료되었습니다.')?></h2>
			</div>
			<div class="area_info non">
				<div class="inner">
					<div class="tbl_basic ty2">
						<table class="write">
							<caption>정보</caption>
							<colgroup>
								<col class="wid1">
								<col>
							</colgroup>
							<tbody>
								<tr>
									<th class="ta-c">예약번호</th>
									<td><?=$booking_view['booking_num']?></td>
								</tr>
								<tr>
									<th class="ta-c">차량정보</th>
									<td>
                                        <strong><?=$booking_view['goods_title']?></strong>
                                        <?if ($booking_view['goods_options'] != '') {?>
                                            (<?=$booking_view['goods_options']?>)
                                        <?}?>
                                    </td>
								</tr>
                                <tr>
                                    <th class="ta-c">현장<br class="view-m">지불금액</th>
                                    <td colspan="3" class="et" data-th="현장 지불금액">
                                        <strong class="c-orange">$<?=formatNumbers($booking_view['total_rental_amt']+$booking_view['total_add_amt'])?></strong>
                                        <span style="font-size:0.8em;">
                                            [
                                                (렌트비 : $<?=formatNumbers($booking_view['rental_amt'])?> +
                                                아동보조시트 : $<?=formatNumbers($booking_view['total_seat_amt'])?> +
                                                추가선택사항 : $<?=formatNumbers($booking_view['add_option_1_amt'] + $booking_view['add_option_2_amt'])?> +
                                                공항픽업 : $<?=formatNumbers(iif($booking_view['airport_meeting']=="Y", $booking_view['airport_meeting_amt'], 0))?>
                                                <?if ($booking_view['total_add_amt'] != 0) {?>
                                                    + 추가・할인 : $<?=formatNumbers($booking_view['total_add_amt'])?>
                                                <?}?>) - <span class="c-orange">온라인 예약 대행 수수료 : $<?=formatNumbers($booking_view['booking_agency_fee'])?></span>
                                            ]
                                        </span>
                                    </td>
                                </tr>
                                <?if ($booking_view['total_add_amt'] != 0) {?>
                                    <tr>
                                        <th class="ta-c">추가・할인 내역</th>
                                        <td colspan="3" class="et" data-th="추가・할인 내역">
                                            <?for ($i=0; $i<count($add_amt_list); $i++) {?>
                                                <p class="fc_gray <?if ($i>0) {?>mt5<?}?>"><?=$i+1?>. <?=$add_amt_list[$i]['content']?> ($<?=$add_amt_list[$i]['amount']?>)</p>
                                            <?}?>
                                        </td>
                                    </tr>
                                <?}?>
								<tr>
									<th class="ta-c">총 온라인 예약 대행 수수료</th>
									<td><p class="fz-b1 c-orange">$ <?=formatNumbers($booking_view['booking_agency_fee'])?></p></td>
								</tr>
							</tbody>
						</table>
					</div>

					<button type="button" class="btn-pk b orange w100p f-gm" onclick="location='/'"><span>메인화면으로 이동</span></button>
				</div>
			</div>

		</div>
	</div>
</div><!--//container -->

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>