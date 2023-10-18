<? include "../inc/config.php" ?>
<?
	$token = chkReqRpl("token", "", "max", "", "STR");
	if (chkBlank($token)) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");

	$cls_goods = new CLS_GOODS;
	$cls_booking = new CLS_BOOKING;
	$cls_jwt = new CLS_JWT();

	//토큰정보 확인
	$token_data = $cls_jwt->dehashing($token);
	if (chkBlank($token_data)) fnMsgGo(501, "잘못된 요청정보 입니다.", "BACK", "");

    //예약접수 정보 불러오기(예약번호+휴대번호 조회)
    $booking_view = $cls_booking->choice_tour_booking_view('', $token_data['booking_num'], '', $token_data['booker_phone'], '');
	if ($booking_view == false) fnMsgGo(502, "일치하는 예약정보가 없습니다.\n예약정보를 다시 확인해주세요.", "BACK", "");

	//상품정보 불러오기
	$goods_view = $cls_goods->choice_tour_view($booking_view['goods_idx']);
	if ($goods_view == false) fnMsgGo(503, "일치하는 상품정보가 없습니다.", "BACK", "");


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
							</td>
						</tr>
						<tr>
							<th>상품명</th>
							<td colspan="3" class="et" data-th="차량정보">
                                <strong><?=$booking_view['goods_title']?></strong>
                            </td>
						</tr>
						<tr>
							<th>투어 날짜</th>
							<td><?=formatDates($booking_view['tour_date'], 'Y.m.d')?></td>
							<th>시간</th>
							<td><?=$booking_view['tour_time']?></td>
						</tr>
						<tr>
							<th>투어 인원</th>
							<td>
                                <?=formatNumbers($booking_view['adult_cnt']+$booking_view['child_cnt']+$booking_view['infant_cnt'])?>명
                                <?
                                    $tour_people = '';
                                    if ($booking_view['adult_cnt'] > 0) {
                                        $tour_people .= '성인 '. $booking_view['adult_cnt'].'명';
                                    }

                                    if ($booking_view['child_cnt'] > 0) {
                                        if ($tour_people != '') $tour_people .= ' / ';
                                        $tour_people .= '아동 '. $booking_view['child_cnt'].'명';
                                    }

                                    if ($booking_view['infant_cnt'] > 0) {
                                        if ($tour_people != '') $tour_people .= ' / ';
                                        $tour_people .= '유아 '. $booking_view['infant_cnt'].'명';
                                    }

                                    echo "($tour_people)";
                                ?>
                            </td>
							<th>픽업 장소</th>
							<td colspan="3"><?=$booking_view['pickup_area']?></td>
						</tr>
						<tr>
							<th>현장<br class="view-m">지불금액</th>
							<td colspan="3" class="et" data-th="현장 지불금액">
                                <strong class="c-orange">$<?=(formatNumbers($booking_view['total_tour_amt']+$booking_view['total_add_amt']))?></strong>
                            </td>
						</tr>
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


			<div class="">
                <?if ($booking_view['notice'] != '') {?>
                    <h2 class="stit1">담당자 안내문</h2>
                    <div class="box_info">
                        <p><?=textareaDecode($booking_view['notice'])?></p>
                    </div>
                <?}?>
				<div class="box_total">
					<p class="t1">현지지불금액 : <strong class="total_tour_amt">$<?=formatNumbers(($booking_view['total_tour_amt']+$booking_view['total_add_amt']))?></strong></p>
					<p class="tt c-orange"><span>총 온라인 예약 대행 수수료</span><strong class="f-gm total_agency_amt">￦<?=formatNumbers($booking_view['booking_agency_fee'])?></strong></p>
				</div>
				<div class="t_info">
                    <?if ($booking_view['status'] == '10') {?>
                        ※ 예약을 신청한 후 담당자와 상담을 통해 가격 및 예약을 확정합니다.
                    <?} else if ($booking_view['status'] == '20') {?>
                        ※ 무통장 입금의 경우 입금 후 입금확인 요청을 해주세요.<br>
                        ※ 예약 불가시 대행 수수료는 환불 처리됩니다.<br>
                        ※ 확정 된 예약 변경 또는 취소는 홈페이지 상단 "고객센터 > 문의하기"로  문의 부탁 드립니다.<br>
                        ※ 입금 된 예약 대행 수수료는 출발일 기준 10일 전에는 취소 및 변경 시 환불 되지 않습니다.(단, 주말 및 공휴일은 제외 됩니다.)
                    <?} else {?>
                        ※ 확정 된 예약 변경 또는 취소는 홈페이지 상단 "고객센터 > 문의하기"로  문의 부탁 드립니다.<br>
                        ※ 입금 된 예약 대행 수수료는 출발일 기준 10일 전에는 취소 및 변경 시 환불 되지 않습니다.(단, 주말 및 공휴일은 제외 됩니다.)
                    <?}?>
				</div>
			</div>

			<div class="btn-bot mbtn_ty1">
                <?if ($booking_view['status'] == '10') {?>
                    <p class="t1">※ 예약을 신청한 후 담당자와 상담을 통해 가격 및 예약을 확정합니다. </p>
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
			AJ.callAjax("_choice_tour_payment_proc.php", {"gubun": gubun, "booking_num": "<?=$booking_view['booking_num']?>", "tid": tid}, function(data){
				if (data.result == 200) {
					if (gubun == "BANK") {
						alert("입금 확인요청을 하였습니다.");
					}

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

			AJ.callAjax("_choice_tour_cancel_proc.php", {"token": "<?=$token?>"}, function(data){
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