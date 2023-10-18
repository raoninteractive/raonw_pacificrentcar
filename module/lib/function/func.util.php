<?php
	//----------------------------------------------------------------------
	//	@description
	//		입력된 조건값을 비교 후에 Boolean 값을 반환한다.
	//	@param
	//		(Boolean) condition : 비교할 데이터 조건
	//		(string)  true_val  : True 일경우 반환값
	//		(string)  false_val : False 일경우 반환값
	//	@return
	//		(string) true or false 반환값
	//----------------------------------------------------------------------
	function iif($condition, $true_val, $false_val) {
		return ($condition) ? $true_val : $false_val;
	}

	function ifNull($val = "", $replace_val = null) {
		return iif(chkBlank($val), $replace_val, $val);
	}

	function isDate($chk_date, $format='Y-m-d') {
		if (chkBlank($chk_date)) return false;

		date_default_timezone_set('UTC');
		$date = DateTime::createFromFormat($format, $chk_date);

		return $date && ($date->format($format) === $chk_date);

		/*
		if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$chk_date)) {
			$year  = date('Y', strtotime($chk_date));
			$month = date('m', strtotime($chk_date));
			$day   = date('d', strtotime($chk_date));

			return checkdate($month, $day, $year);
		} else {
			return false;
		}
		*/
	}

	function isStrpos($input_val, $find_val) {
		if (strpos($input_val, ",") !== false) {
			foreach (explode(",", $input_val) as $item) {
				if (trim($item) == $find_val) {
					return true;
				}
			}
		} else {
			if (strval($input_val) === strval($find_val)) {
				return true;
			}
		}

		return false;
	}

	function weekName($date, $types='w') {
		global $CONST_WEEK_NAME, $CONST_WEEK_NAME2;

		if (chkBlank($date)) return "";

		$week = date('w', strtotime($date));

		if ($types == 'w') {
			echo $CONST_WEEK_NAME2[$week];
		} else if ($types == 'W') {
			echo $CONST_WEEK_NAME[$week];
		}

		return "";
	}

	function weekDay($date) {
		if (chkBlank($date)) return "";

		return date('w', strtotime($date));
	}

	//----------------------------------------------------------------------
	//	@description
	//		입력된 데이터 공백, null 체크 후 Boolean 값을 반환한다.
	//	@param
	//		(string) value : 데이터값
	//	@return
	//		(Boolean) true or false
	//----------------------------------------------------------------------
	function chkBlank($val) {
		if (is_array($val)) {
			if (count($val) == 0) {
				return true;
			} else {
				return false;
			}
		} else {
			if (trim($val) === "" || strlen(trim($val)) == 0 || is_null($val)) {
				return true;
			} else {
				return false;
			}
		}
	}


	//----------------------------------------------------------------------
	//	@description
	//		페이지 referer 체크
	//		페이지 바로 접속여부 확인용
	//	@param
	//	@return
	//		(Boolean) true or false
	//----------------------------------------------------------------------
	function chkReferer() {
		if (strlen(URL_REFERER) === 0) {
			return false;
		} else {
			return true;
		}
	}


	//----------------------------------------------------------------------
	//	@description
	//		입력된 데이터를 string 형식으로 체크 후 공백일경우
	//		설정된 기본값을 반환, 값이 있을경우 SQL Injection 체크 후
	//		포함된 단어일 경우 Replace
	//	@param
	//		(string) request_name : 넘겨올 변수명
	//		(string) default_val  : 기본값
	//		(string) length       : 입력길이 (한글 2byte, 영문 1byte)
	//		(string) method       : 넘겨받는 메소드구분
	//		(string) types        : 값 타입 (STR:string, INT:inteter)
	//	@return
	//		(string) 비교후 반환값
	//----------------------------------------------------------------------
	function chkReqRpl($request_name, $default_val=null, $length=0, $method="", $types="STR", $checkmb=true) {
		if (strtoupper($method) == "POST") {
			$request_val = $_POST[$request_name];
		} else if (strtoupper($method) == "GET") {
			$request_val = $_GET[$request_name];
		} else {
			$request_val = $_REQUEST[$request_name];
		}

		//배열로 넘오면 데이터는 string형식으로 변환 (예:Array → 1, 2, 3)
		if (is_array($request_val)) {
			$tmp_val = array();
			for($i=0; $i<count($request_val); $i++) {
				$val = htmlEncode( trim($request_val[$i]) );

				if (chkBlank($val)) {
					$tmp_val[$i] = $default_val;
				} else {
					if (strtoupper($types) == "INT") {
						if (!is_numeric($val)) {
							$tmp_val[$i] = null;
						} else {
							$tmp_val[$i] = $val;
						}
					} else {
						if (strtoupper($length) != "MAX") {
							$tmp_val[$i] = returnToCut($val, $length, "", $checkmb);
						} else {
							$tmp_val[$i] = $val;
						}
					}
				}
			}

			return $tmp_val;
		} else {
			$val = htmlEncode( trim($request_val) );

			if (chkBlank($val)) {
				return $default_val;
			} else {
				if (strtoupper($types) == "INT") {
					$val = str_replace(',' ,'' ,$val);

					if (!is_numeric($val)) {
						return null;
					} else {
						//return (float)$val;
						return floatval($val);
					}
				} else {
					if (strtoupper($length) != "MAX") {
						return returnToCut($val, $length, "", $checkmb);
					} else {
						return $val;
					}
				}
			}
		}
	}

	//----------------------------------------------------------------------
	//	@description
	//		입력된 데이터 비교 후 리턴값 반환
	//	@param
	//		(string) input_val  : 데이터값
	//		(string) find_val   : 입력값
	//		(string) return_val : 반환정보
	//	@return
	//		(string) 값이 있을경우 반환정보 반환
	//----------------------------------------------------------------------
	function chkCompare($input_val, $find_val, $return_val) {
		if (chkBlank($input_val) || chkBlank($find_val) || chkBlank($return_val)) return "";

		if (strpos($input_val, ",") !== false) {
			foreach (explode(",", $input_val) as $item) {
				if (trim($item) == $find_val) {
					return $return_val;
					break;
				}
			}
		} else {
			if (strval($input_val) === strval($find_val)) {
				return $return_val;
			}
		}
	}

	//----------------------------------------------------------------------
	// Description
	//		문자열 UrlEncode형식으로 처리
	// Params
	//		val : 변환할 문자 데이터
	// Return
	//		UrlEncode형식으로 변경하여 반환
	//----------------------------------------------------------------------
	function returnURLEncode($val){
		if (chkBlank($val)) {
			return "";
		} else {
			return urlencode($val);
		}
	}

	//----------------------------------------------------------------------
	//	Description
	//		입력된 자를 문자열이 길이값을 반환
	//	Params
	//		val		: 문자열 데이터
	//		checkmb : 완성형 문자 체크 (true: 한글 2byte, false: 한글 1byte)
	//	Return
	//		입력된 자를 문자열이 길이값을 반환(한글 2byte, 영문/숫자 1byte)
	//----------------------------------------------------------------------
	function returnToByte($val, $checkmb=true) {
		$count=0;

		if ($checkmb) {
			$count = mb_strwidth($val, 'utf-8');
		} else {
			$count = mb_strlen($val, 'utf-8');
		}

		return $count;
	}


	//----------------------------------------------------------------------
	//	Description
	//		입력된 자를 문자열이 길이 만큼 문자열을 잘라서 반환
	//	Params
	//		val		: 문자열 데이터
	//		len  	: 자를 문자열 길이
	//		tail    : 말줄임 문자
	//		checkmb : 완성형 문자 체크 (true: 한글 2byte, false: 한글 1byte)
	//	Return
	//		입력된 길이만큼 잘라져서   문자열 반환
	//----------------------------------------------------------------------
	function returnToCut($val, $len=0, $tail='', $checkmb=true) {
		if (chkBlank($val)) return $val;

		preg_match_all('/[\xE0-\xFF][\x80-\xFF]{2}|./', $val, $match);
		$m = $match[0];

		$ret = array();
		$count = 0;
		for ($i=0; $i < $len; $i++) {
			$count += ($checkmb) ? mb_strwidth($m[$i], 'utf-8') : mb_strlen($m[$i], 'utf-8');
			if ($count > $len) break;
			$ret[] = $m[$i];
		}

		return join('', $ret).$tail;
	}


	//----------------------------------------------------------------------
	//	Description
	//		입력받은 데이터를 HTML Encoding 처리
	//	Params
	//		data : 데이터 자료
	//	Return
	//		변경된 데이터 자료
	//----------------------------------------------------------------------
	function htmlEncode($data="") {
		if (chkBlank($data)) {
			return "";
		} else {
			/*
			$data = htmlspecialchars($data, ENT_QUOTES);
			*/

			$data = trim($data);
			$data = str_replace("&", "&amp;", $data);
			$data = str_replace(">", "&gt;", $data);
			$data = str_replace("<", "&lt;", $data);
			$data = str_replace("\"", "&quot;", $data);
			$data = str_replace("'", "&#39;", $data);


			return $data;
		}
	}


	//----------------------------------------------------------------------
	//	Description
	//		입력받은 데이터를 HTML Decoding 처리
	//	Params
	//		data : 데이터 자료
	//	Return
	//		변경된 데이터 자료
	//----------------------------------------------------------------------
	function htmlDecode($data="") {
		if (chkBlank($data)) {
			return "";
		} else {
			/*
			$data = htmlspecialchars_decode($data);
			$data = stripslashes($data);
			$data = str_replace('&amp;', '&', $data);
			*/

			$data = trim($data);
			$data = str_replace("&#39;", "'", $data);
			$data = str_replace("&quot;", "\"", $data);
			$data = str_replace("&lt;", "<", $data);
			$data = str_replace("&gt;", ">", $data);
			$data = str_replace("&amp;", "&", $data);

			return $data;
		}
	}


	//----------------------------------------------------------------------
	//	Description
	//		Enter Key와 Space를 HTML형식으로 변환 처리
	//	Params
	//		data : 데이터 자료
	//	Return
	//		변경된 데이터 자료
	//----------------------------------------------------------------------
	function textareaDecode($data="") {
		if (chkBlank($data)) {
			return "";
		} else {
			//$data = nl2br($data);

			//$data = htmlspecialchars($data, ENT_QUOTES); 	//태그처리
			$data = str_replace("\u0020","&nbsp;",$data); 	//스페이스바 처리
			$data = str_replace(" ","&nbsp;",$data); 		// 공백 처리
			$data = nl2br($data);
			$data = str_replace("\r\n","<br>",$data); //줄바꿈 처리
			$data = str_replace("\r\n","<br>",$data); //줄바꿈 처리
			$data = str_replace("\r\n","<br>",$data); //줄바꿈 처리

			return $data;
		}
	}


	//----------------------------------------------------------------------
	//	Description
	//		문자열에서 HTML태그를 삭제
	//	Params
	//		data : 문자열 데이터
	//		tags : 허용태그 (<p><a>)
	//	Return
	//		HTML태그가 제거된 문자 반환
	//----------------------------------------------------------------------
	function replaceContTag($data, $tags="") {
		if (chkBlank($data)) {
			return "";
		} else {
			return strip_tags($data, $tags);
		}
	}


	//----------------------------------------------------------------------
	//	Description
	//		데이트 날짜형식을 변환해서 리턴
	//	Params
	//		types : 변환될 데이트
	//		date  : 날짜데이트
	//	Return
	//		변환된 날짜값
	//----------------------------------------------------------------------
	function dateTypes($types='', $date) {
		if (chkBlank($date)) return $date;

		$date = strtotime($date);
		return date($types, $date);
	}


	//----------------------------------------------------------------------
	//	Description
	//		숫자형의 자리수가 한자리일경우 두자리로 변환 처리
	//	Params
	//		data	: 데이터값
	//	Return
	//		두자리 문자형 값으로 반환
	//----------------------------------------------------------------------
	function addZero($val) {
		return sprintf('%02d',$val);
	}


	//----------------------------------------------------------------------
	//	Description
	//		right 사용자 정의 함수
	//	Params
	//		$value	: 데이터값
	//		$count	: 불러올 수
	//	Return
	//		오른쪽 자른 문자열
	//----------------------------------------------------------------------
	function right($value, $count){
		$value = mb_substr($value, (mb_strlen($value, 'utf-8') - $count), mb_strlen($value, 'utf-8'), 'utf-8');

		return $value;
	}


	//----------------------------------------------------------------------
	//	Description
	//		left 사용자 정의 함수
	//	Params
	//		$value	: 데이터값
	//		$count	: 불러올 수
	//	Return
	//		오른쪽 자른 문자열
	//----------------------------------------------------------------------
	function left($value, $count){
		return mb_substr($value, 0, $count, 'utf-8');
	}


	//----------------------------------------------------------------------
	//	Description
	//		세션저장
	//	Params
	//		$name  : 세션명
	//		$value : 값
	//	Return
	//
	//----------------------------------------------------------------------
	function setSession($name, $value) {
		$_SESSION[$name] = $value;
	}

	//----------------------------------------------------------------------
	//	Description
	//		저장된 세션 불러오기
	//	Params
	//		$name  : 세션명
	//	Return
	//
	//----------------------------------------------------------------------
	function getSession($name) {
		return $_SESSION[$name];
	}


	//----------------------------------------------------------------------
	//	Description
	//		세션삭제
	//	Params
	//		$name  : 세션명
	//	Return
	//
	//----------------------------------------------------------------------
	function delSession($name) {
		unset($_SESSION[$name]);
	}


	//----------------------------------------------------------------------
	//	Description
	//		인증번호 생성
	//	Params
	//		length	: 반환길이
	//	Return
	//		생성된 인증번호 반환
	//----------------------------------------------------------------------
	function returnAuthNum($length) {
		$tmpAuthNum = "0123456789";
		$tmpAuthNumResult = "";
		$tmpAuthLength = $length;

		while ($tmpAuthLength--) {
			$tmpAuthNumResult .= $tmpAuthNum[mt_rand(0, strlen($tmpAuthNum)-1)];
		}

		return $tmpAuthNumResult;
	}


	//----------------------------------------------------------------------
	//	Description
	//		현재 시간을 기준으로 고유한 값을 돌려준다 시분초까지 사용.
	//		YYYYHHMMSS 형식의 값을 반환 한다.
	//	Params
	//	Return
	//		생성된 returnNowNum값
	//----------------------------------------------------------------------
	function returnNowNum() {
		return date("YmdHis");
	}


	//----------------------------------------------------------------------
	//	Description
	//		GUID 만들기
	//	Params
	//	Return
	//		생성된 코드 반환
	//----------------------------------------------------------------------
    function getGUID() {
		$data = openssl_random_pseudo_bytes( 16 );
		$data[6] = chr( ord( $data[6] ) & 0x0f | 0x40 ); // set version to 0100
		$data[8] = chr( ord( $data[8] ) & 0x3f | 0x80 ); // set bits 6-7 to 10

		return vsprintf( '%s%s-%s-%s-%s-%s%s%s', str_split( bin2hex( $data ), 4 ) );
    }


	//----------------------------------------------------------------------
	//	Description
	//		주문번호생성
	//	Params
	//
	//	Return
	//		생성된 코드 반환
	//----------------------------------------------------------------------
    function getCreateOrderNum() {
		return date("Ymd").returnAuthNum(7);
    }


	//----------------------------------------------------------------------
	//	Description
	//		이미지 사이즈 비교 후 정비율로 이미지 사이즈값을 반환한다.
	//	Params
	//		$width     = 이미지 가로 크기값
	//		$height    = 이미지 세로 크기값
	//		$maxWidth  = 비교할 가로 크기값
	//		$maxHeight = 비교할 세로 크기값
	//	Return
	//		변환된 width:height
	//----------------------------------------------------------------------
	function getImageRatioSize($width, $height, $maxWidth, $maxHeight) {
		if ($width > $maxWidth) {
			$ratio = $maxWidth / $width;

			$width = $maxWidth;
			$height = (int)$height * $ratio;
		}

		if ($height > $maxHeight) {
			$ratio = $maxHeight / $height;

			$height = $maxHeight;
			$width = (int)$width * $ratio;
		}

		return "$width:$height";
	}


	function getImagesTagSearch($content, $patrn = 'src') {
		preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $matches);

		if ($patrn == 'src') {
			return $matches[1];
		} else if ($patrn == 'tag') {
			return $matches[0];
		} else {
			return "";
		}
	}

	//----------------------------------------------------------------------
	//	Description
	//		지정된 값에 대해서 입력된 값을 절사 시킨다.
	//	Params
	//		$number = 입력된 숫자형 데이터
	//		$digits = 절사시킬 자리수
	//	Return
	//		절사처리된 값
	//----------------------------------------------------------------------
	function numberCutting($number, $digits) {
		return floor( $number / $digits ) * $digits;
	}



	//----------------------------------------------------------------------
	//	Description
	//		가운데 자리 보안처리
	//	Params
	//		$value = 입력 데이터 값
	//	Return
	//		변환된 데이터값
	//----------------------------------------------------------------------
	function getSecureWord($value) {
		if (chkBlank($value)) return "";

		$resultWord = "";

		if (mb_strlen($value, 'utf-8') < 3) {
			$resultWord .= left($value,1)."*";
		} else {
			$resultWord .= left($value,1);

			for ($i=1; $i<=mb_strlen($value, 'utf-8')-2; $i++) {
				$resultWord .= "*";
			}

			$resultWord .= right($value,1);
		}

		return $resultWord;
	}


	//----------------------------------------------------------------------
	//	Description
	//		길이에 맞게 앞에 설정자리외 *표시
	//	Params
	//		$value	  = 입력 데이터 값
	//		$openLens = 보여줄 자리수
	//		$hideLens = 숨길 고정 자리수
	//	Return
	//		변환된 데이터값
	//----------------------------------------------------------------------
	function getSecureWord2($value, $openLens=2, $hideLens="") {
		$tmpValue = $value;
		$showStar = "";

		if (mb_strlen($tmpValue, 'utf-8') <= 4) {
			$tmpValue = left($tmpValue, 2);
			$openLens = 2;
		} else {
			$tmpValue = left($tmpValue, $openLens);
		}

		if (chkBlank($hideLens)) $hideLens = mb_strlen($value, 'utf-8');

		for ($i=$openLens+1; $i<=$hideLens; $i++) {
			$showStar .= "*";
		}

		return $tmpValue.$showStar;
	}

	//----------------------------------------------------------------------
	//	Description
	//		입력된 데이터 총길이의 앞절반은 오픈 뒤절반은 *표시
	//	Params
	//		$value	  = 입력 데이터 값
	//	Return
	//		변환된 데이터값
	//----------------------------------------------------------------------
	function getSecureWord3($value) {
		if (chkBlank($value)) return "";

		$tmpValue = $value;
		$openLens = (int)(mb_strlen($tmpValue, 'utf-8') / 2);
		$wordLens = mb_strlen($tmpValue, 'utf-8');

		$showStar = "";
		for ($i=$openLens+1; $i<=$wordLens; $i++) {
			$showStar .= "*";
		}

		return left($tmpValue,$openLens) . $showStar;
	}


	//----------------------------------------------------------------------
	//	Description
	//		모바일 기기체크
	//	Params
	//
	//	Return
	//		모바일 기기에서 접속인지 체크여부 반환
	//----------------------------------------------------------------------
	function returnMobileCheck() {
		global $HTTP_USER_AGENT;
		$mobile_agent = '/(iPhone|iPod|BlackBerry|Android|Windows CE|Nokia|LG|MOT|SAMSUNG|SCH-M\d+|SonyEricsson|sony|Mobile|Symbian|SymbianOS|Opera Mobi|Opera Mini|IEmobile|Mobile|lgtelecom|PPC|PalmOS|webOS)/';

		if(preg_match($mobile_agent, $_SERVER['HTTP_USER_AGENT'])) {
			return true;
		} else {
			return false;
		}
	}

	function returnAppleCheck() {
		global $HTTP_USER_AGENT;

		if(preg_match('/Chrome/i',$HTTP_USER_AGENT) ) {
			return false;
		} else if (preg_match('/Safari/i',$HTTP_USER_AGENT)){
			return true;
		} else if (preg_match('/iPhone/i',$HTTP_USER_AGENT)){
			return true;
		} else if (preg_match('/iPod/i',$HTTP_USER_AGENT)){
			return true;
		} else if (preg_match('/iPad/i',$HTTP_USER_AGENT)){
			return true;
		} else {
			return false;
		}
	}



	//----------------------------------------------------------------------
	//	Description
	//		만나이 계산
	//	Params
	//		$birthday = 생년월일
	//	Return
	//		모바일 기기에서 접속인지 체크여부 반환
	//----------------------------------------------------------------------
	function getKorAge($birthday) {
		$year  = (int)date('Y', strtotime($birthday));
		$month = (int)date('m', strtotime($birthday));
		$day   = (int)date('d', strtotime($birthday));

		$now_year  = (int)date('Y');
		$now_month = (int)date('m');
		$now_day   = (int)date('d');

		//return ($now_year - $year)+1;

		if ($month < $now_month) {
			return $now_year - $year;
		} else if ($month == $now_month) {
			if ($day <= $now_day) {
				return $now_year - $year;
			} else {
				return $now_year - $year - 1;
			}
		} else {
			return $now_year - $year - 1;
		}
	}

	//----------------------------------------------------------------------
	//	Description
	//		이메일 발송 파일내용 불러오기
	//	Params
	//		$file_path = 파일경로
	//	Return
	//		파일내용
	//----------------------------------------------------------------------
	function getEmailSendFile($file_path) {
		$file_path = PHYSICAL_PATH . $file_path;

		$fp = fopen($file_path, "r") or die("파일열기에 실패하였습니다");
		$contents = fread($fp, filesize($file_path));
		fclose($fp);

		if (mb_detect_encoding($contents,"UTF-8, EUC-KR") != "UTF-8") {
			return iconv('euc-kr','utf-8',$contents);
		} else {
			//return iconv('utf-8','euc-kr',$contents);
			return $contents;
		}
	}



	//문자셋전환
	function changeValueCharset($value, $types="utf-8") {
		if ($types == "utf-8") {
			//return iconv('euc-kr','utf-8',$value);
		} else {
			//return iconv('utf-8','euc-kr',$value);
		}
		return $value;
	}


	//파일 캐리터셋체크
	if(!function_exists('mb_detect_encoding')) {
		function mb_detect_encoding($string, $enc=null) {

			static $list = array('utf-8', 'iso-8859-1', 'windows-1251');

			foreach ($list as $item) {
				$sample = iconv($item, $item, $string);
				if (md5($sample) == md5($string)) {
					if ($enc == $item) { return true; }    else { return $item; }
				}
			}
			return null;
		}
	}


	//----------------------------------------------------------------------
	//	Description
	//		배열의 지정위치 값 불러오기
	//	Params
	//		arr  : 배열정보 Array 형식
	//		pos  : 배열위치 0부터 시작
	//	Return
	//		해당 위치의 배열값 출력
	//----------------------------------------------------------------------
	function getArrayValue($arr, $pos) {
		//배열값이 아니면 false 반환
		if (!is_array($arr)) return false;

		//위치값이 숫자형이 아니면 false반환
		if (!is_numeric($pos)) return false;

		for ($i=0; $i<count($arr); $i++) {
			if ($i == $pos) {
				return $arr[$i];
			}
		}

		return false;
	}

	//파라미터정보 불러오기
	function getParamsValue($params, $name) {
		if (chkBlank($params)) return "";

		$params_arr = explode("&", $params);
		foreach ($params_arr as $item) {
			if (strpos($item, "=") !== false) {
				if (explode("=", $item)[0] == $name) {
					return explode("=", $item)[1];
				}
			}
		}

		return "";
	}

	//페이지 파라미터정보 설정
	function setPageParamsValue($params, $dendy = "") {
		if (chkBlank($params)) return "";

		if (is_array($params)) {
			$paramsVal = "";
			foreach ($params as $item => $value) {
				if (!chkBlank( trim($value) )) {
					if ($dendy != "") {
						$tmp_dendy = str_replace(" ", "", $dendy);
						$tmp_dendy = explode(",", $tmp_dendy);
						$tmp_cnt = 0;
						foreach ($tmp_dendy as $tmp_item) {
							if ($tmp_item == trim($item)) $tmp_cnt++;
						}

						if ($tmp_cnt == 0) {
							$paramsVal .= "&". trim($item) ."=". returnURLEncode(trim($value));
						}
					} else {
						$paramsVal .= "&". trim($item) ."=". returnURLEncode(trim($value));
					}
				}
			}
		} else {
			$params    = str_replace(" ", "", $params);
			$paramsArr = explode(",", $params);
			$paramsVal = "";
			foreach ($paramsArr as $item) {
				global ${$item};

				if (!chkBlank( trim(${$item} ) )) {
					if ($dendy != "") {
						$tmp_dendy = str_replace(" ", "", $dendy);
						$tmp_dendy = explode(",", $tmp_dendy);
						$tmp_cnt = 0;
						foreach ($tmp_dendy as $tmp_item) {
							if ($tmp_item == trim($item)) $tmp_cnt++;
						}

						if ($tmp_cnt == 0) {
							$paramsVal .= "&". trim($item) ."=". returnURLEncode(trim( ${$item} ));
						}
					} else {
						$paramsVal .= "&". trim($item) ."=". returnURLEncode(trim( ${$item} ));
					}
				}
			}
		}

		return $paramsVal;
	}


	//----------------------------------------------------------------------
	//	Description
	//		dateadd 함수
	//	Params
	//		gubun : 구분 (d,m,y)
	//		num   : 이동 수
	//		date  : 기준날짜
	//	Return
	//		해당 위치의 배열값 출력
	//----------------------------------------------------------------------
	function dateAdd($gubun, $num, $date) {
		if ($gubun == "d" || $gubun == "day") {
			$sql = "SELECT DATE_ADD('$date', INTERVAL $num DAY);";
		} else if ($gubun == "m" || $gubun == "month") {
			$sql = "SELECT DATE_ADD('$date', INTERVAL $num MONTH);";
		} else if ($gubun == "Y" || $gubun == "yyyy" || $gubun == "year") {
			$sql = "SELECT DATE_ADD('$date', INTERVAL $num YEAR);";
		} else if ($gubun == "H" || $gubun == "hour") {
			$sql = "SELECT DATE_ADD('$date', INTERVAL $num HOUR);";
		} else if ($gubun == "i" || $gubun == "minute") {
			$sql = "SELECT DATE_ADD('$date', INTERVAL $num MINUTE);";
		} else if ($gubun == "s" || $gubun == "second") {
			$sql = "SELECT DATE_ADD('$date', INTERVAL $num SECOND);";
		} else if ($gubun == "w" || $gubun == "week") {
			$sql = "SELECT DATE_ADD('$date', INTERVAL $num WEEK);";
		}

		$db = new DB_HELPER;
		$result = $db->getQueryValue($sql);

		if (strlen($date) == 10) {
			$date = date("Y-m-d", strtotime($result[0]));
		} else {
			$date = date("Y-m-d H:i:s", strtotime($result[0]));
		}

		return $date;
	}

	//----------------------------------------------------------------------
	//	Description
	//		초 단위 시간변경 함수
	//	Params
	//		time  : 시간(초단위)
	//		fmt   : 변환 형식
	//	Return
	//		변환된 값
	//----------------------------------------------------------------------
	function timeToKor($time, $fmt = "H:i:s") {
		$days = floor($time / (60 * 60 * 24));
		$time -= $days * (60 * 60 * 24);

		$hours = floor($time / (60 * 60));
		$time -= $hours * (60 * 60);

		$minutes = floor($time / 60);
		$time -= $minutes * 60;

		$seconds = floor($time);
		$time -= $seconds;

		$result = $fmt;

		//Day 제거, Hour에 합산
		//$result = str_replace("d", addZero($days), $result);	//(01,02,03...)
		//$result = str_replace("D", $days, $result);				//(1,2,3...)

		$result = str_replace("H", addZero($hours + ($days*24)), $result);	//(00 to 23)
		$result = str_replace("G", $hours + ($days*24), $result); 			//(0 to 23)

		$result = str_replace("i", addZero($minutes), $result); //(00 to 59)
		$result = str_replace("I", $minutes, $result);			//(0 to 59)

		$result = str_replace("s", addZero($seconds), $result);	//(00 to 59)
		$result = str_replace("S", $seconds, $result);			//(0 to 59)

		return $result;
	}

	//----------------------------------------------------------------------
	//	Description
	//		datediff 함수
	//	Params
	//		gubun : 구분 (d,m,y)
	//		num   : 이동 수
	//		date  : 기준날짜
	//	Return
	//		해당 위치의 배열값 출력
	//----------------------------------------------------------------------
	function dateDiff($gubun, $sdate, $edate) {
		if ($gubun == "d" || $gubun == "day") {
			$sql = "SELECT TIMESTAMPDIFF(DAY, '$sdate', '$edate')";
		} else if ($gubun == "m" || $gubun == "month") {
			$sql = "SELECT TIMESTAMPDIFF(MONTH, '$sdate', '$edate')";
		} else if ($gubun == "Y" || $gubun == "yyyy" || $gubun == "year") {
			$sql = "SELECT TIMESTAMPDIFF(YEAR, '$sdate', '$edate')";
		} else if ($gubun == "H" || $gubun == "hour") {
			$sql = "SELECT TIMESTAMPDIFF(HOUR, '$sdate', '$edate')";
		} else if ($gubun == "i" || $gubun == "minute") {
			$sql = "SELECT TIMESTAMPDIFF(MINUTE, '$sdate', '$edate')";
		} else if ($gubun == "s" || $gubun == "second") {
			$sql = "SELECT TIMESTAMPDIFF(SECOND, '$sdate', '$edate')";
		}

		$db = new DB_HELPER;
		$result = $db->getQueryValue($sql);

		return $result[0];
	}


	//데이터 유효성 체크
	function isDataCheck($str, $gubun) {
		if (chkBlank($str) || chkBlank($gubun)) {
			return false;
		}

		if ($gubun == "email") {
			$pattern = "/^([a-z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-z0-9_\-]+\.)+))([a-z]{2,4}|[0-9]{1,3})(\]?)/";
			if( preg_match($pattern, $str) ){
				return true;
			} else {
				return false;
			}
		} else if ($gubun == "phone") {
			$pattern = "/^0(10|11|16|17|18|19)-[0-9]{3,4}-[0-9]{4}/";
			if( preg_match($pattern, $str) ){
				return true;
			} else {
				return false;
			}
		} else if ($gubun == "phone2") {
			$pattern = "/^0(10|11|16|17|18|19)[0-9]{3,4}[0-9]{4}/";
			if( preg_match($pattern, $str) ){
				return true;
			} else {
				return false;
			}
		} else if ($gubun == "tel") {
			$pattern = "/^0(2|31|33|32|42|43|41|44|50|53|54|55|52|51|60|63|61|62|64|70|502|504)-[0-9]{3,4}-[0-9]{4}/";
			if( preg_match($pattern, $str) ){
				return true;
			} else {
				return false;
			}
		} else if ($gubun == "biz_num") {
			$check_cnt = 0;
			$str = preg_replace("/[^0-9]/", "", $str);

			$att = 0;
			$sum = 0;
			$arr = array(1, 3, 7, 1, 3, 7, 1, 3, 5);
			$cnt = count($arr);
			for($i=0; $i<$cnt; $i++) {
				$sum += ($str[$i] * $arr[$i]);
			}
			$sum += intval(($str[8] * 5) / 10);
			$at = $sum % 10;
			if ($at != 0) $att = 10 - $at;

			if ($str[9] != $att) {
				return false;
			} else {
				return true;
			}
		} else if ($gubun == "password") {
			$passCheck1 = 0;
			$passCheck2 = 0;
			$passCheck3 = 0;

			for ($i=0; $i< strlen($str); $i++) {
				$word = mb_substr($str,$i,1, 'utf-8');

				if (preg_match("/[a-z]/", $word)) $passCheck1++;
				if (preg_match("/[A-Z]/", $word)) $passCheck2++;
				if (preg_match("/[0-9]/", $word)) $passCheck3++;
			}

			if (($passCheck1 + $passCheck2) == 0 || $passCheck3 == 0) {
				return false;
			} else {
				return true;
			}
		} else if ($gubun == "specialword") {
			for ($i=0; $i< strlen($str); $i++) {
				$word = mb_substr($str,$i,1, 'utf-8');

				//허용특수문자 체크
				if (!preg_match("/[a-zA-z0-9]/", $word)) {
					if (!preg_match("/[!@$%^&*_~\-]/", $word)) {
						return false;
					}
				}
			}

			return true;
		} else {
			return false;
		}

		return false;
	}

	//----------------------------------------------------------------------
	//	Description
	//		날짜 형식 맞게 반환
	//	Params
	//		'Y : 년(yyyy)
	//		'y : 년(yy)
	//		'm : 월
	//		'd : 일
	//		'H : 시
	//		'i : 분
	//		's : 초
	//	Return
	//		변환된 값
	//----------------------------------------------------------------------
	function formatDates($dates, $types) {
		if (chkBlank($dates)) {
			return "-";
		}

		$date = new DateTime($dates);
		$new_date_format = $date->format($types);

		return $new_date_format;
	}

	function formatNumbers($str, $decimals=null) {
		if (chkBlank($str)) return "";
		if (strpos($str, ".") !== false) {
			if (chkBlank($decimals)) {
				$tmp_arr = explode(".", $str);
				$tmp_str = number_format($tmp_arr[0],0) .".". $tmp_arr[1];

				return $tmp_str;
			} else {
				return number_format($str, $decimals);
			}
		} else {
			if (chkBlank($decimals)) {
				return number_format($str);
			} else {
				return number_format($str, $decimals);
			}
		}
	}

	//전화번호 형식 '-' 넣기
	function formatPhoneNum($phone_num) {
		$phone_num = preg_replace('/[^0-9]/', '', $phone_num);
		$length = strlen($phone_num);

		if( preg_match('/^1(544|644|661|800|833|522|566|600|670|599|688|666|877|855|577|588|899)/', $phone_num) ){
			if ($length == 8) {
				$phone_num = preg_replace('/([0-9]{4})([0-9]{4})/', '$1-$2', $phone_num);
			}
		} else if (left($phone_num,2) == '02') {
			if ($length == 9) {
				$phone_num = preg_replace('/([0-9]{2})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $phone_num);
			} else if ($length == 10) {
				$phone_num = preg_replace('/([0-9]{2})([0-9]{4})([0-9]{4})/', '$1-$2-$3', $phone_num);
			}
		} else {
			if ($length == 10) {
				$phone_num = preg_replace('/([0-9]{3})([0-9]{3})([0-9]{4})/', '$1-$2-$3', $phone_num);
			} else if ($length == 11) {
				$phone_num = preg_replace('/([0-9]{3})([0-9]{4})([0-9]{4})/', '$1-$2-$3', $phone_num);
			} else if ($length == 12) {
				$phone_num = preg_replace('/([0-9]{4})([0-9]{4})([0-9]{4})/', '$1-$2-$3', $phone_num);
			}
		}

		return $phone_num;
	}

	function char_at($str, $pos){
		return $str{$pos};
	}

	function rpHash($value) {
		$hash = 5381;

		$value = strtoupper($value);
		for($i = 0; $i < strlen($value); $i++) {
			$hash = (($hash << 5) + $hash) + ord(mb_substr($value, $i, 'utf-8'));
		}
		return $hash;
	}

	function calendar_first_date($curr_date) {
		while ( date('w', strtotime($curr_date)) > 0 ) {
			$curr_date = dateAdd('d', -1, $curr_date);
		}

		return $curr_date;
	}


	//https연결 확인
	function isSecure() {
		return
			(!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
			|| $_SERVER['SERVER_PORT'] == 443;
	}


    //Naver 단축URL 변환
    function naverShorturl($url, &$return_val="") {
        // 네이버 단축URL Open API 예제
        $client_id     = NAVER_CLIENT_ID;
        $client_secret = NAVER_CLIENT_SECRET;
        $encText       = urlencode($url);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://openapi.naver.com/v1/util/shorturl?url=".$encText);
        curl_setopt($ch, CURLOPT_POST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $headers = array();
        $headers[] = "X-Naver-Client-Id: ".$client_id;
        $headers[] = "X-Naver-Client-Secret: ".$client_secret;
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            $response    = json_decode(curl_exec ($ch), true);
            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close ($ch);
        if($status_code == 200) {
            $return_val = $response['result']['url'];

            return true;
        } else {
            $return_val = $response['result']['message'];
            return false;
        }
    }


    /*
    'Naver 단축URL 변환
	Function naverShorturl(ByVal url)
		%><script language="javascript" runat="server" src="/module/lib/class/common/asp_json.asp"></script><%
		Dim httpObj, params, lResolve, lConnect, lSend, lReceive
		Dim token_info
		Dim i, result, code, message, shorturl

		If InStr(url, "http://") = 0 And InStr(url, "https://") = 0 Then
			url = URL_HTTP & URL_HOST & url
		End If

		params = "url=" & returnURLEncode(url)

		Set httpObj = server.CreateObject("MSXML2.ServerXMLHTTP")
			httpObj.setTimeouts 360000, 360000, 360000, 360000
			httpObj.open "POST", "https://openapi.naver.com/v1/util/shorturl", False
			httpObj.setRequestHeader "Content-Type", "application/x-www-form-urlencoded"

			httpObj.SetRequestHeader "X-Naver-Client-Id", NAVER_CLIENT_ID
			httpObj.SetRequestHeader "X-Naver-Client-Secret", NAVER_CLIENT_SECRET

			httpObj.send params
			result = httpObj.responseText
		Set httpObj = Nothing


		Set token_info = JSON.parse(result)
			code    = token_info.code
			message = token_info.message
			If code = 200 Then
				shorturl = token_info.result.url
			End If
		Set token_info = Nothing

		If code<> 200 Then Exit Function

		naverShorturl = shorturl
	End Function
    */