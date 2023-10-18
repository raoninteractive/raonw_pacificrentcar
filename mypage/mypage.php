<? include "../inc/config.php" ?>
<?
	$pageNum = "0501";
	$pageName = "나의 예약정보";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
    <? include "../inc/spot.php" ?>

	<div class="contents wrap_mypage">
		<div class="inr-c">
			<div class="hd_tit1">
				<h2 class="h2">예약시 작성하신 예약번호와 연락처 입력 후 <br class="hide-m">확인을 클릭하세요.</h2>
			</div>
			<div class="area_info">
                <form name="bookingFrm" id="bookingFrm" method="post">
				<div class="inner">
					<ul>
						<li>
							<span>예약번호</span>
							<div><input type="text" name="booking_num" id="booking_num" class="inp_txt w100p" maxlength="15" placeholder="예약번호를 입력해 주십시오."></div>
						</li>
						<li>
							<span>연락처</span>
							<div><input type="text" name="booker_phone" id="booker_phone" class="inp_txt w100p" maxlength="11" placeholder="'-'없이 입력해 주십시오."></div>
						</li>
					</ul>

					<a href="javascript:;" class="btn-pk b orange w100p f-gm" onclick="bookingSearchGo()"><span>확인</span></a>
				</div>
                </form>
			</div>
		</div>
	</div>
</div><!--//container -->

<script>
	function bookingSearchGo() {
		var h = new clsJsHelper();

		if (!h.checkValNLen("booking_num", 15, 15, "예약번호", "Y", "ON")) return false;
		if (!h.checkValNLen("booker_phone", 10, 11, "휴대폰번호", "Y", "ON")) return false;
		if (!phoneRegExpCheck(h.objVal("booker_phone"), "휴대폰번호", "")) return false;

		AJ.ajaxForm($("#bookingFrm"), "_booking_check_proc.php", function(data) {
			if (data.result == 200) {
				location = data.page_url + "?token="+data.token;
			} else {
				alert(data.message);
			}
		});
	}
</script>

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>