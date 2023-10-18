<? include "../inc/config.php" ?>
<?
	$params['gubun']       = chkReqRpl("gubun", "C001", "10", "", "STR");
	$params['goods_idx']   = chkReqRpl("goods_idx", null, "", "", "INT");
	$params['rental_date'] = chkReqRpl("rental_date", "", "10", "", "STR");
	$params['rental_hour'] = chkReqRpl("rental_hour", "09", "2", "", "STR");
	$params['rental_day']  = chkReqRpl("rental_day", 1, "", "", "INT");

	if (chkBlank($params['goods_idx'])) fnMsgGo(501, "잘못된 요청 정보 입니다.", "BACK", "");

	$cls_goods = new CLS_GOODS;

	//상품정보 불러오기
	$goods_view = $cls_goods->goods_view($params['goods_idx']);
	if ($goods_view == false) fnMsgGo(502, "일치하는 상품정보가 없습니다.", "BACK", "");

    //출발가능일
    $start_rent_date = dateAdd("day", 1, date('Y-m-d'));

	//예약가능일 불러오기
	$stock_params['goods_idx'] = $params['goods_idx'];
    $stock_params['sch_sdate'] = date('Y-m-d');
    $stock_params['sch_stock'] = 'Y';
	$stock_list = $cls_goods->stock_list($stock_params);
	if (count($stock_list) == 0) fnMsgGo(503, "예약이 불가능한 상품입니다.", "BACK", "");
	if (chkBlank($params['rental_date'])) {
		for ($i=0; $i<count($stock_list); $i++) {
			if ($start_rent_date <= $stock_list[$i]['sdate']) {
				$params['rental_date'] = $stock_list[$i]['sdate'];
				break;
			}
		}
	} else {
        if ($params['rental_date'] <= $start_rent_date) {
            $params['rental_date'] = $start_rent_date;
        }

		for ($i=0; $i<count($stock_list); $i++) {
			if ($params['rental_date'] <= $stock_list[$i]['sdate']) {
				$params['rental_date'] = $stock_list[$i]['sdate'];
				break;
			}
		}
	}

	//예약설정 정보 불러오기
	$setview = getBookingSettingInfoView($params['gubun']);

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

	if ($params['gubun'] == 'C001') {
		$pageNum = "0101";
		$pageName = "예약하기";
	} else if ($params['gubun'] == 'C002') {
		$pageNum = "0201";
		$pageName = "예약하기";
	} else {
		fnMsgGo(500, "잘못된 요청 정보 입니다.", "BACK", "");
	}


    //정책적용 시작일
    $apply_policy_221101 = iif(datediff('day', '2022-11-01', date('Y-m-d')) >= 0, true, false); //2022.11.01 정책적용
    if ($DEV_IP) {
        $apply_policy_221101 = true;
    }

	$pageNum = "0102";
	$pageName = "예약";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
    <? include "../inc/spot.php" ?>

	<div class="contents">
		<div class="inr-c">
            <form name="bookingFrm" id="bookingFrm" method="post">
			<input type="hidden" name="goods_idx" value="<?=$params['goods_idx']?>">
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
							<th>차량정보</th>
							<td colspan="3" data-th="차량정보" class="et">
                                <strong><?=$goods_view['title']?></strong>
                                <?
									$option_txt = "";
									if ($goods_view['option_1'] == 'Y') {
										$option_txt .= "주유포함";
									}

									if ($goods_view['option_2'] == 'Y') {
										if ($option_txt != "") $option_txt .= ", ";
										$option_txt .= "CDW포함";
									}

									if ($goods_view['option_7'] == 'Y') {
										if ($option_txt != "") $option_txt .= ", ";
										$option_txt .= "ZDC포함";
									}

									if ($goods_view['option_8'] == 'Y') {
										if ($option_txt != "") $option_txt .= ", ";
										$option_txt .= "PAI포함";
									}

									if ($option_txt == "") {
										echo "";
									} else {
										echo "(". $option_txt .")";
									}
								?>
                            </td>
						</tr>
						<tr>
							<th>차량인수<br class="view-m">일시</th>
							<td data-th="차량인수 일시">
								<div class="inp_day">
									<input type="text" name="rental_date" id="rental_date" value="<?=$params['rental_date']?>" class="inp_txt calender" readonly>
									<select name="rental_hour" id="rental_hour" class="select1">
                                        <?for ($i=0; $i<=23; $i++) {?>
                                            <option value="<?=addZero($i)?>" <?=chkCompare($params['rental_hour'], addZero($i), 'selected')?>><?=addZero($i)?>시</option>
                                        <?}?>
                                    </select>
                                    <select name="rental_minute" id="rental_minute" class="select1">
                                        <?for ($i=0; $i<=59; $i+=10) {?>
                                            <option value="<?=addZero($i)?>"><?=addZero($i)?>분</option>
                                        <?}?>
                                    </select>
								</div>
							</td>
							<th>기간</th>
							<td data-th="기간">
                                <select name="rental_day" id="rental_day" class="select1 wid2">
									<?
                                        /*
										$rental_amt = 0;
										for ($i=1; $i<=30; $i++) {
											if ($i % 7 == 0) {
												$rental_amt = $goods_view['day7_amt'] * ($i/7);
											} else if ($i % 30 == 0) {
												$rental_amt = $goods_view['day30_amt'] * ($i/30);
											} else {
												$rental_amt += $goods_view['day1_amt'];
											}
									        ?><option value="<?=$i?>" data-rental-amt="<?=$rental_amt?>" <?=chkCompare($params['rental_day'], $i, 'selected')?>><?=$i?>일 ($<?=$rental_amt?>)</option><?
										}*/

                                        for ($i=1; $i<=30; $i++) {
                                            if (strpos("10019, 10020", (string)$params['goods_idx']) !== false) {
                                                $rental_amt = $goods_view['day1_amt'] * $i;
                                            } else {
                                                if ($i < $GOODS_DC_PERIOD_DAY) {
                                                    $rental_amt = $goods_view['day1_amt'] * $i;
                                                } else {
                                                    $rental_amt = round(($goods_view['day1_amt'] - ($goods_view['day1_amt'] * $GOODS_DC_RATE)) * $i);
                                                }
                                            }

                                            ?><option value="<?=$i?>" data-rental-amt="<?=$rental_amt?>" <?=chkCompare($params['rental_day'], $i, 'selected')?>>
                                                <?if (strpos("10019, 10020", (string)$params['goods_idx']) !== false) {?>
                                                    <?=$i?>일 ($<?=formatNumbers($rental_amt)?>)
                                                <?} else {?>
                                                    <?=$i?>일 ($<?=formatNumbers($rental_amt)?> <?=iif($i>=$GOODS_DC_PERIOD_DAY, ' / '. $GOODS_DC_RATE*100 .'% 할인', '')?>)
                                                <?}?>
                                            </option><?
                                        }
									?>
								</select>
							</td>
						</tr>
						<tr>
							<th>인수 픽업 <br class="view-m">장소</th>
							<td data-th="인수 픽업">
                                <select name="pickup_area" id="pickup_area" class="select1 w100p">
									<option value="">인수 픽업 장소 선택</option>
									<?for ($i=0; $i<count($pickup_area_list); $i++) {?>
										<option value="<?=$pickup_area_list[$i]?>"><?=$pickup_area_list[$i]?></option>
									<?}?>
								</select>
							</td>
							<th>한국 출발 항공편</th>
							<td data-th="한국 출발 항공편">
								<div class="inp_n2">
                                    <select name="out_airline" id="out_airline" class="select1">
                                        <option value="">항공사 선택</option>
                                        <?for ($i=0; $i<count($out_airline_list); $i++) {?>
                                            <option value="<?=$out_airline_list[$i]?>"><?=$out_airline_list[$i]?></option>
                                        <?}?>
                                    </select>

									<input type="text" name="out_date" id="out_date" value="<?=$params['rental_date']?>" class="inp_txt calender" readonly>
								</div>
							</td>
						</tr>
                        <tr>
                            <th>투숙 호텔</th>
                            <td data-th="투숙 호텔">
                                <select name="hotel" id="hotel" class="select1 w100p">
                                    <option value="">투숙 호텔 선택</option>
                                    <?for ($i=0; $i<count($hotel_list); $i++) {?>
                                        <option value="<?=$hotel_list[$i]?>"><?=$hotel_list[$i]?></option>
                                    <?}?>
                                </select>
                            </td>
                            <th>반납 장소</th>
                            <td data-th="반납 장소">
                                <select name="return_area" id="return_area" class="select1 w100p">
                                    <option value="">반납 장소 선택</option>
                                    <?for ($i=0; $i<count($return_area_list); $i++) {?>
                                        <option value="<?=$return_area_list[$i]?>"><?=$return_area_list[$i]?></option>
                                    <?}?>
                                </select>
                            </td>
                        </tr>

                        <?if ($apply_policy_221101) {?>
                            <tr>
                                <td colspan="4">
                                    <p class="t_info">
                                        <!-- ※ <strong>공항 픽업시</strong> 공항 주차장에서 픽업 진행이 되며 <strong>Airport(공항픽업)</strong>을 선택을 하셔야 합니다.<br>
                                        ※ <strong>공항 반납시</strong> 1) AIRPORT PARKING을 선택 하시면 <strong>$5 추가</strong>가 되며 직원 미팅 없이 공항 주차장에 직접 반납하시면 됩니다.<br>
                                        <span style="color:#fff">※ <strong>공항 반납시</strong></span> 2) PACIFICRENT OFFICE를 선택 하시면 사무실에 차량 반납을 하시면 직원이 전용차량으로 공항으로 모셔다 드리며  <strong>별도 추가비용은 없습니다.</strong><br> -->

                                        ※ 공항주차장 반납시 AIRPORT PARKING ($5) 선택해 주시면 되며 직원 확인 없이 공항주차장에 직접 반납이 가능 합니다.<br>
                                        ※ 퍼시픽렌트카 사무실로 반납만 가능 합니다.<br>
                                        ※ 24시간 연중무휴로 운영 중이며  사무실로 반납 해 주시면 공항 또는 숙박 하시는 호텔로 모셔다 드립니다.<br>
                                        ※ 시내에서 약 5분거리에 위치해 있습니다.<br>
                                        <span style="color:#fff">※ </span>자세한 사무실 위치는 고객센터 > 공지사항에서 확인 가능 합니다.<br>
                                    </p>
                                </td>
                            </tr>
                        <?}?>

						<tr>
							<th>보조시트</th>
							<td colspan="3" data-th="보조시트">
								<div class="inp_bind">
									<div class="col">
										<span>유아 시트(~12개월)</span>
										<select name="infant_seat_cnt" id="infant_seat_cnt" class="select1">
                                            <option value="" data-seat-amt="0">선택없음</option>
                                            <?for($i=1; $i<=3; $i++) {?>
                                                <option value="<?=$i?>" data-seat-amt="<?=$goods_view['option_6_amt'] * $i?>"><?=$i?>개<?=iif($goods_view['option_6_amt']>0, ' ($'.$goods_view['option_6_amt'] * $i.')', '')?></option>
                                            <?}?>
                                        </select>
									</div>
									<div class="col">
										<span>어린이 시트 (12~24개월)</span>
										<select name="child_seat_cnt" id="child_seat_cnt" class="select1">
                                            <option value="" data-seat-amt="0">선택없음</option>
                                            <?for($i=1; $i<=3; $i++) {?>
                                                <option value="<?=$i?>" data-seat-amt="<?=$goods_view['option_6_amt'] * $i?>"><?=$i?>개<?=iif($goods_view['option_6_amt']>0, ' ($'.$goods_view['option_6_amt'] * $i.')', '')?></option>
                                            <?}?>
                                        </select>
									</div>
									<div class="col">
										<span>부스터 시트 (24개월~)</span>
										<select name="booster_seat_cnt" id="booster_seat_cnt" class="select1">
                                            <option value="" data-seat-amt="0">선택없음</option>
                                            <?for($i=1; $i<=3; $i++) {?>
                                                <option value="<?=$i?>" data-seat-amt="<?=$goods_view['option_6_amt'] * $i?>"><?=$i?>개<?=iif($goods_view['option_6_amt']>0, ' ($'.$goods_view['option_6_amt'] * $i.')', '')?></option>
                                            <?}?>
                                        </select>
									</div>
								</div>
								<p class="t_info">
                                    유아나 어린이 동반시에는 카시트, 부스터를 꼭 신청하셔야 합니다.(현지교통법)<br>
                                    아동보조시트는 <?=$CONST_CAR_SEAT_FREE?>개 무료, 초과분 <?=iif($goods_view['option_6_amt']>0, '$'.$goods_view['option_6_amt'].'/24시간', '무료')?>
                                    (표시가격이 있어도 <?=$CONST_CAR_SEAT_FREE?>대까지 무료지원 됩니다.)
                                </p>
							</td>
						</tr>
                        <?if ($goods_view['option_3']=='Y' || $goods_view['option_5']=='Y') {?>
                            <tr>
                                <th>추가옵션</th>
                                <td colspan="3" class="et" data-th="추가옵션">
                                    <?if ($goods_view['option_3']=='Y') {?>
                                        <label class="inp_checkbox">
                                            <input type="checkbox" name="add_option_1" id="add_option_1" value="Y" data-option-amt="<?=$goods_view['option_3_amt']?>">
                                            <span>아이스박스 (<?=iif($goods_view['option_3_amt']>0, '$'.$goods_view['option_3_amt'].'/24시간', '무료')?>)</span>
                                        </label>
                                    <?}?>
                                    <?if ($goods_view['option_5']=='Y') {?>
                                        <label class="inp_checkbox ml10">
                                            <input type="checkbox" name="add_option_2" id="add_option_2" value="Y" data-option-amt="<?=$goods_view['option_5_amt']?>">
                                            <span>네비게이션 (<?=iif($goods_view['option_5_amt']>0, '$'.$goods_view['option_5_amt'].'/24시간', '무료')?>)</span>
                                        </label>
                                    <?}?>
                                </td>
                            </tr>
                        <?}?>
                        <?if ($goods_view['option_4']=='Y') {?>
							<tr>
								<th>공항픽업</th>
								<td colspan="3" data-th="공항픽업">
									<select name="airport_meeting" id="airport_meeting" class="select1 wid1">
										<option value="N" data-meeting-amt="0">선택안함</option>
										<!-- <option value="N" data-meeting-amt="0">선택안함</option> -->
										<option value="Y" data-meeting-amt="<?=$goods_view['option_4_amt']?>">공항픽업 (<?=iif($goods_view['option_4_amt']>0, '$'.$goods_view['option_4_amt'], '무료')?>)</option>
									</select>

                                    <p class="t_info ml">※ 인수 픽업 장소가 "Airport(공항픽업)"인 경우 공항픽업 선택은 필수 입니다.</p>
								</td>
							</tr>
						<?}?>
						<tr>
							<th>추가<br class="view-m">요청사항</th>
							<td colspan="3" data-th="추가 요청사항"><textarea name="booking_memo" id="booking_memo" class="textarea1" placeholder="500자 이내로 입력해 주세요." maxlength="500"></textarea></td>
						</tr>
					</tbody>
				</table>
			</div>

			<div class="tbl_basic ty2 pr-mb2">
				<table class="write">
					<caption>예약자명</caption>
					<colgroup>
						<col class="th1">
						<col>
					</colgroup>
					<tbody>
						<tr>
							<th>예약자명</th>
							<td>
                                <span>한글이름</span>
                                <input type="text" name="name_kor" id="name_kor" class="inp_txt mr20 w2" placeholder="여권 이름">
                                <br class="view-m">
                                <span>영문이름</span>
                                <input type="text" name="name_eng1" id="name_eng1" class="inp_txt w1" maxlength="10" placeholder="성">
                                <input type="text" name="name_eng2" id="name_eng2" class="inp_txt w2" maxlength="30" placeholder="이름">
                            </td>
						</tr>
						<tr>
							<th>연락처</th>
							<td>
								<input type="text" name="booking_phone" id="booking_phone" class="inp_txt wid1 onlyNum" placeholder="'-' 없이 숫자만 입력" maxlength="11">
								<p class="t_info ml">※ 로밍 예정 핸드폰 번호 입력 요망</p>
							</td>
						</tr>
						<tr>
							<th>이메일</th>
							<td><input type="text" name="booking_email" id="booking_email" class="inp_txt wid1" placeholder="아이디@서비스도메인" maxlength="50"></td>
						</tr>
					</tbody>
				</table>
			</div>

			<!-- 20211108 | 추후사용예정 <div class="tbl_bind">
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
                                    <td><input type="text" name="driver_name_kor<?=$i?>" id="driver_name_kor<?=$i?>" class="inp_txt w100p" maxlength="10" placeholder="여권 이름"></td>
                                </tr>
                                <tr>
                                    <th>운전자 성함(영문)</th>
                                    <td>
                                        <div class="inp_name">
                                            <input type="text" name="driver_name_eng1<?=$i?>" id="driver_name_eng1<?=$i?>" class="inp_txt w1" maxlength="10" placeholder="성 (HONG)">
                                            <input type="text" name="driver_name_eng2<?=$i?>" id="driver_name_eng2<?=$i?>" class="inp_txt w2" maxlength="30" placeholder="이름 (GILDONG)">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>휴대폰번호</th>
                                    <td>
                                        <input type="text" name="driver_phone<?=$i?>" id="driver_phone<?=$i?>"class="inp_txt w100p onlyNum" placeholder="'-' 없이 숫자만 입력" maxlength="11">
                                        <p class="t_info mt5">※ 로밍 예정 핸드폰 번호 입력요망</p>
                                    </td>
                                </tr>
                                <tr>
                                    <th>생년월일</th>
                                    <td><input type="text" name="driver_birthdate<?=$i?>" id="driver_birthdate<?=$i?>" class="inp_txt w100p onlyNum" placeholder="예) <?=date('Ymd')?>" maxlength="8"></td>
                                </tr>
                                <tr>
                                    <th>운전면허증 번호</th>
                                    <td><input type="text" name="driver_license<?=$i?>" id="driver_license<?=$i?>" class="inp_txt w100p" placeholder="예) 서울 01-123456-78" maxlength="20"></td>
                                </tr>
                                <tr>
                                    <th>운전면허 만료일</th>
                                    <td><input type="text" name="driver_license_expiry_date<?=$i?>" id="driver_license_expiry_date<?=$i?>" class="inp_txt w100p onlyNum" placeholder="예) <?=date('Ymd')?>" maxlength="8"></td>
                                </tr>
                                <tr>
                                    <th>한국주소</th>
                                    <td>
                                        <div class="inp_addr">
                                            <input type="hidden" name="driver_zipcode<?=$i?>" id="driver_zipcode<?=$i?>" />
                                            <button type="button" class="btn-pk green n" onclick="postCode('driver_zipcode<?=$i?>', 'driver_addr<?=$i?>', 'driver_addr_detail<?=$i?>')"><span>주소 찾기</span></button>
                                            <input type="text" name="driver_addr<?=$i?>" id="driver_addr<?=$i?>" class="inp_txt" placeholder="현재 거주 중인 주소를 선택해 주세요." readonly>
                                            <input type="text" name="driver_addr_detail<?=$i?>" id="driver_addr_detail<?=$i?>" class="inp_txt w100p" maxlength="100" placeholder="상세 주소를 입력해 주세요.">
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <th>현지주소</th>
                                    <td><input type="text" name="driver_local_addr<?=$i?>" id="driver_local_addr<?=$i?>" class="inp_txt w100p" maxlength="200"
										placeholder="현지에서 거주 중인 주소 또는 투숙 호텔 정보를 입력해 주세요."></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                <?}?>
			</div> -->

			<div class="mt30">
				<div><label class="inp_checkbox"><input type="checkbox" id="agree_1" value="Y"><span>(필수)이용약관에 동의합니다. <a href="/customer/terms.php" target="_blank" class="c-orange a_link">보기</a></span></label></div>
				<div class="mt15"><label class="inp_checkbox"><input type="checkbox" id="agree_2" value="Y"><span>(필수)개인정보처리방침에 동의합니다. <a href="/customer/privacy.php" target="_blank" class="c-orange a_link">보기</a></span></label></div>
			</div>
            </form>

			<div class="btn-bot">
				<div class="box_total">
					<p class="t1">총 대여일 : <strong class="total_rental_day"><?=$params['rental_day']?>일</strong></p>
					<p class="t1">현지지불금액 : <strong class="total_rental_amt">$0</strong></p>
					<p class="tt c-orange"><span>총 온라인 예약 대행 수수료</span><strong class="f-gm total_agency_amt">￦0</strong></p>
				</div>

				<button type="button" class="btn-pk b orange w100p f-gm" onclick="bookingGo()"><span>예약하기</span></button>
			</div>
		</div>
	</div>
