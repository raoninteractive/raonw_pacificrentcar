<?include("../inc/config.php")?>
<?
	$pageNum = "0501";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

    $params['idx']           = chkReqRpl("idx", null, "", "", "INT");
	$params['page']          = chkReqRpl("page", 1, "", "", "INT");
	$params['sch_sdate']     = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']     = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_picksdate'] = chkReqRpl("sch_picksdate", "", 10, "", "STR");
	$params['sch_pickedate'] = chkReqRpl("sch_pickedate", "", 10, "", "STR");
	$params['sch_cate']      = chkReqRpl("sch_cate", "", 10, "", "STR");
	$params['sch_status']    = chkReqRpl("sch_status", "", 10, "", "STR");
    $params['sch_type']      = chkReqRpl("sch_type", null, "", "", "INT");
	$params['sch_word']      = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

    $cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;
    $cls_jwt = new CLS_JWT();

    //예약정보 불러오기
    $booking_view = $cls_booking->booking_view($params['idx']);
    if ($booking_view == false) fnMsgGo(501, "일치하는 예약정보가 없습니다.", "BACK", "");

    //추가・할인 내역 목록 불러오기
    $add_amt_list = $cls_booking->booking_add_amount_list($params['idx']);

    //예약상태 상태목록
    $status_list = getResvStatusList();

    //확정서 상태목록
    $confirm_list = getConfirmCateList();

    $confirm_token = $cls_jwt->hashing(array(
            'booking_num'=> $booking_view['booking_num']
        ));


	//예약설정 정보 불러오기
	$setview = getBookingSettingInfoView($booking_view['goods_category']);

	//차량 인수,픽업 장소 목록
	$pickup_area_list = $setview['pickup_area'];

	//차량 반납 장소 목록
	$return_area_list = $setview['return_area'];

	//출국 항공사
	$out_airline_list = $setview['out_airline'];

	//귀국 항공사
	$in_airline_list = $setview['in_airline'];

	//호텔
	$hotel_list = $setview['hotel'];

    //정책적용 시작일
    $apply_policy_221101 = iif(datediff('day', '2022-11-01', date('Y-m-d')) >= 0, true, false); //2022.11.01 정책적용


	//확정서 토큰 생성
    $confirm_token = $cls_jwt->hashing(array(
		'booking_num'=> $booking_view['booking_num']
	));
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
                <form name="saveFrm" id="saveFrm" method="post">
                <input type="hidden" name="booking_idx" value="<?=$booking_view['idx']?>" />
				<div class="group">
                    <h3 class="g_title">예약정보 정보 <span class="explain">여행상품정보는 예약당시 여행상품정보이며 현재 여행상품정보와 무관할 수 있습니다.</span></h3>
					<table class="g_table">
						<colgroup>
                            <col width="12%">
                            <col width="38%">
                            <col width="12%">
                            <col width="38%">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_h">예약번호<span></th>
                                <td><?=$booking_view['booking_num']?></td>
                                <th><span class="t_h">예약 접수일<span></th>
								<td><?=formatDates($booking_view['reg_date'],'Y.m.d H:i:s')?></td>
                            </tr>
                            <tr>
								<th><span class="t_imp">예약상태<span></th>
								<td>
                                    <div class="box">
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="status" id="status" onchange="bookingStatusGo(this.value)">
                                                <?for ($i=0; $i<count($status_list); $i++) {?>
                                                    <?if (strpos('20,23,24,22,30,32,40,42,43,44,50,52', $status_list[$i]['code']) !== false) {?>
                                                        <option value="<?=$status_list[$i]['code']?>" <?=chkCompare($booking_view['status'],$status_list[$i]['code'],'selected')?>><?=$status_list[$i]['name2']?></option>
                                                    <?}?>
                                                <?}?>
                                            </select>
                                        </div>
                                        <p class="normal fc_red">※ 알림톡발송구간 : 결제대기(접수완료), 예약대기(결제완료), 예약불가(환불예정), 출발확정, 취소완료</p>
                                    </div>
                                </td>
                                <th><span class="t_h">확정서 번호<span></th>
                                <td>
                                    <?if ($booking_view['status'] == '40') {?>
                                        <div class="box">
                                            <div class="c_selectbox">
                                                <label for=""></label>
                                                <select name="confirm_status" id="confirm_status">
                                                    <?for ($i=0; $i<count($confirm_list); $i++) {?>
                                                        <option value="<?=$confirm_list[$i]['code']?>" <?=chkCompare($booking_view['confirm_status'],$confirm_list[$i]['code'],'selected')?>><?=$confirm_list[$i]['name']?></option>
                                                    <?}?>
                                                </select>
                                            </div>
                                            <div class="input_box" style="width:110px">
                                                <input type="text" name="confirm_num" id="confirm_num" value="<?=$booking_view['confirm_num']?>" placeholder="확정서 번호 입력" maxlength="20" />
                                            </div>

                                            <a href="javascript:;" class="btn_30" onclick="confirmStatusGo()">저장</a>

                                            <?if ($booking_view['confirm_num'] != '') {?>
                                                <a href="javascript:;" class="btn_30 ml5 white" onclick="popupOpen('/mypage/certificate.php?token=<?=$confirm_token?>','pop','1000','700');">확정서 미리보기</a>
                                                <p class="normal fc_red ml10">※ 알림톡발송구간 : 발행완료</p>
                                            <?}?>
                                        </div>
                                    <?} else {?>
                                        <?if ($booking_view['confirm_num'] != "") {?>
                                            <?=$booking_view['confirm_num']?>
                                            <a href="javascript:;" class="btn_30 white" onclick="popupOpen('/mypage/certificate.php?token=<?=$confirm_token?>','pop','1000','700');">확정서 보기</a>
                                        <?} else {?>
                                            <strong class="fc_red">※ 예약확정 상태에서만 입력가능합니다.</strong>
                                        <?}?>
                                    <?}?>
                                </td>
                            </tr>
							<tr>
								<th><span class="t_h">상품정보<span></th>
								<td><strong class="fc_red"><?=getGoodsCateName($booking_view['goods_category'])?></strong> - <?=$booking_view['goods_title']?></td>
								<th><span class="t_h">옵션<span></th>
								<td><?=$booking_view['goods_options']?></td>
                            </tr>
							<tr>
                                <th><span class="t_h">출국일(항공편)<span></th>
								<td colspan="3">
                                    <div class="box">
                                        <div class="input_box" style="width:130px;">
                                            <input type="text" name="out_date" id="out_date" value="<?=$booking_view['out_date']?>" readonly />
                                        </div>
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="out_airline" id="out_airline">
                                                <?for ($i=0; $i<count($out_airline_list); $i++) {?>
                                                    <option value="<?=$out_airline_list[$i]?>" <?=chkCompare($booking_view['out_airline'], $out_airline_list[$i], 'selected')?>><?=$out_airline_list[$i]?></option>
                                                <?}?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
								<!-- <th><span class="t_h">귀국일(항공편)<span></th>
								<td><?=formatDates($booking_view['in_date'],'Y.m.d')?> (<?=$booking_view['in_airline']?>)</td> -->
                            </tr>
                            <tr>
								<th><span class="t_h">투숙호텔<span></th>
								<td colspan="3">
                                    <div class="box">
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="hotel" id="hotel">
                                                <?for ($i=0; $i<count($hotel_list); $i++) {?>
                                                    <option value="<?=$hotel_list[$i]?>" <?=chkCompare($booking_view['hotel'], $hotel_list[$i], 'selected')?>><?=$hotel_list[$i]?></option>
                                                <?}?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="t_h">수령일시<span></th>
                                <td>
                                    <div class="box">
                                        <div class="input_box" style="width:130px;">
                                            <input type="text" name="rental_sdate" id="rental_sdate" value="<?=$booking_view['rental_sdate']?>" readonly />
                                        </div>
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="rental_hour" id="rental_hour">
                                                <?for ($i=0; $i<=23; $i++) {?>
                                                    <option value="<?=addZero($i)?>" <?=chkCompare(explode(":", $booking_view['rental_time'])[0], addZero($i), 'selected')?>><?=addZero($i)?>시</option>
                                                <?}?>
                                            </select>
                                        </div>
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="rental_minute" id="rental_minute">
                                                <?for ($i=0; $i<=59; $i+=10) {?>
                                                    <option value="<?=addZero($i)?>" <?=chkCompare(explode(":", $booking_view['rental_time'])[1], addZero($i), 'selected')?>><?=addZero($i)?>분</option>
                                                <?}?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <th><span class="t_h">반납일<span></th>
                                <td>
                                    <div class="box">
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="rental_day" id="rental_day">
                                                <?
                                                    /*
                                                    $rental_amt = 0;
                                                    for ($i=1; $i<=30; $i++) {
                                                        if ($i % 7 == 0) {
                                                            $rental_amt = $booking_view['day7_amt'] * ($i/7);
                                                        } else if ($i % 30 == 0) {
                                                            $rental_amt = $booking_view['day30_amt'] * ($i/30);
                                                        } else {
                                                            $rental_amt += $booking_view['day1_amt'];
                                                        }
                                                        ?><option value="<?=$i?>" data-rental-amt="<?=$rental_amt?>" <?=chkCompare($params['rental_day'], $i, 'selected')?>><?=$i?>일 ($<?=$rental_amt?>)</option><?
                                                    }*/

                                                    for ($i=1; $i<=30; $i++) {
                                                        if ($i < $GOODS_DC_PERIOD_DAY) {
                                                            $rental_amt = $booking_view['goods_rent_day1_amt'] * $i;
                                                        } else {
                                                            $rental_amt = round(($booking_view['goods_rent_day1_amt'] - ($booking_view['goods_rent_day1_amt'] * $GOODS_DC_RATE)) * $i);
                                                        }

                                                        ?><option value="<?=$i?>" data-rental-amt="<?=$rental_amt?>" <?=chkCompare($booking_view['rental_day'], $i, 'selected')?>><?=$i?>일 ($<?=$rental_amt?> <?=iif($i>=$GOODS_DC_PERIOD_DAY, ' / '. $GOODS_DC_RATE*100 .'% 할인', '')?>)</option><?
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                        <p class="normal">
                                            <span>렌트기간: <?=$booking_view['rental_day']?>일($<?=formatNumbers($booking_view['rental_amt'])?>)</span>
                                            <span class="ml10">반납일: <?=formatDates($booking_view['rental_edate'], 'Y.m.d')?></span>
                                        </p>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="t_h">인수/픽업 장소<span></th>
                                <td>
                                    <div class="box">
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="pickup_area" id="pickup_area">
                                                <?for ($i=0; $i<count($pickup_area_list); $i++) {?>
                                                    <option value="<?=$pickup_area_list[$i]?>" <?=chkCompare($booking_view['pickup_area'], $pickup_area_list[$i], 'selected')?>><?=$pickup_area_list[$i]?></option>
                                                <?}?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                                <th><span class="t_h">차량반납 장소<span></th>
                                <td>
                                    <div class="box">
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="return_area" id="return_area">
                                                <?for ($i=0; $i<count($return_area_list); $i++) {?>
                                                    <option value="<?=$return_area_list[$i]?>" <?=chkCompare($booking_view['return_area'], $return_area_list[$i], 'selected')?>><?=$return_area_list[$i]?></option>
                                                <?}?>
                                            </select>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
								<th><span class="t_h">예약자 이름(국문)<span></th>
                                <td><?=$booking_view['name']?></td>
                                <th><span class="t_h">예약자 이름(영문)<span></th>
								<td><?=$booking_view['eng_name1']?> <?=$booking_view['eng_name2']?></td>
                            </tr>
                            <tr>
								<th><span class="t_h">예약자 연락처<span></th>
                                <td><?=$booking_view['phone']?></td>
                                <th><span class="t_h">예약자 이메일<span></th>
								<td><?=$booking_view['email']?></td>
                            </tr>
                            <!-- <tr>
                                <th><span class="t_h">여행인원<span></th>
                                <td colspan="3">
                                    성인 <?=$booking_view['adult_cnt']?>명 /
                                    소아 <?=$booking_view['child_cnt']?>명 /
                                    유아 <?=$booking_view['infant_cnt']?>명
                                </td>
                            </tr> -->
                            <tr>
                                <th><span class="t_h">아동보조시트<span></th>
                                <td colspan="3">
                                    <div class="box">
                                        <div>
                                            <p class="normal" style="width:160px">유아 보조시트(~12개월)</p>
                                            <div class="c_selectbox">
                                                <label for=""></label>
                                                <select name="infant_seat_cnt" id="infant_seat_cnt">
                                                    <option value="">선택없음</option>
                                                    <?for($i=1; $i<=3; $i++) {?>
                                                        <option value="<?=$i?>" <?=chkCompare($booking_view['infant_seat_cnt'], $i, 'selected')?>><?=$i?>개<?=iif($booking_view['goods_car_seat_amt']>0, ' ($'.$booking_view['goods_car_seat_amt'] * $i.')', '')?></option>
                                                    <?}?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt5">
                                            <p class="normal" style="width:160px">어린이 보조시트 (12~24개월)</p>
                                            <div class="c_selectbox">
                                                <label for=""></label>
                                                <select name="child_seat_cnt" id="child_seat_cnt">
                                                    <option value="">선택없음</option>
                                                    <?for($i=1; $i<=3; $i++) {?>
                                                        <option value="<?=$i?>" <?=chkCompare($booking_view['child_seat_cnt'], $i, 'selected')?>><?=$i?>개<?=iif($booking_view['goods_car_seat_amt']>0, ' ($'.$booking_view['goods_car_seat_amt'] * $i.')', '')?></option>
                                                    <?}?>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="mt5">
                                            <p class="normal" style="width:160px">부스터 시트 (24개월~)</p>
                                            <div class="c_selectbox">
                                                <label for=""></label>
                                                <select name="booster_seat_cnt" id="booster_seat_cnt">
                                                    <option value="">선택없음</option>
                                                    <?for($i=1; $i<=3; $i++) {?>
                                                        <option value="<?=$i?>" <?=chkCompare($booking_view['booster_seat_cnt'], $i, 'selected')?>><?=$i?>개<?=iif($booking_view['goods_car_seat_amt']>0, ' ($'.$booking_view['goods_car_seat_amt'] * $i.')', '')?></option>
                                                    <?}?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <p class="mt10 fc_red">
										※ 유아나 어린이 동반시에는 카시트, 부스터를 꼭 장착하여야 합니다.(현지교통법)<br>
										※ 표시가격이 있어도 <?=$booking_view['seat_free_cnt']?>대까지 무료지원 됩니다.
                                    </p>
                                </td>
                            </tr>
                            <tr>
							    <th><span class="t_h">추가선택사항<span></th>
                                <td colspan="3">
                                <div class="box">
                                        <p class="normal" style="width:160px">
                                            <?if ($booking_view['add_option_1_flag'] == 'Y') {?>
                                                아이스박스 (<?=iif($booking_view['add_option_1'] == 'Y', iif($booking_view['add_option_1_amt']>0, '$'.formatNumbers($booking_view['add_option_1_amt']), '무료'), '선택안함')?>)
                                            <?} else {?>
                                                <?$booking_view['add_option_1_amt'] = 0;?>
                                                아이스박스 (예약불가)
                                            <?}?>
                                        </p>

                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="add_option_1_flag" id="add_option_1_flag" onchange="optionStatusGo('icebox', this)">
                                                <option value="Y" <?=chkCompare($booking_view['add_option_1_flag'], 'Y', 'selected')?>>확정</option>
                                                <option value="N" <?=chkCompare($booking_view['add_option_1_flag'], 'N', 'selected')?>>예약불가</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="box mt5">
                                        <p class="normal" style="width:160px">
                                            <?if ($booking_view['add_option_2_flag'] == 'Y') {?>
                                                네비게이션 (<?=iif($booking_view['add_option_2'] == 'Y', iif($booking_view['add_option_2_amt']>0, '$'.formatNumbers($booking_view['add_option_2_amt']), '무료'), '선택안함')?>)
                                            <?} else {?>
                                                <?$booking_view['add_option_2_amt'] = 0;?>
                                                네비게이션 (예약불가)
                                            <?}?>
                                        </p>

                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="add_option_2_flag" id="add_option_2_flag" onchange="optionStatusGo('navi', this)">
                                                <option value="Y" <?=chkCompare($booking_view['add_option_2_flag'], 'Y', 'selected')?>>확정</option>
                                                <option value="N" <?=chkCompare($booking_view['add_option_2_flag'], 'N', 'selected')?>>예약불가</option>
                                            </select>
                                        </div>
                                        <!-- <p class="normal fc_red">※ 예약접수 상태에서만 변경가능합니다. 예약상태 변경 또는 예약불가 변경시 더 이상 수정은 불가능합니다.</p> -->
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="t_h">공항픽업<span></th>
                                <td colspan="3">
                                <div class="box">
                                        <p class="normal" style="width:160px">
                                            <?if ($booking_view['airport_meeting_flag'] == 'Y') {?>
                                                <?=iif($booking_view['airport_meeting'] == 'Y', iif($booking_view['airport_meeting_amt']>0, '공항픽업 ($'.formatNumbers($booking_view['airport_meeting_amt']).')', '(무료)'), '개별이동')?>
                                            <?} else {?>
                                                <?$booking_view['airport_meeting_amt'] = 0;?>
                                                <?=iif($booking_view['airport_meeting'] == 'Y', '공항픽업 (예약불가-개별이동)', '개별이동')?>
                                            <?}?>
                                        </p>

                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="airport_meeting_flag" id="airport_meeting_flag" onchange="optionStatusGo('meet', this)">
                                                <option value="Y" <?=chkCompare($booking_view['airport_meeting_flag'], 'Y', 'selected')?>>확정</option>
                                                <option value="N" <?=chkCompare($booking_view['airport_meeting_flag'], 'N', 'selected')?>>예약불가</option>
                                            </select>
                                        </div>

                                        <!-- <p class="normal fc_red">※ 예약접수 상태에서만 변경가능합니다. 예약상태 변경 또는 예약불가 변경시 더 이상 수정은 불가능합니다.</p> -->
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="t_h">현장 지불금액<span></th>
                                <td colspan="3">
                                    <strong class="fc_red">$<?=formatNumbers(($booking_view['total_rental_amt']+$booking_view['total_add_amt']))?></strong>
                                    <span>
                                        (렌트비 : $<?=formatNumbers($booking_view['rental_amt'])?> +
                                        아동보조시트 : $<?=formatNumbers($booking_view['total_seat_amt'])?> +
                                        추가선택사항 : $<?=formatNumbers($booking_view['add_option_1_amt'] + $booking_view['add_option_2_amt'])?> +
                                        공항픽업 : $<?=formatNumbers(iif($booking_view['airport_meeting']=="Y", $booking_view['airport_meeting_amt'], 0))?> +
                                        공항파킹 : $<?=formatNumbers($booking_view['airport_car_return_amt'])?>
                                        <?if ($booking_view['total_add_amt'] != 0) {?>
                                            + 추가・할인 : <?=iif($booking_view['total_add_amt']<0, '-$'. formatNumbers(abs($booking_view['total_add_amt'])), '$'.formatNumbers($booking_view['total_add_amt']))?>
                                        <?}?>)
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="t_h">추가 할인 내역<span></th>
                                <td colspan="3">
                                    <?for ($i=0; $i<count($add_amt_list); $i++) {?>
                                        <p class="fc_gray <?if ($i>0) {?>mt5<?}?>">
                                            <?=$i+1?>. <?=$add_amt_list[$i]['content']?> (<?=iif($add_amt_list[$i]['amount']<0, '-$'. formatNumbers(abs($add_amt_list[$i]['amount'])), '$'.formatNumbers($add_amt_list[$i]['amount']))?>)
                                        </p>
                                    <?}?>
                                </td>
                            </tr>
                            <tr>
								<th><span class="t_h">온라인 예약 대행 수수료<span></th>
								<td colspan="3"><?=formatNumbers($booking_view['booking_agency_fee'])?>원</td>
                            </tr>
                            <tr>
								<th><span class="t_h">요청사항<span></th>
								<td colspan="3">
                                    <div style="max-height:200px; overflow:auto;">
                                        <?=textareaDecode($booking_view['booking_memo'])?>
                                    </div>
                                </td>
                            </tr>

                            <?if ($booking_view['status']>='30') {?>
                                <tr>
                                    <th><span class="t_h">결제방법<span></th>
                                    <td>
                                        <?=iif($booking_view['payment_method']=='BANK', '무통장입금', '카드결제 <span class="fc_gray">(결제번호: '. $booking_view['payment_tid'] .')</span>')?>
                                    </td>
                                    <th><span class="t_h">결제상태<span></th>
                                    <td>
                                        <?=getPayStatusName($booking_view['payment_status'])?>
                                    </td>
                                </tr>
                                <tr>
                                    <th><span class="t_h">결제 처리일<span></th>
                                    <td colspan="3">
                                        <?=formatDates($booking_view['payment_dt'], 'Y.m.d H:i:s')?>
                                    </td>
                                </tr>
                            <?}?>

                            <tr>
								<th><span class="t_h">담당자 안내문<span></th>
								<td colspan="3">
                                    <div class="box">
										<div class="textarea_box" style="width:100%">
											<textarea name="notice" id="notice" style="height:100px" placeholder="2000자 내로 입력해주세요."><?=$booking_view['notice']?></textarea>
										</div>
                                    </div>
                                    <div class="mt10 ta_r">
                                        <a href="javascript:;" class="btn_30" onclick="noticeSaveGo()">안내문 저장</a>
                                    </div>
                                </td>
                            </tr>

                            <tr>
								<th><span class="t_h">현지 메일 발송<span></th>
								<td colspan="3">
                                    <div class="c_checkbox mr5">
                                        <input type="checkbox" name="local_send_email_flag" id="local_send_email_flag" value="Y" <?=chkCompare($booking_view['local_send_email_flag'], 'Y', 'checked')?> />
                                        <label for="local_send_email_flag">발송완료</label>
                                    </div>
                                </td>
                            </tr>
						</tbody>
                    </table>

                    <h3 class="g_title">운전자 정보</h3>
					<table class="g_table">
						<colgroup>
                            <col width="12%">
                            <col width="38%">
                            <col width="12%">
                            <col width="38%">
						</colgroup>
						<tbody>
                            <?for ($i=1; $i<=2; $i++) {?>
                                <?if ($booking_view['driver_name'.$i] != '') {?>
                                    <tr <?if($i%2==0){?>style="border-top:4px double #aaa"<?}?>>
                                        <th><span class="t_h">운전자 이름 <?=$i?><span></th>
                                        <td colspan="3"><?=$booking_view['driver_name'.$i]?> (<?=$booking_view['driver_name_eng'.$i]?>)</td>
                                    </tr>
                                    <tr>
                                        <th><span class="t_h">한국 주소<span></th>
                                        <td><?=$booking_view['driver_home_addr'.$i]?></td>
                                        <th><span class="t_h">현지 주소<span></th>
                                        <td><?=$booking_view['driver_local_addr'.$i]?></td>
                                    </tr>
                                    <tr>
                                        <th><span class="t_h">휴대폰 번호<span></th>
                                        <td><?=$booking_view['driver_phone'.$i]?></td>
                                        <th><span class="t_h">생년월일<span></th>
                                        <td><?=$booking_view['driver_birthdate'.$i]?></td>
                                    </tr>
                                    <tr>
                                        <th><span class="t_h">운전면허증 번호<span></th>
                                        <td><?=$booking_view['driver_license'.$i]?></td>
                                        <th><span class="t_h">운전면허증 만료일<span></th>
                                        <td><?=$booking_view['driver_license_expiry_date'.$i]?></td>
                                    </tr>
                                <?}?>
                            <?}?>
                        </tbody>
                    </table>
                </div>
                </form>

				<div class="page_btn_a center mt30">
                    <a href="booking_list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
                    <a href="javascript:;" class="btn_40 white" onclick="saveGo()"><span>예약정보 수정</span></a>
                    <a href="javascript:;" class="btn_40 gray" onclick="localEmaiSend()"><span>현지 이메일 발송</span></a>
                </div>

                <!-- 관리자 메모영역 -->
                <?if ($params['idx'] != '') {?>
                    <?
                        $admin_memo_section = "booking_view";
                        $admin_memo_gubun = $params['idx'];
                    ?>
                    <?include("../common/admin_memo_log_include.php")?>
                <?}?>
			</div>
		</div>
	</div>
    <!-- //container -->

    <!-- 레이어팝업 : 상품가격 추가 -->
    <article class="layer_popup goods_price_popup"></article>


	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
            $("#out_date, #rental_sdate").datepicker();

            $("#confirm_status").change(function(){
                $("#confirm_num").prop("disabled", $(this).val() == 10?true:false);
            })
            $("#confirm_status").trigger("change");
		})

        //예약정보 상태값 수정
        function bookingStatusGo(status) {
            if (status == "") return false;

            if (!confirm("예약정보 상태값을 수정하시겠습니까?\n\n계속 진행하시려면 '확인'을 눌러주세요.")) {
                selectboxInit();
                return false;
            }

            AJ.callAjax("__booking_status_proc.php", {"booking_idx": "<?=$booking_view['idx']?>", "status": status}, function(data){
                if (data.result == 200) {
                    alert("수정 되었습니다.");

                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }

        //옵션 상태값 수정
        function optionStatusGo(gubun, obj) {
            if ($(obj).val() == "") return false;

            if (!confirm("확정 상태값을 수정하시겠습니까?\n예약불가 변경시 더 이상 수정은 절대 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) {
                $(obj).val("Y");
                selectboxInit();
                return false;
            }

            AJ.callAjax("__option_status_proc.php", {"booking_idx": "<?=$booking_view['idx']?>", "gubun": gubun, "status": $(obj).val()}, function(data){
                if (data.result == 200) {
                    alert("수정 되었습니다.");

                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }

        //확정서 정보 저정
        function confirmStatusGo() {
            var h = new clsJsHelper();

            var confirm_num = h.objVal("confirm_num");
            if (h.objVal("confirm_status") == "30") {
                if (!h.checkValNLen("confirm_num", 1, 20, "확정서 번호", "Y", "KO")) return false;

                //if (!confirm("확정서를 발행완료 하시겠습니까?\n발행완료 후 수정은 절대 불가합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;
            }

            AJ.callAjax("__confirm_status_proc.php", {"booking_idx": "<?=$booking_view['idx']?>", "confirm_num": confirm_num, "status": h.objVal("confirm_status")}, function(data){
                if (data.result == 200) {
                    alert("수정 되었습니다.");

                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }


        //담당자 안내문 저장
        function noticeSaveGo() {
            if (!h.checkValNLen("notice", 1, 4000, "담당자 안내문", "N", "KO")) return false;

            if (!confirm("담당자 안내문을 저장하시겠습니까?\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

            AJ.callAjax("__notice_save_proc.php", {"booking_idx": "<?=$booking_view['idx']?>", "notice": h.objVal("notice")}, function(data){
                if (data.result == 200) {
                    alert("수정 되었습니다.");

                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }

        //현지 이메일 발송
        function localEmaiSend() {
            if (!confirm("현재 예약정보를 현지에 이메일에 발송하시겠습니까?\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

            AJ.callAjax("__local_email_send.php", {"booking_idx": "<?=$booking_view['idx']?>"}, function(data){
                if (data.result == 200) {
                    alert("발송 되었습니다.");

                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }

        //예약정보 저장
        function saveGo() {
			AJ.ajaxForm($("#saveFrm"), "booking_view_proc.php", function(data) {
				if (data.result == 200) {
                    alert("처리 되었습니다.");

                    location.reload();
				} else {
					alert(data.message);
				}
			});
        }
	</script>
<?include("../inc/footer.php")?>