<?include("../inc/config.php")?>
<?
	$pageNum = "0302";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

    $params['idx']          = chkReqRpl("idx", null, "", "", "INT");
	$params['page']         = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']    = 10;
	$params['block_size']   = 10;
	$params['sch_sdate']    = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']    = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_mgubun1']  = chkReqRpl("sch_mgubun1", null, "", "", "INT");
	$params['sch_mgubun2']  = chkReqRpl("sch_mgubun2", null, "", "", "INT");
	$params['sch_mgubun3']  = chkReqRpl("sch_mgubun3", null, "", "", "INT");
	$params['sch_mgubun4']  = chkReqRpl("sch_mgubun4", null, "", "", "INT");
	$params['sch_mgubun5']  = chkReqRpl("sch_mgubun5", null, "", "", "INT");
	$params['sch_mgubun6']  = chkReqRpl("sch_mgubun6", null, "", "", "INT");
	$params['sch_apply']    = chkReqRpl("sch_apply", "", 1, "", "STR");
	$params['sch_status']   = chkReqRpl("sch_status", "", 1, "", "STR");
	$params['sch_type']     = chkReqRpl("sch_type", null, "", "", "INT");
	$params['sch_word']     = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

    $cls_content = new CLS_CONTENT;

    $view = $cls_content->class_view($params['idx']);
    if ($view == false) {
        $view['sort'] = '0';
        $view['types'] = '10';
        $view['period_flag'] = 'N';
        $view['allow_auth'] = '00,10,20,21,22,30';
        $view['apply_status'] = 'N';
        $view['limit_flag'] = 'N';
        $view['total_lecture_cnt'] = '0';
    } else {
        if ($view['period'] == '0') $view['period'] = '';
        if ($view['limit_cnt'] == '0') $view['limit_cnt'] = '';
    }


	//콘텐츠관리 1차 메뉴 목록 불러오기
	$ctg_list1 = $cls_content->category_list(1, '');
	$ctg_list2 = $cls_content->category_list(2, $view['category_1']);
	$ctg_list3 = $cls_content->category_list(3, $view['category_2']);
    $ctg_list4 = $cls_content->category_list(4, $view['category_3']);

    //강의 목록 불러오기
    $lecture_list = $cls_content->lecture_list_admin($view['idx']);

    //강좌 수강신청 건수 체크
    $class_apply_count = $cls_content->class_apply_count($view['idx']);

    //강사회원 목록 불러오기
    $mem_params['page']      = 1;
	$mem_params['list_size'] = 999999;
	$mem_params['sch_gubun'] = '30';
    $lecturer_list = $cls_member->user_list($mem_params);
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
				<div class="group">
					<form name="regFrm" id="regFrm" method="post">
                    <input type="hidden" name="idx" value="<?=$params['idx']?>" />

                    <h3 class="g_title">강좌 정보</h3>
					<table class="g_table">
						<colgroup>
                            <col width="12%">
                            <col width="38%">
                            <col width="12%">
                            <col width="38%">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_imp">메뉴위치<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="category_1" id="category_1" onchange="categoryList(2, this.value)">
                                                <option	value="">1차 메뉴 선택</option>
                                                <?for ($i=0; $i<count($ctg_list1); $i++) {?>
                                                    <option value="<?=$ctg_list1[$i]['category_idx']?>" <?=chkCompare($view['category_1'], $ctg_list1[$i]['category_idx'], "selected")?>>
                                                        <?=$ctg_list1[$i]['name']?> <?=iif($ctg_list1[$i]['open_flag']!='Y', ' (사용안함)', '')?>
                                                    </option>
                                                <?}?>
                                            </select>
                                        </div>
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="category_2" id="category_2" onchange="categoryList(3, this.value)">
                                                <option	value="">2차 메뉴 선택</option>
                                                <?for ($i=0; $i<count($ctg_list2); $i++) {?>
                                                    <option value="<?=$ctg_list2[$i]['category_idx']?>" <?=chkCompare($view['category_2'], $ctg_list2[$i]['category_idx'], "selected")?>>
                                                        <?=$ctg_list2[$i]['name']?> <?=iif($ctg_list2[$i]['open_flag']!='Y', ' (사용안함)', '')?>
                                                    </option>
                                                <?}?>
                                            </select>
                                        </div>
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="category_3" id="category_3" onchange="categoryList(4, this.value)">
                                                <option	value="">3차 메뉴 선택</option>
                                                <?for ($i=0; $i<count($ctg_list3); $i++) {?>
                                                    <option value="<?=$ctg_list3[$i]['category_idx']?>" <?=chkCompare($view['category_3'], $ctg_list3[$i]['category_idx'], "selected")?>>
                                                        <?=$ctg_list3[$i]['name']?> <?=iif($ctg_list3[$i]['open_flag']!='Y', ' (사용안함)', '')?>
                                                    </option>
                                                <?}?>
                                            </select>
                                        </div>
                                        <div class="c_selectbox">
                                            <label for=""></label>
                                            <select name="category_4" id="category_4">
                                                <option	value="">4차 메뉴 선택</option>
                                                <?for ($i=0; $i<count($ctg_list4); $i++) {?>
                                                    <option value="<?=$ctg_list4[$i]['category_idx']?>" <?=chkCompare($view['category_4'], $ctg_list4[$i]['category_idx'], "selected")?>>
                                                        <?=$ctg_list4[$i]['name']?> <?=iif($ctg_list4[$i]['open_flag']!='Y', ' (사용안함)', '')?>
                                                    </option>
                                                <?}?>
                                            </select>
                                        </div>
                                    </div>

                                    <?if (!$cls_content->class_category_check($params['idx'])) {?>
                                        <p class="fc_red mt5 cgtWarning"><strong>
                                            ※ 연결된 메뉴 카테고리 중 '사용안함' 또는 '삭제' 처리된 내역이 있습니다.<br>
                                            ※ 메뉴위치를 변경 해주세요. 미변경시 메뉴위치는 반영되지 않습니다.
                                        </strong></p>
                                    <?}?>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">강좌명<span></th>
								<td colspan="3">
                                    <div class="box">
										<div class="input_box" style="width:100%">
											<input type="text" name="title" id="title" value="<?=$view['title']?>" />
										</div>
									</div>
								</td>
							</tr>
							<tr>
								<th><span class="t_imp">강좌형식<span></th>
								<td>
                                    <div class="box">
                                        <div class="c_radiobox">
                                            <input type="radio" name="types" id="types_1" value="10" <?=chkCompare($view['types'], '10', 'checked')?> />
                                            <label for="types_1">VOD 강의</label>
                                        </div>
                                        <div class="c_radiobox">
                                            <input type="radio" name="types" id="types_2" value="20" <?=chkCompare($view['types'], '20', 'checked')?> />
                                            <label for="types_2">기타</label>
                                        </div>
									</div>
                                </td>
								<th><span class="t_imp">수강기간<span></th>
								<td>
									<div class="box">
										<div class="input_box mr0" style="width:42px">
											<input type="text" name="period" id="period" value="<?=$view['period']?>" class="onlyNum" maxlength="3" />
                                        </div>
                                        <p class="normal ml5 mr10">일</p>

                                        <div class="c_checkbox">
                                            <input type="checkbox" name="period_flag" id="period_flag" value="N" <?=chkCompare($view['period_flag'], 'N', 'checked')?> />
                                            <label for="period_flag">없음</label>
                                        </div>
									</div>
								</td>
                            </tr>
							<tr>
								<th><span class="t_imp">강사명<span></th>
								<td colspan="3">
									<div class="box">
										<div class="input_box" style="width:150px">
											<input type="text" name="inst_name" id="inst_name" value="<?=$view['inst_name']?>" />
										</div>
									</div>
                                </td>
                            </tr>
							<tr>
								<th><span class="t_h">강사회원<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="c_selectbox">
											<label for=""></label>
											<select name="inst_id" id="inst_id">
                                                <option value="">강사회원 선택</option>
                                                <?for ($i=0; $i<count($lecturer_list); $i++) {?>
                                                    <option value="<?=$lecturer_list[$i]['usr_id']?>" <?=chkCompare($view['inst_id'], $lecturer_list[$i]['usr_id'], "selected")?>><?=$lecturer_list[$i]['usr_name']?>(<?=$lecturer_list[$i]['usr_id']?>)<?=iif($lecturer_list[$i]['status']=='N', ' [이용중지]', '')?></option>
                                                <?}?>
											</select>
										</div>

                                        <p class="normal fc_red">※ 연결된 강의회원은 웹사이트에서 강좌에 대한 강의를 등록/수정이 가능합니다</p>
                                    </div>
								</td>
                            </tr>
							<tr>
								<th><span class="t_imp">이용 회원등급<span></th>
								<td colspan="3">
                                    <div class="box">
                                        <div class="c_checkbox">
                                            <input type="checkbox" name="allow_auth[]" id="allow_auth_0" value="00" <?=chkCompare($view['allow_auth'], 0, 'checked')?> />
                                            <label for="allow_auth_0">비회원</label>
                                        </div>

                                        <?foreach($CONST_MEMBER_GUBUN as $item) {?>
                                            <div class="c_checkbox">
                                                <input type="checkbox" name="allow_auth[]" id="allow_auth_<?=$item[0]?>" value="<?=$item[0]?>" <?=chkCompare($view['allow_auth'], $item[0], 'checked')?> />
                                                <label for="allow_auth_<?=$item[0]?>"><?=$item[1]?></label>
                                            </div>
                                        <?}?>
                                    </div>
                                </td>
                            </tr>
							<tr>
								<!-- <th><span class="t_imp">수강신청 사용여부<span></th>
								<td>
                                    <div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="apply_status" id="apply_status">
												<option value="Y" <?=chkCompare($view['apply_status'], 'Y', 'selected')?>>사용</option>
												<option value="N" <?=chkCompare($view['apply_status'], 'N', 'selected')?>>미사용</option>
											</select>
										</div>
									</div>
                                </td> -->
								<th><span class="t_imp">수강신청 제한 인원<span></th>
								<td colspan="3">
									<div class="box">
										<div class="input_box mr0" style="width:42px">
											<input type="text" name="limit_cnt" id="limit_cnt" value="<?=$view['limit_cnt']?>" class="onlyNum" maxlength="3" />
                                        </div>
                                        <p class="normal ml5 mr10">명</p>

                                        <div class="c_checkbox">
                                            <input type="checkbox" name="limit_flag" id="limit_flag" value="N" <?=chkCompare($view['limit_flag'], 'N', 'checked')?> />
                                            <label for="limit_flag">인원제한 없음</label>
                                        </div>
									</div>
                                </td>
                            </tr>
							<tr>
                                <th><span class="t_imp">강좌 상태<span></th>
								<td>
                                    <div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="class_status" id="class_status">
												<option value="Y" <?=chkCompare($view['class_status'], 'Y', 'selected')?>>수강가능</option>
												<option value="N" <?=chkCompare($view['class_status'], 'N', 'selected')?>>종료</option>
											</select>
										</div>
									</div>
								</td>
								<th><span class="t_imp">노출 상태<span></th>
								<td>
                                    <div class="box">
										<div class="c_selectbox">
											<label for=""></label>
											<select name="open_flag" id="open_flag">
												<option value="Y" <?=chkCompare($view['open_flag'], 'Y', 'selected')?>>노출</option>
												<option value="N" <?=chkCompare($view['open_flag'], 'N', 'selected')?>>숨김</option>
											</select>
										</div>
									</div>
								</td>
                            </tr>
                            <tr>
                                <th><span class="t_imp">강좌내용<span></th>
                                <td colspan="3">
                                    <div class="box">
                                        <div class="textarea_box" style="width:100%">
                                            <textarea name="class_content" id="class_content" style="height:150px"><?=$view['class_content']?></textarea>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="t_imp">총 강의 수</span></th>
                                <td colspan="3">
                                    <div class="box">
                                        <?if ($class_apply_count > 0) {?>
                                            <div class="input_box disabled" style="width:60px">
                                                <input type="text" name="total_lecture_cnt" id="total_lecture_cnt" value="<?=$view['total_lecture_cnt']?>" readonly class="onlyNum" maxlength="3" data-val="<?=$view['total_lecture_cnt']?>" />
                                            </div>
                                        <?} else {?>
                                            <div class="input_box" style="width:60px">
                                                <input type="text" name="total_lecture_cnt" id="total_lecture_cnt" value="<?=$view['total_lecture_cnt']?>" class="onlyNum" maxlength="3" data-val="<?=$view['total_lecture_cnt']?>" />
                                            </div>
                                        <?}?>

                                        <?if ($params['idx'] != '') {?>
                                            <p class="normal fc_red">※ 수강 신청자가 없을경우만 수정가능하며, 1명이상 수강 신청자가 있을경우 수정이 불가능합니다.</p>
                                        <?}?>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th><span class="t_imp">정렬순번</span></th>
                                <td colspan="3">
                                    <div class="box">
                                        <div class="input_box" style="width:60px">
                                            <input type="text" name="sort" id="sort" value="<?=$view['sort']?>" class="onlyNum" maxlength="3" placeholder="0~999 낮은순으로 정렬" />
                                        </div>

                                        <p class="normal fc_red">※ 정렬순번이 낮은 번호가 상위로 배치됩니다. 동일순번일경우 등록일순으로 배치됩니다. (기본값: 0)</p>
                                    </div>
                                </td>
                            </tr>
						</tbody>
					</table>
                    </form>
				</div>
				<div class="page_btn_a center">
					<a href="class_list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
					<a href="javascript:;" class="btn_40 white" onclick="regGo();"><?=iif($params['idx'] == '', '등록하기', '수정하기')?></a>
                </div>

                <?if ($view['idx'] != '') {?>
                    <div class="common_list">
                        <h3 class="g_title" style="position:relative;">
                            강의 목록
                            <a href="javascript:;" class="btn_30" style="position:absolute; top:0; right:0; margin-top:-7px" onclick="lectureWriteGo('')">강의등록</a>
                        </h3>
                        <table>
                            <colgroup>
                                <col width="70" />
                                <col width="70" />
                                <col width="*" />
                                <col width="400" />
                                <col width="70" />
                                <col width="70" />
                                <!-- <col width="70" /> -->
                                <col width="120" />
                                <col width="120" />
                                <col width="70" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>번호</th>
                                    <th>정렬순번</th>
                                    <th>강의명</th>
                                    <th>첨부자료</th>
                                    <th>맛보기<br>여부</th>
                                    <th>진행바<br>여부</th>
                                    <!-- <th>노출<br>상태</th> -->
                                    <th>등록자</th>
                                    <th>등록일</th>
                                    <th>관리</th>
                                </tr>
                            </thead>
                            <tbody class="lecture_list">
                                <?for ($i=0; $i<count($lecture_list);$i++) {?>
                                    <tr>
                                        <td><?=formatNumbers(count($lecture_list) - $i)?></td>
                                        <td><?=formatNumbers($lecture_list[$i]['sort'])?></td>
                                        <td class="left"><a href="javascript:;" class="a_link" onclick="lectureWriteGo(<?=$lecture_list[$i]['idx']?>)"><?=$lecture_list[$i]['title']?></a></td>
                                        <td class="left">
                                            <?if (getUpfileName($lecture_list[$i]['up_file_1']) != '') {?>
                                                <p id="filebox_<?=$view['idx']?>_1">동영상/오디오: <a href="lecture_file_down.php?idx=<?=$view['idx']?>&fnum=1"><?=getUpfileOriName($lecture_list[$i]['up_file_1'])?></a></p>
                                            <?}?>
                                            <?if (getUpfileName($lecture_list[$i]['up_file_2']) != '') {?>
                                                <p id="filebox_<?=$view['idx']?>_2">첨부자료1: <a href="lecture_file_down.php?idx=<?=$view['idx']?>&fnum=2"><?=getUpfileOriName($lecture_list[$i]['up_file_2'])?></a></p>
                                            <?}?>
                                            <?if (getUpfileName($lecture_list[$i]['up_file_3']) != '') {?>
                                                <p id="filebox_<?=$view['idx']?>_3">첨부자료2: <a href="lecture_file_down.php?idx=<?=$view['idx']?>&fnum=3"><?=getUpfileOriName($lecture_list[$i]['up_file_3'])?></a></p>
                                            <?}?>
                                        </td>
                                        <td><?=$lecture_list[$i]['sample_flag']?></td>
                                        <td><?=$lecture_list[$i]['controll_flag']?></td>
                                        <!-- <td><?=$lecture_list[$i]['open_flag']?></td> -->
                                        <td><?=$lecture_list[$i]['reg_name']?><br>(<?=$lecture_list[$i]['reg_id']?>)</td>
                                        <td><?=formatDates($lecture_list[$i]['reg_date'], "Y.m.d H:i")?></td>
                                        <td>
                                            <?if ($cls_content->class_apply_lecture_count($params['idx']) == 0) {?>
                                                <a href="javascript:;" class="btn_26 red" onclick="lectureDeleteGo(<?=$lecture_list[$i]['idx']?>);">삭제</a>
                                            <?} else {?>
                                                <a href="javascript:;" class="btn_26 white" onclick="alert('현재 강의를 수강 시청중인 회원이 있습니다.\n시수강 시청중인 강의는 삭제가 불가능합니다.')">시청중</a>
                                            <?}?>
                                        </td>
                                    </tr>
                                <?}?>

                                <?if (count($lecture_list) == 0) {?>
                                    <tr>
                                        <td colspan="9">등록된 데이터가 없습니다.</td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>
                    </div>
                <?}?>
			</div>
		</div>
	</div>
    <!-- //container -->

	<!-- 레이어팝업 : 강좌 강의 등록 팝업 -->
	<article class="layer_popup lecture_write_popup"></article>


	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
            //수강기간 체크
            $(":checkbox[name=period_flag]").click(function(){
                $("#period").prop("disabled", this.checked);
            })
            $("#period").prop("disabled", $(":checkbox[name=period_flag]").prop("checked"));

            //수강신청 제한 인원 체크
            $(":checkbox[name=limit_flag]").click(function(){
                $("#limit_cnt").prop("disabled", this.checked);
            })
            $("#limit_cnt").prop("disabled", $(":checkbox[name=limit_flag]").prop("checked"));

            /*
            //수강신청 사용여부 체크
            $("#apply_status").change(function(){
                if ($(this).val() == "Y") {
                    $("#limit_cnt").prop("disabled", $(":checkbox[name=limit_flag]").prop("checked"));
                    $("#limit_flag").parent().removeClass("disabled");
                    $("#limit_flag").prop("disabled", false);
                } else {
                    $("#limit_cnt").prop("disabled", true);
                    $("#limit_flag").parent().addClass("disabled");
                    $("#limit_flag").prop("disabled", true);
                }
            })
            $("#apply_status").trigger("change");
            */
		})

		//강좌등록 폼체크
		function regGo() {
            if (!h.checkSelect("category_1", "1차 메뉴")) return false;
            var cgt_cnt = 0;
            if ($("#category_1 option:selected").text().indexOf("(사용안함)") > -1) cgt_cnt++;
            if ($("#category_2 option:selected").text().indexOf("(사용안함)") > -1) cgt_cnt++;
            if ($("#category_3 option:selected").text().indexOf("(사용안함)") > -1) cgt_cnt++;
            if ($("#category_4 option:selected").text().indexOf("(사용안함)") > -1) cgt_cnt++;

            if (!h.checkValNLen("title", 2, 100, "강좌명", "N", "KO")) return false;
            if (!$("#period_flag").is(":checked")) {
                if (!h.checkValNLen("period", 1, 3, "수강기간", "Y", "ON")) return false;
                if (h.objVal("period") == 0) {
                    alert("수강기간은 0일 이상 입력해주세요.");
                    return false;
                }
            }
            if (!h.checkValNLen("inst_name", 2, 50, "강사명", "N", "KO")) return false;

            if ($(":checkbox[name='allow_auth[]']:checked").length == 0) {
                alert("이용 회원등급은 최소 1개 이상 선택하셔야 합니다.");

                return false;
            }

            //if ($("#apply_status").val() == "Y") {
                if (!$("#limit_flag").is(":checked")) {
                    if (!h.checkValNLen("limit_cnt", 1, 3, "수강신청 제한 인원", "Y", "ON")) return false;
                    if (h.objVal("limit_cnt") == 0) {
                        alert("수강신청 제한 인원은 0명 이상 입력해주세요.");
                        return false;
                    }
                }
            //}

            if (!h.checkVal("class_content", "강좌내용", "N", "KO")) return false;

            if (!h.checkValNLen("total_lecture_cnt", 1, 3, "총 강의 수", "Y", "ON")) return false;
            if (!h.checkValNLen("sort", 1, 3, "정렬순번", "Y", "ON")) return false;

            if (cgt_cnt > 0 || $(".cgtWarning").is(":visible")) {
                if (!confirm("메뉴위치중 '사용안함' 또는 '삭제' 처리된 메뉴가 조회 됩니다.\n'사용안함' 또는 '삭제' 메뉴가 있는 강좌는 웹페이지에 노출이 안됩니다.\n메뉴위치를 변경 해주세요. 미변경시 메뉴위치는 반영되지 않습니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;
            }

			AJ.ajaxForm($("#regFrm"), "class_write_proc.php", function(data) {
				if (data.result == 200) {
                    alert("처리 되었습니다.");

                    <?if (chkBlank($params['idx'])) {?>
                        location.replace("class_write.php?page=<?=$params['page'] . $page_params?>&idx="+data.class_idx);
                    <?} else {?>
                        location.reload();
                    <?}?>
				} else {
					alert(data.message);
				}
			});
        }

		//메뉴 카테고리 목록 불러오기
		function categoryList(depth, category_idx) {
			var max_depth = 4;

			for (i=depth; i<=max_depth; i++) {
				$("#category_"+i).html("<option value=''>"+ i +"차 메뉴 선택</option>");
			}
			if (category_idx != "") {
				AJ.callAjax("__menu_category_list.php", {"depth":depth, "category_idx": category_idx}, function(data){
					if (data.result == 200) {
						$.each(data.list, function(i, item){
							$("#category_"+depth).append("<option value='"+ item.category_idx +"'>"+ item.name + (item.open_flag!='Y'?' (사용안함)':'') +"</option>")
						})
					} else {
						alert(data.message);
					}
				},"json","get");
			}

			selectboxInit();
        }

        //강의 등록
        function lectureWriteGo(idx) {
            if (idx=='') {
                var total_lecture_cnt = $("#total_lecture_cnt").attr("data-val").toInt();
                var lecture_cnt = $(".lecture_list").find("tr").length;

                if (total_lecture_cnt <= lecture_cnt) {
                    alert("강의는 최대 " + total_lecture_cnt +"강 까지 등록가능합니다.\n강의를 추가 하실경우 총 강의 수를 변경 후 등록을 다시 해주세요.");
                    return false;
                }
            }

			AJ.callAjax("lecture_write.php", {"class_idx": "<?=$view['idx']?>", "idx": idx}, function(data){
				$(".lecture_write_popup").html(data);
				commonLayerOpen('lecture_write_popup');
			}, "html");
        }

        //강의 삭제
        function lectureDeleteGo(idx) {
			if (!confirm("선택한 강의를 삭제 하시겠습니까?\n삭제시 데이터는 복구가 불가능하며 사용자 페이지에 노출도 숨김처리 됩니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.callAjax("lecture_delete_proc.php", {"class_idx": "<?=$view['idx']?>", "idx": idx}, function(data){
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