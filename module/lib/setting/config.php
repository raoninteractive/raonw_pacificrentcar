<?php
	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);	//에러체크
    ini_set('display_errors', '1');

	@set_time_limit(0);	//최대 실행 시간을 제한 (0: 무제한)

	//짧은 환경변수를 지원하지 않는다면
	if (isset($HTTP_POST_VARS) && !isset($_POST)) {
		$_POST   = &$HTTP_POST_VARS;
		$_GET    = &$HTTP_GET_VARS;
		$_REQUEST= &$HTTP_REQUEST_VARS;
		$_SERVER = &$HTTP_SERVER_VARS;
		$_COOKIE = &$HTTP_COOKIE_VARS;
		$_ENV    = &$HTTP_ENV_VARS;
		$_FILES  = &$HTTP_POST_FILES;

		if (!isset($_SESSION)) $_SESSION = &$HTTP_SESSION_VARS;
	}


	//-------------------------------------------------------------------------------------------------------------------------
	//	extract($_GET); 명령으로 인해 page.php?_POST[var1]=data1&_POST[var2]=data2 와 같은 코드가 _POST 변수로 사용되는 것을 막음
	//-------------------------------------------------------------------------------------------------------------------------
	$ext_arr = array ('PHP_SELF', '_ENV', '_GET', '_POST', '_FILES', '_SERVER', '_COOKIE', '_SESSION', '_REQUEST',
					  'HTTP_ENV_VARS', 'HTTP_GET_VARS', 'HTTP_POST_VARS', 'HTTP_POST_FILES', 'HTTP_SERVER_VARS',
					  'HTTP_COOKIE_VARS', 'HTTP_SESSION_VARS', 'GLOBALS');
	$ext_cnt = count($ext_arr);
	for ($i=0; $i<$ext_cnt; $i++) {
		// GET, POST 로 선언된 전역변수가 있다면 unset() 시킴
		if (isset($_GET[$ext_arr[$i]])) unset($_GET[$ext_arr[$i]]);
		if (isset($_POST[$ext_arr[$i]])) unset($_POST[$ext_arr[$i]]);
	}


	//PHP 4.1.0 부터 지원됨
	//php.ini 의 register_globals=off 일 경우
	@extract($_GET);
	@extract($_POST);
	@extract($_REQUEST);
	@extract($_SERVER);


	//세션 만료시간 설정
	session_cache_limiter('no-cache');

	ini_set("session.save_path", $_SERVER["DOCUMENT_ROOT"]."/upload/session"); //세션저장 경로
	ini_set("session.cookie_lifetime", "0");	//세션ID를 저장한 쿠키의 활성화 시간 또는 유효 시간을 설정하는 것이다. (브라우저 닫는즉시)
	ini_set("session.cache_expire", "60");		//서버에 저장된 세션ID의 활성화 시간이다. (1시간 - 분단위)
	ini_set("session.gc_maxlifetime", "3600");  //사용되지 않는 것으로 보이는 세션 데이터를 삭제한다. (1시간 - 초단위)

	//세션시작
	session_start();

	//현재 URL 설정
	define("URL_METHOD", $_SERVER["REQUEST_METHOD"]);
	define("URL_HTTP", ($_SERVER["HTTPS"] == "on") ? "https://" : "http://");
	define("URL_PORT", $_SERVER['SERVER_PORT']);
	define("URL_HOST", $_SERVER['HTTP_HOST']);
	define("URL_PATH", $_SERVER['PHP_SELF']); //basename($_SERVER['PHP_SELF']) → 파일명만 불러올때
	define("URL_QUERYSTRING", $_SERVER['QUERY_STRING']);
	define("URL_REFERER", $_SERVER['HTTP_REFERER']);
	define("NOW_URL", (URL_QUERYSTRING != "") ? URL_PATH."?".URL_QUERYSTRING : URL_PATH);


	//Referer 설정
	$returnReferer = str_replace(URL_REFERER, "", URL_HTTP.URL_HOST);

	//사용자 IP설정
	if(!empty($_SERVER['HTTP_CLIENT_IP']) && getenv('HTTP_CLIENT_IP')){
		define("NOW_IP", $_SERVER['HTTP_CLIENT_IP']);
	} elseif(!empty($_SERVER['HTTP_X_FORWARDED_FOR']) && getenv('HTTP_X_FORWARDED_FOR')){
		define("NOW_IP", $_SERVER['HTTP_X_FORWARDED_FOR']);
	} elseif(!empty($_SERVER['REMOTE_HOST']) && getenv('REMOTE_HOST')){
		define("NOW_IP", $_SERVER['REMOTE_HOST']);
	} elseif(!empty($_SERVER['REMOTE_ADDR']) && getenv('REMOTE_ADDR')){
		define("NOW_IP", $_SERVER['REMOTE_ADDR']);
	}

	$NOW_IP = NOW_IP;

	//마스터 비밀번호
	define("MASTER_PASSWD", "raon8228@@");

	//사용자 세션ID 설정
	define("USER_SESSION", session_id());

	//서버상 루트 위치경로
	define("PHYSICAL_PATH", $_SERVER["DOCUMENT_ROOT"]);

	//서버상 기본 업로드 경로 설정
	define("UPLOAD_PATH_DETAUL", PHYSICAL_PATH."/upload");

	//서버상 기본 로그기록 경로 설정
	define("LOGPATH_DEFAULT", PHYSICAL_PATH."/logs");

	//파일 업로드 가능 확장자
	define("CONST_FILE_EXTS", "alz,asf,ai,avi,bmp,csv,doc,docx,fla,gif,htm,html,hwp,jpeg,jpg,mp3,mp4,png,ppt,pptx,psd,swf,txt,xml,xls,xlsx,zip,pdf");

	//메일주소 목록
	$CONST_MAIL = array("naver.com", "daum.net", "hanmail.net", "nate.com", "gmail.com", "hotmail.com", "yahoo.co.kr");

	//지역번호 목록
	$CONST_TEL = array("02","031","032","033","041","042","043","051","052","053","054","055","061","062","063","064","070","0502","0505");

	//휴대폰번호 목록
	$CONST_PHONE = array("010","011","016","017","018","019");

	//지역구분
	$CONST_AREA = array("서울", "부산", "대구", "인천", "광주", "대전", "울산", "경기", "강원", "충북", "충남", "전북", "전남", "경북", "경남", "제주", "세종");

	//요일명
	$CONST_WEEK_NAME = array("일요일", "월요일", "화요일", "수요일", "목요일", "금요일", "토요일");
	$CONST_WEEK_NAME2 = array("일", "월", "화", "수", "목", "금", "토");

	//SMS발신번호
	$CONST_SMS_SENDER = array("010-6245-7938");
	$CONST_SMS_ID     = "";
	$CONST_SMS_PW     = "";

	//배너위치구역
	$CONST_BANNER_AREA = array("", "메인");

	//팝업위치구역
	$CONST_POPUP_AREA = array("", "메인");

	//회원구분
	$CONST_MEMBER_GUBUN = array(
		array("00", "비회원"),
		array("10", "일반회원")
	);

	//초기비밀번호 설정
	define("CONST_RESET_PWD", "pwd");

	//비밀번호 변경기간
	define("MEMBER_PWD_EXPIRE_DAY", "90");

	//암복호화 키
	define("CONST_SECRET_KEY", "2AI#ze0P6B*iDkQ!ezDm");
	define("CONST_SECRET_IV", "qD2H!rBGDwUmjxQyr%5N");
    define("CONST_KISA_KEY", "50,67,77,112,69,98,42,72,41,90,43,81,82,56,74,118");
	define("CONST_KISA_IV", "50,86,118,107,42,107,87,80,86,89,56,123,52,90,82,95");

	//네이버 어플리케이션키
	define("NAVER_CLIENT_ID", "a8ENoe9dPamSGyCdilOD");
	define("NAVER_CLIENT_SECRET", "FOVaa0toJ5");

	//카카오 어플리케이션키
	define("KAKAO_CLIENT_ID", "");
	define("KAKAO_CLIENT_SECRET", "");

	//디버깅 설정
	$DEV_MODE = false;
	$DEV_IP = "121.142.29.233";

	foreach (explode(',', $DEV_IP) AS $tmp_ip) {
		if (strpos($tmp_ip, $NOW_IP) !== false || $NOW_IP=='127.0.0.1') {
			$DEV_MODE = true;
			break;
		}
	}

	//DB 설정
	if (strpos(URL_HOST, 'raonworks.co.kr') !== false || strpos(URL_HOST, 'raondev.co.kr') !== false || strpos(URL_HOST, '192.168.10.37') !== false) {
		//개발 DB
		$CONST_DB_IP   = "127.0.0.1";
		$CONST_DB_NAME = "raonw_pacificrentcar";
		$CONST_DB_ID   = "raonw_pacificrentcar";
		$CONST_DB_PW   = "raon8228@@";
		$CONST_DB_PORT = "3306";
	} else {
		//운영 DB
		$CONST_DB_IP   = "localhost";
		$CONST_DB_NAME = "raonw_pacific";
		$CONST_DB_ID   = "raonw_pacific";
		$CONST_DB_PW   = "raon8228@@";
		$CONST_DB_PORT = "3306";
	}

	$ACTION_IFRAME = "<iframe name=\"action_ifrm\" id=\"action_ifrm\" width=\"100%\" height=\"200\" frameborder=\"0\" style=\"display:none\" alt=\"히든 프레임\"></iframe>";


	//년도 공통 마지막 년도
	$CONST_START_YEAR = date("Y");
	$CONST_LAST_YEAR  = date("Y") - 99;


	//사이트 셋팅정보
	define("SITE_NAME", "괌 퍼시픽렌트카");
	define("SITE_URL", "http://www.pacificrentcar.kr");
	define("SITE_TEL", "010-6245-7938");
	define("SITE_PHONE", "010-6245-7938");
	define("SITE_FAX", "02-0000-0000");
	define("SITE_EMAIL", "pacificrentcarguam@gmail.com");
	define("SITE_UPLOAD_MAX_SIZE ", 100);


	//기본 배열값
	$MEM_USR = array();
	$MEM_ADM = array();
	$params  = array();

	//테스트 이메일 수신처
	$CONST_TEST_EAMIL = array("sj.yoon@raonworks.co.kr", "tjrwlslo@naver.com");

	//테스트 SMS 수신처
	$CONST_TEST_SMS = array("01043390723");

	//현지 담당자 이메일
	$CONST_LOCAL_CONTACT_EMAIL1 = array('reservations@pacificrentalsguam.com','hit9157@naver.com');
	$CONST_LOCAL_CONTACT_EMAIL2 = 'pacificrentcarguam@gmail.com';


    //할인적용기간
    $GOODS_DC_PERIOD_DAY = 15; //15일 이후부터 적용
    $GOODS_DC_RATE       = 0.1; //할인율(10%)