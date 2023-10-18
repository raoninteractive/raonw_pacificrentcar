<?
	if (!$cls_set_menu->is_menu_auth($MEM_ADM['usr_gubun'], $pageNum)) fnMsgGo(500, "메뉴 권한이 없습니다.", "../common/logout.php", "");
?>
<!doctype html>
<html lang="ko">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta property="og:type" content="website" />
	<meta property="og:title" content="<?=SITE_NAME?>">
	<meta property="og:site_name" content="<?=SITE_NAME?>" />
	<meta property="og:title" content="<?=SITE_NAME?>" />
	<meta property="og:description" content="<?=SITE_NAME?>" />
	<title><?=iif($pageSubName2 != "", $pageSubName2 & " &lt; ", "")?><?=$pageSubName?> &lt; <?=$pageName?> &lt; 관리자 | <?=SITE_NAME?></title>
	<link rel="stylesheet" href="../css/reset.css">
	<link rel="stylesheet" href="../css/jquery_ui.css">
	<link rel="stylesheet" href="../css/common.css">
	<link rel="stylesheet" href="../css/popup.css">
	<script src="../js/jquery-1.10.1.min.js"></script>
	<script src="../js/jquery-ui-1.10.3.custom.min.js"></script>
	<script src="../js/html5shiv.js"></script>
	<script src="../js/prefixfree.min.js"></script>
	<script src="../js/amcharts.js"></script>
	<script src="../js/serial.js"></script>
	<script src="../js/common.js"></script>

	<script type="text/javascript" src="/module/js/class.helper.js"></script>
	<script type="text/javascript" src="/module/js/fn.user.define.js"></script>

	<script type="text/javascript">
		var ajaxStatus = false;

		$(function(){
			//달력(datepicker)형식의 input_box안의 입력취소 클릭시 날짜초기화
			$(document).on("click", ".input_box.date + span", function(){
				$(this).prev().find("input").val("");
				$(this).closest(".box").find("input").each(function(){
					$(this).datepicker("option", "minDate", "");
					$(this).datepicker("option", "maxDate", "");
				})
			})
		})
	</script>
</head>
<body>
	<!-- header -->
	<header class="header">
		<div class="row">
			<div class="area">
				<h1 class="logo">
					<a href="../"><img src="../images/img_login_top.png" /></a>
				</h1>
				<div class="info_a">
					<div class="info">
						<p class="txt_welcome"><?=$MEM_ADM['usr_name']?>님 환영합니다.</p>
						<p class="recent_visit">최근방문일 <?=formatDates($_COOKIE["ADMIN_VISIT_LAST_DATE"],"Y.m.d H:i:s")?></p>
					</div>
					<div class="btns">
						<a href="javascript:;" class="btn modify" onclick="adminModifyPopup();">정보수정</a>
						<a href="../common/logout.php?tp=btn" class="btn logout">로그아웃</a>
					</div>
				</div>
			</div>
		</div>
		<nav class="gnb">
			<ul>
				<?
					$cls_set_menu = new CLS_SETTING_MENU_AUTH;

					$menu_list = $cls_set_menu->menu_list("", "Y");
				?>
				<?for ($i=0; $i<count($menu_list); $i++) {?>
					<?if ($cls_set_menu->menu_auth_check($MEM_ADM['menu_auth'], $menu_list[$i]['code'], "")) {?>
						<li <?if (left($pageNum,2) == $menu_list[$i]['code']) {?>class="curr"<?}?>>
							<a href="javascript:;"><span><?=$menu_list[$i]['code_name']?></span></a>
							<div class="depth" <?if (left($pageNum,2) != $menu_list[$i]['code']) {?>style="display:none"<?}?>>
								<ul>
									<?
										$sub_list = $cls_set_menu->menu_list($menu_list[$i]['code'], "Y");
									?>
									<?for ($j=0; $j<count($sub_list); $j++) {?>
										<?if ($cls_set_menu->menu_auth_check($MEM_ADM['menu_auth'], $menu_list[$i]['code'], $sub_list[$j]['code'])) {?>
											<li <?if ($pageNum == $sub_list[$j]['code']) {?>class="curr"<?}?>><a href="<?=$sub_list[$j]['page_url']?>"><?=$sub_list[$j]['code_name']?></a></li>
										<?}?>
									<?}?>
								</ul>
							</div>
						</li>
					<?}?>
				<?}?>
			</ul>
		</nav>
	</header>
	<!-- //header -->