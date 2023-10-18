<?php
	//----------------------------------------------------------------------
	//	Description
	//		메시지창 띄운 후 페이지 이동 처리
	//	Params
	//		code : 오류코드
	//		msg		: 메시지
	//		url		: 페이지 이동 주소
	//		target	: 페이지 이동 타겟
	//	Return
	//		설정정보에 따른 페이지 이동 반환
	//----------------------------------------------------------------------
	function fnMsgGo($code="", $msg="", $url="", $target="") {
		$msg = trim($msg);
		$url = trim($url);
		$target = trim($target);

		if (DEBUG_CHECK && $msg != '') {
			$msg = "[$code] $msg";
		}

		$msg = str_replace("\n", "\\n", $msg);

		echo "<script type=\"text/javascript\">" . PHP_EOL;

		if (!chkBlank($msg)) {
			echo "alert(\"$msg\");" . PHP_EOL;
		}

		if (!chkBlank($url)) {
			switch (strtoupper($target)) {
				case "P" :
					echo "parent.";
					break;
				case "O" :
					echo "opener.";
					break;
				case "PO" :
					echo "parent.opener.";
					break;
				case "T" :
					echo "top.";
					break;
				default :
					if (!chkBlank($target)) {
						echo target.".";
					}
					break;
			}

			switch(strtoupper($url)) {
				case "ALERT" :										//Alert 창만 띄움
					break;
				case "SPAMCODE" :									//스팸방지코드 재생성
					echo "spamImageChange();";
					break;
				case "BACK" :										//뒤로가기
					echo "history.go(-1);" . PHP_EOL;
					break;
				case "CLOSE" :										//창닫기
					echo "self.close();" . PHP_EOL;
					break;
				case "WCLOSE" :										//창닫기
					echo "window.close();" . PHP_EOL;
					break;
				case "RELOAD" :										//새로고침
					echo "location.reload();" . PHP_EOL;

					if (strtoupper($target) == "O") {
						echo "self.close();" . PHP_EOL;
					}
					break;
				default :
					echo "location.replace('" . $url . "');" . PHP_EOL;

					if (strtoupper($target) == "O") {
						echo "self.close();" . PHP_EOL;
					} else if (strtoupper($target) == "PO") {
						echo "parent.self.close();" . PHP_EOL;
					}
					break;
			}
		}

		echo "</script>" . PHP_EOL;
		exit;
	}

	//----------------------------------------------------------------------
	//	Description
	//		json 결과물로 메시지 출력
	//	Params
	//		code : 오류코드
	//		msg	 : 오류메시지
	//		url	 : 페이지 이동
	//	Return
	//		설정정보에 따른 json 데이터 출력
	//----------------------------------------------------------------------
	function fnMsgJson($code="", $msg="", $url="") {
		$code = trim($code);
		$msg = trim($msg);
		$url = trim($url);

		if (DEBUG_CHECK) {
			$msg = "[$code] $msg";
		}

		$data = array(
			"result" => $code,
			"message" => $msg
		);

		if (!chkBlank($url)) {
			$data["location"] = $url;
		}

		echo json_encode($data, JSON_UNESCAPED_UNICODE);
		exit;
	}

	//----------------------------------------------------------------------
	//	Description
	//		메시지 출력 후 스크립트 호출
	//	Params
	//		code : 오류코드
	//		msg	 : 오류메시지
	//		fn_name : 호출 스크립트명
	//	Return
	//		설정정보에 따른 json 데이터 출력
	//----------------------------------------------------------------------
	function fnScriptMsg($code="", $msg="", $fn_name="") {
		$code = trim($code);
		$msg = trim($msg);
		$fn_name = trim($fn_name);

		if (DEBUG_CHECK) {
			$msg = "[$code] $msg";
		}

		echo "<script type=\"text/javascript\">" . PHP_EOL;
		if (!chkBlank($msg)) {
			echo "alert(\"$msg\");" . PHP_EOL;
		}
		if (!chkBlank($fn_name)) {
			echo "setTimeout(function() { $fn_name }, 50);" . PHP_EOL;
		}
		echo "</script>" . PHP_EOL;
		exit;
	}
