<? include "../inc/config.php" ?>
<?
    if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "BACK", "");

    $params['goods_idx']   = chkReqRpl("goods_idx", null, "", "POST", "INT");
    $params['tour_date']   = chkReqRpl("tour_date", "", 10, "POST", "STS");
    $params['adult_cnt']   = chkReqRpl("adult_cnt", 0, "", "POST", "INT");
    $params['child_cnt']   = chkReqRpl("child_cnt", 0, "", "POST", "INT");
    $params['infant_cnt']  = chkReqRpl("infant_cnt", 0, "", "POST", "INT");
    $params['pickup_area'] = chkReqRpl("pickup_area", "", 50, "POST", "STS");
    $params['tour_time']   = chkReqRpl("tour_time", "", 10, "POST", "STS");

    if (chkBlank($params['goods_idx'])) fnMsgGo(501, "상품정보 값이 유효하지 않습니다.", "BACK", "");
    if (chkBlank($params['tour_date'])) fnMsgGo(502, "투어 날짜 값이 유효하지 않습니다.", "BACK", "");
    if (chkBlank($params['pickup_area'])) fnMsgGo(503, "픽업 장소 고유번호 값이 유효하지 않습니다.", "BACK", "");
    if (chkBlank($params['tour_time'])) fnMsgGo(504, "시간 고유번호 값이 유효하지 않습니다.", "BACK", "");
    if (($params['adult_cnt']+$params['child_cnt']) == 0) fnMsgGo(505, "투어 인원 값이 유효하지 않습니다.", "BACK", "");

    $cls_goods = new CLS_GOODS;

    $view = $cls_goods->choice_tour_view($params['goods_idx'], 'Y');
    if ($view == false) fnMsgGo(506, "일치하는 상품정보가 없습니다.", "BACK", "");

    $total_tour_amt   = ($view['adult_amt']*$params['adult_cnt']) + ($view['child_amt']*$params['child_cnt']) + ($view['infant_amt']*$params['infant_cnt']);
    $total_agency_fee = ($params['adult_cnt'] + $params['child_cnt']) * $view['agency_fee'];

	$pageNum = "0303";
	$pageName = "선택관광";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
    <? include "../inc/spot.php" ?>

	<div class="contents">
		<div class="inr-c">
			<div class="tbl_basic ty2 pr-mb2">
				<table class="write">
					<caption>예약정보</caption>
					<colgroup>
						<col class="th1">
						<col>
					</colgroup>
					<tbody>
						<tr>
							<th>상품명</th>
							<td><?=$view['title']?></td>
						</tr>
						<tr>
							<th>투어 날짜</th>
							<td><?=formatDates($params['tour_date'], 'Y.m.d')?></td>
						</tr>
						<tr>
							<th>시간</th>
							<td><?=$params['tour_time']?></td>
						</tr>
						<tr>
							<th>투어 인원</th>
							<td>
                                <?=formatNumbers($params['adult_cnt']+$params['child_cnt']+$params['infant_cnt'])?>명
                                <?
                                    $tour_people = '';
                                    if ($params['adult_cnt'] > 0) {
                                        $tour_people .= '성인 '. $params['adult_cnt'].'명';
                                    }

                                    if ($params['child_cnt'] > 0) {
                                        if ($tour_people != '') $tour_people .= ' / ';
                                        $tour_people .= '아동 '. $params['child_cnt'].'명';
                                    }

                                    if ($params['infant_cnt'] > 0) {
                                        if ($tour_people != '') $tour_people .= ' / ';
                                        $tour_people .= '유아 '. $params['infant_cnt'].'명';
                                    }

                                    echo "($tour_people)";
                                ?>
                            </td>
						</tr>
						<tr>
							<th>픽업 장소</th>
							<td><?=$params['pickup_area']?></td>
						</tr>
					</tbody>
				</table>
			</div>

            <form name="bookingFrm" id="bookingFrm" method="post">
			<input type="hidden" name="goods_idx" value="<?=$params['goods_idx']?>">
			<input type="hidden" name="tour_date" value="<?=$params['tour_date']?>">
			<input type="hidden" name="adult_cnt" value="<?=$params['adult_cnt']?>">
			<input type="hidden" name="child_cnt" value="<?=$params['child_cnt']?>">
			<input type="hidden" name="infant_cnt" value="<?=$params['infant_cnt']?>">
			<input type="hidden" name="pickup_area" value="<?=$params['pickup_area']?>">
			<input type="hidden" name="tour_time" value="<?=$params['tour_time']?>">
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
							<th>이름(한글)</th>
							<td data-th="이름(한글)"><input type="text" name="name_kor" id="name_kor" class="inp_txt w100p" placeholder="여권 이름"></td>
							<th>이름(영문)</th>
							<td data-th="이름(영문)">
								<div class="inp_name">
									<input type="text" name="name_eng1" id="name_eng1" class="inp_txt w1" maxlength="10" placeholder="성">
									<input type="text" name="name_eng2" id="name_eng2" class="inp_txt w2" maxlength="30" placeholder="이름">
								</div>
							</td>
						</tr>
						<tr>
							<th>연락처</th>
							<td data-th="연락처">
								<input type="text" name="booking_phone" id="booking_phone" class="inp_txt w100p onlyNum" placeholder="'-' 없이 숫자만 입력" maxlength="11">
								<p class="t_info mt">※ 로밍 예정 핸드폰 번호 입력 요망</p>
							</td>
							<th>이메일</th>
							<td data-th="이메일"><input type="text" name="booking_email" id="booking_email" class="inp_txt w100p" placeholder="아이디@서비스도메인" maxlength="50"></td>
						</tr>
						<tr>
							<th>추가요청사항</th>
							<td colspan="3" data-th="추가 요청사항"><textarea name="booking_memo" id="booking_memo" class="textarea1" placeholder="500자 이내로 입력해 주세요." maxlength="500"></textarea></td>
						</tr>
					</tbody>
				</table>

                <div class="mt30">
                    <div><label class="inp_checkbox"><input type="checkbox" id="agree_1" value="Y"><span>(필수)이용약관에 동의합니다. <a href="/customer/terms.php" target="_blank" class="c-orange a_link">보기</a></span></label></div>
                    <div class="mt15"><label class="inp_checkbox"><input type="checkbox" id="agree_2" value="Y"><span>(필수)개인정보처리방침에 동의합니다. <a href="/customer/privacy.php" target="_blank" class="c-orange a_link">보기</a></span></label></div>
                </div>
			</div>
            </form>

			<div class="btn-bot">
				<div class="box_total">
					<p class="t1">현지지불금액 : <strong class="total_rental_amt">$<?=formatNumbers($total_tour_amt)?></strong></p>
					<p class="tt c-orange"><span>총 온라인 예약 대행 수수료</span><strong class="f-gm total_agency_amt">￦<?=formatNumbers($total_agency_fee)?></strong></p>
				</div>

				<button type="button" class="btn-pk b orange w100p f-gm" onclick="bookingGo()"><span>예약하기</span></button>
			</div>
		</div>
	</div>
</div><!--//container -->

<script>
    $(function(){
		//영문 대문자변환
		$("#name_eng1, #name_eng2, #driver_name_eng11").blur(function(){
			var this_val = $(this).val();

			$(this).val( this_val.toUpperCase() );
		})
    })

    function bookingGo() {
        var h = new clsJsHelper();

		if (!h.checkValNLen("name_kor", 2, 20, "예약자 한글 이름", "Y", "KO")) return false;
		if (!h.checkValNLen("name_eng1", 2, 10, "예약자 영문(성)", "Y", "EN")) return false;
		if (!h.checkValNLen("name_eng2", 2, 30, "예약자 영문(이름)", "Y", "EN")) return false;
		if (!h.checkValNLen("booking_phone", 10, 11, "예약자 휴대폰번호", "Y", "ON")) return false;
		if (!phoneRegExpCheck(h.objVal("booking_phone"), "예약자 휴대폰번호", "")) return false;
		if (!h.checkValNLen("booking_email", 10, 50, "예약자 이메일", "Y", "EN")) return false;
		if (!h.checkEmail("booking_email", "예약자 이메일")) return false;

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
				location.replace("/mypage/reservation_tour.php?token="+data.token);
			} else {
				alert(data.message);
			}
		});
    }
</script>

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>