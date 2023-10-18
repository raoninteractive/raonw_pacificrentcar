<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgGo(500, "잘못된 접근 입니다.", "../", "");
    if (!isAdmin()) fnMsgGo(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "../", "");

	$idx = chkReqRpl("idx", null, "", "", "INT");

    $cls_sms = new CLS_SMS;

    $view = $cls_sms->send_view($idx);
    if ($view == false) fnMsgGo(502, "일치하는 데이터가 없습니다.", "sms_send_list.php", "");
?>
<div class="dim"></div>
<div class="contents" style="width:700px;margin-left:-350px;">
	<div class="layer_header">
		<h2>문자발송 정보 상세보기</h2>
		<button type="button" title="레이어 팝업 닫기" class="btn_close_layer" onclick="commonLayerClose('sms_send_view_popup')"></button>
	</div>
	<div class="cont">
		<div class="common_form">
			<table class="g_table">
				<colgroup>
					<col width="15%">
					<col width="35%">
					<col width="15%">
					<col width="35%">
				</colgroup>
				<tbody>
					<tr>
						<th>발송위치</th>
						<td><?=$view['section']?></td>
						<th>수신아이디</th>
						<td><?=$view['usr_id']?></td>
					</tr>
					<tr>
						<th>수신정보</th>
						<td><?=$view['recipient_tel']?><?=iif($view['recipient_name']!="", " / ". $view['recipient_name'], "")?></td>
						<th>발신정보</th>
						<td><?=$view['sender_tel']?><?=iif($view['sender_name']!="", " / ". $view['sender_name'], "")?></td>
					</tr>
					<tr>
						<th>발송일시</th>
						<td>
							<?=iif($view['send_gubun']=='R','[예약발송]','[일반발송]')?>&nbsp;
							<?=formatDates($view['reserve_date'],"Y-m-d H:i")?>
						</td>
						<th>발송타입</th>
						<td><?=$view['send_type']?></td>
					</tr>
					<tr>
						<th>발송내용</th>
						<td colspan="3">
							<div style="max-height:150px; overflow-y:auto;">
								<?=textareaDecode($view['send_msg'])?>

								<?if ($view['up_file'] != '') {?>
									<p style="margin-top:10px"><img src="/upload/sms/<?=$view['up_file']?>" style="max-width:100%" /></p>
                                <?}?>
							</div>
						</td>
					</tr>
					<tr>
						<th>발송상태</th>
						<td colspan="3">
                            <?if ($view['status']=='0' && $view['send_gubun']=='R') {?>
                                <?if ($view['status']=='0' && $view['send_gubun']=='R') {?>
                                    <a href="javascript:;" class="a_link fc_red" onclick="smsSendCancel(<?=$view['idx']?>)">예약발송취소</a>
                                <?} else {?>
                                    <?=$view['status_name']?>
                                <?}?>
                            <?} else {?>
                                <?if ($view['status'] > '1') {?>
                                    <strong class="fc_red"><?=$view['status_name']?></strong>
                                    [<?=$view['status_memo']?>]
                                <?} else {?>
                                    <?=$view['status_name']?>
                                <?}?>
                            <?}?>
						</td>
					</tr>
				</tbody>
			</table>

			<div class="btn_area">
				<a href="javascript:;" class="btn gray" onclick="commonLayerClose('sms_send_view_popup')">닫기</a>
			</div>
		</div>
	</div>
</div>