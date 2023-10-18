<?include("../inc/config.php")?>
<?
	$pageNum = "0201";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

	$params['usr_idx']    = chkReqRpl("usr_idx", null, "", "", "INT");
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_gubun']  = chkReqRpl("sch_gubun", null, "", "", "INT");
	$params['sch_status'] = chkReqRpl("sch_status", null, "", "", "INT");
	$params['sch_type']   = chkReqRpl("sch_type", null, "", "", "INT");
	$params['sch_word']   = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

	//상세정보 불러오기
	$view = $cls_member->user_view($params['usr_idx']);
	if ($view == false) fnMsgGo(500, "일치하는 데이터가 없습니다.", "BACK", "");
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
				<div class="group">
					<form name="regFrm" id="regFrm" method="post" enctype="multipart/form-data">
					<input type="hidden" name="usr_idx" value="<?=$params['usr_idx']?>" />
					<h3 class="g_title">회원 정보</h3>
					<table class="g_table">
						<colgroup>
							<col style="width:12%">
							<col style="width:38%">
							<col style="width:12%">
							<col style="width:38%">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_h">아이디</span></th>
								<td><?=$view['usr_id']?></td>
								<th><span class="t_h"></span>비밀번호</th>
								<td>
									<div class="box">
										<a href="javascript:;" class="btn_30" onclick="passwordReset()">비밀번호 초기화</a>
										<p class="normal fc_blue" style="margin-left:10px">마지막 비밀번호 변경일은 <strong>"<?=formatDates($view['pwd_last_date'],"Y.m.d H:i:s")?>"</strong> 입니다.</p>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_h">이름<span></th>
								<td><?=$view['usr_name']?></td>
								<th><span class="t_h">닉네임<span></th>
								<td><?=$view['nick_name']?></td>
							</tr>
							<tr>
								<th><span class="t_h">이메일</span></th>
								<td>
									<p class="normal mr10"><?=$view['usr_email']?></p>
									<div class="c_selectbox">
										<label for=""></label>
										<select name="ㅍ" id="recv_email_flag">
											<option value="Y" <?=chkCompare($view['recv_email_flag'],'Y','selected')?>>수신</option>
											<option value="N" <?=chkCompare($view['recv_email_flag'],'N','selected')?>>미수신</option>
										</select>
									</div>
									<p class="normal">확인일: <?=formatDates($view['recv_email_dt'], "Y.m.d H:i:s")?></p>
								</td>
								<th><span class="t_h">휴대폰번호</span></th>
								<td>
									<div class="box">
										<p class="normal mr10"><?=$view['usr_phone']?></p>
										<div class="c_selectbox">
											<label for=""></label>
											<select name="recv_sms_flag" id="recv_sms_flag">
												<option value="Y" <?=chkCompare($view['recv_sms_flag'],'Y','selected')?>>수신</option>
												<option value="N" <?=chkCompare($view['recv_sms_flag'],'N','selected')?>>미수신</option>
											</select>
										</div>
										<p class="normal">확인일: <?=formatDates($view['recv_sms_dt'], "Y.m.d H:i:s")?></p>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_h">생년월일<span></th>
								<td><?=$view['birthdate']?></td>
								<th><span class="t_h">성별<span></th>
								<td><?=$view['gender_name']?></td>
							</tr>
							<tr>
								<th><span class="t_h">주소<span></th>
								<td colspan="3">
									<?if ($view['zipcode'] !='' && $view['addr']) {?>
										(<?=$view['zipcode']?>) <?=$view['addr']?> <?=$view['addr_detail']?>
									<?}?>
								</td>
							</tr>
							<tr>
								<th><span class="t_h">장애구분<span></th>
								<td><?=$view['disa_gubun']?></td>
								<th><span class="t_h">장애급수<span></th>
								<td><?=$view['disa_grade']?></td>
							</tr>
							<tr>
								<th><span class="t_h">장애경중<span></th>
								<td colspan="3"><?=$view['disa_state']?></td>
							</tr>
							<tr>
								<th><span class="t_h">복지카드<span></th>
								<td colspan="3">
									<div class="box file">
										<div class="input_box" style="width:200px;">
											<input type="text" placeholder="이미지를 등록해주세요." readonly />
										</div>
										<input type="file" name="up_file_1" id="up_file_1" class="upload-hidden" upload-type="img" upload-size="5" />
										<label for="up_file_1" class="btn_30 gray">찾아보기</label>
										<?if (getUpfileName($view['up_file_1']) != '') {?>
											<p class="mt5">
												<a href="javascript:;" onclick="imgPreviwePopupOpen('/upload/member/<?=getUpfileName($view['up_file_1'])?>')">
													<img src="/upload/member/<?=getUpfileName($view['up_file_1'])?>" style="max-width:300px" />
												</a>

												<a href="javascript:;" class="btn_26 gray" onclick="fileDelGo(1)">삭제</a>
											</p>
										<?}?>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">회원등급</span></th>
								<td>
									<div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="usr_gubun" id="usr_gubun">
												<?foreach ($CONST_MEMBER_GUBUN as $item) {?>
													<option value="<?=$item[0]?>" <?=chkCompare($view['usr_gubun'],$item[0],'selected')?>><?=$item[1]?></option>
												<?}?>
											</select>
										</div>
									</div>
								</td>
								<th><span class="t_imp">회원상태</span></th>
								<td>
									<div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="status" id="status">
												<option value="Y" <?=chkCompare($view['status'],'Y','selected')?>>정상</option>
												<option value="N" <?=chkCompare($view['status'],'N','selected')?>>이용중지</option>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">아이프리 회원 여부</span></th>
								<td>
									<div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="eyefree_flag" id="eyefree_flag">
												<option value="Y" <?=chkCompare($view['eyefree_flag'],'Y','selected')?>>회원</option>
												<option value="N" <?=chkCompare($view['eyefree_flag'],'N','selected')?>>비회원</option>
											</select>
										</div>
									</div>
								</td>
								<th><span class="t_imp">회원인증 여부</span></th>
								<td>
									<div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="auth_flag" id="auth_flag">
												<option value="Y" <?=chkCompare($view['auth_flag'],'Y','selected')?>>인증</option>
												<option value="N" <?=chkCompare($view['auth_flag'],'N','selected')?>>미인증</option>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">자동재생 사용여부</span></th>
								<td>
									<div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="movie_autoplay" id="movie_autoplay">
												<option value="Y" <?=chkCompare($view['movie_autoplay'],'Y','selected')?>>예</option>
												<option value="N" <?=chkCompare($view['movie_autoplay'],'N','selected')?>>아니요</option>
											</select>
										</div>
									</div>
								</td>
								<th><span class="t_imp">이어듣기 사용여부</span></th>
								<td>
									<div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="movie_continue" id="movie_continue">
												<option value="Y" <?=chkCompare($view['movie_continue'],'Y','selected')?>>예</option>
												<option value="N" <?=chkCompare($view['movie_continue'],'N','selected')?>>아니요</option>
											</select>
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_h">선호교육</span></th>
								<td colspan="3"><?=textareaDecode($view['preference_edu'])?></td>
							</tr>
							<tr>
								<th><span class="t_h">최근 접속일</span></th>
								<td colspan="3">
									<?if ($view['visit_last_date'] != '') {?>
										<?=formatDates($view['visit_last_date'], "Y.m.d H:i:s")?> / <?=$view['visit_cnt']?>회 로그인
									<?} else {?>
										로그인 기록이 없습니다.
									<?}?>
								</td>
							</tr>
							<tr>
								<th><span class="t_h">최근 수정일</span></th>
								<td><?=formatDates($view['upt_date'], "Y.m.d H:i:s")?></td>
								<th><span class="t_h">최초 가입일시</span></th>
								<td><?=formatDates($view['reg_date'], "Y.m.d H:i:s")?></td>
							</tr>
						</tbody>
					</table>
					</form>

					<!-- 탭 영역 -->
					<h3 class="g_title mt50">수강 이력</h3>
					<div class="tabs_cont"></div>

					<!-- 관리자 메모영역 -->
					<?
						$admin_memo_section = "usr_view";
						$admin_memo_gubun = $params['usr_idx'];
					?>
					<?include("../common/admin_memo_log_include.php")?>
				</div>
				<div class="page_btn_a center mt50">
					<a href="user_list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
					<a href="javascript:;" class="btn_40 white" onclick="regGo();">수정하기</a>
					<a href="javascript:;" class="btn_40 gray" onclick="outGo();">탈퇴처리</a>
				</div>
			</div>
		</div>
	</div>
	<!-- //container -->

	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			getLectureList(1);
		})

		//수강이력 목록 불러오기
		function getLectureList(page) {
			$(".tabs_cont").load("__lecture_history_list.php?sch_id=<?=$view['usr_id']?>&page="+page);
		}

		//회원정보 수정
		function regGo() {
			AJ.ajaxForm($("#regFrm"), "user_view_proc.php", function(data) {
				if (data.result == 200) {
					alert("처리 되었습니다.");

					location.reload();
				} else {
					alert(data.message);
				}
			});
		}

		//비밀번호 초기화
		function passwordReset() {
			if (!confirm("비밀번호를 초기화 하시겠습니까?\n비밀번호는＂<?=CONST_RESET_PWD?> + 휴대폰번호 뒤 4자리＂로 초기화 됩니다.\n\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

			AJ.callAjax("__password_reset_proc.php", {"usr_idx": "<?=$params['usr_idx']?>"}, function(data){
				if (data.result == 200) {
					alert("비밀번호가 초기화 되었습니다.");

					location.reload();
				} else {
					alert(data.message);
				}
			});
		}

		//탈퇴처리
		function outGo() {
			if (!confirm("사용자를 탈퇴처리 하시겠습니까?\n탈퇴시 사용자는 더이상 접속이 불가능합니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

			AJ.callAjax("__out_proc.php", {"usr_idx": "<?=$params['usr_idx']?>"}, function(data){
				if (data.result == 200) {
					alert("탈퇴 처리 되었습니다.");

					location.replace("out_list.php");
				} else {
					alert(data.message);
				}
			});
		}

		//파일삭제
		function fileDelGo(fnum) {
			if (!confirm("첨부파일을 삭제 하시겠습니까?\n삭제시 파일은 복구가 불가능합니다.\n\n계속 진행하시려면 확인을 눌러주세요.")) return false;

			AJ.callAjax("__file_del_proc.php", {"fnum": fnum, "usr_idx": "<?=$params['usr_idx']?>"}, function(data){
				if (data.result == 200) {
					alert("삭제 처리 되었습니다.");

					location.reload();
				} else {
					alert(data.message);
				}
			});
		}
	</script>
<?include("../inc/footer.php")?>