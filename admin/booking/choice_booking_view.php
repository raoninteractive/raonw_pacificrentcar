<?include("../inc/config.php")?>
<?
	$pageNum = "0502";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

    $params['idx']           = chkReqRpl("idx", null, "", "", "INT");
	$params['page']          = chkReqRpl("page", 1, "", "", "INT");
	$params['sch_sdate']     = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']     = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_status']    = chkReqRpl("sch_status", "", 10, "", "STR");
    $params['sch_type']      = chkReqRpl("sch_type", null, "", "", "INT");
	$params['sch_word']      = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

    $cls_goods = new CLS_GOODS;
    $cls_booking = new CLS_BOOKING;
    $cls_jwt = new CLS_JWT();

    //예약정보 불러오기
    $booking_view = $cls_booking->choice_tour_booking_view($params['idx']);
    if ($booking_view == false) fnMsgGo(501, "일치하는 예약정보가 없습니다.", "BACK", "");

    //추가・할인 내역 목록 불러오기
    $add_amt_list = $cls_booking->booking_add_amount_list($params['idx']);

    //예약상태 상태목록
    $status_list = getResvStatusList();

?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
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
								<td colspan="3">
                                    <div class="box">
                                        <p class="normal mr10"><?=getResvStatusName($booking_view['status'], 'name2')?></p>
                                        <?if ($booking_view['status'] == '10' || $booking_view['status'] == '11') {?>
                                            <div class="c_selectbox">
                                                <label for=""></label>
                                                <select name="status" id="status" onchange="bookingStatusGo(this.value)">
                                                    <option value="">상태선택</option>
                                                    <?for ($i=0; $i<count($status_list); $i++) {?>
                                                        <?if ($status_list[$i]['code']=='11' || $status_list[$i]['code']=='20') {?>
                                                            <option value="<?=$status_list[$i]['code']?>" <?=chkCompare($booking_view['status'],$status_list[$i]['code'],'selected')?>><?=$status_list[$i]['name2']?></option>
                                                        <?}?>
                                                    <?}?>
                                                </select>
                                            </div>
                                        <?} else if ($booking_view['status'] == '20' || $booking_view['status'] == '21') {?>
                                            <div class="c_selectbox">
                                                <label for=""></label>
                                                <select name="status" id="status" onchange="bookingStatusGo(this.value)">
                                                    <option value="">상태선택</option>
                                                    <?for ($i=0; $i<count($status_list); $i++) {?>
                                                        <?if ($status_list[$i]['code']=='22' || $status_list[$i]['code']=='30') {?>
                                                            <option value="<?=$status_list[$i]['code']?>" <?=chkCompare($booking_view['status'],$status_list[$i]['code'],'selected')?>><?=$status_list[$i]['name2']?></option>
                                                        <?}?>
                                                    <?}?>
                                                </select>
                                            </div>
                                        <?} else if ($booking_view['status'] == '30' || $booking_view['status'] == '31') {?>
                                            <div class="c_selectbox">
                                                <label for=""></label>
                                                <select name="status" id="status" onchange="bookingStatusGo(this.value)">
                                                    <option value="">상태선택</option>
                                                    <?for ($i=0; $i<count($status_list); $i++) {?>
                                                        <?if ($status_list[$i]['code']=='32' || $status_list[$i]['code']=='40') {?>
                                                            <option value="<?=$status_list[$i]['code']?>" <?=chkCompare($booking_view['status'],$status_list[$i]['code'],'selected')?>><?=$status_list[$i]['name2']?></option>
                                                        <?}?>
                                                    <?}?>
                                                </select>
                                            </div>
                                        <?} else if ($booking_view['status'] == '40' || $booking_view['status'] == '41') {?>
                                            <div class="c_selectbox">
                                                <label for=""></label>
                                                <select name="status" id="status" onchange="bookingStatusGo(this.value)">
                                                    <option value="">상태선택</option>
                                                    <?for ($i=0; $i<count($status_list); $i++) {?>
                                                        <?if ($status_list[$i]['code']=='42') {?>
                                                            <option value="<?=$status_list[$i]['code']?>" <?=chkCompare($booking_view['status'],$status_list[$i]['code'],'selected')?>><?=$status_list[$i]['name2']?></option>
                                                        <?}?>
                                                    <?}?>
                                                </select>
                                            </div>
                                        <?}?>
                                    </div>
                                </td>
                            </tr>
							<tr>
								<th><span class="t_h">상품정보<span></th>
								<td colspan="3"><?=$booking_view['goods_title']?></td>
                            </tr>
                            <tr>
                                <th><span class="t_h">투어 날짜</span></th>
                                <td><?=formatDates($booking_view['tour_date'], 'Y.m.d')?></td>
                                <th><span class="t_h">시간</span></th>
                                <td><?=$booking_view['tour_time']?></td>
                            </tr>
                            <tr>
                                <th><span class="t_h">투어 인원</span></th>
                                <td>
                                    <?=formatNumbers($booking_view['adult_cnt']+$booking_view['child_cnt']+$booking_view['infant_cnt'])?>명
                                    <?
                                        $tour_people = '';
                                        if ($booking_view['adult_cnt'] > 0) {
                                            $tour_people .= '성인 '. $booking_view['adult_cnt'].'명';
                                        }

                                        if ($booking_view['child_cnt'] > 0) {
                                            if ($tour_people != '') $tour_people .= ' / ';
                                            $tour_people .= '아동 '. $booking_view['child_cnt'].'명';
                                        }

                                        if ($booking_view['infant_cnt'] > 0) {
                                            if ($tour_people != '') $tour_people .= ' / ';
                                            $tour_people .= '유아 '. $booking_view['infant_cnt'].'명';
                                        }

                                        echo "($tour_people)";
                                    ?>
                                </td>
                                <th><span class="t_h">픽업 장소</span></th>
                                <td colspan="3"><?=$booking_view['pickup_area']?></td>
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
                            <tr>
                                <th><span class="t_h">현장 지불금액<span></th>
                                <td>
                                    <strong class="fc_red">$<?=(formatNumbers($booking_view['total_tour_amt']+$booking_view['total_add_amt']))?></strong>
                                </td>
								<th><span class="t_h">온라인 예약 대행 수수료<span></th>
								<td >￦<?=formatNumbers($booking_view['booking_agency_fee'])?></td>
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
						</tbody>
                    </table>
                </div>

				<div class="page_btn_a center mt30">
                    <a href="choice_booking_list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
                    <!-- <a href="javascript:;" class="btn_40 white" onclick="localEmaiSend()"><span>현지 이메일 발송</span></a> -->
                </div>

                <!-- 관리자 메모영역 -->
                <?if ($params['idx'] != '') {?>
                    <?
                        $admin_memo_section = "choice_tour_booking_view";
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
		})

        //예약정보 상태값 수정
        function bookingStatusGo(status) {
            if (status == "") return false;

            if (!confirm("예약정보 상태값을 수정하시겠습니까?\n\n계속 진행하시려면 '확인'을 눌러주세요.")) {
                selectboxInit();
                return false;
            }

            AJ.callAjax("__choice_tour_booking_status_proc.php", {"booking_idx": "<?=$booking_view['idx']?>", "status": status}, function(data){
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

            AJ.callAjax("__choice_tour_notice_save_proc.php", {"booking_idx": "<?=$booking_view['idx']?>", "notice": h.objVal("notice")}, function(data){
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

            AJ.callAjax("__choice_tour_local_email_send.php", {"booking_idx": "<?=$booking_view['idx']?>"}, function(data){
                if (data.result == 200) {
                    alert("발송 되었습니다.");

                    location.reload();
                } else {
                    alert(data.message);
                }
            });
        }
	</script>
<?include("../inc/footer.php")?>