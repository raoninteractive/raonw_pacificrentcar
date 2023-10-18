<?include("../inc/config.php")?>
<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgGo(501, "잘못된 접근 입니다.", "../", "");
	if (!isAdmin()) fnMsgGo(502, "해당정보에 접근할 수 있는 권한이 없습니다.", "../", "");

    $depth        = chkReqRpl("depth", null, "", "POST", "INT");
    $category_idx = chkReqRpl("category_idx", null, "", "POST", "INT");
    $parent_idx   = chkReqRpl("parent_idx", null, "", "POST", "INT");

	if (chkBlank($depth)) fnMsgGo(503, "카테고리 정보 값이 유효하지 않습니다.", "RELOAD", "");
    //if (chkBlank($category_idx)) fnMsgGo(504, "카테고리 정보 값이 유효하지 않습니다.", "RELOAD", "");
    //if (chkBlank($parent_idx)) fnMsgGo(505, "카테고리 정보 값이 유효하지 않습니다.", "RELOAD", "");

    $cls_content = new CLS_CONTENT;

    //카테고리 정보 불러오기
    $ctg_view = $cls_content->category_view($category_idx);
    if ($ctg_view == false) {
        $ctg_view['sort'] = '0';
        $ctg_view['allow_auth'] = '00,10,20,21,22,30';
    }

    //상위 메뉴정보 불러오기
    if ($depth == 1) {
        $parent_path = '최상위 메뉴';
    } else {
        $parent_path = $cls_content->parent_category_path($parent_idx);
    }
