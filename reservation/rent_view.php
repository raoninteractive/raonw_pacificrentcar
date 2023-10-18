<?
    require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

    if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "RELOAD", "");

    $params['goods_idx']  = chkReqRpl("goods_idx", null, "", "", "INT");

    $cls_goods = new CLS_GOODS;

    $view = $cls_goods->goods_view($params['goods_idx'], 'Y');
    if ($view == false) fnMsgGo(501, "일치하는 차량정보가 없습니다.", "RELOAD", "");
?>
<div class="popup">
    <div class="p_head ty1">
        <h2 class="hidden">차량상세</h2>
        <button type="button" class="btn_close b-close"><span>닫기</span></button>
    </div>
    <div class="p_cont">
        <?if ($view['keyword'] != '') {?>
            <div class="bat">
                <span class="i-txt"><?=implode('</span><span class="i-txt">',explode(',', $view['keyword']))?></span>
            </div>
        <?}?>
        <div class="cont">
            <div class="img ta-c mb20"><img src="<?=filePathCheck("/upload/goods/thumb/".getUpfileName($view['up_file_1']))?>" alt="사진"></div>
            <p class="h1">
                <!-- <span>괌(GUAM)</span> -->
                <span class="tit f-gm"><?=$view['title']?></span>
            </p>
            <div class="tbl_basic f-gm">
                <table class="list">
                    <caption>차량정보</caption>
                    <thead>
                        <tr>
                            <th>주유포함</th>
                            <th>CDW</th>
                            <th>ZDC</th>
                            <th>PAI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><?=iif($view['option_1'] == 'Y', 'O', '')?></td>
                            <td><?=iif($view['option_2'] == 'Y', 'O', '')?></td>
                            <td><?=iif($view['option_7'] == 'Y', 'O', '')?></td>
                            <td><?=iif($view['option_8'] == 'Y', 'O', '')?></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?if ($view['content'] != '') {?>
                <div class="lst_dot">
                    <ul>
                        <li><?=implode("</li><li>",explode("\n", $view['content']))?></li>
                    </ul>
                </div>
            <?}?>
        </div>
    </div>
</div>