<?include("../inc/config.php")?>
<?
	$pageNum = "0402";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

    $params['idx']        = chkReqRpl("idx", null, "", "", "INT");
	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['sch_sdate']  = chkReqRpl("sch_sdate", "", 10, "", "STR");
	$params['sch_edate']  = chkReqRpl("sch_edate", "", 10, "", "STR");
	$params['sch_cate']   = chkReqRpl("sch_cate", "", 10, "", "STR");
    $params['sch_open']   = chkReqRpl("sch_open", "", 1, "", "STR");
	$params['sch_word']   = chkReqRpl("sch_word", "", 50, "", "STR");
	$page_params          = setPageParamsValue($params, "page,list_size,block_size");

    $cls_goods = new CLS_GOODS;

    $view = $cls_goods->choice_tour_view($params['idx'], 'Y');
    if (!chkBlank($params['idx']) && $view == false) fnMsgGo(503, "일치하는 데이터가 없습니다.", "BACK", "");
    if ($view == false) {
        $view['sort'] = 0;
    } else {
    }

    $goods_cate = getGoodsCateList();
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_form">
                <form name="regFrm" id="regFrm" method="post">
                <input type="hidden" name="idx" value="<?=$params['idx']?>" />
				<div class="group">
                    <h3 class="g_title">상품 정보</h3>
					<table class="g_table">
						<colgroup>
                            <col width="12%">
                            <col width="38%">
                            <col width="12%">
                            <col width="38%">
						</colgroup>
						<tbody>
							<tr>
								<th><span class="t_imp">상품명<span></th>
								<td>
                                    <div class="box">
										<div class="input_box" style="width:100%">
											<input type="text" name="title" id="title" value="<?=$view['title']?>" />
										</div>
									</div>
                                </td>
								<th><span class="t_imp">출항시간<span></th>
								<td>
                                    <div class="box">
										<div class="input_box" style="width:100%">
											<input type="text" name="tour_time" id="tour_time" value="<?=$view['tour_time']?>" placeholder="콤마(,)로 구분지어 주세요. (예: 09:00,11:30,14:00)" />
										</div>
									</div>
                                </td>
							</tr>
							<tr>
								<th><span class="t_imp">목록 상품설명<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="introduction" id="introduction" style="height:100px"><?=$view['introduction']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
							<tr>
								<th><span class="t_imp">상품가격<span></th>
								<td>
									<div class="box">
                                        <p class="normal mr10"><strong>어른</strong></p>
                                        <p class="normal mr5">$</p>
                                        <div class="input_box mr15" style="width: 50px">
                                            <input type="text" name="adult_amt" id="adult_amt" value="<?=$view['adult_amt']?>" class="onlyNum"  maxlength="4" />
                                        </div>

                                        <p class="normal mr10"><strong>아동</strong></p>
                                        <p class="normal mr5">$</p>
                                        <div class="input_box mr15" style="width: 50px">
                                            <input type="text" name="child_amt" id="child_amt" value="<?=$view['child_amt']?>" class="onlyNum" maxlength="4" />
                                        </div>

                                        <p class="normal mr10"><strong>유아</strong></p>
                                        <p class="normal mr5">$</p>
                                        <div class="input_box mr15" style="width: 50px">
                                            <input type="text" name="infant_amt" id="infant_amt" value="<?=$view['infant_amt']?>" class="onlyNum" maxlength="4" />
                                        </div>
                                    </div>
                                </td>
								<th><span class="t_imp">예약 대행 수수료<span></th>
								<td>
									<div class="box">
                                        <div class="input_box" style="width: 100px">
                                            <input type="text" name="agency_fee" id="agency_fee" value="<?=$view['agency_fee']?>" class="onlyNum"  maxlength="9" />
                                        </div>
                                        <p class="normal">원</p>
                                    </div>
								</td>
                            </tr>
                            <tr>
                                <?for ($i=1; $i<=1; $i++) {?>
                                    <th><span class="t_imp"><?=iif($i==1, '목록 이미지', '상세 이미지')?><span></th>
                                    <td>
                                        <div class="box file">
                                            <div class="input_box" style="width:400px;">
                                                <input type="text" placeholder="이미지 파일을 등록해주세요. (2MB / png,jpg,gif,jpeg)" readonly />
                                            </div>
                                            <input type="hidden" name="old_up_file_<?=$i?>" id="old_up_file_<?=$i?>" value="<?=$view['up_file_'.$i]?>" />
                                            <input type="file" name="up_file_<?=$i?>" id="up_file_<?=$i?>" class="upload-hidden" upload-type="img" upload-size="2" upload-ext="png,jpg,gif,jpeg" >
                                            <label for="up_file_<?=$i?>" class="btn_30 gray">찾아보기</label>
                                            <?if (getUpfileName($view['up_file_'.$i]) != '') {?>
												<p class="mt5">
													<a href="javascript:;" onclick="imgPreviwePopupOpen('/upload/choice_tour/<?=iif($i==1,'list','view')?>/<?=getUpfileName($view['up_file_'.$i])?>')">
														<img src="/upload/choice_tour/<?=iif($i==1,'list','view')?>/<?=getUpfileName($view['up_file_'.$i])?>" style="max-width:200px" />
													</a>
												</p>
											<?}?>
                                        </div>
                                    </td>
                                <?}?>
                            </tr>
							<tr>
								<th><span class="t_imp">내용<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="content" id="content" style="height:200px"><?=$view['content']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
							<tr>
								<th><span class="t_imp">유의사항<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="textarea_box" style="width:100%">
											<textarea name="notice" id="notice" style="height:200px"><?=$view['notice']?></textarea>
										</div>
                                    </div>
								</td>
                            </tr>
							<tr>
								<th><span class="t_imp">상품기간<span></th>
								<td>
                                    <div class="box">
										<div class="input_box" style="width:130px;margin-right:0">
											<input type="text" name="sdate" id="sdate" value="<?=$view['sdate']?>" readonly />
										</div>
										<p class="normal" style="margin:0 8px;">~</p>
										<div class="input_box" style="width:130px;margin-right:12px">
											<input type="text" name="edate" id="edate" value="<?=$view['edate']?>" readonly />
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
								<th><span class="t_imp">상품정렬순번<span></th>
								<td colspan="3">
									<div class="box">
                                        <div class="input_box mr20" style="width: 50px">
                                            <input type="text" name="sort" id="sort" value="<?=$view['sort']?>" class="onlyNum"  maxlength="3" placeholder="순번" />
                                        </div>
                                        <p class="normal fc_red">※ 순번은 정렬순번 높은순으로 정렬됩니다.</p>
                                    </div>
                                </td>
                            </tr>
						</tbody>
					</table>
                </div>
                </form>

				<div class="page_btn_a center mt30">
					<a href="choice_list.php?page=<?=$params['page'] . $page_params?>" class="btn_40 list"><span>목록이동</span></a>
					<a href="javascript:;" class="btn_40 white" onclick="regGo();"><?=iif($params['idx'] == '', '등록하기', '수정하기')?></a>
                </div>

                <!-- 관리자 메모영역 -->
                <?if ($params['idx'] != '') {?>
                    <?
                        $admin_memo_section = "choice_write";
                        $admin_memo_gubun = $params['idx'];
                    ?>
                    <?include("../common/admin_memo_log_include.php")?>
                <?}?>
			</div>
		</div>
	</div>
    <!-- //container -->

    <script type="text/javascript" src="/module/ckeditor/ckeditor.js"></script>
	<script type="text/javascript">
		var h = new clsJsHelper();

		$(function(){
			//상품기간
			$("#sdate").datepicker({
				maxDate: "<?=$params['edate']?>",
				onClose: function(selectedDate) {
					$("#edate").datepicker("option", "minDate", selectedDate);
				}
			});
			$("#edate").datepicker({
				minDate: "<?=$params['sdate']?>",
				onClose: function(selectedDate) {
					$("#sdate").datepicker("option", "maxDate", selectedDate);
				}
			});

            CKEDITOR.replace('content',{
                height:300
            });

            CKEDITOR.replace('notice',{
                height:300
            });
		})

		//폼체크
		function regGo() {
            if (!h.checkValNLen("title", 2, 200, "상품명", "N", "KO")) return false;
            if (!h.checkValNLen("tour_time", 4, 100, "출항시간", "Y", "KO")) return false;
            if (!h.checkValNLen("introduction", 1, 1000, "목록 상품설명", "N", "KO")) return false;

            if (!h.checkValNLen("adult_amt", 1, 4, "상품가격(1일)", "Y", "ON")) return false;
            if (!h.checkValNLen("child_amt", 1, 4, "상품가격(7일)", "Y", "ON")) return false;
            if (!h.checkValNLen("infant_amt", 1, 4, "상품가격(30일)", "Y", "ON")) return false;
            if (!h.checkValNLen("agency_fee", 1, 9, "예약 대행 수수료", "Y", "ON")) return false;

            if (h.objVal("old_up_file_1")=='' && h.objVal("up_file_1")=='') {
                alert("목록 이미지 선택해주세요.");
                return false;
            }

            if (h.objVal("old_up_file_2")=='' && h.objVal("up_file_2")=='') {
                alert("상세 이미지 선택해주세요.");
                return false;
            }

			CKEDITOR.instances.content.updateElement();
			if (!h.checkVal("content", "내용", "N", "KO")) {
				CKEDITOR.instances.notice.focus();

				return false;
			}

			CKEDITOR.instances.notice.updateElement();
			if (!h.checkVal("notice", "유의사항", "N", "KO")) {
				CKEDITOR.instances.notice.focus();

				return false;
			}

            if (!h.checkSelect("sdate", "상품기간")) return false;
            if (!h.checkSelect("edate", "상품기간")) return false;

			AJ.ajaxForm($("#regFrm"), "choice_write_proc.php", function(data) {
				if (data.result == 200) {
                    alert("처리 되었습니다.");

                    <?if (chkBlank($params['idx'])) {?>
                        //location.replace("choice_write.php?page=<?=$params['page'] . $page_params?>&idx="+data.goods_idx);
                        location.replace("choice_list.php");
                    <?} else {?>
                        location.reload();
                    <?}?>
				} else {
					alert(data.message);
				}
			});
        }
	</script>
<?include("../inc/footer.php")?>