<?include("../inc/config.php")?>
<?
	$pageNum = "0301";
	$cls_set_menu->menu_code_name($pageNum, $pageName, $pageSubName);

    $cls_content = new CLS_CONTENT;

	//1차 메뉴 목록 불러오기
	$ctg_list = $cls_content->category_list(1);
?>
<?include("../inc/header.php")?>
	<!-- container -->
	<div class="container sub">
		<div class="contents">
			<?include("../inc/top_navi.php")?>

			<div class="common_list">
                <?for ($i=1; $i<=4; $i++) {?>
                    <div id="ctgDepth<?=$i?>" parent_idx="" style="display:inline-block;width:calc(25% - 8px);margin:0 2px; vertical-align:top;">
                        <table>
                            <colgroup>
                                <col width="50" />
                                <col width="*" />
                                <col width="50" />
                                <col width="98" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>순번</th>
                                    <th><?=$i?>차 메뉴명</th>
                                    <th>사용<br>여부</th>
                                    <th>관리</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?if ($i==1) {?>
                                    <?for ($k=0; $k<count($ctg_list); $k++) {?>
                                        <tr class="ctgRow" category_idx="<?=$ctg_list[$k]['category_idx']?>">
                                            <td><?=$ctg_list[$k]['sort']?></td>
                                            <td class="left"><a href="javascript:;" class="link" onclick="childViewGo(<?=$i?>, <?=$ctg_list[$k]['category_idx']?>)"><?=$ctg_list[$k]['name']?></a></td>
                                            <td><?=$ctg_list[$k]['open_flag']?></td>
                                            <td>
                                                <a href="javascript:;" class="btn_22 white" onclick="menuWriteGo(<?=$i?>, '<?=$ctg_list[$k]['category_idx']?>', '<?=$ctg_list[$k]['parent_idx']?>');">수정</a>
                                                <a href="javascript:;" class="btn_22 red" onclick="menuDeleteGo(<?=$i?>, '<?=$ctg_list[$k]['category_idx']?>', '<?=$ctg_list[$k]['parent_idx']?>');">삭제</a>
                                            </td>
                                        </tr>
                                    <?}?>

                                    <?if (count($ctg_list) == 0) {?>
                                        <tr>
                                            <td colspan="4"><?=$i?>차 메뉴를 등록해주세요.</td>
                                        </tr>
                                    <?}?>
                                <?} else {?>
                                    <tr>
                                        <td colspan="4"><?=$i-1?>차 메뉴명을 선택해주세요.</td>
                                    </tr>
                                <?}?>
                            </tbody>
                        </table>

                        <div id="ctgBtn1" class="mt10 ta_r">
                            <a href="javascript:;" class="btn_30" onclick="menuWriteGo(<?=$i?>, '', '')"><?=$i?>차 메뉴 등록</a>
                        </div>
                    </div>
                <?}?>
			</div>
		</div>
	</div>
    <!-- //container -->

	<!-- 레이어팝업 : 메뉴등록 팝업 -->
	<article class="layer_popup menu_write_popup"></article>

	<script type="text/javascript">
        function menuWriteGo(depth, ctg_idx, prt_idx) {
            if (depth != 1 && prt_idx == '') {
                alert((depth-1)+"차 메뉴명을 선택해주세요.");

                return false;
            }

			AJ.callAjax("menu_write.php", {"depth": depth, "category_idx": ctg_idx, "parent_idx": prt_idx}, function(data){
				$(".menu_write_popup").html(data);
				commonLayerOpen('menu_write_popup');
			}, "html");
        }

        function menuDeleteGo(depth, ctg_idx, prt_idx) {
			if (!confirm("선택한 메뉴를 삭제 하시겠습니까?\n삭제시 데이터는 복구가 불가능합니다.\n\n계속 진행하시려면 '확인'을 눌러주세요.")) return false;

			AJ.callAjax("menu_delete_proc.php", {"category_idx": ctg_idx}, function(data){
				if (data.result == 200) {
                    alert("처리 되었습니다.");

                    var check_idx = $("#ctgDepth"+depth).find("tr.check").attr("category_idx");
                    if (!check_idx) check_idx = '';
                    AJ.callAjax("__menu_list.php", {"depth": depth, "category_idx": prt_idx, "check_idx": check_idx}, function(data){
                        $("#ctgDepth"+depth).html(data);

                        if (ctg_idx == check_idx) {
                            var max_depth = 4;
                            for (i=depth+1; i<=max_depth; i++) {
                                $("#ctgDepth"+i).find("tbody").html('<tr><td colspan="4">'+ (i-1) +'차 메뉴명을 선택해주세요.</td></tr>');
                                $("#ctgBtn"+i).html('<a href="javascript:;" class="btn_30" onclick="menuWriteGo('+ i +', \'\', \'\')">'+ i +'차 메뉴 등록</a>');
                            }
                        }
                    }, "html");
				} else {
					alert(data.message);
				}
			});
        }

        function childViewGo(depth, ctg_idx) {
            var $target = $("tr[class^='ctgRow'][category_idx='"+ ctg_idx +"']");
            $target.siblings().removeClass("check");
            $target.addClass("check");

			AJ.callAjax("__menu_list.php", {"depth": (depth+1), "category_idx": ctg_idx}, function(data){
                $("#ctgDepth"+(depth+1)).html(data);

                var max_depth = 4;
                for (i=depth+2; i<=max_depth; i++) {
                    $("#ctgDepth"+i).find("tbody").html('<tr><td colspan="4">'+ (i-1) +'차 메뉴명을 선택해주세요.</td></tr>');
                    $("#ctgBtn"+i).html('<a href="javascript:;" class="btn_30" onclick="menuWriteGo('+ i +', \'\', \'\')">'+ i +'차 메뉴 등록</a>');
                }
            }, "html");
        }
	</script>
<?include("../inc/footer.php")?>