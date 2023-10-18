<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	if (!chkReferer()) fnMsgJson(500, "잘못된 접근 입니다.", "");
	if (!isAdmin()) fnMsgJson(501, "해당정보에 접근할 수 있는 권한이 없습니다.", "");

	$params['page']       = chkReqRpl("page", 1, "", "", "INT");
	$params['list_size']  = 10;
	$params['block_size'] = 10;
	$params['sch_id']     = chkReqRpl("sch_id", "", 10, "", "STR");
	$page_params = setPageParamsValue($params, "page,list_size,block_size");

	$db = new DB_HELPER;

	$sub_sql = "";

	//회원 아이디
	if ($params['sch_id'] != "") {
		$sub_sql .= " AND a.reg_id = '". $params['sch_id'] ."'";
	}

	$sql = "
			SELECT
				a.idx,
				e.title AS class_title,
				d.title AS lecture_title,
				a.reg_date AS sdate,
				a.upt_date AS edate,
				a.play_time,
				a.total_time,
				a.status
			FROM content_class_apply_lecture a INNER JOIN content_class_apply b ON a.apply_idx=b.idx AND b.del_flag='N'
				LEFT OUTER JOIN content_class_lecture d ON a.lecture_idx=d.idx
				LEFT OUTER JOIN content_class e ON b.class_idx=e.idx
			WHERE a.status > 0 AND a.del_flag='N' $sub_sql
			ORDER BY a.reg_date DESC
		";
	$list = $db->getList($sql, $params['page'], $params['list_size'], $total_cnt, $total_page);
?>
<div class="common_list">
    <!-- <div class="list_header">
        <dl class="cnt">
            <dt>Total</dt>
            <dd><?=formatNumbers($total_cnt)?></dd>
        </dl>
    </div> -->
    <table>
        <colgroup>
            <col width="70" />
            <col width="*" />
            <col width="*" />
            <col width="140" />
            <col width="140" />
            <col width="90" />
            <col width="90" />
            <col width="90" />
        </colgroup>
        <thead>
            <tr>
                <th>번호</th>
                <th>강좌명</th>
                <th>강의명</th>
                <th>시작일시</th>
                <th>종료일시</th>
                <th>진행 시간</th>
                <th>콘텐츠 시간</th>
                <th>진행현황</th>
            </tr>
        </thead>
        <tbody>
            <?for ($i=0; $i<count($list); $i++) {?>
                <tr>
                    <td><?=formatNumbers($total_cnt-(($params['page']-1)*$params['list_size'])-$i)?></td>
                    <td><?=$list[$i]['class_title']?></td>
                    <td><?=$list[$i]['lecture_title']?></td>
                    <td><?=formatDates($list[$i]['sdate'], "Y.m.d H:i:s")?></td>
                    <td><?=formatDates($list[$i]['edate'], "Y.m.d H:i:s")?></td>
                    <td><?=timeToKor($list[$i]['play_time'])?></td>
                    <td><?=timeToKor($list[$i]['total_time'])?></td>
                    <td>
                        <?
                            if ($list[$i]['status'] == '1') {
                                echo "진행중 (". round(($list[$i]['play_time'] / $list[$i]['total_time'])*100) ."%)";
                            } else {
                                echo "완료";
                            }
                        ?>
                    </td>
                </tr>
            <?}?>

            <?if (count($list) == 0) {?>
                <tr>
                    <td colspan="8">등록된 데이터가 없습니다.</td>
                </tr>
            <?}?>
        </tbody>
    </table>

    <nav class="page_nate">
        <? adminScriptPaging($total_page, $params['block_size'], $params['page'], "getLectureList({page})") ?>
    </nav>
</div>