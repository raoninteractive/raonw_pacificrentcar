<?include("../inc/config.php")?>
<?
	if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "../common/logout.php", "");
?>
<div class="dim"></div>
<div class="contents" style="width:600px;margin-left:-300px;">
	<div class="layer_header">
		<h2>SMS 수신자 대량 등록 관리</h2>
		<button type="button" title="레이어 팝업 닫기" class="btn_close_layer" onclick="commonLayerClose('sms_recipient_add_list')"></button>
	</div>
	<div class="cont">
		<div class="explain">
			※ 연락처는 “-”로 구분해서 등록해주십시오. (010-1234-5678, 01012345678)
		</div>
		<div class="common_form">
			<form name="popAdminModifyFrm" id="popAdminModifyFrm" method="post">
			<table class="g_table">
				<colgroup>
					<col style="width:*">
				</colgroup>
				<tbody>
					<tr>
						<td>
							<div class="textarea_box" style="width:100%;">
								<textarea name="phone_num" id="phone_num" style="height:400px" placeholder="엑셀의 휴대폰번호를 복사 붙여넣기 해주세요."></textarea>
							</div>
						</td>
					</tr>
				</tbody>
			</table>
			</form>

			<div class="btn_area two">
				<a href="javascript:;" class="btn gray" onclick="commonLayerClose('sms_recipient_add_list')">닫기</a>
				<a href="javascript:;" class="btn blue" onclick="recipientBigAddGo()">적용</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	//수신자 대략 등록
	function recipientBigAddGo() {
		if (!h.checkVal("phone_num", "휴대폰번호")) return false;

		var phone_num = h.objVal("phone_num");

		phone_arr = phone_num.split(/\n/);

		//휴대폰번호 체크
		for (i=0; i<phone_arr.length; i++) {
			var phoneNum = $.trim(phone_arr[i]);

			if (!phoneRegExpCheck(phoneNum, "", "-") && !phoneRegExpCheck(phoneNum, "", "")) {
				alert((i+1)+"번째 휴대폰번호 형식이 올바르지 않습니다.\n\n※올바른 형식 : 010-1234-5678, 01012345678");
				return false;
			}
		}

		//휴대폰번호 등록
		for (i=0; i<phone_arr.length; i++) {
			var phoneNum = $.trim(phone_arr[i]);

			var name = phoneNum.right(4)+"님";
			recipientListAdd("non-member|"+ name +"|"+ phoneNum);
		}

		commonLayerClose('sms_recipient_add_list')
	}
</script>