</div><!--//container -->

<script>
	var enable_day = "";
	$(function(){
		$("#out_date").datepicker({
			minDate: "<?=date('Y-m-d')?>",
			dateFormat: "yy-mm-dd",
			showOn: "both",
			buttonImage: "/images/common/ico_calender.png"
		});

		//출발가능 체크
		AJ.callAjax("_check_date.php", {"goods_idx": <?=$goods_view['idx']?>}, function(data){
			if (data.result == 200) {
				if (data.list) {
					var enable_day = [];
					$.each(data.list, function(i, item){
						enable_day.push(item.date);
					})

					setTimeout(function(){
						$("#rental_date").datepicker({
							minDate: "<?=$start_rent_date?>",
							dateFormat: "yy-mm-dd",
							showOn: "both",
							buttonImage: "/images/common/ico_calender.png",
							beforeShowDay: function(date) {
								var dummy = date.getFullYear() + "-" + addZero(date.getMonth() + 1) +"-"+ addZero(date.getDate());

								if ($.inArray(dummy, enable_day) > -1) {
									return [true, ""];
								}

								return [false, ""];
							}
						});
					},0)
				} else {

				}
			} else {
				alert(data.message);

				location.back();
			}
		});

		//예약금액 확인
		$("#rental_day, #infant_seat_cnt, #child_seat_cnt, #booster_seat_cnt, #airport_meeting").change(function(){
			totalRentalAmountCalc();
		})
		$("#add_option_1, #add_option_2").click(function(){
			totalRentalAmountCalc();
		})
		$("#rental_day").trigger("change");


		//영문 대문자변환
		$("#name_eng1, #name_eng2, #driver_name_eng11, #driver_name_eng21, #driver_name_eng12, #driver_name_eng22").blur(function(){
			var this_val = $(this).val();

			$(this).val( this_val.toUpperCase() );
		});

        //공항픽업 선택
        $("#pickup_area").change(function(){
            if ($(this).val() == 'Airport(공항픽업)') {
                $("#airport_meeting").val("Y");
            } else {
                $("#airport_meeting").val("N");
            }

            totalRentalAmountCalc();
        });

        //반납장소 선택
        $("#return_area").change(function(){
            totalRentalAmountCalc();
        });
	})

	function totalRentalAmountCalc() {
		var rental_day          = parseInt($("#rental_day").val());
		var rental_amt          = $("#rental_day").find("option:selected").data("rental-amt");
		var infant_seat_cnt     = parseInt($("#infant_seat_cnt").val());
		var infant_seat_amt     = $("#infant_seat_cnt").find("option:selected").data("seat-amt");
		var child_seat_cnt      = parseInt($("#child_seat_cnt").val());
		var child_seat_amt      = $("#child_seat_cnt").find("option:selected").data("seat-amt");
		var booster_seat_cnt    = parseInt($("#booster_seat_cnt").val());
		var booster_seat_amt    = $("#booster_seat_cnt").find("option:selected").data("seat-amt");
		var airport_meeting_amt = $("#airport_meeting").find("option:selected").data("meeting-amt");
		var add_option_1_amt    = $("#add_option_1:checked").data("option-amt");
		var add_option_2_amt    = $("#add_option_2:checked").data("option-amt");
        var agency_fee          = <?=$goods_view['agency_fee']?>;
        var return_area_amt     = $("#return_area").val();
            return_area_amt     = return_area_amt.indexOf("AIRPORT PARKING") > -1 ? return_area_amt.split("(")[1].toInt() : 0;

		if (!infant_seat_cnt) infant_seat_cnt = 0;
		if (!infant_seat_amt) infant_seat_amt = 0;
		if (!child_seat_cnt) child_seat_cnt = 0;
		if (!child_seat_amt) child_seat_amt = 0;
		if (!booster_seat_cnt) booster_seat_cnt = 0;
		if (!booster_seat_amt) booster_seat_amt = 0;
		if (!airport_meeting_amt) airport_meeting_amt = 0;
		if (!add_option_1_amt) add_option_1_amt = 0;
		if (!add_option_2_amt) add_option_2_amt = 0;
		if (!return_area_amt) return_area_amt = 0;

		var total_seat_amt = 0;
		<?if ($goods_view['option_6']=='Y') {?>
			if ((infant_seat_cnt + child_seat_cnt + booster_seat_cnt) > <?=$CONST_CAR_SEAT_FREE?>) {
				total_seat_amt = (infant_seat_amt + child_seat_amt + booster_seat_amt) - <?=$goods_view['option_6_amt'] * $CONST_CAR_SEAT_FREE?>;
			}

            total_seat_amt = total_seat_amt * rental_day;
		<?}?>

        var total_agency_amt = agency_fee;
		var total_rental_amt = (rental_amt + total_seat_amt + airport_meeting_amt + add_option_1_amt + add_option_2_amt + return_area_amt);

		$(".total_rental_day").text(rental_day+"일");
		$(".total_rental_amt").text("$"+total_rental_amt.addComma());
		$(".total_agency_amt").text("￦"+total_agency_amt.addComma());
	}

	function bookingGo() {
		var h = new clsJsHelper();

		if (!h.checkSelect("pickup_area", "인수 픽업 장소")) return false;
		if (!h.checkSelect("return_area", "반납 장소")) return false;
		if (!h.checkSelect("hotel", "투숙 호텔")) return false;
		if (!h.checkSelect("out_airline", "한국 출발 항공편")) return false;
		//if (!h.checkSelect("in_airline", "귀국일 항공사")) return false;

		<?if ($goods_view['option_4']=='Y') {?>
		    //if (!h.checkSelect("airport_meeting", "공항픽업")) return false;
            if (h.objVal("pickup_area") == "Airport(공항픽업)") {
                if (h.objVal("airport_meeting") != "Y") {
                    alert("인수 픽업 장소가 'Airport(공항픽업)'인 경우 공항픽업 선택은 필수 입니다.");
                    return false;
                }
            } else {
                if (h.objVal("airport_meeting") == "Y") {
                    alert("공항픽업은 인수 픽업 장소가 'Airport(공항픽업)'인 경우만 선택 가능합니다.");
                    return false;
                }
            }
		<?}?>

		if (h.objVal("booking_memo")) {
			if (!h.checkValNLen("booking_memo", 1, 1000, "추가요청사항", "N", "KO")) return false;
		}

		if (!h.checkValNLen("name_kor", 2, 20, "예약자 한글 이름", "Y", "KO")) return false;
		if (!h.checkValNLen("name_eng1", 2, 10, "예약자 영문(성)", "Y", "EN")) return false;
		if (!h.checkValNLen("name_eng2", 2, 30, "예약자 영문(이름)", "Y", "EN")) return false;
		if (!h.checkValNLen("booking_phone", 10, 11, "예약자 휴대폰번호", "Y", "ON")) return false;
		if (!phoneRegExpCheck(h.objVal("booking_phone"), "예약자 휴대폰번호", "")) return false;
		if (!h.checkValNLen("booking_email", 10, 50, "예약자 이메일", "Y", "EN")) return false;
		if (!h.checkEmail("booking_email", "예약자 이메일")) return false;
		//if (!h.checkSelect("adult_cnt", "여행인원(성인)")) return false;
		//if ((parseInt(h.objVal("adult_cnt")) + parseInt(h.objVal("child_cnt")) + parseInt(h.objVal("infant_cnt"))) >= 10) {
		//	alert("여행인원은 최대 10명까지 선택 가능합니다.");
		//	return false;
		//}

		if (h.objVal("out_date") > h.objVal("rental_date")) {
			alert("렌트 시작 날짜는 출국일 보다 작을 수 없습니다.\n렌트 시작 날짜를 다시 확인해주세요.");
			return false;
		}

		/*
		var rent_period = moment(h.objVal("rental_date")).add(h.objVal("rental_day"),'days').format("YYYY-MM-DD");
		if (rent_period > h.objVal("in_date")) {
			alert("귀국일은 렌트 종료 날짜("+ rent_period +") 보다 작을 수 없습니다.\n귀국일을 다시 확인해주세요.");
			return false;
		}
		*/


		<?if ($goods_view['option_6']=='Y') {?>
			var total_seat_cnt   = 0;
			var infant_seat_cnt  = parseInt($("#infant_seat_cnt").val());
			var child_seat_cnt   = parseInt($("#child_seat_cnt").val());
			var booster_seat_cnt = parseInt($("#booster_seat_cnt").val());

			if (!infant_seat_cnt) infant_seat_cnt = 0;
			if (!child_seat_cnt) child_seat_cnt = 0;
			if (!booster_seat_cnt) booster_seat_cnt = 0;

			total_seat_cnt = infant_seat_cnt + child_seat_cnt + booster_seat_cnt;

			/*
			if (total_seat_cnt > 0) {
				var child_cnt  = parseInt($("#child_cnt").val());
				var infant_cnt = parseInt($("#infant_cnt").val());

				if (!child_cnt) child_cnt = 0;
				if (!infant_cnt) infant_cnt = 0;

				if ((child_cnt+infant_cnt) < total_seat_cnt) {
					alert("소아/유아의 인원수가 보조시트 신청 개수 보다 작습니다.\n소아/유아의 인원수를 선택한 보조시트 개수 보다 같거나 크게 선택해 주세요.");
					return false;
				}
			}
			*/
		<?}?>

        /* 20211108 | 추후사용예정
		for (i=1; i<=2; i++) {
			if (i==1 || h.objVal("driver_name_kor"+i) || h.objVal("driver_name_eng1"+i) || h.objVal("driver_name_eng2"+i)
				|| h.objVal("driver_addr"+i) || h.objVal("driver_addr_detail"+i) || h.objVal("driver_local_addr"+i)
				|| h.objVal("driver_phone"+i) || h.objVal("driver_birthdate"+i) || h.objVal("driver_license"+i) || h.objVal("driver_license_expiry_date"+i)
			) {
				if (!h.checkValNLen("driver_name_kor"+i, 2, 20, "운전자 "+ i +"의 한글 이름", "Y", "KO")) return false;
				if (!h.checkValNLen("driver_name_eng1"+i, 2, 10, "운전자 "+ i +"의 영문(성)", "Y", "EN")) return false;
				if (!h.checkValNLen("driver_name_eng2"+i, 2, 30, "운전자 "+ i +"의 영문(이름)", "Y", "EN")) return false;
				if (!h.checkSelect("driver_addr"+i, "운전자 "+ i +"의 주소")) return false;
				if (h.objVal("driver_addr_detail"+i)) {
					if (!h.checkValNLen("driver_addr_detail"+i, 1, 100, "운전자 "+ i +"의 상세주소", "N", "KO")) return false;
				}
				if (!h.checkValNLen("driver_local_addr"+i, 1, 200, "운전자 "+ i +"의 현지주소", "N", "KO")) return false;
				if (!h.checkValNLen("driver_phone"+i, 10, 11, "운전자 "+ i +"의 휴대폰번호", "Y", "ON")) return false;
				if (!phoneRegExpCheck(h.objVal("driver_phone"+i), "운전자 "+ i +"의 휴대폰번호", "")) return false;
				if (!h.checkValNLen("driver_birthdate"+i, 8, 8, "운전자 "+ i +"의 생년월일", "Y", "ON")) return false;
				if(!moment(h.objVal("driver_birthdate"+i), 'YYYYMMDD' , true).isValid()) {
					alert("생년월일이 유효하지 않습니다.\n생년월일을 다시 확인해주세요.")
					return false;
				}
				if (!h.checkValNLen("driver_license"+i, 2, 20, "운전자 "+ i +"의 운전면허번호", "N", "KO")) return false;
				if (!h.checkValNLen("driver_license_expiry_date"+i, 8, 8, "운전자 "+ i +"의 운전면허 만료일", "Y", "ON")) return false;
				if(!moment(h.objVal("driver_license_expiry_date"+i), 'YYYYMMDD' , true).isValid()) {
					alert("만료일이 유효하지 않습니다.\n만료일을 다시 확인해주세요.")
					return false;
				}
			}
		}
        */

		if (!$("#agree_1").is(":checked")) {
			alert("이용약관에 동의하셔야 합니다.");
			return false;
		}
		if (!$("#agree_2").is(":checked")) {
			alert("개인정보처리방침에 동의하셔야 합니다.");
			return false;
		}

		if (!confirm("입력하신 정보로 예약을 진행하시겠습니까?")) return false;

		AJ.ajaxForm($("#bookingFrm"), "booking_proc.php", function(data) {
			if (data.result == 200) {
				location.replace("/mypage/reservation.php?token="+data.token);
			} else {
				alert(data.message);
			}
		});
	}
</script>

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>