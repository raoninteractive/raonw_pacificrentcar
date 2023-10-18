<?include("../inc/config.php")?>
<?
	$pageNum = "9101";
    $cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
            <?include("../inc/top_navi.php")?>

			<div class="sms_w">
				<div class="phone">
					<form name="regFrm" id="regFrm" method="post" enctype="multipart/form-data">
					<input type="hidden" name="section" value="관리자 발송" />
					<div class="bg_top">
						<div class="box file">
							<p class="byte"><span class="cnt msgByte">0 / 2,000</span> Byte</p>
							<div class="textarea_box">
								<textarea id="send_msg" name="send_msg" placeholder="문자내용을 입력해주세요"></textarea>
							</div>

							<!-- <div class="file_box">
								<div class="head">
									<strong class="title">파일첨부하기</strong>
									<p class="summary">파일을 첨부하시면 mms로 발송됩니다.</p>
								</div>
							</div>

							<div class="input_box">
								<input type="text" name="up_file_path" id="up_file_path" placeholder="이미지첨부 (176×144 / 50KB 이하 권장)" readonly />
							</div>
							<input type="file" name="up_file" id="up_file" />
							<label for="up_file" class="btn_30 gray">찾아보기</label> -->
						</div>
					</div>
					<div class="bg_mid">
						<!-- <div class="type_choice">
							<strong class="title">발송방법</strong>
							<div class="choice">
								<input type="radio" id="send_gubun1" name="send_gubun" value="I" checked />
								<label for="send_gubun1">즉시</label>
								<input type="radio" id="send_gubun2" name="send_gubun" value="R" />
								<label for="send_gubun2">예약</label>
							</div>
						</div> -->

						<div class="box" style="margin-top:5px;display:none;" id="sendDateBox">
							<div class="input_box date">
								<input type="text" name="send_date" id="send_date" />
							</div><span></span>

							<div class="c_selectbox" style="margin-left:10px;margin-right:0">
								<label for="send_date_hour"></label>
								<select name="send_date_hour" id="send_date_hour">
                                    <?for ($i=0; $i<=23; $i++) {?>
										<option value="<?=addZero($i)?>"><?=addZero($i)?>시</option>
									<?}?>
								</select>
							</div>
							<div class="c_selectbox" style="margin-left:5px;margin-right:0">
								<label for="send_date_minute"></label>
								<select name="send_date_minute" id="send_date_minute">
                                    <?for ($i=0; $i<=59; $i+=10) {?>
										<option value="<?=addZero($i)?>"><?=addZero($i)?>분</option>
                                    <?}?>
								</select>
							</div>
						</div>

						<div class="box">
							<div class="to_list">
								<div class="head">
									<strong class="title">
										받는사람
										<a href="javascript:;" class="ml5" style="float:right;font-size:13px;color:#1c59a4; text-decoration:underline;" onclick="recResetGo()">전체삭제</a>
									</strong>
									<strong class="cnt">총 <span class="mark recTotalCnt">0건</span></strong>
								</div>
								<div class="list_a">
									<ul id="recipient_list"></ul>
								</div>
							</div>
							<div class="mt5">
								<div class="input_box mr0" style="width:calc(100% - 116px)">
									<input type="text" id="add_rec_num" placeholder="수신번호 (예:010-1234-5678)" maxlength="13" />
								</div>
								<a href="javascript:;" class="btn_30 gray" onclick="addRecNumGo()">추가</a>
								<a href="javascript:;" class="btn_30 gray" onclick="addBigRecNumGo()">대량</a>
							</div>
						</div>
						<div class="box">
							<div class="from">
								<div class="head">
									<strong class="title">보내는 사람</strong>
								</div>
								<div class="c_selectbox" style="width:calc(100% - 8px)">
									<label for=""></label>
									<select name="sender_tel" id="sender_tel">
                                        <?foreach($CONST_SMS_SENDER as $item) {?>
                                            <option value="<?=$item?>"><?=$item?></option>
                                        <?}?>
									</select>
								</div>
							</div>
						</div>
					</div>
					<div class="bg_foot">
						<div class="box">
							<a href="javascript:;" class="btn_send" onclick="smsSend()">
								<p class="kor">발송하기</p>
								<p class="eng">SEND MESSAGE</p>
							</a>
							<select name="recipient_info[]" id="recipient_info" multiple style="display:none"></select>
						</div>
					</div>
					</form>
				</div>
				<div class="cont rightBox">
					<div class="search_box">
						<form name="searchFrm" id="searchFrm" method="get">
						<table>
							<colgroup>
								<col width="110">
								<col >
							</colgroup>
							<tbody>
								<tr>
									<th>검색설정</th>
									<td class="com">
										<div class="box">
											<div class="input_box date">
												<input type="text" name="sch_sdate" id="sch_sdate" value="" readonly placeholder="가입일 검색" />
											</div>
											<p class="dash">~</p>
											<div class="input_box date">
												<input type="text" name="sch_edate" id="sch_edate" value="" readonly placeholder="가입일 검색" />
											</div>
											<div class="c_selectbox">
												<label for=""></label>
												<select name="sch_gubun" id="sch_gubun">
													<option value="">회원권한</option>
													<?for ($i=1; $i<count($CONST_MEMBER_GUBUN); $i++) {?>
														<option value="<?=$CONST_MEMBER_GUBUN[$i][0]?>"><?=$CONST_MEMBER_GUBUN[$i][1]?></option>
													<?}?>
												</select>
											</div>
											<div class="c_selectbox">
												<label for=""></label>
												<select name="sch_type" id="sch_type">
													<option value="">전체검색</option>
													<option value="1">아이디</option>
													<option value="2">이름</option>
												</select>
											</div>
											<div class="input_box">
												<input type="text" name="sch_word" id="sch_word" maxlength="20" placeholder="검색어를 입력해주세요." />
											</div>
											<a href="javascript:;" class="btn_search" onclick="searchGo(1)">검색</a>
										</div>
									</td>
								</tr>
							</tbody>
						</table>
						</form>
					</div>
					<div class="common_list member_list_box">목록을 불러오는 중입니다.</div>
				</div>
			</div>
		</div>
	</div>
	<!-- //container -->

	<!-- 레이어팝업 : 대량발송 추가 팝업 -->
	<article class="layer_popup sms_recipient_add_list"></article>

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			//날짜검색
			$("#sch_sdate, #sch_edate").datepicker();

			//발송문자길이 체크
			$("#send_msg").keyup(function(){
				var msg_len = returnToByte("send_msg");

				if (msg_len >= 2000) {
					this.value = returnToCut(this.value, 2000);
				}

				$(".msgByte").text(returnToByte("send_msg") + " / 2,000");
			})
			$("#send_msg").trigger("keyup");

			//파일추가
			$("#up_file").change(function(){
				var $target  = $(this);
				var filePath = $target.val();

				var fileName = filePath.split("\\").pop();
				//if (fileName && /\s/gi.test(fileName)) {
				//	alert("파일명에 공백이 들어가있습니다.\n파일명에 공백을 제거 후 다시 등록해주세요.");
				//	fileDel($target);
				//	return false;
				//}
				if (fileName && returnToByte2(fileName, false) > 50) {
					alert("파일명은 50자를 초과할 수 없습니다.\n파일명을 변경 후 다시 등록해주세요.");
					fileDel($target);
					return false;
				}

				var fileExt = fileName.split('.').pop().toLowerCase();
				if(fileExt && $.inArray(fileExt, ['jpg','jpeg']) == -1) {
					alert('jpg,jpeg 파일만 업로드 할수 있습니다.');
					fileDel($target);
					return false;
				}

				$target.siblings('.input_box').children('input').val(filePath);
				$(".file_box").find(".summary").html("<a href='javascript:;' style='color:#f65d00' onclick='fileDel()'>첨부파일삭제</a>");
			});

			//발송타입
			$("#send_date").datepicker({
				minDate: 0
			})
			$(":radio[name=send_gubun]").click(function(){
				$("#send_date").val("");
				$("#send_date_hour, #send_date_minute").find("option:eq(0)").prop("selected", true);
				selectboxInit();
				$("#sendDateBox").css("display", (this.value == "I")?"none":"block");
			})

			//검색설정 중 회원구분이 전문가회원일 경우 전문가등급 검색추가
			$("#search_grade").change(function(){
				if ($(this).find("option:selected").index() == 0) {
					$("#search_permit").closest(".c_selectbox").hide();
					$("#search_permit").val('');
					selectboxInit();
				} else {
					$("#search_permit").closest(".c_selectbox").show();
				}
			})

			searchGo(1);
		})

		//첨부파일삭제
		function fileDel() {
			$(".file_box").find(".summary").html("파일을 첨부하시면 mms로 발송됩니다.");
			$("#up_file_path").val("");

			if (navigator.appName.indexOf("Explorer") > -1) {
				$("#up_file").replaceWith( $("#up_file").clone(true) );
			} else {
				$("#up_file").val("");
			}
		}

		//회원검색
		function searchGo(page) {
			var params = util.query2json($("#searchFrm").serialize());
				params.page = page;

			AJ.callAjax("__member_list.php", params, function(data){
				$(".member_list_box").html(data);
			}, "html");
		}


		//선택추가
		function recipientListAdd(member_info) {
			var this_val   = member_info;
			var this_id    = this_val.split("|")[0];
			var this_name  = this_val.split("|")[1];
			var this_phone = this_val.split("|")[2];

			var recipient_info = "" +
						"<li id=\"mem_id_"+this_id+"\">" +
						"	<p class=\"name\">"+ this_name +"</p>" +
						"	<span class=\"num\">"+ this_phone +"</span>" +
						"	<a href=\"javascript:;\" class=\"btn_delete\" onclick=\"recipientListDel('"+ this_id +"', '"+ this_phone +"')\"><img src=\"../images/btn_to_delete.gif\" alt=\"삭제\"></a>" +
						"</li>"

			var check_cnt = 0;
			$("#recipient_info option").each(function(){
				var rec_val = this.value;
				var rec_id    = rec_val.split("|")[0];
				var rec_name  = rec_val.split("|")[1];
				var rec_phone = rec_val.split("|")[2];

				if (this_id == rec_id && this_phone == rec_phone) {
					check_cnt++;
					return false;
				}
			})

			if (check_cnt == 0) {
				$("#recipient_list").append(recipient_info);
				$("#recipient_info").append("<option value='"+ this_val +"'>"+ this_val +"</option>")
			}

			$(".recTotalCnt").html( $("#recipient_list li").size().addComma()+"건" );
		}

		//선택삭제
		function recipientListDel(rec_id, rec_phone) {
			$("#recipient_info option").each(function(){
				var this_val = this.value;
				var this_id    = this_val.split("|")[0];
				var this_name  = this_val.split("|")[1];
				var this_phone = this_val.split("|")[2];

				if (rec_id == this_id && rec_phone == this_phone) {
					$(this).remove();
					$("#mem_id_"+rec_id).remove();
				}
			})

			$(".recTotalCnt").html( $("#recipient_list li").size().addComma()+"건" );
		}

		//SMS발송하기
		function smsSend() {
			if (!h.checkValNLen("send_msg", 2, 2000, "문자내용", "N", "KO")) return false;

			var send_msg = h.objVal("send_msg");
			var check_cnt = 0;
			for (i=0; i < send_msg.length; i++) {
				var thisCharAt = send_msg.charAt(i);

				//특수문자 체크(한글제외한)
				if (escape(thisCharAt).length < 4) {
					if (/[^a-zA-z0-9\s]/gi.test(thisCharAt)) {
						check_cnt++;
					}
				}
			}

			if (check_cnt > 0) {
				if (!confirm("문자내용에 특수문자가 포함되어있습니다.\n핸드폰에서 표시 불가능한 특수문자를 입력하는 경우\n전송이 실패될 수 있습니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;
			}

			/*
			if ($(":radio[name=send_gubun]:checked").val() == "R") {
				if (!h.checkSelect("send_date", "예약일")) return false;

				var rec_date   = $("#send_date").val()+" "+$("#send_date_hour").val()+":"+$("#send_date_minute").val()+":00";
				var check_time = new Date(rec_date);
				var now_time   = new Date();
				var gap        = now_time.getTime() - check_time.getTime();
				var min_gap    = parseInt(gap / 1000 /60);

				if (min_gap >=0 || min_gap > -60) {
					var tmp_time = new Date()

					var time  = new Date(tmp_time.getTime() + (60*60*1000));
					var year  = time.getFullYear()
					var month = ("0"+(time.getMonth()+1)).right(2);
					var day   = ("0"+time.getDate()).right(2);

					var hour   = ("0"+time.getHours()).right(2);
					var minute = ("0"+ parseInt(time.getMinutes()/10)*10 ).right(2);

					var rec_time = year+"-"+month+"-"+day+" "+ hour+":"+minute;

					alert("예약은 현재시간 이후 1시간 뒤부터 예약이 가능합니다.\n예약시간을 다시 선택해주세요.\n예약 가능시간 [ " + rec_time + " ] 이후");
					return false;
				}
			}
			*/


			if ($("#recipient_info option").size() == 0) {
				alert("받는 사람을 선택해주세요.");
				return false;
			}

			if (!confirm("선택한 회원 모두에게 문자를 발송하시겠습니까?\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;
			$("#recipient_info option").prop("selected", true);

			AJ.ajaxForm($("#regFrm"), "sms_send_proc.php", function(data) {
				if (data.result == 200) {
					alert("문자발송 처리가 완료되었습니다.");
					location.reload();
				} else {
					alert(data.message);
				}
			});
		}

		//받는사람 수기 추가
		function addRecNumGo() {
			if (!h.checkValNLen("add_rec_num", 11, 13, "수신번호", "Y", "N-")) return false;
			if (!phoneRegExpCheck(h.objVal("add_rec_num"), "수신번호", "-")) return false;

			recipientListAdd("non-member|수신추가|"+ h.objVal("add_rec_num"));

			$("#add_rec_num").val("");
		}

		//검색설정 기준 전체회원 추가
		function addAllListGo() {
			var params = util.query2json($("#searchFrm").serialize());

			AJ.callAjax("__sms_all_send_proc.php", params, function(data){
				$.each(data.list, function(i, item){
					recipientListAdd(item.member_info)
				})
			});
		}

		//받는사람 초기화
		function recResetGo() {
			$("#recipient_list, #recipient_info").empty();
			$(".recTotalCnt").html( "0건" );
		}

		//대량발송 첨부팝업
		function addBigRecNumGo() {
			AJ.callAjax("__recipient_add.php", null, function(data){
				$(".sms_recipient_add_list").html(data);
				commonLayerOpen('sms_recipient_add_list');
			}, "html");
		}
	</script>
<?include("../inc/footer.php")?>