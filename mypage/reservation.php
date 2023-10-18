<? include "../inc/config.php" ?>
<?
	$token = chkReqRpl("token", "", "max", "", "STR");
	if (chkBlank($token)) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");

	$cls_goods = new CLS_GOODS;
	$cls_booking = new CLS_BOOKING;
	$cls_jwt = new CLS_JWT();
    $cls_jwt->expire_check = false;

	//토큰정보 확인
	$token_data = $cls_jwt->dehashing($token);
	if (chkBlank($token_data)) fnMsgGo(501, "잘못된 요청정보 입니다.", "BACK", "");

    //예약접수 정보 불러오기(예약번호+휴대번호 조회)
    $booking_view = $cls_booking->booking_view('', $token_data['booking_num'], '', $token_data['booker_phone'], '');
	if ($booking_view == false) fnMsgGo(502, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "BACK", "");

	//상품정보 불러오기
	$goods_view = $cls_goods->goods_view($booking_view['goods_idx']);
	if ($goods_view == false) fnMsgGo(503, "일치하는 상품정보가 없습니다.", "BACK", "");

    //추가・할인 내역 목록 불러오기
    $add_amt_list = $cls_booking->booking_add_amount_list($params['idx']);

	//확정서 토큰 생성
    $confirm_token = $cls_jwt->hashing(array(
		'booking_num'=> $booking_view['booking_num']
	));

    //정책적용 시작일
    $apply_policy_221101 = iif(datediff('day', '2022-11-01', date('Y-m-d')) >= 0, true, false); //2022.11.01 정책적용
    if ($DEV_IP) {
        $apply_policy_221101 = true;
    }

	$pageNum = "0502";
	$pageName = "나의 예약정보";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
    <? include "../inc/spot.php" ?>

	<div class="contents">
		<div class="inr-c">
			<div class="tbl_basic ty2 pr-mb2 mtbl_basic">
				<table class="write">
					<caption>예약정보</caption>
					<colgroup>
						<col class="th1">
						<col class="th2">
						<col class="th1">
						<col>
					</colgroup>
					<tbody>
                        <tr>
                            <th>예약 접수일</th>
							<td class="et" colspan="3" data-th="예약 접수일"><?=formatDates($booking_view['reg_date'], 'Y.m.d H:i:s')?></td>
						</tr>
						<tr>
							<th>예약번호</th>
							<td class="et" data-th="예약번호">
                                <?=$booking_view['booking_num']?>
                                <span class="c-orange">(※ 예약확인을 위해 꼭 기억해주세요.)</span>
                            </td>
							<th>예약상태</th>
							<td class="et" data-th="예약상태">
								<?=getResvStatusName($booking_view['status'])?>
								<?if ($booking_view['status'] == '40') {?>
									<?if ($booking_view['confirm_status'] != '30') {?>
                                        <button type="button" class="btn-pk s green ml20"><span>확정서 <?=getConfirmCateName($booking_view['confirm_status'])?></span></button>
									<?} else {?>
                                        <button type="button" class="btn-pk s green ml20" onclick="popupOpen('/mypage/certificate.php?token=<?=$confirm_token?>','', '1200','1000');"><span>확정서 보기</span></button>
									<?}?>
								<?}?>
							</td>
						</tr>
						<tr>
							<th>차량정보</th>
							<td colspan="3" class="et" data-th="차량정보">
                                <strong><?=$booking_view['goods_title']?></strong>
                                <?if ($booking_view['goods_options'] != '') {?>
                                    (<?=$booking_view['goods_options']?>)
                                <?}?>
                            </td>
						</tr>
						<tr>
							<th>차량<br class="view-m">인수일시</th>
							<td class="et" data-th="차량 인수일시"><?=formatDates($booking_view['rental_sdate'], 'Y.m.d')?> <?=$booking_view['rental_time']?></td>
							<th>반납일 (렌트기간)</th>
							<td class="et" data-th="반납일 (렌트기간)"><?=formatDates($booking_view['rental_edate'], 'Y.m.d')?> ($<?=formatNumbers($booking_view['rental_amt'])?>/<?=$booking_view['rental_day']?>일)</td>
						</tr>
						<tr>
							<th>인수픽업 장소</th>
							<td class="et" data-th="인수픽업 장소"><?=$booking_view['pickup_area']?></td>
							<th>반납 장소</th>
							<td class="et" data-th="반납 장소"><?=$booking_view['return_area']?></td>
						</tr>
						<tr>
							<th>투숙 호텔</th>
							<td class="et" data-th="투숙 호텔"><?=$booking_view['hotel']?></td>
							<th>출국일</th>
							<td class="et" data-th="출국일"><?=formatDates($booking_view['out_date'], 'Y.m.d')?> (<?=$booking_view['out_airline']?>)</td>
						</tr>

                        <?if ($apply_policy_221101) {?>
                            <tr>
                                <td colspan="4">
                                    <p class="t_info">
                                        ※ <strong>공항 픽업시</strong> 공항 주차장에서 픽업 진행이 되며 <strong>Airport(공항픽업)</strong>을 선택을 하셔야 합니다.<br>
                                        ※ <strong>공항 반납시</strong> 1) AIRPORT PARKING을 선택 하시면 <strong>$5 추가</strong>가 되며 직원 미팅 없이 공항 주차장에 직접 반납하시면 됩니다.<br>
                                        <span style="color:#fff">※ <strong>공항 반납시</strong></span> 2) PACIFICRENT OFFICE를 선택 하시면 공항 사무실에 차량 반납을 하시면 직원이 전용차량으로 공항으로 모셔다 드리며 <strong>추가비용은 없습니다.</strong>
                                    </p>
                                </td>
                            </tr>
                        <?}?>

						<tr>
							<th>보조시트</th>
							<td colspan="3" class="et" data-th="보조시트">
                                <?if ($booking_view['infant_seat_cnt']>0 || $booking_view['child_seat_cnt']>0 || $booking_view['booster_seat_cnt']>0) {?>
									<?if ($booking_view['infant_seat_cnt'] > 0) {?>
										<p>유아 보조시트(~12개월) : <?=$booking_view['infant_seat_cnt']?>개 (<?=iif($booking_view['infant_seat_amt']>0, '$'.formatNumbers($booking_view['infant_seat_amt'] * $booking_view['rental_day']) .'/'. $booking_view['rental_day'] .'일', '무료')?>)</p>
									<?}?>
									<?if ($booking_view['child_seat_cnt'] > 0) {?>
										<p>어린이 보조시트 (12~24개월) : <?=$booking_view['child_seat_cnt']?>개 (<?=iif($booking_view['child_seat_amt']>0, '$'.formatNumbers($booking_view['child_seat_amt'] * $booking_view['rental_day']) .'/'. $booking_view['rental_day'] .'일', '무료')?>)</p>
									<?}?>
									<?if ($booking_view['booster_seat_cnt'] > 0) {?>
										<p>부스터 시트 (24개월~) : <?=$booking_view['booster_seat_cnt']?>개 (<?=iif($booking_view['booster_seat_amt']>0, '$'.formatNumbers($booking_view['booster_seat_amt'] * $booking_view['rental_day']) .'/'. $booking_view['rental_day'] .'일', '무료')?>)</p>
									<?}?>

									<span class="t_info c-color ml0 mt5" style="font-size:0.8em;">
										유아나 어린이 동반시에는 카시트, 부스터를 꼭 장착하여야 합니다.(현지교통법)<br>
										표시가격이 있어도 <?=$booking_view['seat_free_cnt']?>대까지 무료지원 됩니다.
									</span>
								<?} else {?>
									선택안함
								<?}?>
                            </td>
						</tr>
						<tr>
							<th>추가옵션</th>
							<td colspan="3" class="et" data-th="추가옵션">
                                <?if ($booking_view['add_option_1_flag'] == 'Y') {?>
									아이스박스 (<?=iif($booking_view['add_option_1'] == 'Y', iif($booking_view['add_option_1_amt']>0, '$'.formatNumbers($booking_view['add_option_1_amt']), '무료'), '선택안함')?>)
								<?} else {?>
									<?$booking_view['add_option_1_amt'] = 0;?>
									아이스박스 (<span class="c-orange">예약불가</span>)
								<?}?>
								/
								<?if ($booking_view['add_option_2_flag'] == 'Y') {?>
									네비게이션 (<?=iif($booking_view['add_option_2'] == 'Y', iif($booking_view['add_option_2_amt']>0, '$'.formatNumbers($booking_view['add_option_2_amt']).'/24시간', '무료'), '선택안함')?>)
								<?} else {?>
									<?$booking_view['add_option_2_amt'] = 0;?>
									네비게이션 (<span class="c-orange">예약불가</span>)
								<?}?>
                            </td>
						</tr>
						<tr>
							<th>공항픽업</th>
							<td colspan="3">
								<?if ($booking_view['airport_meeting_flag'] == 'Y') {?>
									<?=iif($booking_view['airport_meeting'] == 'Y', iif($booking_view['airport_meeting_amt']>0, '공항픽업 ($'.formatNumbers($booking_view['airport_meeting_amt']).')', '(무료)'), '선택안함')?>
								<?} else {?>
									<?$booking_view['airport_meeting_amt'] = 0;?>
									<?=iif($booking_view['airport_meeting'] == 'Y', '공항픽업 (<span class="c-orange">예약불가</span>)', '선택안함')?>
								<?}?>
							</td>
						</tr>
						<tr>
							<th>현장<br class="view-m">지불금액</th>
							<td colspan="3" class="et" data-th="현장 지불금액">
                                <strong class="c-orange">$<?=(formatNumbers($booking_view['total_rental_amt']+$booking_view['total_add_amt']))?></strong>
								<span style="font-size:0.8em;">
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
						</tr>
                        <?if ($booking_view['total_add_amt'] != 0) {?>
                            <tr>
                                <th>추가・할인 내역</th>
                                <td colspan="3" class="et" data-th="추가・할인 내역">
                                    <?for ($i=0; $i<count($add_amt_list); $i++) {?>
                                        <p class="fc_gray <?if ($i>0) {?>mt5<?}?>"><?=$i+1?>. <?=$add_amt_list[$i]['content']?> ($<?=$add_amt_list[$i]['amount']?>)</p>
                                    <?}?>
                                </td>
                            </tr>
                        <?}?>
						<tr>
							<th>추가<br class="view-m">요청사항</th>
							<td colspan="3" class="et" data-th="추가 요청사항"><?=textareaDecode($booking_view['booking_memo'])?></td>
						</tr>
					</tbody>
				</table>
			</div>


			<h2 class="stit1">예약자 정보</h2>
			<div class="tbl_basic ty2 pr-mb2 mtbl_basic">
				<table class="write">
					<caption>예약자명</caption>
					<colgroup>
						<col class="th1">
						<col class="th2">
						<col class="th1">
						<col>
					</colgroup>
					<tbody>
						<tr>
							<th>예약자 이름(한글)</th>
							<td class="et" data-th="예약자 이름(한글)"><?=$booking_view['name']?></td>
							<th>예약자 이름(영문)</th>
							<td class="et" data-th="예약자 이름(영문)"><?=$booking_view['eng_name1']?> <?=$booking_view['eng_name2']?></td>
						</tr>
						<tr>
							<th>연락처</th>
							<td class="et" data-th="연락처"><?=$booking_view['phone']?> <span class="c-orange ml10">(※ 예약확인을 위해 꼭 기억해주세요.)</span></td>
							<th>이메일</th>
							<td class="et" data-th="이메일"><?=$booking_view['email']?></td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- <div class="tbl_bind pr-mb2">
                <?for($i=1; $i<=2; $i++) {?>
                    <div class="tbl_basic ty2">
                        <h2 class="stit1">운전자정보<?=$i?> <span class="c-orange"><?=iif($i==1,'(필수)','(선택)')?></span></h2>
                        <table class="write">
                            <caption>정보</caption>
                            <colgroup>
                                <col class="th1">
                                <col>
                            </colgroup>
                            <tbody>
                                <tr>
                                    <th>운전자 성함(한글)</th>
                                    <td><?=$booking_view['driver_name'.$i]?></td>
                                </tr>
                                <tr>
                                    <th>운전자 성함(영문)</th>
                                    <td><?=$booking_view['driver_name_eng'.$i]?></td>
                                </tr>
                                <tr>
                                    <th>휴대폰번호</th>
                                    <td class="c-orange"><?=$booking_view['driver_phone'.$i]?></td>
                                </tr>
                                <tr>
                                    <th>생년월일</th>
                                    <td class="c-orange"><?=$booking_view['driver_birthdate'.$i]?></td>
                                </tr>
                                <tr>
                                    <th>운전면허증 번호</th>
                                    <td><?=$booking_view['driver_license'.$i]?></td>
                                </tr>
                                <tr>
                                    <th>운전면허 만료일</th>
                                    <td><?=$booking_view['driver_license_expiry_date'.$i]?></td>
                                </tr>
                                <tr>
                                    <th>한국주소</th>
                                    <td><?=$booking_view['driver_home_addr'.$i]?></td>
                                </tr>
                                <tr>
                                    <th>현지주소</th>
                                    <td><?=$booking_view['driver_local_addr'.$i]?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?}?>
			</div> -->

			<div class="">
                <?if ($booking_view['notice'] != '') {?>
                    <h2 class="stit1">담당자 안내문</h2>
                    <div class="box_info">
                        <p><?=textareaDecode($booking_view['notice'])?></p>
                    </div>
                <?}?>
				<div class="box_total">
                    <p class="t1">총 대여일 : <strong class="total_rental_day"><?=$booking_view['rental_day']?>일</strong></p>
					<p class="t1">현지지불금액 : <strong class="total_rental_amt">$<?=formatNumbers(($booking_view['total_rental_amt']+$booking_view['total_add_amt']))?></strong></p>
					<p class="tt c-orange"><span>총 온라인 예약 대행 수수료</span><strong class="f-gm total_agency_amt">￦<?=formatNumbers($booking_view['booking_agency_fee'])?></strong></p>
				</div>
				<div class="t_info">
                    <?if ($booking_view['status'] == '10') {?>
                        ※ 예약을 신청한 후 담당자와 상담을 통해 가격 및 예약을 확정합니다.
                    <?} else if ($booking_view['status'] == '20') {?>
                        ※ 무통장 입금의 경우 입금 후 입금확인 요청을 해주세요.<br>
                        ※ 예약 확정시 차량 확정서가 발급 처리되며 예약 불가시 대행 수수료는 환불 처리됩니다.<br>
                        ※ 확정 된 예약 변경 또는 취소는 홈페이지 상단 "고객센터 > 문의하기"로  문의 부탁 드립니다.<br>
                        ※ 입금 된 예약 대행수수료는 차량 확정 후 취소 시 환불이 되지 않습니다.
                    <?} else {?>
                        ※ 확정 된 예약 변경 또는 취소는 홈페이지 상단 "고객센터 > 문의하기"로  문의 부탁 드립니다.<br>
                        ※ 입금 된 예약 대행 수수료는 출발일 기준 10일 전에는 취소 및 변경 시 환불 되지 않습니다.(단, 주말 및 공휴일은 제외 됩니다.)
                        ※ 입금 된 예약 대행수수료는 차량 확정 후 취소 시 환불이 되지 않습니다.
                    <?}?>
				</div>
			</div>

			<div class="btn-bot mbtn_ty1">
                <?if ($booking_view['status'] == '10') {?>
                    <!-- <a href="javascript:;" class="btn-pk b orange f-gm"><span>예약정보수정</span></a> -->
                <?} else if ($booking_view['status'] == '20') {?>
                    <a href="javascript:;" class="btn-pk b orange f-gm" onclick="paymentGo('BANK')"><span>무통장 입금 확인요청</span></a>
                    <!-- <a href="javascript:;" class="btn-pk b orange f-gm" onclick="paymentGo('CARD')"><span>카드결제</span></a> -->
                <?}?>

                <?if (strpos("10,20,30", $booking_view['status']) !== false && dateDiff("d", $booking_view['out_date'], date('Y-m-d')) < -10) {?>
                    <a href="javascript:;" class="btn-pk b gray f-gm" onclick="cancelReqGo()"><span>예약취소요청</span></a>
                <?}?>
			</div>
		</div>
	</div>
