<?php
	header("Pragma:no-cache");
	header("Cache-Control:no-cache,must-revalidate");
	header('P3P: CP="ALL CURa ADMa DEVa TAIa OUR BUS IND PHY ONL UNI PUR FIN COM NAV INT DEM CNT STA POL HEA PRE LOC OTC"');
	header("Content-Type:text/html;charset=utf-8");

	error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);	//에러체크
    ini_set('display_errors', '1');

	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/setting/config.php");

	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/dbhelper.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/admin_memo.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/board.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/member.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/setting_banner.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/setting_menu.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/setting_popup.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/jwt.php");
    include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/kisa_seed_cbc.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/sms.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/goods.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/class/booking.php");

	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.aes256.php");
    include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.kisa_seed.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.category.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.db.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.email.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.file.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.goods.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.location.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.logger.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.paging.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.sms.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.util.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/lib/function/func.member.php");

	//동영상 정보불러오기 플러그인
	//include_once ($_SERVER["DOCUMENT_ROOT"]."/module/getid3/getid3.php");

	//부트페이 클라이언트 API
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/bootpay/Singleton.php");
	include_once ($_SERVER["DOCUMENT_ROOT"]."/module/bootpay/BootpayApi.php");

    require_once ($_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php");