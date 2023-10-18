<? include "../inc/config.php" ?>
<?
	$pageNum = "0403";
	$pageName = "고객센터";
?>
<? include "../inc/top.php" ?>
<? include "../inc/header.php" ?>

<div id="container" class="container sub">
    <? include "../inc/spot.php" ?>

	<div class="contents wrap_notice">
		<div class="inr-c">
			<div class="tab ty1 bd">
				<ul>
					<li><a href="notice.php">공지사항</a></li>
					<li><a href="faq.php">자주하는 질문</a></li>
					<li class="on"><a href="inquiry.php">문의하기</a></li>
				</ul>
			</div>

            <form name="qnaFrm" id="qnaFrm" method="post">
			<div class="tbl_basic ty2 pr-mb2">
				<table class="write">
					<colgroup>
						<col class="th1">
						<col>
					</colgroup>
					<tbody>
						<tr>
							<th>이름</th>
							<td><input type="text" name="qna_name" id="qna_name" class="inp_txt w100p" placeholder="이름을 입력해주세요." maxlength="10"></td>
						</tr>
						<tr>
							<th>휴대폰 번호</th>
							<td><input type="text" name="qna_phone" id="qna_phone" class="inp_txt w100p onlyNum" placeholder="'-' 없이 입력해주세요." maxlength="11"></td>
						</tr>
						<tr>
							<th>이메일</th>
							<td><input type="text" name="qna_email" id="qna_email" class="inp_txt w100p" placeholder="이메일을 입력해주세요." maxlength="50"></td>
						</tr>
						<tr>
							<th>문의내용</th>
							<td><textarea name="qna_content" id="qna_content" class="textarea1" placeholder="내용은 500자 이내로 입력해주세요." maxlength="500"></textarea></td>
						</tr>
					</tbody>
				</table>
			</div>
            </form>

			<h2 class="stit1">개인정보수집 및 이용에 대한 동의 </h2>
			<div class="box_terms"><strong>개인정보보호법 등 관련 법규에 의거하여 퍼시픽렌터카 개인정보 수집 및 활용함에 대해 동의합니다.</strong>
				- 개인정보 수집 항목 : 성명, 휴대폰 이메일
				- 개인정보 이용 목적 : 렌터카 이용에 대한 문의 및 답변 목적으로만 사용되며, 다른 목적으로는 사용되지 않습니다.
			</div>
			<div class="mt20 ta-r">
				<div><label class="inp_checkbox"><input type="checkbox" id="agree"><span>개인정보 수집 및 이용에 동의합니다.</span></label></div>
			</div>

			<div class="btn-bot ta-c">
				<a href="javascript:;" class="btn-pk b orange f-gm mw100p" onclick="qnaGo()">문의등록</a>
			</div>
		</div>
	</div>
</div><!--//container -->

<script>
    function qnaGo() {
		var h = new clsJsHelper();

		if (!h.checkValNLen("qna_name", 2, 20, "이름", "Y", "KO")) return false;
		if (!h.checkValNLen("qna_phone", 10, 11, "휴대폰번호", "Y", "ON")) return false;
		if (!phoneRegExpCheck(h.objVal("qna_phone"), "휴대폰번호", "")) return false;
		if (!h.checkValNLen("qna_email", 10, 50, "이메일", "Y", "EN")) return false;
		if (!h.checkEmail("qna_email", "이메일")) return false;
		if (!h.checkValNLen("qna_content", 1, 1000, "문의내용", "N", "KO")) return false;

        if (!$("#agree").is(":checked")) {
            alert("개인정보 수집 및 이용에 동의해주세요.");
            return false;
        }

		AJ.ajaxForm($("#qnaFrm"), "inquiry_proc.php", function(data) {
			if (data.result == 200) {
				alert("문의가 접수되었습니다.\n빠른 시일내에 답변 드리겠습니다.");

				$("#qnaFrm")[0].reset();
			} else {
				alert(data.message);
			}
		});
	}
</script>

<? include "../inc/footer.php" ?>
<? include "../inc/bottom.php" ?>