</div><!--//container -->

<script>
	<?if ($booking_view['status'] == '20') {?>
		function paymentGo(gubun) {
			if (gubun == 'BANK') {
				if (!confirm("무통장 입금을 완료하였습니까?\n입금이 미확인 될시 예약은 취소 될수 있습니다.\n\n무통장 입금 확인요청을 하시려면 '확인'을 눌러주세요.")) return false;

				paymentCompleteGo(gubun, "");
			} else {
			}
		}

		function paymentCompleteGo(gubun, tid) {
			AJ.callAjax("_payment_proc.php", {"gubun": gubun, "booking_num": "<?=$booking_view['booking_num']?>", "tid": tid}, function(data){
				if (data.result == 200) {
					if (gubun == "BANK") {
						alert("결제 확인 요청을 하였습니다.");
					}

					//location.replace('end.php?token='+data.token);
                    location.reload();
				} else {
					alert(data.message);
				}
			});
		}
	<?}?>

	<?if (strpos("10,20,30,40", $booking_view['status']) !== false && dateDiff("d", $booking_view['out_date'], date('Y-m-d')) < -10) {?>
		function cancelReqGo() {
			if (!confirm("예약취소요청 하시겠습니까?\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.callAjax("_cancel_proc.php", {"token": "<?=$token?>"}, function(data){
				if (data.result == 200) {
					alert("예약 취소가 접수 되었습니다.\n예약 정보 확인 후 빠른 시일 내로 처리하겠습니다.\n\n감사합니다.");

					location.reload();
				} else {
					alert(data.message);
				}
			});
		}
	<?}?>
</script>

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>