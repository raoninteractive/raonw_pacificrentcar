<?include("../inc/config.php")?>
<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "../", "");
    if (!isAdmin()) fnMsgGo(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "../", "");

    $class_idx = chkReqRpl("class_idx", null, "", "POST", "INT");
    $idx       = chkReqRpl("idx", null, "", "POST", "INT");

    if (chkBlank($class_idx)) fnMsgGo(502, "강좌 고유번호 값이 유효하지 않습니다.", "RELOAD", "");

    $cls_content = new CLS_CONTENT;

    //강좌 정보 불러오기
    $class_view = $cls_content->class_view($class_idx);
    if ($class_view == false) fnMsgGo(503, "일치하는 강좌 정보가 없습니다.", "RELOAD", "");

    //강의 정보 불러오기
    $view = $cls_content->lecture_view($idx);
    if ($view == false) {
        $view['sort'] = '0';
    }
?>
<div class="dim"></div>
<div class="contents" style="width:800px;margin-left:-400px;">
	<div class="layer_header">
		<h2>강의 <?=iif(chkBlank($view['idx']), '등록', '수정')?> 설정</h2>
		<button type="button" title="레이어 팝업 닫기" class="btn_close_layer" onclick="commonLayerClose('lecture_write_popup')"></button>
	</div>
	<div class="cont">
		<div class="common_form">
			<form name="popCategoryFrm" id="popCategoryFrm" method="post" enctype="multipart/form-data">
            <input type="hidden" name="class_idx" value="<?=$class_idx?>" />
            <input type="hidden" name="idx" value="<?=$idx?>" />
			<table class="g_table">
                <colgroup>
                    <col width="17%">
					<col width="*">
				</colgroup>
				<tbody>
					<tr>
                        <th><span class="t_h">강좌명</span></th>
						<td><?=$class_view['title']?></td>
                    </tr>
					<tr>
                        <th><span class="t_imp">강의명</span></th>
						<td>
                            <div class="box">
								<div class="input_box" style="width:100%">
									<input type="text" name="title" id="pop_title" value="<?=$view['title']?>" />
								</div>
							</div>
                        </td>
                    </tr>
					<tr>
                        <th><span class="t_imp">옵션</span></th>
						<td>
                            <div class="box">
                                <div class="c_checkbox">
                                    <input type="checkbox" name="sample_flag" id="pop_sample_flag" value="Y" <?=chkCompare($view['sample_flag'], 'Y', 'checked')?> />
                                    <label for="pop_sample_flag">맛보기 강의</label>
                                </div>

                                <div class="c_checkbox">
                                    <input type="checkbox" name="controll_flag" id="pop_controll_flag" value="Y" <?=chkCompare($view['controll_flag'], 'Y', 'checked')?> />
                                    <label for="pop_controll_flag">막대(진행바) 조절 기능</label>
                                </div>
							</div>
                        </td>
                    </tr>
                    <tr>
                        <th><span class="t_imp">동영상/오디오 파일</span></th>
                        <td>
                            <div class="box file">
                                <div class="input_box" style="width:250px">
                                    <input type="text" placeholder="동영상/오디오 파일을 첨부해 주세요." readonly />
                                </div>
                                <input type="file" name="up_file_1" id="pop_up_file_1" class="upload-hidden" upload-type="video" upload-size="500" upload-ext="mp3,mp4" />
                                <input type="hidden" name="old_up_file_1" id="pop_old_file_1" value="<?=$view['up_file_1']?>" />
                                <label for="pop_up_file_1" class="btn_30 gray">찾아보기</label>
                                <?if (getUpfileName($view['up_file_1']) != '') {?>
                                    <p class="mt5" id="pop_filebox_1">
                                        <a href="lecture_file_down.php?idx=<?=$view['idx']?>&fnum=1"><?=getUpfileOriName($view['up_file_1'])?></a>
                                        <!-- <a href="javascript:;" onclick="popFileDel('1')">[삭제]</a> -->
                                    </p>
                                    <p class="mt5 fc_red">
                                        ※ 파일명 또는 파일용량 변경시 현재 수강 시청중인 강의는 모두 초기화 처리 됩니다.<br>
                                        ※ 동영상/오디오 수정시 유의해주세요.
                                    </p>
                                <?}?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><span class="t_h">첨부파일1</span></th>
                        <td>
                            <div class="box file">
                                <div class="input_box" style="width:250px">
                                    <input type="text" placeholder="첨부자료 파일을 첨부해 주세요." readonly />
                                </div>
                                <input type="file" name="up_file_2" id="pop_up_file_2" class="upload-hidden" upload-type="file" upload-size="100" />
                                <input type="hidden" name="old_up_file_2" id="pop_old_file_2" value="<?=$view['up_file_2']?>" />
                                <label for="pop_up_file_2" class="btn_30 gray">찾아보기</label>
                                <?if (getUpfileName($view['up_file_2']) != '') {?>
                                    <p class="mt5" id="pop_filebox_2">
                                        <a href="lecture_file_down.php?idx=<?=$view['idx']?>&fnum=2"><?=getUpfileOriName($view['up_file_2'])?></a>
                                        <a href="javascript:;" onclick="popFileDel('2')">[삭제]</a>
                                    </p>
                                <?}?>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <th><span class="t_h">첨부파일2</span></th>
                        <td>
                            <div class="box file">
                                <div class="input_box" style="width:250px">
                                    <input type="text" placeholder="첨부자료 파일을 첨부해 주세요." readonly />
                                </div>
                                <input type="file" name="up_file_3" id="pop_up_file_3" class="upload-hidden" upload-type="file" upload-size="100" />
                                <input type="hidden" name="old_up_file_3" id="pop_old_file_3" value="<?=$view['up_file_3']?>" />
                                <label for="pop_up_file_3" class="btn_30 gray">찾아보기</label>
                                <?if (getUpfileName($view['up_file_3']) != '') {?>
                                    <p class="mt5" id="pop_filebox_3">
                                        <a href="lecture_file_down.php?idx=<?=$view['idx']?>&fnum=3"><?=getUpfileOriName($view['up_file_3'])?></a>
                                        <a href="javascript:;" onclick="popFileDel('3')">[삭제]</a>
                                    </p>
                                <?}?>
                            </div>
                        </td>
                    </tr>
					<tr>
                        <th><span class="t_imp">정렬순번</span></th>
						<td>
                            <div class="box">
								<div class="input_box" style="width:60px">
									<input type="text" name="sort" id="pop_sort" value="<?=$view['sort']?>" class="onlyNum" maxlength="3" placeholder="0~999 낮은순으로 정렬" />
                                </div>

                                <p class="normal fc_red">※ 정렬순번이 낮은 번호가 상위로 배치됩니다. 동일순번일경우 등록일순으로 배치됩니다. (기본값: 0)</p>
                            </div>
                        </td>
                    </tr>
					<!-- <tr>
                        <th><span class="t_imp">사용유무</span></th>
						<td>
                            <div class="box">
							    <div class="c_selectbox">
                                    <label for=""></label>
                                    <select name="open_flag" id="pop_open_flag">
                                        <option value="Y" <?=chkCompare($view['open_flag'],'Y','selected')?>>사용</option>
                                        <option value="N" <?=chkCompare($view['open_flag'],'N','selected')?>>사용안함</option>
                                    </select>
                                </div>
                            </div>
                        </td>
                    </tr> -->
				</tbody>
			</table>
			</form>

			<div class="btn_area two">
				<a href="javascript:;" class="btn gray" onclick="commonLayerClose('lecture_write_popup')">닫기</a>
				<a href="javascript:;" class="btn blue" onclick="popLectureSaveGo()">저장하기</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
    function popLectureSaveGo() {
        var h = new clsJsHelper();

        if (!h.checkValNLen("pop_title", 2, 100, "강의명", "N", "KO")) return false;
        if (h.objVal("pop_old_file_1")=="" && h.objVal("pop_up_file_1")=="") {
            if (!h.checkSelect("pop_up_file_1", "동영상/오디오 파일")) return false;
        }
        if (!h.checkValNLen("pop_sort", 1, 3, "정렬순번", "Y", "ON")) return false;

		AJ.ajaxForm($("#popCategoryFrm"), "lecture_write_proc.php", function(data) {
			if (data.result == 200) {
                alert("저장 처리 되었습니다.");

                location.reload();
			} else {
				alert(data.message);
			}
		});
    }

    function popFileDel(fnum) {
        if (!confirm("첨부파일을 삭제하시겠습니까?\n삭제 후 파일은 복구가 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

        AJ.callAjax("lecture_file_delete_proc.php", {"idx":"<?=$idx?>", "fnum": fnum}, function(data){
            if (data.result == 200) {
                alert("파일이 삭제되었습니다.");

                $("#pop_filebox_"+fnum).remove();
                $("#filebox_<?=$idx?>_"+fnum).remove();

            } else {
                alert(data.message);
            }
        });
    }
</script>