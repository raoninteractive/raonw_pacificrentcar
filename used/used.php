<? include "../inc/config.php" ?>
<?
    //정책적용 시작일
    $apply_policy_221101 = iif(datediff('day', '2022-11-01', date('Y-m-d')) >= 0, true, false); //2022.11.01 정책적용
    if ($DEV_IP) {
        $apply_policy_221101 = true;
    }


	$pageNum = "0201";
	$pageName = "이용안내";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
	<? include "../inc/spot.php" ?>

	<div class="contents wrap_used">
		<div class="inr-c">
			<h2 class="stit1">렌터카 예약 및 이용</h2>
			<div class="lst_used pr-mb1">
				<ul>
					<li>
						<div class="icon"><span class="i-set i_use1"></span></div>
						<p class="t1">렌터카 예약신청<br>(홈페이지)</p>
					</li>
					<li>
						<div class="icon"><span class="i-set i_use2"></span></div>
						<p class="t1">예약수수료 결제 및 <br>확정서 확인</p>
					</li>
					<li>
						<div class="icon"><span class="i-set i_use3"></span></div>
						<p class="t1">현지도착후 <br>미팅 /계약서 작성</p>
					</li>
					<li>
						<div class="icon"><span class="i-set i_use4"></span></div>
						<p class="t1">렌터카 요금<br>결제 및 차량인수</p>
					</li>
					<li>
						<div class="icon"><span class="i-set i_use5"></span></div>
						<p class="t1">이용 후 차량반납</p>
					</li>
				</ul>
			</div>

			<h2 class="stit1">픽업/반납 이용방법</h2>
			<div class="tbl_bind pr-mb1">
				<div class="tbl_basic bg1">
					<table>
						<thead>
							<tr>
								<th colspan="2">픽업</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>공항</th>
								<td>
                                    공항 도착 후 WEST ARRIVALS로 이동 후 PACIFIC RENTALS 직원 미팅 공항내에 계시면 미팅이 불가능 하므로 WEST ARRIVALS 입구 밖에서 대기 부탁 드립니다.<br>
                                    직원 미팅 후 공항주차장으로 이동 후 차량 인수 및 결제 진행이 됩니다.
								</td>
							</tr>
							<tr>
								<th>호텔 픽업  <br>(오전8시~오후10시)</th>
								<td>
                                    <?if ($apply_policy_221101) {?>
                                        예약 시 요청 하신 픽업 시간에 맞춰 로비에서 PACIFICRENT CAR 직원 미팅 후 "PACIFICRENT" 사무실로 이동<br>
                                        (투몬 기준 편도 10분이내)<br>
                                        사무실 도착 후 차량 인수 및 결제 진행
                                    <?} else {?>
                                        지정 된 시간에 호텔 로비로 나오시면  PECIFIC RENT CAR 직원 미팅 ( 미팅 보드판 또는 호명)
                                        호텔로 요청 하신 차량을 가지고 직원이 직접 갑니다.<br>
                                        호텔 주차장에서 차량 확인 후 결제 진행이 됩니다.
                                   <?}?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>

				<div class="tbl_basic bg2">
					<table>
						<thead>
							<tr>
								<th colspan="2">반납</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<th>공항</th>
								<td>
                                    <?if ($apply_policy_221101) {?>
                                        <strong>1) 예약 시 PACIFICRENT OFFICE 선택</strong><br>
                                        괌공항에서 약 5분거리에 있는 "PACIFICRENT" 사무실로 차량 반납 해 주시면 차량 반납 확인 후 직원이 직접 공항으로 모셔다 드립니다.<br>
                                        추가 비용은 없습니다.<br>
                                        <strong>2) 예약 시 AIRPORT PARKING 선택</strong><br>
                                        차종 및 사용일 구분 없이 추가 비용 $5 이며 공항 주차장에 직원 미팅 없이 반납이 가능합니다.
                                    <?} else {?>
                                        공항 도착 후 차량진입은 Public (퍼블릭)주차장으로 하셔야 렌트카 주차가 가능 하며 (타주차장은불가)
										주차권 발급 후 주차 가능한곳(장애인구역, 타렌트카 회사 지정석등 제외)에 주차 후
										추차권 및 차량키는 보조석에 넣으시며 됩니다 (별도로 차량 잠금장치는 하지 않습니다)<br>
										차량 주차 후 차량 외관 사진촬영 또는 동영상을 촬영 해 주시면 추후 문제 발생시 처리가 쉽습니다. <br>
										반납 시간 외 반납 시 차종 구분 없이 대여 차량 1일 금액이 청구가 됩니다.
                                    <?}?>
								</td>
							</tr>
							<!-- <tr>
								<th>호텔 반납 </th>
								<td>반납 하시리고 한 호텔 및 반납 시간에 차량키는 보조석에 넣으신 후 주차 가능 지역에 주차 하시면 완료 됩니다.<br>
										(차량문은 잠금장치는 하지 않습니다. 장애인구역, 타렌트카 회사 지정석등 제외)  <br>
										차량 주차 후 차량 외관 사진촬영 또는 동영상을 촬영 해 주시면 추후 문제 발생시 처리가 쉽습니다 <br>
										반납 시간 외 반납 시 차종 구분 없이 대여 차량 1일 금액이 청구가 됩니다.<br>
								</td>
							</tr> -->

                            <tr>
								<th>호텔 반납 </th>
								<td>
                                    퍼시픽렌트카 사무실로 반납만 가능 합니다.<br>
                                    24시간 연중무휴로 운영 중이며  사무실로 반납 해 주시면 공항 또는 숙박 하시는 호텔로 모셔다 드립니다.<br>
                                    자세한 사무실 위치는 고객센터 > 공지사항에서 확인 가능 합니다.
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>

			<h2 class="stit1">결제방법</h2>
			<p class="txt1"><strong>결제 및 디파짓</strong></p>
			<div class="lst_dot">
				<ul>
					<li>결제는 차량 인수 시 카드 결제만 가능 합니다 (비자 마스타  JCB 디스커버 다이너스카드만 가능) 그외 카드 불가</li>
					<li>타렌트카 회사와 달리 디파짓은 별도로 하지 않습니다</li>
				</ul>
			</div>
		</div>
	</div>