?>
<div class="dim"></div>
<div class="contents" style="width:800px;margin-left:-400px;">
	<div class="layer_header">
		<h2><?=$depth?>차 메뉴 설정</h2>
		<button type="button" title="레이어 팝업 닫기" class="btn_close_layer" onclick="commonLayerClose('menu_write_popup')"></button>
	</div>
	<div class="cont">
		<div class="common_form">
			<form name="popCategoryFrm" id="popCategoryFrm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="depth" value="<?=$depth?>" />
            <input type="hidden" name="category_idx" value="<?=$category_idx?>" />
            <input type="hidden" name="parent_idx" value="<?=$parent_idx?>" />
			<table class="g_table">
                <colgroup>
                    <col width="15%">
					<col width="*">
				</colgroup>
				<tbody>
					<tr>
                        <th><span class="t_imp">상위메뉴</span></th>
						<td><?=$parent_path?></td>
                    </tr>
					<tr>
                        <th><span class="t_imp"><?=$depth?>차 메뉴명</span></th>
						<td>
                            <div class="box">
								<div class="input_box" style="width:100%">
									<input type="text" name="name" id="pop_name" value="<?=$ctg_view['name']?>" />
								</div>
							</div>
                        </td>
                    </tr>
					<tr>
                        <th><span class="t_imp">정렬순번</span></th>
						<td>
                            <div class="box">
								<div class="input_box" style="width:60px">
									<input type="text" name="sort" id="pop_sort" value="<?=$ctg_view['sort']?>" class="onlyNum" maxlength="3" placeholder="0~999 낮은순으로 정렬" />
                                </div>

                                <p class="normal fc_red">※ 정렬순번이 낮은 번호가 상위로 배치됩니다. 동일순번일경우 등록일순으로 배치됩니다. (기본값: 0)</p>
                            </div>
                        </td>
                    </tr>
					<tr>
                        <th><span class="t_imp">사용권한</span></th>
						<td>
                            <div class="box">
                                <div class="c_checkbox">
                                    <input type="checkbox" name="allow_auth[]" id="allow_auth_0" value="00" <?=chkCompare($ctg_view['allow_auth'], '00', 'checked')?> />
                                    <label for="allow_auth_0">비회원</label>
                                </div>

                                <?foreach($CONST_MEMBER_GUBUN as $item) {?>
                                    <div class="c_checkbox">
                                        <input type="checkbox" name="allow_auth[]" id="allow_auth_<?=$item[0]?>" value="<?=$item[0]?>" <?=chkCompare($ctg_view['allow_auth'], $item[0], 'checked')?> />
                                        <label for="allow_auth_<?=$item[0]?>"><?=$item[1]?></label>
                                    </div>
                                <?}?>
                            </div>
                        </td>
                    </tr>
					<tr>
                        <th><span class="t_imp">노출상태</span></th>
						<td>
                            <div class="box">
							    <div class="c_selectbox">
                                    <label for=""></label>
                                    <select name="open_flag" id="pop_open_flag">
                                        <option value="Y" <?=chkCompare($ctg_view['open_flag'],'Y','selected')?>>노출</option>
                                        <option value="N" <?=chkCompare($ctg_view['open_flag'],'N','selected')?>>숨김</option>
                                    </select>
                                </div>

                                <p class="normal fc_red">※ 사용안함 선택시 하위 카테고리도 사용유무 관계없이 비노출 됩니다.</p>
                            </div>
                        </td>
                    </tr>

                    <?if ($depth == '1') {?>
                        <tr>
                            <th><span class="t_h">아이콘 파일</span></th>
                            <td>
                                <div class="box file">
                                    <div class="input_box" style="width:250px">
                                        <input type="text" placeholder="아이콘 파일을 첨부해 주세요." readonly />
                                    </div>
                                    <input type="file" name="up_file" id="pop_up_file" class="upload-hidden" upload-type="img" upload-size="1" />
                                    <input type="hidden" name="old_up_file" id="pop_old_file" value="<?=$ctg_view['up_file']?>" />
                                    <label for="pop_up_file" class="btn_30 gray">찾아보기</label>
                                    <?if (getUpfileName($ctg_view['up_file']) != '') {?>
                                        <p class="mt5" >
                                            <a href="javascript:;" onclick="imgPreviwePopupOpen('/upload/content/icon/<?=getUpfileName($ctg_view['up_file'])?>')">
                                                <img src="/upload/content/icon/<?=getUpfileName($ctg_view['up_file'])?>" style="max-height:100px" />
                                            </a>
                                        </p>
                                    <?}?>
                                </div>
                            </td>
                        </tr>
                    <?}?>

					<tr>
                        <th><span class="t_h">메모</span></th>
						<td>
                            <div class="box">
                                <div class="textarea_box" style="width:100%">
                                    <textarea name="memo" id="pop_memo" style="height:80px"><?=$ctg_view['memo']?></textarea>
                                </div>
                            </div>
                        </td>
					</tr>
				</tbody>
			</table>
			</form>

			<div class="btn_area two">
				<a href="javascript:;" class="btn gray" onclick="commonLayerClose('menu_write_popup')">닫기</a>
				<a href="javascript:;" class="btn blue" onclick="popMenuSaveGo()">저장하기</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    function popMenuSaveGo() {
        var h = new clsJsHelper();

        if (!h.checkValNLen("pop_name", 2, 50, "메뉴명", "N", "KO")) return false;
        if (!h.checkValNLen("pop_sort", 1, 3, "정렬순번", "Y", "ON")) return false;
        if ($(":checkbox[name='allow_auth[]']:checked").length == 0) {
            alert("사용권한은 최소 1개이상 선택하셔야 합니다.");

            return false;
        }

        <?if ($depth == '1') {?>
        //if (h.objVal("pop_old_file")=="" && h.objVal("pop_up_file")=="") {
        //    if (!h.checkSelect("pop_up_file", "아이콘 파일")) return false;
        //}
        <?}?>

		AJ.ajaxForm($("#popCategoryFrm"), "menu_write_proc.php", function(data) {
			if (data.result == 200) {
                alert("저장 처리 되었습니다.");

                var check_idx = $("#ctgDepth<?=$depth?>").find("tr.check").attr("category_idx");
                if (!check_idx) check_idx = '';
                AJ.callAjax("__menu_list.php", {"depth": "<?=$depth?>", "category_idx": "<?=$parent_idx?>", "check_idx": check_idx}, function(data){
                    $("#ctgDepth<?=$depth?>").html(data);

                    commonLayerClose('menu_write_popup');
                }, "html");
			} else {
				alert(data.message);
			}
		});
    }
</script>