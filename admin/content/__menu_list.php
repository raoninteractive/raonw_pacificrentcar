<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "../", "");
	if (!isAdmin()) fnMsgGo(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "../", "");

    $depth        = chkReqRpl("depth", null, "", "POST", "INT");
    $category_idx = chkReqRpl("category_idx", null, "", "POST", "INT");
    $check_idx    = chkReqRpl("check_idx", null, "", "POST", "INT");

	if (chkBlank($depth)) fnMsgGo(503, "카테고리 정보 값이 유효하지 않습니다.", "RELOAD", "");
    if ($depth>1 && chkBlank($category_idx)) fnMsgGo(504, "카테고리 정보 값이 유효하지 않습니다.", "RELOAD", "");

    $cls_content = new CLS_CONTENT;

	//메뉴 목록 불러오기
	$ctg_list = $cls_content->category_list($depth, $category_idx);
?>
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
            <th><?=$depth?>차 메뉴명</th>
            <th>사용<br>여부</th>
            <th>관리</th>
        </tr>
    </thead>
    <tbody>
        <?for ($i=0; $i<count($ctg_list); $i++) {?>
            <tr class="ctgRow <?=iif($check_idx==$ctg_list[$i]['category_idx'], 'check', '')?>" category_idx="<?=$ctg_list[$i]['category_idx']?>">
                <td><?=$ctg_list[$i]['sort']?></td>
                <td class="left">
                    <?if ($depth < 4) {?>
                        <a href="javascript:;" class="link" onclick="childViewGo(<?=$depth?>, <?=$ctg_list[$i]['category_idx']?>)"><?=$ctg_list[$i]['name']?></a>
                    <?} else {?>
                        <?=$ctg_list[$i]['name']?>
                    <?}?>
                </td>
                <td><?=$ctg_list[$i]['open_flag']?></td>
                <td>
                    <a href="javascript:;" class="btn_22 white" onclick="menuWriteGo(<?=$depth?>, '<?=$ctg_list[$i]['category_idx']?>', '<?=$ctg_list[$i]['parent_idx']?>');">수정</a>
                    <a href="javascript:;" class="btn_22 red" onclick="menuDeleteGo(<?=$depth?>, '<?=$ctg_list[$i]['category_idx']?>', '<?=$ctg_list[$i]['parent_idx']?>');">삭제</a>
                </td>
            </tr>
        <?}?>

        <?if (count($ctg_list) == 0) {?>
            <tr>
                <td colspan="4"><?=$depth?>차 메뉴를 등록해주세요.</td>
            </tr>
        <?}?>
    </tbody>
</table>

<div id="ctgBtn<?=$depth?>" class="mt10 ta_r ctgBtn">
    <a href="javascript:;" class="btn_30" onclick="menuWriteGo(<?=$depth?>, '', '<?=$category_idx?>')"><?=$depth?>차 메뉴 등록</a>
</div>