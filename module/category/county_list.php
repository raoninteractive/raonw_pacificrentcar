<?
	require($_SERVER["DOCUMENT_ROOT"]."/module/lib/header.php");

	$city = chkReqRpl("city", "", 50, "", "STR");

	$rows = category_area_list($city);

	$data['result'] = 200;

	if (count($rows) > 0) {
		$data['message'] = "OK";

		for ($i=0; $i<count($rows);$i++) {
			$data['list'][$i]['county'] = $rows[$i]['county'];
		}
	} else {
		$data['message'] = "일치하는 데이터가 없습니다.";
	}

	echo json_encode($data, JSON_UNESCAPED_UNICODE);
?>