</div><!--//container -->




<!-- 팝업 -->
<div id="popCarview" class="layerPopup pop_carview">
	<div class="popup">
		<div class="p_head ty1">
			<h2 class="hidden">차량상세</h2>
			<button type="button" class="btn_close b-close"><span>닫기</span></button>
		</div>
		<div class="p_cont">
			<div class="bat"><span class="i-txt">5인승</span><span class="i-txt">소형</span><span class="i-txt">오토매틱</span><span class="i-txt">휘발류</span></div>
			<div class="cont">
				<div class="img"><img src="../images/img_car1.jpg" alt="사진"></div>
				<p class="h1"><span>Mitsubishi</span><span class="f-gm">Mirage</span></p>
				<div class="tbl_basic f-gm">
					<table class="list">
						<caption>차량정보</caption>
						<thead>
							<tr>
								<th>주유</th>
								<th>CDW</th>
								<th>ZDC</th>
								<th>PAI</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td></td>
								<td>O</td>
								<td></td>
								<td>O</td>
							</tr>
						</tbody>
					</table>
				</div>
				<div class="lst_dot">
					<ul>
						<li>5인승/4도어/오토매틱/휘발유(GAS)</li>
						<li>공항 및 호텔 픽업 반납 모두 무료</li>
						<li>아이스박스, 보조시트2개, 추가운전자 무료</li>
						<li>각종 세금(TAX) 포함</li>
						<li>종합보험(CDW)+상해보험 (PAI)포함</li>
					</ul>
				</div>
			</div>
		</div>
		<div class="p_foo">
			<p class="f-gm"><strong>$ 38</strong> / 24시간</p>
		</div>
	</div>
</div>

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>