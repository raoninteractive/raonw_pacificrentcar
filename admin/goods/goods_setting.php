<?include("../inc/config.php")?>
<?
	$pageNum = "9904";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);


    $db = new DB_HELPER;

    $sql = "SELECT * FROM setting_info";
    $view = $db->getQueryValue($sql);
?>
<style>
    .common_form .g_table td .textarea_box textarea {
        line-height: 18px;
    }
</style>

<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
                <form name="regFrm" id="regFrm" method="post">
				<div class="group">
                    <h3 class="g_title">
                        괌(GUAM) 설정
                        <span class="explain">각 설정은 줄(엔터키-Enter Key)로 구분지어 주세요.</span>
                    </h3>
					<table class="g_table">
						<colgroup>
                            <col width="12%">
                            <col width="38%">
                            <col width="12%">
                            <col width="38%">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_imp">인수/픽업 장소<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="guam_pickup_area" id="guam_pickup_area" style="height:100px"><?=$view['guam_pickup_area']?></textarea>
										</div>
                                    </div>
								</td>
								<th><span class="t_imp">반납장소<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="guam_return_area" id="guam_return_area" style="height:100px"><?=$view['guam_return_area']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
                            <tr>
								<th><span class="t_imp">출발 항공사<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="guam_out_airline" id="guam_out_airline" style="height:100px"><?=$view['guam_out_airline']?></textarea>
										</div>
                                    </div>
								</td>
								<th><span class="t_imp">도착 항공사<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="guam_in_airline" id="guam_in_airline" style="height:100px"><?=$view['guam_in_airline']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
                            <tr>
								<th><span class="t_imp">투숙 호텔<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="guam_hotel" id="guam_hotel" style="height:100px"><?=$view['guam_hotel']?></textarea>
										</div>
                                    </div>
								</td>
								<th><span class="t_imp">선택 광광 픽업 장소<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="guam_choice_pickup" id="guam_choice_pickup" style="height:100px"><?=$view['guam_choice_pickup']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
							<tr>
								<th><span class="t_imp">확정서 안내 사항<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="guam_guide_notice" id="guam_guide_notice" style="height:200px"><?=$view['guam_guide_notice']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
						</tbody>
					</table>
                </div>
				<!-- <div class="group">
                    <h3 class="g_title">
                        사이판(SAIPAN) 설정
                        <span class="explain">각 설정은 줄(엔터키-Enter Key)로 구분지어 주세요.</span>
                    </h3>
					<table class="g_table">
						<colgroup>
                            <col width="12%">
                            <col width="38%">
                            <col width="12%">
                            <col width="38%">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_imp">인수/픽업 장소<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="saipan_pickup_area" id="saipan_pickup_area" style="height:100px"><?=$view['saipan_pickup_area']?></textarea>
										</div>
                                    </div>
								</td>
								<th><span class="t_imp">반납장소<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="saipan_return_area" id="saipan_return_area" style="height:100px"><?=$view['saipan_return_area']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
                            <tr>
								<th><span class="t_imp">출발 항공사<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="saipan_out_airline" id="saipan_out_airline" style="height:100px"><?=$view['saipan_out_airline']?></textarea>
										</div>
                                    </div>
								</td>
								<th><span class="t_imp">도착 항공사<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="saipan_in_airline" id="saipan_in_airline" style="height:100px"><?=$view['saipan_in_airline']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
                            <tr>
								<th><span class="t_imp">투숙 호텔<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="saipan_hotel" id="saipan_hotel" style="height:100px"><?=$view['saipan_hotel']?></textarea>
										</div>
                                    </div>
								</td>
                                <th><span class="t_imp">선택 광광 픽업 장소<span></th>
								<td>
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="saipan_choice_pickup" id="saipan_choice_pickup" style="height:100px"><?=$view['saipan_choice_pickup']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
							<tr>
								<th><span class="t_imp">확정서 안내 사항<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="saipan_guide_notice" id="saipan_guide_notice" style="height:200px"><?=$view['saipan_guide_notice']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
						</tbody>
					</table>
                </div> -->
                </form>

				<div class="page_btn_a center mt30">
					<a href="javascript:;" class="btn_40 white" onclick="regGo();">저장하기</a>
                </div>

                <!-- 관리자 메모영역 -->
                <?
                    $admin_memo_section = "goods_setting";
                    $admin_memo_gubun = 1;
                ?>
                <?include("../common/admin_memo_log_include.php")?>
			</div>
		</div>
	</div>
    <!-- //container -->

	<script type="text/javascript" src="/module/ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
		var h = new clsJsHelper();

		CKEDITOR.replace('guam_guide_notice',{
			height:400
		});
		CKEDITOR.replace('saipan_guide_notice',{
			height:400
		});

		//폼체크
		function regGo() {
            if (!h.checkValNLen("guam_pickup_area", 1, 2000, "괌 인수/픽업 장소", "N", "KO")) return false;
            if (!h.checkValNLen("guam_return_area", 1, 2000, "괌 반납장소", "N", "KO")) return false;
            if (!h.checkValNLen("guam_out_airline", 1, 2000, "괌 출발 항공사", "N", "KO")) return false;
            if (!h.checkValNLen("guam_in_airline", 1, 2000, "괌 도착 항공사", "N", "KO")) return false;
            if (!h.checkValNLen("guam_hotel", 1, 2000, "괌 투숙 호텔", "N", "KO")) return false;

			CKEDITOR.instances.guam_guide_notice.updateElement();
			if (!h.checkVal("guam_guide_notice", "괌 확정서 안내 사항", "N", "KO")) {
				CKEDITOR.instances.content.focus();

				return false;
			}
            /*
            if (!h.checkValNLen("saipan_pickup_area", 1, 2000, "사이판 인수/픽업 장소", "N", "KO")) return false;
            if (!h.checkValNLen("saipan_return_area", 1, 2000, "사이판 반납장소", "N", "KO")) return false;
            if (!h.checkValNLen("saipan_out_airline", 1, 2000, "사이판 출발 항공사", "N", "KO")) return false;
            if (!h.checkValNLen("saipan_in_airline", 1, 2000, "사이판 도착 항공사", "N", "KO")) return false;
            if (!h.checkValNLen("saipan_hotel", 1, 2000, "사이판 투숙 호텔", "N", "KO")) return false;

			CKEDITOR.instances.saipan_guide_notice.updateElement();
			if (!h.checkVal("saipan_guide_notice", "사이판 확정서 안내 사항", "N", "KO")) {
				CKEDITOR.instances.content.focus();

				return false;
			}
            */
			AJ.ajaxForm($("#regFrm"), "goods_setting_proc.php", function(data) {
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